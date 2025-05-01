import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function Annonces() {
  const [annonces, setAnnonces] = useState([]);
  const { user, token } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    const fetchAnnonces = async () => {
      try {
        const res = await api.get("/annonces", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setAnnonces(res.data);
      } catch (error) {
        console.error("Erreur lors du chargement des annonces :", error);
      }
    };

    fetchAnnonces();
  }, [token]);

  return (
    <div className="max-w-3xl mx-auto mt-8">
      <h2 className="text-2xl font-bold mb-4">Annonces disponibles</h2>

      {user?.role === "commercant" && (
        <button
            onClick={() => navigate("/annonces/creer")}
            className="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
            Créer une annonce
        </button>
        )}

      {annonces.length === 0 ? (
        <p>Aucune annonce pour le moment.</p>
      ) : (
        <ul className="space-y-4">
          {annonces.map((annonce) => (
            <li key={annonce.id} className="p-4 border rounded bg-white shadow">
              <h3 className="text-lg font-semibold">{annonce.titre}</h3>
              <p>{annonce.description}</p>
              <p className="text-sm text-gray-500">Publié le : {new Date(annonce.created_at).toLocaleDateString()}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
