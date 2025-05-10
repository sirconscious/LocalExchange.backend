<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StatisticsTestSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing categories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return; // Exit if no categories exist
        }

        // Create users with registration dates spread over the last 6 months
        $users = [];
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'status' => fake()->randomElement(['active', 'suspended', 'pending']),
                'last_login' => fake()->dateTimeBetween('-1 month', 'now'),
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);

            // Assign roles
            $user->roles()->attach(Role::where('name', 'user')->first());
            if ($i === 0) {
                $user->roles()->attach(Role::where('name', 'admin')->first());
            }

            $users[] = $user;
        }

        // Create products with different statuses and dates
        $statuses = ['active', 'sold', 'pending', 'inactive'];
        $locations = ['Marrakech', 'Casablanca', 'Rabat', 'Fès', 'Tanger'];

        for ($i = 0; $i < 200; $i++) {
            $createdAt = fake()->dateTimeBetween('-6 months', 'now');
            $status = fake()->randomElement($statuses);
            
            Product::create([
                'nom' => fake()->words(3, true),
                'description' => fake()->paragraph(),
                'prix' => fake()->numberBetween(10, 5000),
                'status' => $status,
                'localisation' => fake()->randomElement($locations),
                'vendeur_id' => fake()->randomElement($users)->id,
                'categorie_id' => $categories->random()->id, // Use existing category IDs
                'dateDepot' => $createdAt,
                'etat' => fake()->randomElement(['Neuf', 'Bon état', 'Moyen', 'Usé']),
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);
        }
    }
} 