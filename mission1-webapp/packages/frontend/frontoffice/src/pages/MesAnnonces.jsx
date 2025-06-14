import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesAnnonces() {
  const { token } = useAuth();
  const [annonces, setAnnonces] = useState([]);

  const fetchAnnonces = async () => {
    try {
      const res = await api.get("/mes-annonces", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setAnnonces(res.data);
    } catch (err) {
      console.error("Erreur lors du chargement :", err);
    }
  };

  useEffect(() => {
    fetchAnnonces();
  }, [token]);

  const handleDelete = async (id) => {
    if (!window.confirm("Confirmez-vous l'annulation de cette annonce ?")) return;
    try {
      await api.delete(`/annonces/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setAnnonces((prev) => prev.filter((a) => a.id !== id));
    } catch (err) {
      console.error("Erreur suppression :", err);
      alert("Échec de l'annulation.");
    }
  };

  const afficherStatut = (statut) => {
    switch (statut) {
      case "en_attente":
        return "🕐 En attente";
      case "acceptee":
        return "✅ Acceptée";
      case "en_cours":
        return "📦 En cours de livraison";
      case "livree":
        return "🎉 Livrée";
      default:
        return "❓ Inconnu";
    }
  };

  return (
    <div className="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-6">Mes annonces</h2>

      {annonces.length === 0 ? (
        <p>Aucune annonce trouvée.</p>
      ) : (
        <ul className="space-y-6">
          {annonces.map((a) => (
            <li key={a.id} className="border p-4 rounded shadow-sm">
              <h3 className="text-xl font-semibold">{a.titre}</h3>
              <p className="text-gray-600 mb-2">{a.description}</p>
              <p><strong>Prix :</strong> {a.prix_propose} €</p>
              {a.lieu_depart && (
                <p><strong>Trajet :</strong> {a.lieu_depart} → {a.lieu_arrivee}</p>
              )}

              <p className="mt-2 text-blue-700 font-medium">
                Statut : {afficherStatut(a.statut)}
              </p>

              {a.livreurs?.length > 0 ? (
                <div className="mt-2 text-green-600 font-medium">
                  ✅ Livreurs assignés :
                  <ul className="list-disc ml-6">
                    {a.livreurs.map((l) => (
                      <li key={l.id}>
                        {l.prenom} {l.nom} ({l.telephone})
                        {l.pivot?.accepte_le && (
                          <span className="text-gray-500 text-sm ml-2">
                            — accepté le {new Date(l.pivot.accepte_le).toLocaleString()}
                          </span>
                        )}
                      </li>
                    ))}
                  </ul>
                </div>
              ) : (
                <p className="mt-2 text-yellow-600">⏳ En attente d’un livreur</p>
              )}

              <button
                onClick={() => handleDelete(a.id)}
                className="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
              >
                Annuler l’annonce
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
