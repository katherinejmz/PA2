<?php

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['id_client', 'id_livreur', 'id_prestataire', 'note', 'commentaire'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur');
    }

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }
}
