<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'produit_id',
        'url',
        'description',
    ];

    // Relationships

    public function produit()
    {
        return $this->belongsTo(Product::class, 'produit_id');
    }
}
