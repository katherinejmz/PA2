import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";

export default function CreerAnnonce() {
  const { user, token } = useAuth();
  const navigate = useNavigate();

  // Déterminer le type selon le rôle
  let typeAnnonce = null;
  if (user?.role === "client") typeAnnonce = "livraison_client";
  else if (user?.role === "commercant") typeAnnonce = "produit_livre";

  const [entrepots, setEntrepots] = useState([]);
  const [form, setForm] = useState({
    titre: "",
    description: "",
    prix_propose: "",
    photo: "",
    entrepot_depart_id: "",
    entrepot_arrivee_id: "",
  });

  useEffect(() => {
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
    fetchEntrepots();
  }, [token]);

  if (!typeAnnonce) {
    return (
      <p className="text-center mt-10 text-red-600">
        Vous n'avez pas le droit de créer une annonce.
      </p>
    );
  }

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      await api.post(
        "/annonces",
        { ...form, type: typeAnnonce },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      alert("Annonce créée avec succès.");
      navigate("/annonces");
    } catch (err) {
      console.error(err);
      alert("Erreur lors de la création de l'annonce.");
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4">Créer une annonce</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          name="titre"
          placeholder="Titre"
          value={form.titre}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <textarea
          name="description"
          placeholder="Description"
          value={form.description}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <input
          type="number"
          name="prix_propose"
          placeholder="Prix proposé"
          value={form.prix_propose}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />

        {(typeAnnonce === "livraison_client" || typeAnnonce === "produit_livre") && (
          <>
            <select
              name="entrepot_depart_id"
              value={form.entrepot_depart_id}
              onChange={handleChange}
              className="w-full p-2 border rounded"
              required
            >
              <option value="">Ville de départ</option>
              {entrepots.map((e) => (
                <option key={e.id} value={e.id}>
                  {e.ville}
                </option>
              ))}
            </select>

            <select
              name="entrepot_arrivee_id"
              value={form.entrepot_arrivee_id}
              onChange={handleChange}
              className="w-full p-2 border rounded"
              required
            >
              <option value="">Ville d'arrivée</option>
              {entrepots.map((e) => (
                <option key={e.id} value={e.id}>
                  {e.ville}
                </option>
              ))}
            </select>
          </>
        )}

        <input
          type="text"
          name="photo"
          placeholder="URL de la photo (optionnel)"
          value={form.photo}
          onChange={handleChange}
          className="w-full p-2 border rounded"
        />

        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
          disabled={entrepots.length === 0}
        >
          Créer l'annonce
        </button>
      </form>
    </div>
  );
}
