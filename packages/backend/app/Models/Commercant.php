<?php

class Commercant extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'entreprise', 'adresse', 'siret'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_commercant');
    }
}

