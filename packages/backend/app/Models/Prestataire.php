<?php

class Prestataire extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type_de_service', 'tarif_horaire'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id');
    }

    public function livraisons()
    {
        return $this->hasMany(Livraison::class, 'id_prestataire');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_prestataire');
    }
}

