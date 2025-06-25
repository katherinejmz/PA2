import { useEffect, useState } from "react";
import api from "../../services/api";

export default function AnnoncesList() {
  const [annonces, setAnnonces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [form, setForm] = useState({ titre: "", description: "", prix_propose: "" });
  const [editingId, setEditingId] = useState(null);
  const [errors, setErrors] = useState({});
  const [apiError, setApiError] = useState("");

  useEffect(() => {
    fetchAnnonces();
  }, []);

  const fetchAnnonces = async () => {
    try {
      const res = await api.get("/annonces");
      setAnnonces(res.data);
    } catch (err) {
      console.error(err);
      alert("Erreur lors du chargement des annonces");
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    if (editingId) {
      await updateAnnonce();
    } else {
      await createAnnonce();
    }
  };

  const createAnnonce = async () => {
    try {
      const res = await api.post("/annonces", form);
      setAnnonces([...annonces, res.data.annonce]);
      resetForm();
    } catch (err) {
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else {
        setApiError(err.response?.data?.message || "Erreur lors de la création");
      }
    }
  };

  const updateAnnonce = async () => {
    try {
      const res = await api.patch(`/annonces/${editingId}`, form);
      setAnnonces((prev) =>
        prev.map((a) => (a.id === editingId ? res.data.annonce : a))
      );
      resetForm();
    } catch (err) {
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      }
      setApiError(err.response?.data?.message || "Erreur lors de la mise à jour");
    }
  };

  const startEdit = (annonce) => {
    setEditingId(annonce.id);
    setForm({
      titre: annonce.titre || "",
      description: annonce.description || "",
      prix_propose: annonce.prix_propose || "",
    });
    setErrors({});
  };

  const resetForm = () => {
    setForm({ titre: "", description: "", prix_propose: "" });
    setEditingId(null);
  };

  const deleteAnnonce = async (id) => {
    if (!window.confirm("Confirmer la suppression de l'annonce ?")) return;
    setApiError("");

    if (!id) {
      setApiError("ID de l'annonce manquant pour la suppression");
      return;
    }

    try {
      await api.delete(`/annonces/${id}`);
      setAnnonces((prev) => prev.filter((a) => a.id !== id));
    } catch (err) {
      const message = err.response?.data?.message || "Erreur lors de la suppression";
      setApiError(message);
    }
  };

  if (loading) return <div className="p-4">Chargement...</div>;

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Gestion des annonces</h1>
      {apiError && (
        <p className="text-red-600" role="alert">
          {apiError}
        </p>
      )}

      <form onSubmit={handleSubmit} className="bg-white shadow p-4 rounded space-y-4">
        <h2 className="text-xl font-semibold">
          {editingId ? "Modifier l'annonce" : "Nouvelle annonce"}
        </h2>
        <div>
          <label className="block font-semibold">Titre</label>
          <input
            type="text"
            name="titre"
            value={form.titre}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.titre && <p className="text-red-600 text-sm">{errors.titre[0]}</p>}
        </div>
        <div>
          <label className="block font-semibold">Description</label>
          <textarea
            name="description"
            value={form.description}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.description && (
            <p className="text-red-600 text-sm">{errors.description[0]}</p>
          )}
        </div>
        <div>
          <label className="block font-semibold">Prix proposé</label>
          <input
            type="number"
            name="prix_propose"
            value={form.prix_propose}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.prix_propose && (
            <p className="text-red-600 text-sm">{errors.prix_propose[0]}</p>
          )}
        </div>
        <div className="flex gap-4">
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            {editingId ? "Mettre à jour" : "Ajouter"}
          </button>
          {editingId && (
            <button
              type="button"
              onClick={resetForm}
              className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
            >
              Annuler
            </button>
          )}
        </div>
      </form>

      <div className="overflow-x-auto">
        <table className="min-w-full bg-white rounded shadow">
          <thead>
            <tr className="bg-gray-100 text-left text-sm uppercase text-gray-600">
              <th className="p-3">Titre</th>
              <th className="p-3">Description</th>
              <th className="p-3">Prix</th>
              <th className="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            {annonces.map((a) => (
              <tr key={a.id} className="border-b hover:bg-gray-50">
                <td className="p-3">{a.titre}</td>
                <td className="p-3">{a.description}</td>
                <td className="p-3">{a.prix_propose}</td>
                <td className="p-3 space-x-2">
                  <button
                    onClick={() => startEdit(a)}
                    className="text-yellow-600 hover:underline"
                  >
                    Modifier
                  </button>
                  <button
                    onClick={() => deleteAnnonce(a.id)}
                    className="text-red-600 hover:underline"
                  >
                    Supprimer
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}