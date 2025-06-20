import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesLivraisons() {
  const { token } = useAuth();
  const [etapes, setEtapes] = useState([]);

  const fetchEtapes = async () => {
    try {
      const res = await api.get("/mes-etapes", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setEtapes(res.data);
    } catch (err) {
      console.error("Erreur chargement étapes :", err);
    }
  };

  useEffect(() => {
    fetchEtapes();
  }, [token]);

  const handleStatutChange = async (etapeId, nouveauStatut) => {
    try {
      await api.patch(
        `/etapes/${etapeId}/statut`,
        { statut: nouveauStatut },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      fetchEtapes();
    } catch (err) {
      console.error("Erreur mise à jour statut :", err);
      alert("Impossible de changer le statut.");
    }
  };

  return (
    <div className="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-6">Mes étapes de livraison</h2>

      {etapes.length === 0 ? (
        <p>Aucune étape de livraison assignée pour le moment.</p>
      ) : (
        <ul className="space-y-6">
          {etapes.map((e) => (
            <li key={e.id} className="border p-4 rounded shadow-sm">
              <h3 className="text-xl font-semibold">
                {e.annonce?.titre || "Annonce sans titre"}
              </h3>
              <p className="text-gray-600">
                {e.annonce?.description || "Pas de description."}
              </p>
              <p><strong>Étape :</strong> {e.lieu_depart} → {e.lieu_arrivee}</p>
              <p className="text-sm text-gray-500">
                Créée par : {e.annonce?.client?.prenom || e.annonce?.commercant?.prenom}{" "}
                {e.annonce?.client?.nom || e.annonce?.commercant?.nom}
              </p>
              <p className="mt-2">
                <strong>Statut de l'étape :</strong>{" "}
                <span className="font-semibold">{e.statut}</span>
              </p>

              <div className="mt-2 flex gap-2 flex-wrap">
                {["en_attente", "en_cours", "terminee"].map((statut) => (
                  <button
                    key={statut}
                    onClick={() => handleStatutChange(e.id, statut)}
                    disabled={statut === e.statut}
                    className={`px-3 py-1 rounded text-white ${
                      statut === e.statut
                        ? "bg-blue-700 cursor-not-allowed"
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
