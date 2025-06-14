import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function Annonces() {
  const [annonces, setAnnonces] = useState([]);
  const [titre, setTitre] = useState("");
  const [type, setType] = useState("");
  const [prixMin, setPrixMin] = useState("");
  const [lieuDepart, setLieuDepart] = useState("");
  const { user, token } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (user?.role === "livreur") {
      navigate("/annonces-disponibles");
    }
  }, [user, navigate]);

  const fetchAnnonces = async () => {
    try {
      const params = {};
      if (titre) params.titre = titre;
      if (type) params.type = type;
      if (prixMin) params.prix_min = prixMin;
      if (lieuDepart) params.lieu_depart = lieuDepart;

      const res = await api.get("/annonces", {
        headers: { Authorization: `Bearer ${token}` },
        params,
      });

      setAnnonces(res.data);
    } catch (error) {
      console.error("Erreur lors du chargement des annonces :", error);
    }
  };

  useEffect(() => {
    fetchAnnonces();
  }, [token]);

  const handleSearch = (e) => {
    e.preventDefault();
    fetchAnnonces();
  };

  const resetFilters = () => {
    setTitre("");
    setType("");
    setPrixMin("");
    setLieuDepart("");
    fetchAnnonces();
  };

  return (
    <div className="max-w-4xl mx-auto mt-8">
      <h2 className="text-2xl font-bold mb-6">Annonces disponibles</h2>

      <form onSubmit={handleSearch} className="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input
          type="text"
          placeholder="Titre"
          value={titre}
          onChange={(e) => setTitre(e.target.value)}
          className="p-2 border rounded"
        />
        <select
          value={type}
          onChange={(e) => setType(e.target.value)}
          className="p-2 border rounded"
        >
          <option value="">Tous les types</option>
          <option value="livraison_client">Livraison client</option>
          <option value="produit_livre">Produit à livrer</option>
          <option value="service">Service</option>
        </select>
        <input
          type="number"
          placeholder="Prix min"
          value={prixMin}
          onChange={(e) => setPrixMin(e.target.value)}
          className="p-2 border rounded"
        />
        <input
          type="text"
          placeholder="Lieu de départ"
          value={lieuDepart}
          onChange={(e) => setLieuDepart(e.target.value)}
          className="p-2 border rounded"
        />
        <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-2">
          Rechercher
        </button>
        <button type="button" onClick={resetFilters} className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 col-span-2">
          Réinitialiser
        </button>
      </form>

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
                Type : {annonce.type} • Prix : {annonce.prix_propose} € • Départ : {annonce.lieu_depart}
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
