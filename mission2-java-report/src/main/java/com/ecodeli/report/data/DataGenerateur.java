package com.ecodeli.report.data;

import com.ecodeli.report.model.*;

import com.github.javafaker.Faker;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Random;

public class DataGenerateur {

    private static final Faker faker = new Faker();
    private static final Random random = new Random();

    // Génère des factures aléatoires
    public static List<Facture> genererFactures(int nombre) {
        List<Facture> factures = new ArrayList<>();
        for (int i = 0; i < nombre; i++) {
            String ref = "FAC-" + faker.number().digits(6);
            Date date = faker.date().past(365, java.util.concurrent.TimeUnit.DAYS);
            double montant = 100 + random.nextDouble() * 2000;
            factures.add(new Facture(ref, date, montant));
        }
        return factures;
    }

    // Génère la liste des clients avec factures
    public static List<Clients> genererClients() {
        List<Clients> clients = new ArrayList<>();
        String[] types = { "Particulier", "Professionnel", "Entreprise" };

        for (int i = 0; i < 30; i++) {
            String nom = faker.name().fullName();
            String typeClient = types[random.nextInt(types.length)];
            List<Facture> factures = genererFactures(1 + random.nextInt(5));
            double ca = factures.stream().mapToDouble(f -> f.montant).sum();
            int nbCommandes = factures.size();
            clients.add(new Clients(nom, typeClient, ca, nbCommandes));
        }
        return clients;
    }

    // Génère prestations comme avant
    public static List<Prestations> genererPrestations() {
        List<Prestations> prestations = new ArrayList<>();
        String[] types = {
                "Salles", "Matériel", "Service de nettoyage", "Transport", "Catering"
        };

        for (int i = 0; i < 30; i++) {
            String type = types[random.nextInt(types.length)];
            int frequence = 1 + random.nextInt(15);
            double tarif = 10 + random.nextDouble() * 90;
            prestations.add(new Prestations(type, frequence, tarif));
        }

        return prestations;
    }

    // Génère livraisons comme avant
    public static List<Livraisons> genererLivraisons() {
        List<Livraisons> livraisons = new ArrayList<>();
        String[] types = { "Colis", "Document", "Petit paquet", "Objet fragile" };
        String[] modalites = { "A domicile", "Point relais", "En main propre", "Casier connecté" };

        for (int i = 0; i < 30; i++) {
            String type = types[random.nextInt(types.length)];
            String contenu = faker.commerce().productName();
            String modalite = modalites[random.nextInt(modalites.length)];
            livraisons.add(new Livraisons(type, contenu, modalite));
        }

        return livraisons;
    }
}
