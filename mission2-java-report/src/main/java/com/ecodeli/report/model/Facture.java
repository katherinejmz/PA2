package com.ecodeli.report.model;

import java.util.Date;

public class Facture {
    public String reference;
    public Date dateFacture;
    public double montant;

    public Facture(String reference, Date dateFacture, double montant) {
        this.reference = reference;
        this.dateFacture = dateFacture;
        this.montant = montant;
    }
}
