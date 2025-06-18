<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $mois }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Facture du mois de {{ $mois }}</h1>
    <p>Prestataire : {{ $prestataire->prenom }} {{ $prestataire->nom }} (ID #{{ $prestataire->id }})</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Durée</th>
                <th>Tarif (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($interventions as $intervention)
            <tr>
                <td>{{ \Carbon\Carbon::parse($intervention->prestation->date_heure)->format('d/m/Y') }}</td>
                <td>{{ $intervention->prestation->type_prestation }}</td>
                <td>{{ $intervention->prestation->duree_estimee ?? 'N/A' }} min</td>
                <td>{{ number_format($intervention->prestation->tarif, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="text-align:right;">Total à verser : {{ number_format($total, 2) }} €</h3>
</body>
</html>
