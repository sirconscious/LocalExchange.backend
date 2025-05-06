<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{ 
    use HasFactory ;
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'localisation',
        'vendeur_id',
        'categorie_id',
        'dateDepot', 
        'etat'
    ];

    // Relationships

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'produit_id');
    }
}
