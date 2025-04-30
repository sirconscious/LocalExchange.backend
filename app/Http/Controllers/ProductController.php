<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsRessource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
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
        
        $formFields = $request->validate([
            "nom" => "required",
            "description" => "required",
            "prix" => "required",
            "localisation" => "required",
            "categorie_id" => "required",
        ]);
        
        $formFields["vendeur_id"] = $vendeur_id;
        $formFields["dateDepot"] = now();
        
        // Créer le produit
        $product = Product::create($formFields);
        
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
