import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function Interventions() {
  const { token, user } = useAuth();
  const [interventions, setInterventions] = useState([]);
  const [commentaires, setCommentaires] = useState({});
  const [notes, setNotes] = useState({});

  const fetchInterventions = async () => {
    try {
      const res = await api.get("/interventions", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setInterventions(res.data);
    } catch (err) {
      console.error("Erreur lors du chargement :", err);
    }
  };

  useEffect(() => {
    fetchInterventions();
  }, [token]);

  const handleUpdate = async (id) => {
    try {
      await api.put(`/interventions/${id}`, {
        commentaire_client: commentaires[id],
        note: notes[id],
      }, {
        headers: { Authorization: `Bearer ${token}` },
      });
      fetchInterventions();
    } catch (err) {
      console.error("Erreur lors de l'évaluation :", err);
    }
  };

  return (
    <div className="max-w-4xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Mes interventions</h2>

      {interventions.length === 0 ? (
        <p>Aucune intervention enregistrée.</p>
      ) : (
        <ul className="space-y-4">
          {interventions.map((intervention) => {
            const prestation = intervention.prestation;

            return (
              <li key={intervention.id} className="p-4 border rounded bg-white shadow">
                <h3 className="text-lg font-semibold text-blue-600 capitalize">
                  {prestation.type_prestation}
                </h3>
                <p className="text-gray-700">{prestation.description}</p>
                <p className="text-sm text-gray-500">
                  📅 {new Date(prestation.date_heure).toLocaleString()} • ⏱️ {prestation.duree_estimee ?? "?"} min • 💶 {prestation.tarif} €
                </p>
                <p className={`text-sm font-semibold mt-1 ${
                  intervention.statut_final === "effectuée"
                    ? "text-green-600"
                    : "text-red-500"
                }`}>
                  Statut final : {intervention.statut_final}
                </p>

                {/* Évaluation par le client uniquement */}
                {user.role === "client" && intervention.statut_final === "effectuée" && (
                  <div className="mt-4 space-y-2">
                    <textarea
                      rows={2}
                      placeholder="Commentaire"
                      value={commentaires[intervention.id] || ""}
                      onChange={(e) =>
                        setCommentaires({ ...commentaires, [intervention.id]: e.target.value })
                      }
                      className="w-full p-2 border rounded"
                    />
                    <input
                      type="number"
                      min={1}
                      max={5}
                      value={notes[intervention.id] || ""}
                      onChange={(e) =>
                        setNotes({ ...notes, [intervention.id]: e.target.value })
                      }
                      className="p-2 border rounded w-20"
                      placeholder="Note"
                    />
                    <button
                      onClick={() => handleUpdate(intervention.id)}
                      className="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                      Enregistrer l'avis
                    </button>
                  </div>
                )}

                {intervention.note && (
                  <p className="text-sm text-yellow-600 mt-2">
                    🌟 Note : {intervention.note} / 5
                  </p>
                )}

                {intervention.commentaire_client && (
                  <p className="text-sm text-gray-600 italic mt-1">
                    💬 "{intervention.commentaire_client}"
                  </p>
                )}
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}
