<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
            // Product::factory(10)->create() ; 
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Role::factory()->create([
        //     'name' => 'admin',
        // ]);
        // Role::factory()->create([
        //     'name' => 'user',
        // ]); 
        // Category::create(
        //    [ "nom"=>"Électronique" ]
        // ) ;
        Product::create(
            [
                'nom' => fake()->name(), 
                    'description' => fake()->text() ,
                    "prix"=>fake()->numberBetween(2 , 3000) , 
                    "localisation"=>"Marrakech" , 
                    "vendeur_id"=>1 , 
                    "categorie_id" => 1  ,
                    "dateDepot" => now()
                ]
        ) ;
    }
}
