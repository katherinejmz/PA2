<?php

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'vehicule', 'note_moyenne', 'nombre_livraisons'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id');
    }

    public function livraisons()
    {
        return $this->hasMany(Livraison::class, 'id_livreur');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_livreur');
    }
}

