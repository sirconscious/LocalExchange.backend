<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens ;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', 
        "image" , 
        "phone" , 
        "city"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function roles()
{
    return $this->belongsToMany(Role::class);
}

public function hasRole($role)
{
    return $this->roles()->where('name', $role)->exists();
}



// public function produits()
// {
//     return $this->hasMany(Product::class, 'vendeur_id');
// }

// public function transactionsEnTantQuAcheteur()
// {
//     return $this->hasMany(Transaction::class, 'acheteur_id');
// }

// public function transactionsEnTantQueVendeur()
// {
//     return $this->hasMany(Transaction::class, 'vendeur_id');
// }

// public function messagesEnvoyes()
// {
//     return $this->hasMany(Message::class, 'expediteur_id');
// }

// public function messagesRecus()
// {
//     return $this->hasMany(Message::class, 'destinataire_id');
// }

// public function avisRediges()
// {
//     return $this->hasMany(Avis::class, 'auteur_id');
// }

// public function avisRecus()
// {
//     return $this->hasMany(Avis::class, 'cible_id');
// }




}
