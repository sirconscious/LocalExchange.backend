<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caracteristique extends Model
{
    protected $fillable = [
        'produit_id',
        'caracteristique',
    ];

    public function produit()
    {
        return $this->belongsTo(Product::class); 

    }
    public function getCaracteristiqueAttribute($value)
    {
        return json_decode($value, true);
    }
}
