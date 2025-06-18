import { useEffect, useState } from 'react';
import api from '../../services/api';
import CardStat from '../../components/common/CardStat';

export default function Dashboard() {
  const [stats, setStats] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    api.get('/admin/statistiques')
      .then(res => setStats(res.data))
      .catch(err => {
        console.error('Erreur lors du chargement du dashboard', err);
        setError("Impossible de charger les statistiques");
      });
  }, []);

  if (error) return <p className="text-red-500 p-4">{error}</p>;
  if (!stats) return <p className="p-4">Chargement des statistiques...</p>;

  const data = [
    { title: 'Annonces publiées', value: stats.annonces },
    { title: 'Clients', value: stats.clients },
    { title: 'Commerçants', value: stats.commercants },
    { title: 'Prestataires', value: stats.prestataires },
    { title: 'Livreurs', value: stats.livreurs },
  ];

  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">Dashboard Admin</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {data.map((stat, i) => (
          <CardStat key={i} title={stat.title} value={stat.value} />
        ))}
      </div>
    </div>
  );
}
