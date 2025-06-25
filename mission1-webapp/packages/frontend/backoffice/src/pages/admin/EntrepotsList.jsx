import { useEffect, useState } from "react";
import api from "../../services/api";

export default function EntrepotsList() {
  const [entrepots, setEntrepots] = useState([]);
  const [loading, setLoading] = useState(true);
  const [form, setForm] = useState({
    nom: "",
    adresse: "",
    ville: "",
    code_postal: "",
  });
  const [editingId, setEditingId] = useState(null);
  const [errors, setErrors] = useState({});
  const [apiError, setApiError] = useState("");

  useEffect(() => {
    fetchEntrepots();
  }, []);

  async function fetchEntrepots() {
    try {
      const res = await api.get("/entrepots");
      setEntrepots(res.data);
    } catch (err) {
      console.error(err);
      setApiError("Erreur lors du chargement des entrep\xF4ts");
    } finally {
      setLoading(false);
    }
  }

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setApiError("");

    if (editingId) {
      await updateEntrepot();
    } else {
      await createEntrepot();
    }
  };

  async function createEntrepot() {
    try {
      const res = await api.post("/entrepots", form);
      setEntrepots([...entrepots, res.data.entrepot]);
      resetForm();
    } catch (err) {
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else {
        setApiError(err.response?.data?.message || "Erreur lors de la cr\xE9ation");
      }
    }
  }

  async function updateEntrepot() {
    try {
      const res = await api.patch(`/entrepots/${editingId}`, form);
      setEntrepots((prev) =>
        prev.map((e) => (e.id === editingId ? res.data.entrepot : e))
      );
      resetForm();
    } catch (err) {
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else {
        setApiError(err.response?.data?.message || "Erreur lors de la mise \xE0 jour");
      }
    }
  }

  async function deleteEntrepot(id) {
    if (!window.confirm("Confirmer la suppression de l'entrep\xF4t ?")) return;
    try {
      await api.delete(`/entrepots/${id}`);
      setEntrepots((prev) => prev.filter((e) => e.id !== id));
    } catch (err) {
      setApiError(err.response?.data?.message || "Erreur lors de la suppression");
    }
  }

  const startEdit = (entrepot) => {
    setEditingId(entrepot.id);
    setForm({
      nom: entrepot.nom || "",
      adresse: entrepot.adresse || "",
      ville: entrepot.ville || "",
      code_postal: entrepot.code_postal || "",
    });
    setErrors({});
  };

  const resetForm = () => {
    setForm({ nom: "", adresse: "", ville: "", code_postal: "" });
    setEditingId(null);
  };

  if (loading) return <div className="p-4">Chargement...</div>;

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Gestion des entrepots</h1>
      {apiError && (
        <p className="text-red-600" role="alert">
          {apiError}
        </p>
      )}

      <form onSubmit={handleSubmit} className="bg-white shadow p-4 rounded space-y-4">
        <h2 className="text-xl font-semibold">
          {editingId ? "Modifier l'entrep\xF4t" : "Nouvel entrep\xF4t"}
        </h2>
        <div>
          <label className="block font-semibold">Nom</label>
          <input
            type="text"
            name="nom"
            value={form.nom}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.nom && <p className="text-red-600 text-sm">{errors.nom[0]}</p>}
        </div>
        <div>
          <label className="block font-semibold">Adresse</label>
          <input
            type="text"
            name="adresse"
            value={form.adresse}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.adresse && <p className="text-red-600 text-sm">{errors.adresse[0]}</p>}
        </div>
        <div>
          <label className="block font-semibold">Ville</label>
          <input
            type="text"
            name="ville"
            value={form.ville}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.ville && <p className="text-red-600 text-sm">{errors.ville[0]}</p>}
        </div>
        <div>
          <label className="block font-semibold">Code postal</label>
          <input
            type="text"
            name="code_postal"
            value={form.code_postal}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.code_postal && (
            <p className="text-red-600 text-sm">{errors.code_postal[0]}</p>
          )}
        </div>
        <div className="flex gap-4">
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            {editingId ? "Mettre \xE0 jour" : "Ajouter"}
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
              <th className="p-3">Nom</th>
              <th className="p-3">Adresse</th>
              <th className="p-3">Ville</th>
              <th className="p-3">Code postal</th>
              <th className="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            {entrepots.map((e) => (
              <tr key={e.id} className="border-b hover:bg-gray-50">
                <td className="p-3">{e.nom}</td>
                <td className="p-3">{e.adresse}</td>
                <td className="p-3">{e.ville}</td>
                <td className="p-3">{e.code_postal}</td>
                <td className="p-3 space-x-2">
                  <button
                    onClick={() => startEdit(e)}
                    className="text-yellow-600 hover:underline"
                  >
                    Modifier
                  </button>
                  <button
                    onClick={() => deleteEntrepot(e.id)}
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