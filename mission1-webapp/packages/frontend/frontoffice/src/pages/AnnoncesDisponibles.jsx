import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function AnnoncesDisponibles() {
  const { token } = useAuth();
  const [annonces, setAnnonces] = useState([]);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchAnnonces = async () => {
      try {
        const res = await api.get("/annonces-disponibles", {
          headers: { Authorization: `Bearer ${token}` },
        });
        setAnnonces(res.data.annonces_disponibles);
      } catch (err) {
        console.error("Erreur API :", err);
        setError("Erreur lors du chargement des annonces.");
      }
    };

    fetchAnnonces();
  }, [token]);

  const handleAccepter = async (annonceId) => {
    if (!window.confirm("Accepter cette annonce ?")) return;

    try {
      await api.post(`/annonces/${annonceId}/accepter`, null, {
        headers: { Authorization: `Bearer ${token}` },
      });
      // Supprime l'annonce de la liste après acceptation
      setAnnonces((prev) => prev.filter((a) => a.id !== annonceId));
    } catch (err) {
      console.error("Erreur acceptation :", err);
      alert("Erreur lors de l'acceptation.");
    }
  };

  if (error) return <p className="text-red-600 text-center mt-10">{error}</p>;

  return (
    <div className="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-6">Annonces disponibles</h2>

      {annonces.length === 0 ? (
        <p>Aucune annonce disponible pour le moment.</p>
      ) : (
        <ul className="space-y-6">
          {annonces.map((a) => (
            <li key={a.id} className="border p-4 rounded shadow">
              <h3 className="text-xl font-semibold">{a.titre}</h3>
              <p>{a.description}</p>
              <p className="text-sm text-gray-600">Prix : {a.prix_propose} €</p>
              {a.lieu_depart && <p>Trajet : {a.lieu_depart} → {a.lieu_arrivee}</p>}
              <p className="text-xs text-gray-400">
                Créée par : {a.client?.prenom || a.commercant?.prenom} {a.client?.nom || a.commercant?.nom}
              </p>

              <button
                onClick={() => handleAccepter(a.id)}
                className="mt-4 bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800"
              >
                Accepter l’annonce
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
