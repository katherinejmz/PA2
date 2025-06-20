import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function EditMesAnnonce() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user, token } = useAuth();

  const [annonce, setAnnonce] = useState(null);
  const [form, setForm] = useState({ titre: "", description: "", prix_propose: "", lieu_depart: "", lieu_arrivee: "", photo: "" });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAnnonce();
  }, [id]);

  const fetchAnnonce = async () => {
    try {
      const res = await api.get(`/annonces/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      const data = res.data;
      setAnnonce(data);
      setForm({
        titre: data.titre,
        description: data.description,
        prix_propose: data.prix_propose,
        lieu_depart: data.lieu_depart || "",
        lieu_arrivee: data.lieu_arrivee || "",
        photo: data.photo || "",
      });
      setLoading(false);
    } catch (err) {
      console.error("Erreur chargement annonce:", err);
      alert("Annonce introuvable ou accès refusé.");
      navigate("/mes-annonces");
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleUpdate = async (e) => {
    e.preventDefault();
    try {
      await api.patch(`/annonces/${id}`, form, {
        headers: { Authorization: `Bearer ${token}` },
      });
      alert("Annonce mise à jour");
      fetchAnnonce();
    } catch (err) {
      console.error("Erreur mise à jour:", err);
      alert("Erreur lors de la mise à jour.");
    }
  };

  const handleDelete = async () => {
    if (!window.confirm("Confirmer la suppression de cette annonce ?")) return;
    try {
      await api.delete(`/annonces/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      alert("Annonce supprimée.");
      navigate("/mes-annonces");
    } catch (err) {
      console.error("Erreur suppression:", err);
      alert("Impossible de supprimer l'annonce.");
    }
  };

  const progression = (etapes) => {
    if (!etapes || etapes.length === 0) return "0% livrée";
    const done = etapes.filter(e => e.statut === "terminee").length;
    return `${Math.round((done / etapes.length) * 100)}% livrée`;
  };

  if (loading) return <p className="text-center mt-10">Chargement...</p>;

  const estProprietaire =
    (user?.role === "client" && annonce?.id_client === user.id) ||
    (user?.role === "commercant" && annonce?.id_commercant === user.id);

  return (
    <div className="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4">Modifier l'annonce</h2>

      {!estProprietaire ? (
        <p className="text-red-600">Vous n'avez pas le droit de modifier cette annonce.</p>
      ) : (
        <form onSubmit={handleUpdate} className="space-y-4">
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

          <input
            type="text"
            name="lieu_depart"
            placeholder="Lieu de départ"
            value={form.lieu_depart}
            onChange={handleChange}
            className="w-full p-2 border rounded"
          />

          <input
            type="text"
            name="lieu_arrivee"
            placeholder="Lieu d'arrivée"
            value={form.lieu_arrivee}
            onChange={handleChange}
            className="w-full p-2 border rounded"
          />

          <input
            type="text"
            name="photo"
            placeholder="URL de la photo"
            value={form.photo}
            onChange={handleChange}
            className="w-full p-2 border rounded"
          />

          <div className="flex gap-4">
            <button
              type="submit"
              className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
              Enregistrer
            </button>
            <button
              type="button"
              onClick={handleDelete}
              className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            >
              Supprimer
            </button>
          </div>
        </form>
      )}

      {/* Badge de progression + affichage des étapes */}
      <div className="mt-8">
        <h3 className="font-semibold mb-2">Suivi des étapes :</h3>
        <div className="text-sm text-blue-700 mb-2">Progression : {progression(annonce.etapes_livraison)}</div>

        {annonce.etapes_livraison?.length > 0 ? (
          <ul className="list-disc ml-6">
            {annonce.etapes_livraison.map((e) => (
              <li key={e.id}>
                {e.lieu_depart} → {e.lieu_arrivee} ({e.statut}) — {e.livreur?.prenom} {e.livreur?.nom}
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-gray-500">Aucune étape définie.</p>
        )}
      </div>
    </div>
  );
}
