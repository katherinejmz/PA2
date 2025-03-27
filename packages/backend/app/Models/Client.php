<?php

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'adresse', 'telephone', 'date_inscription'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_client');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_client');
    }
}

