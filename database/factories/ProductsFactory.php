<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'nom' => fake()->name(), 
            'description' => fake()->text() ,
            "prix"=>fake()->numberBetween(2 , 3000) , 
            "localisation"=>"Marrakech" , 
            "vendeur_id"=>1 , 
            "categorie_id" => 1  ,
            "dateDepot" => now()
        ];
    }
}
