<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nom',
    ];

    // Relationships

    public function produits()
    {
        return $this->hasMany(Product::class, 'categorie_id');
    }
}
