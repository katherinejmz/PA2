package com.ecodeli.report.graphs;

import com.ecodeli.report.model.Clients;
import com.ecodeli.report.model.Prestations;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartUtils;
import org.jfree.chart.JFreeChart;
import org.jfree.data.category.DefaultCategoryDataset;
import org.jfree.data.general.DefaultPieDataset;

import java.io.File;
import java.io.IOException;
import java.util.List;
import java.util.Map;

public class GraphsGenerateur {

    // Camembert CA par type client
    public static void genererCamembertCAparTypeClient(Map<String, Double> data, String cheminFichier)
            throws IOException {
        DefaultPieDataset<String> dataset = new DefaultPieDataset<>();
        data.forEach(dataset::setValue);

        JFreeChart chart = ChartFactory.createPieChart("Répartition du CA par type de client", dataset, true, true,
                false);
        sauvegarderGraphique(chart, cheminFichier);
    }

    // Bar chart Top 5 clients (nom vs nb commandes)
    public static void genererBarChartTop5Clients(List<Clients> topClients, String cheminFichier) throws IOException {
        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (Clients c : topClients) {
            dataset.addValue(c.nbCommandes, "Commandes", c.nom);
        }
        JFreeChart chart = ChartFactory.createBarChart("Top 5 clients les plus fidèles", "Client",
                "Nombre de commandes", dataset);
        sauvegarderGraphique(chart, cheminFichier);
    }

    // Camembert repartition prestations
    public static void genererCamembertPrestations(Map<String, Integer> data, String cheminFichier) throws IOException {
        DefaultPieDataset<String> dataset = new DefaultPieDataset<>();
        data.forEach(dataset::setValue);

        JFreeChart chart = ChartFactory.createPieChart("Répartition des prestations par type", dataset, true, true,
                false);
        sauvegarderGraphique(chart, cheminFichier);
    }

    // Bar chart Top 5 prestations (type vs fréquence)
    public static void genererBarChartTop5Prestations(List<Prestations> topPrestations, String cheminFichier)
            throws IOException {
        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (Prestations p : topPrestations) {
            dataset.addValue(p.frequence, "Fréquence", p.type);
        }
        JFreeChart chart = ChartFactory.createBarChart("Top 5 prestations les plus demandées", "Prestation",
                "Fréquence", dataset);
        sauvegarderGraphique(chart, cheminFichier);
    }

    private static void sauvegarderGraphique(JFreeChart chart, String cheminFichier) throws IOException {
        File fichier = new File(cheminFichier);
        if (fichier.getParentFile() != null && !fichier.getParentFile().exists()) {
            fichier.getParentFile().mkdirs();
        }
        ChartUtils.saveChartAsPNG(fichier, chart, 640, 480);
        System.out.println("Graphique sauvegardé : " + cheminFichier);
    }
}
