<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Utilisateur extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'identifiant',
        'password',
        'role',
        'pays',
        'telephone',
        'adresse_postale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    // Mutator pour hasher automatiquement le mot de passe
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // Annonces créées par le client (type livraison_client, service ou produit_livre)
    public function annoncesClient()
    {
        return $this->hasMany(Annonce::class, 'id_client');
    }

    // Annonces créées par le commerçant (type produit_livre)
    public function annoncesCommercant()
    {
        return $this->hasMany(Annonce::class, 'id_commercant');
    }

    // Annonces créées par le prestataire (type service)
    public function annoncesPrestataire()
    {
        return $this->hasMany(Annonce::class, 'id_prestataire');
    }

    // Annonces pour lesquelles l'utilisateur est livreur
    public function livraisons()
    {
        return $this->belongsToMany(Annonce::class, 'annonce_utilisateur', 'utilisateur_id', 'annonce_id');
    }

    // Commandes faites par ce client
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'client_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBackOffice()
    {
        return $this->role === 'backoffice';
    }

    public function portefeuille()
    {
        return $this->hasOne(Portefeuille::class, 'utilisateur_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'utilisateur_id');
    }

    public function colisLivres()
    {
        return $this->hasMany(Colis::class, 'livreur_id');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Communication::class, 'expediteur_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Communication::class, 'destinataire_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function annoncesLivrees()
    {
        return $this->belongsToMany(Annonce::class, 'annonce_utilisateur', 'utilisateur_id', 'annonce_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function commercant()
    {
        return $this->hasOne(Commercant::class);
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }

    public function prestataire()
    {
        return $this->hasOne(Prestataire::class);
    }


}
