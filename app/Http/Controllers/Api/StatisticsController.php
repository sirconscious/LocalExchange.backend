<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\ProductsRessource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function getDashboardStats()
    {
        // Get total users count
        $totalUsers = User::count();
        
        // Get total products count
        $totalProducts = Product::count();
        
        // Get active products count (using both etat and status)
        $activeProducts = Product::where('etat', 'active')
            ->orWhere('status', 'active')
            ->count();
        
        // Get products by category with full product details
        $productsByCategory = Product::with(['vendeur', 'category', 'images'])
            ->get()
            ->groupBy('categorie_id')
            ->map(function ($products, $categoryId) {
                return [
                    'category_name' => Category::find($categoryId)->nom ?? 'Unknown',
                    'total' => $products->count(),
                    'products' => ProductsRessource::collection($products)
                ];
            });
            
        // Get user registrations for last 6 months
        $userRegistrations = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->get();
            
        // Get product listings for last 6 months with full product details
        $productListings = Product::with(['vendeur', 'category', 'images'])
            ->where('dateDepot', '>=', Carbon::now()->subMonths(6))
            ->get()
            ->groupBy(function($product) {
                return Carbon::parse($product->dateDepot)->format('m');
            })
            ->map(function ($products, $month) {
                return [
                    'month' => $month,
                    'total' => $products->count(),
                    'products' => ProductsRessource::collection($products)
                ];
            });
            
        // Get daily active users (users who have products or have logged in recently)
        $dailyActiveUsers = User::where('last_login', '>=', Carbon::now()->subDay())
            ->orWhereHas('products', function($query) {
                $query->where('updated_at', '>=', Carbon::now()->subDay());
            })
            ->count();
            
        return response()->json([
            'total_users' => $totalUsers,
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'products_by_category' => $productsByCategory->values(),
            'user_registrations' => $userRegistrations,
            'product_listings' => $productListings->values(),
            'daily_active_users' => $dailyActiveUsers
        ]);
    }
    
    public function getUserStats()
    {
        // Get user growth over time
        $userGrowth = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('date')
        ->get();
        
        // Get user activity by day of week
        $userActivity = User::select(
            DB::raw('DAYNAME(last_login) as day'),
            DB::raw('count(*) as total')
        )
        ->where('last_login', '>=', Carbon::now()->subMonths(1))
        ->groupBy('day')
        ->get();
        
        return response()->json([
            'user_growth' => $userGrowth,
            'user_activity' => $userActivity
        ]);
    }
    
    public function getProductStats()
    {
        // Get product growth over time with full product details
        $productGrowth = Product::with(['vendeur', 'category', 'images'])
            ->where('dateDepot', '>=', Carbon::now()->subMonths(6))
            ->get()
            ->groupBy(function($product) {
                return Carbon::parse($product->dateDepot)->format('Y-m-d');
            })
            ->map(function ($products, $date) {
                return [
                    'date' => $date,
                    'total' => $products->count(),
                    'products' => ProductsRessource::collection($products)
                ];
            });
        
        // Get products by status with full product details
        $productsByStatus = Product::with(['vendeur', 'category', 'images'])
            ->get()
            ->groupBy('etat')
            ->map(function ($products, $status) {
                return [
                    'status' => $status,
                    'total' => $products->count(),
                    'products' => ProductsRessource::collection($products)
                ];
            });
            
        // Get average products per user with vendor details
        $usersWithProducts = User::withCount('products')->get();
        $avgProductsPerUser = $usersWithProducts->avg('products_count');
            
        return response()->json([
            'product_growth' => $productGrowth->values(),
            'products_by_status' => $productsByStatus->values(),
            'avg_products_per_user' => $avgProductsPerUser,
            'users_with_products' => $usersWithProducts->map(function($user) {
                return [
                    'user' => $user->name,
                    'products_count' => $user->products_count
                ];
            })
        ]);
    }
} 