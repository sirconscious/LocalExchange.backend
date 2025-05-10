<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'status',
        'categorie_id',
        'user_id',
        'dateDepot',
        'etat'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    // Relationships

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'produit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
}
