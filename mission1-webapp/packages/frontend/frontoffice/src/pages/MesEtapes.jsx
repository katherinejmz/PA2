import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesEtapes() {
  const { token } = useAuth();
  const [etapes, setEtapes] = useState([]);

  const fetchEtapes = async () => {
    try {
      const res = await api.get("/mes-etapes", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setEtapes(res.data);
    } catch (err) {
      console.error("Erreur chargement etapes:", err);
    }
  };

  useEffect(() => {
    fetchEtapes();
  }, [token]);

  const cloturerEtape = async (id) => {
    if (!window.confirm("Confirmer la fin de cette livraison ?")) return;
    try {
      await api.patch(`/etapes/${id}/cloturer`, null, {
        headers: { Authorization: `Bearer ${token}` },
      });
      alert("Étape marquée comme terminée.");
      fetchEtapes();
    } catch (err) {
      console.error("Erreur cloture:", err);
      alert("Impossible de terminer cette étape.");
    }
  };

  return (
    <div className="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-6">Mes étapes de livraison</h2>

      {etapes.length === 0 ? (
        <p>Aucune étape en cours.</p>
      ) : (
        <ul className="space-y-6">
          {etapes.map((e) => (
            <li key={e.id} className="border p-4 rounded shadow">
              <h3 className="text-lg font-semibold">
                {e.lieu_depart} → {e.lieu_arrivee}
              </h3>
              <p className="text-sm text-gray-600">Statut : {e.statut}</p>
              <p className="text-sm text-gray-600">
                Annonce : {e.annonce?.titre || "-"}
              </p>

              {e.statut === "en_cours" && (
                <button
                  onClick={() => cloturerEtape(e.id)}
                  className="mt-3 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                  Marquer comme livrée
                </button>
              )}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
