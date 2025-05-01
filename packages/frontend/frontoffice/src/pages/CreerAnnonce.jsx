import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";

export default function CreerAnnonce() {
  const [description, setDescription] = useState("");
  const [lieu_depart, setLieuDepart] = useState("");
  const [lieu_arrivee, setLieuArrivee] = useState("");
  const [prix_propose, setPrixPropose] = useState("");
  const navigate = useNavigate();
  const { token, user } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post(
        "/annonces",
        {
          id_commercant: user.id_commercant,
          description,
          lieu_depart,
          lieu_arrivee,
          prix_propose
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      navigate("/annonces");
    } catch (error) {
      console.error("Erreur complète :", error);
      const message = error.response?.data?.message || error.message || "Erreur inconnue";
      alert("Erreur lors de la création : " + message);
    }
  };

  if (user?.role !== "commercant") {
    return <p className="text-center mt-10 text-red-600">Accès interdit : seuls les commerçants peuvent créer une annonce.</p>;
  }

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto mt-10 space-y-4 bg-white p-6 shadow rounded">
      <h2 className="text-2xl font-bold text-center">Créer une annonce</h2>

      <textarea
        placeholder="Description"
        value={description}
        onChange={(e) => setDescription(e.target.value)}
        className="w-full p-2 border rounded"
        required
      ></textarea>

      <input
        type="text"
        placeholder="Lieu de départ"
        value={lieu_depart}
        onChange={(e) => setLieuDepart(e.target.value)}
        className="w-full p-2 border rounded"
        required
      />

      <input
        type="text"
        placeholder="Lieu d’arrivée"
        value={lieu_arrivee}
        onChange={(e) => setLieuArrivee(e.target.value)}
        className="w-full p-2 border rounded"
        required
      />

      <input
        type="number"
        placeholder="Prix proposé"
        value={prix_propose}
        onChange={(e) => setPrixPropose(e.target.value)}
        className="w-full p-2 border rounded"
        step="0.01"
        required
      />

      <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        Publier l'annonce
      </button>
    </form>
  );
}
