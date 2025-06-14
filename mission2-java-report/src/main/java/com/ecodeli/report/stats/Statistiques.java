package com.ecodeli.report.stats;

import com.ecodeli.report.model.Clients;
import com.ecodeli.report.model.Prestations;

import java.util.*;
import java.util.stream.Collectors;

public class Statistiques {

    // Répartition du CA par type de client
    public static Map<String, Double> repartitionCAparTypeClient(List<Clients> clients) {
        Map<String, Double> map = new HashMap<>();
        for (Clients c : clients) {
            map.put(c.typeClient, map.getOrDefault(c.typeClient, 0.0) + c.chiffreAffaires);
        }
        return map;
    }

    // Top 5 clients les plus fidèles (plus de commandes)
    public static List<Clients> top5ClientsFideles(List<Clients> clients) {
        return clients.stream()
                .sorted(Comparator.comparingInt(Clients::getNbCommandes).reversed())
                .limit(5)
                .collect(Collectors.toList());
    }

    // Répartition des prestations par type
    public static Map<String, Integer> repartitionPrestationsParType(List<Prestations> prestations) {
        Map<String, Integer> map = new HashMap<>();
        for (Prestations p : prestations) {
            map.put(p.type, map.getOrDefault(p.type, 0) + 1);
        }
        return map;
    }

    // Top 5 prestations les plus demandées (par fréquence)
    public static List<Prestations> top5Prestations(List<Prestations> prestations) {
        return prestations.stream()
                .sorted(Comparator.comparingInt(Prestations::getFrequence).reversed())
                .limit(5)
                .collect(Collectors.toList());
    }
}
