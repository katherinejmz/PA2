import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesLivraisons() {
  const { token } = useAuth();
  const [annonces, setAnnonces] = useState([]);

  const fetchLivraisons = async () => {
    try {
      const res = await api.get("/mes-livraisons", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setAnnonces(res.data);
    } catch (err) {
      console.error("Erreur chargement livraisons :", err);
    }
  };

  useEffect(() => {
    fetchLivraisons();
  }, [token]);

  const handleStatutChange = async (annonceId, nouveauStatut) => {
    try {
      await api.patch(
        `/annonces/${annonceId}/changer-statut`,
        { statut: nouveauStatut },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      fetchLivraisons();
    } catch (err) {
      console.error("Erreur mise à jour du statut :", err);
      alert("Impossible de changer le statut.");
    }
  };

  return (
    <div className="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-6">Mes livraisons</h2>

      {annonces.length === 0 ? (
        <p>Aucune livraison en cours.</p>
      ) : (
        <ul className="space-y-6">
          {annonces.map((a) => (
            <li key={a.id} className="border p-4 rounded shadow-sm">
              <h3 className="text-xl font-semibold">{a.titre}</h3>
              <p className="text-gray-600">{a.description}</p>
              <p><strong>Prix :</strong> {a.prix_propose} €</p>
              {a.lieu_depart && (
                <p><strong>Trajet :</strong> {a.lieu_depart} → {a.lieu_arrivee}</p>
              )}
              <p className="text-sm text-gray-500">
                Créée par : {a.client?.prenom || a.commercant?.prenom} {a.client?.nom || a.commercant?.nom}
              </p>
              <p className="mt-2">
                <strong>Statut actuel :</strong> <span className="font-semibold">{a.statut}</span>
              </p>

              <div className="mt-2 flex gap-2 flex-wrap">
                {["en_attente", "en_cours", "livre"].map((statut) => (
                  <button
                    key={statut}
                    onClick={() => handleStatutChange(a.id, statut)}
                    className={`px-3 py-1 rounded text-white ${
                      a.statut === statut
                        ? "bg-blue-700"
                        : "bg-blue-500 hover:bg-blue-600"
                    }`}
                  >
                    {statut.replace("_", " ")}
                  </button>
                ))}
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
