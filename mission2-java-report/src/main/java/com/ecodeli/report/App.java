package com.ecodeli.report;

import com.ecodeli.report.data.DataGenerateur;
import com.ecodeli.report.graphs.GraphsGenerateur;
import com.ecodeli.report.model.Clients;
import com.ecodeli.report.model.Prestations;
import com.ecodeli.report.stats.Statistiques;
import com.ecodeli.report.pdf.PdfGenerateur;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class App {

    public static void main(String[] args) {
        try {
            // 1. Générer les données
            List<Clients> clients = DataGenerateur.genererClients();
            List<Prestations> prestations = DataGenerateur.genererPrestations();

            // 2. Calculer les stats
            Map<String, Double> caParTypeClient = Statistiques.repartitionCAparTypeClient(clients);
            List<Clients> top5Clients = Statistiques.top5ClientsFideles(clients);
            Map<String, Integer> repartitionPrestations = Statistiques.repartitionPrestationsParType(prestations);
            List<Prestations> top5Prestations = Statistiques.top5Prestations(prestations);

            // 3. Générer graphiques et stocker les chemins
            List<String> cheminsGraphiques = new ArrayList<>();

            String graph1 = "src/main/resources/graphes/ca_type_client.png";
            GraphsGenerateur.genererCamembertCAparTypeClient(caParTypeClient, graph1);
            cheminsGraphiques.add(graph1);

            String graph2 = "src/main/resources/graphes/top5_clients.png";
            GraphsGenerateur.genererBarChartTop5Clients(top5Clients, graph2);
            cheminsGraphiques.add(graph2);

            String graph3 = "src/main/resources/graphes/repartition_prestations.png";
            GraphsGenerateur.genererCamembertPrestations(repartitionPrestations, graph3);
            cheminsGraphiques.add(graph3);

            String graph4 = "src/main/resources/graphes/top5_prestations.png";
            GraphsGenerateur.genererBarChartTop5Prestations(top5Prestations, graph4);
            cheminsGraphiques.add(graph4);

            // 4. Générer le PDF avec les graphiques
            String cheminPdf = "src/main/resources/pdfs/rapport_activite.pdf";
            PdfGenerateur.genererRapportAvecGraphiques(cheminsGraphiques, cheminPdf);

            System.out.println("Rapport PDF généré : " + cheminPdf);

        } catch (IOException e) {
            System.err.println("Erreur dans l’application : " + e.getMessage());
        }
    }
}
