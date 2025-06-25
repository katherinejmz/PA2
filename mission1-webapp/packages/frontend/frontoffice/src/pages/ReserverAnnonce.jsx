import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function ReserverAnnonce() {
  const { annonceId } = useParams();
  const { token, user } = useAuth();
  const navigate = useNavigate();

  const [annonce, setAnnonce] = useState(null);
  const [entrepots, setEntrepots] = useState([]);
  const [entrepotArriveeId, setEntrepotArriveeId] = useState("");
  const [message, setMessage] = useState("");

  useEffect(() => {
    if (user?.role !== "client") {
      navigate("/");
    }

    const fetchAnnonce = async () => {
      try {
        const res = await api.get(`/annonces/${annonceId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        setAnnonce(res.data);
      } catch (err) {
        console.error("Erreur chargement annonce :", err);
      }
    };

    const fetchEntrepots = async () => {
      try {
        const res = await api.get("/entrepots", {
          headers: { Authorization: `Bearer ${token}` },
        });
        setEntrepots(res.data);
      } catch (err) {
        console.error("Erreur chargement entrepôts :", err);
      }
    };

    fetchAnnonce();
    fetchEntrepots();
  }, [annonceId, token, user, navigate]);

  const reserver = async () => {
    if (!entrepotArriveeId) {
      setMessage("Veuillez sélectionner un entrepôt d’arrivée.");
      return;
    }

    try {
      await api.post(
        `/annonces/${annonceId}/reserver`,
        { entrepot_arrivee_id: entrepotArriveeId },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      setMessage("✅ Réservation effectuée avec succès !");
      setTimeout(() => navigate("/annonces"), 1500);
    } catch (err) {
      console.error("Erreur réservation :", err);
      setMessage(err.response?.data?.message || "Erreur lors de la réservation.");
    }
  };

  if (!annonce) return <p className="text-center mt-10">Chargement...</p>;

  return (
    <div className="max-w-2xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4">Réserver l’annonce</h2>

      <p className="mb-2"><strong>Titre :</strong> {annonce.titre}</p>
      <p className="mb-2"><strong>Description :</strong> {annonce.description}</p>
      <p className="mb-2"><strong>Prix proposé :</strong> {annonce.prix_propose} €</p>
      <p className="mb-4"><strong>Départ :</strong> {annonce.entrepot_depart?.ville || "❓"}</p>

      <div className="mb-4">
        <label className="block font-medium mb-1">Sélectionnez un entrepôt d’arrivée :</label>
        <select
          value={entrepotArriveeId}
          onChange={(e) => setEntrepotArriveeId(e.target.value)}
          className="w-full p-2 border rounded"
        >
          <option value="">-- Choisir un entrepôt --</option>
          {entrepots.map((e) => (
            <option key={e.id} value={e.id}>
              {e.ville}
            </option>
          ))}
        </select>
      </div>

      <button
        onClick={reserver}
        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Confirmer la réservation
      </button>

      {message && <p className="mt-4 text-sm text-blue-700">{message}</p>}
    </div>
  );
}
