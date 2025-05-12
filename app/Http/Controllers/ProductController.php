<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsRessource;
use App\Models\Caracteristique;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('vendeur', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Category filter
        if ($request->has('category') && $request->input('category') !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('nom', $request->input('category'));
            });
        }

        // Get paginated results
        $perPage = 12; // Number of items per page
        $products = $query->with(['images', 'vendeur', 'category'])
                         ->orderBy('created_at', 'desc')
                         ->paginate($perPage);

        return ProductsRessource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vendeur_id = auth()->user()->id;
        // return response()->json($request->all()) ;
        $formFields = $request->validate([
            "nom" => "required",
            "description" => "required",
            "prix" => "required",
            "localisation" => "required", 
            "etat"=> "required",
            "categorie_id" => "required", 
        ]);
        
        $formFields["vendeur_id"] = $vendeur_id;
        $formFields["dateDepot"] = now();
        
        // Créer le produit
        // return response()->json($request->all());
        $product = Product::create($formFields);
        if ($request->has('caracteristique')) {
            $caracteristique = new Caracteristique();
            $caracteristique->produit_id = $product->id;
            $caracteristique->caracteristique = json_encode($request->input('caracteristique'));
            $caracteristique->save();
        } 
        // Traiter toutes les images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $imageFile->store('products', 'public');
                
                // Créer une entrée pour chaque image
                Image::create([
                    "produit_id" => $product->id,
                    "url" => asset("storage/$imagePath"),
                    "description" => $formFields["description"]
                ]);
            }
        } elseif ($request->hasFile('image')) {
            // Pour la compatibilité avec un champ unique 'image'
            $imagePath = $request->file('image')->store('products', 'public');
            
            Image::create([
                "produit_id" => $product->id,
                "url" => asset("storage/$imagePath"),
                "description" => $formFields["description"]
            ]);
        }
        
        // Charger les relations pour les inclure dans la réponse
        $product->load('images');
        
        return response()->json($product);
    }    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductsRessource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    } 
    public function filterd(Request $request ){  

        $catego = $request->query('categorie'); 
        $categorie_id = Category::where('nom', $catego)->first();  
        // return response()->json($categorie_id);
        $products = Product::where('categorie_id', $categorie_id->id)->take(3)->get();

        return ProductsRessource::collection($products);
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    } 
 
}
