package com.ecodeli.report.model;

public class Prestations {
    public String type;
    public int frequence;
    public double tarif;

    public Prestations(String type, int frequence, double tarif) {
        this.type = type;
        this.frequence = frequence;
        this.tarif = tarif;
    }

    // Getter pour frequence (utile pour Statistiques)
    public int getFrequence() {
        return frequence;
    }
}
