<?php

namespace App\Http\Controllers;

use App\Models\Caracteristique;
use Illuminate\Http\Request;

class CaraquteristiqueController extends Controller
{
    public function show($produit_id)
    {
        $caracteristique = Caracteristique::where('produit_id', $produit_id)->first();
        if ($caracteristique) {
            return response()->json($caracteristique);
        } else {
            return response()->json(['message' => 'Caracteristique not found'], 404);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:products,id',
            'caracteristique' => 'required|string',
        ]);

        $caracteristique = new Caracteristique();
        $caracteristique->produit_id = $request->produit_id;
        $caracteristique->caracteristique = json_encode($request->caracteristique);
        $caracteristique->save();

        return response()->json($caracteristique, 201);
    }
    public function update(Request $request, $id)
    {
        $caracteristique = Caracteristique::find($id);
        if ($caracteristique) {
            $request->validate([
                'produit_id' => 'required|exists:produits,id',
                'caracteristique' => 'required|string',
            ]);

            $caracteristique->produit_id = $request->produit_id;
            $caracteristique->caracteristique = json_encode($request->caracteristique);
            $caracteristique->save();

            return response()->json($caracteristique);
        } else {
            return response()->json(['message' => 'Caracteristique not found'], 404);
        }
    }
}
