import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesTrajets() {
  const { token } = useAuth();
  const [trajets, setTrajets] = useState([]);
  const [entrepots, setEntrepots] = useState([]);
  const [form, setForm] = useState({
    entrepot_depart_id: "",
    entrepot_arrivee_id: "",
    disponible_du: "",
    disponible_au: "",
  });

  useEffect(() => {
    fetchEntrepots();
    fetchTrajets();
  }, []);

  const fetchEntrepots = async () => {
    try {
      const res = await api.get("/entrepots", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setEntrepots(res.data);
    } catch (err) {
      console.error("Erreur chargement entrepôts:", err);
    }
  };

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
      setForm({
        entrepot_depart_id: "",
        entrepot_arrivee_id: "",
        disponible_du: "",
        disponible_au: "",
      });
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
        <select
          name="entrepot_depart_id"
          value={form.entrepot_depart_id}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        >
          <option value="">Ville de départ</option>
          {entrepots.map((e) => (
            <option key={e.id} value={e.id}>{e.ville}</option>
          ))}
        </select>

        <select
          name="entrepot_arrivee_id"
          value={form.entrepot_arrivee_id}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        >
          <option value="">Ville d’arrivée</option>
          {entrepots.map((e) => (
            <option key={e.id} value={e.id}>{e.ville}</option>
          ))}
        </select>

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
              {t.entrepot_depart?.ville} → {t.entrepot_arrivee?.ville}
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
