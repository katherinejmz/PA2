package com.ecodeli.report.model;

public class Clients {
    public String nom;
    public String typeClient;
    public double chiffreAffaires;
    public int nbCommandes;

    public Clients(String nom, String typeClient, double chiffreAffaires, int nbCommandes) {
        this.nom = nom;
        this.typeClient = typeClient;
        this.chiffreAffaires = chiffreAffaires;
        this.nbCommandes = nbCommandes;
    }

    // Getter pour nbCommandes (utile pour Statistiques)
    public int getNbCommandes() {
        return nbCommandes;
    }
}
