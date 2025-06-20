import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesTrajets() {
  const { token } = useAuth();
  const [trajets, setTrajets] = useState([]);
  const [form, setForm] = useState({
    ville_depart: "",
    ville_arrivee: "",
    disponible_du: "",
    disponible_au: "",
  });

  useEffect(() => {
    fetchTrajets();
  }, []);

  const fetchTrajets = async () => {
    try {
      const res = await api.get("/mes-trajets", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setTrajets(res.data);
    } catch (err) {
      console.error("Erreur trajets:", err);
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post("/mes-trajets", form, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setForm({ ville_depart: "", ville_arrivee: "", disponible_du: "", disponible_au: "" });
      fetchTrajets();
    } catch (err) {
      console.error("Erreur ajout trajet:", err);
      alert("Erreur lors de l'enregistrement");
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Supprimer ce trajet ?")) return;
    try {
      await api.delete(`/mes-trajets/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      fetchTrajets();
    } catch (err) {
      console.error("Erreur suppression:", err);
    }
  };

  return (
    <div className="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4">Mes trajets disponibles</h2>

      <form onSubmit={handleSubmit} className="space-y-4 mb-8">
        <input
          type="text"
          name="ville_depart"
          placeholder="Ville de départ"
          value={form.ville_depart}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <input
          type="text"
          name="ville_arrivee"
          placeholder="Ville d'arrivée"
          value={form.ville_arrivee}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <input
          type="date"
          name="disponible_du"
          value={form.disponible_du}
          onChange={handleChange}
          className="w-full p-2 border rounded"
        />
        <input
          type="date"
          name="disponible_au"
          value={form.disponible_au}
          onChange={handleChange}
          className="w-full p-2 border rounded"
        />
        <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Ajouter le trajet
        </button>
      </form>

      <ul className="space-y-4">
        {trajets.map((t) => (
          <li key={t.id} className="border p-4 rounded shadow-sm">
            <p className="font-semibold">
              {t.ville_depart} → {t.ville_arrivee}
            </p>
            <p className="text-sm text-gray-600">
              Du {t.disponible_du || "-"} au {t.disponible_au || "-"}
            </p>
            <button
              onClick={() => handleDelete(t.id)}
              className="text-sm text-red-600 hover:underline mt-1"
            >
              Supprimer
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}
