import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function AnnonceDetail() {
  const { id } = useParams();
  const { user, token } = useAuth();
  const [annonce, setAnnonce] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchAnnonce = async () => {
      try {
        const res = await api.get(`/annonces/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setAnnonce(res.data);
      } catch (error) {
        console.error("Erreur lors de la récupération de l'annonce :", error);
      }
    };

    fetchAnnonce();
  }, [id, token]);

  const accepterAnnonce = async () => {
    try {
      await api.post(`/annonces/${id}/accepter`, {}, {
        headers: { Authorization: `Bearer ${token}` },
      });
      alert("Annonce acceptée !");
      navigate("/annonces");
    } catch (error) {
      alert("Erreur lors de l'acceptation.");
      console.error(error);
    }
  };

  const commanderAnnonce = async () => {
    try {
      const res = await api.post("/commandes", {
        annonce_id: annonce.id,
        montant: annonce.prix_propose,
      }, {
        headers: { Authorization: `Bearer ${token}` },
      });
  
      const commandeId = res.data.commande.id;
  
      if (annonce.type === "produit_livre") {
        navigate(`/adresse-livraison/${commandeId}`);
      } else if (annonce.type === "service") {
        navigate(`/details-service/${commandeId}`);
      }
  
    } catch (error) {
      alert("Erreur lors de la commande.");
      console.error(error);
    }
  };
  
  

  if (!annonce) {
    return <p className="text-center mt-10">Chargement...</p>;
  }

  return (
    <div className="max-w-2xl mx-auto mt-8 bg-white shadow rounded p-6">
      {annonce.photo && (
        <img
          src={annonce.photo.startsWith("http") ? annonce.photo : `/uploads/${annonce.photo}`}
          alt="Photo de l'annonce"
          className="w-full h-64 object-cover rounded mb-4"
        />
      )}
  
      <h1 className="text-2xl font-bold mb-2">{annonce.titre}</h1>
      <p className="text-gray-600 mb-4">{annonce.description}</p>
      <ul className="mb-4 space-y-1 text-sm text-gray-700">
        <li><strong>Type :</strong> {annonce.type}</li>
        <li><strong>Prix proposé :</strong> {annonce.prix_propose} €</li>
        <li><strong>Lieu de départ :</strong> {annonce.lieu_depart}</li>
        <li><strong>Lieu d’arrivée :</strong> {annonce.lieu_arrivee}</li>
        <li><strong>Publié le :</strong> {new Date(annonce.created_at).toLocaleDateString()}</li>
      </ul>
  
      {user?.role === "livreur" &&
        ["livraison_client", "produit_livre"].includes(annonce.type) && (
          <button onClick={accepterAnnonce} className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Accepter cette annonce
          </button>
      )}
  
      {user?.role === "client" &&
        ["produit_livre", "service"].includes(annonce.type) && (
          <button onClick={commanderAnnonce} className="ml-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Commander
          </button>
      )}
    </div>
  );
  
}
