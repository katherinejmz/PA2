import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function Annonces() {
  const [annonces, setAnnonces] = useState([]);
  const { user, token } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (user?.role === "livreur") {
      navigate("/annonces-disponibles");
    }
  }, [user, navigate]);

  useEffect(() => {
    const fetchAnnonces = async () => {
      try {
        const res = await api.get("/annonces", {
          headers: { Authorization: `Bearer ${token}` },
        });

        // Filtrer uniquement les annonces de type "produit_livre"
        const annoncesFiltrees = res.data.filter(a => a.type === "produit_livre");
        setAnnonces(annoncesFiltrees);
      } catch (error) {
        console.error("Erreur lors du chargement des annonces :", error);
      }
    };

    fetchAnnonces();
  }, [token]);

  return (
    <div className="max-w-4xl mx-auto mt-8">
      <h2 className="text-2xl font-bold mb-6">Annonces disponibles</h2>

      {["client", "commercant", "prestataire"].includes(user?.role) && (
        <button
          onClick={() => navigate("/annonces/creer")}
          className="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Créer une annonce
        </button>
      )}

      {annonces.length === 0 ? (
        <p>Aucune annonce trouvée.</p>
      ) : (
        <ul className="space-y-4">
          {annonces.map((annonce) => (
            <li key={annonce.id} className="p-4 border rounded bg-white shadow">
              <h3
                onClick={() => navigate(`/annonces/${annonce.id}`)}
                className="text-lg font-semibold text-blue-600 hover:underline cursor-pointer"
              >
                {annonce.titre}
              </h3>
              <p>{annonce.description}</p>
              <p className="text-sm text-gray-500">
                Type : {annonce.type} • Prix : {annonce.prix_propose} € • Départ : {annonce.entrepot_depart?.ville || "❓"} → {annonce.entrepot_arrivee?.ville || "❓"}
              </p>
              <p className="text-xs text-gray-400">
                Publié le : {new Date(annonce.created_at).toLocaleDateString()}
              </p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
