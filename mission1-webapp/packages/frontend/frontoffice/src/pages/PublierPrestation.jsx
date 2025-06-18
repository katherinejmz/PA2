import { useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";

export default function PublierPrestation() {
  const { token, user } = useAuth();
  const navigate = useNavigate();

  const [form, setForm] = useState({
    type_prestation: "",
    description: "",
    date_heure: "",
    duree_estimee: "",
    tarif: "",
  });

  const [message, setMessage] = useState("");

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");
    try {
      await api.post("/prestations", form, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setMessage("✅ Prestation publiée avec succès !");
      setForm({
        type_prestation: "",
        description: "",
        date_heure: "",
        duree_estimee: "",
        tarif: "",
      });
      setTimeout(() => navigate("/mes-prestations"), 1500);
    } catch (err) {
      console.error(err);
      setMessage("❌ Erreur lors de la publication.");
    }
  };

  if (user?.role !== "prestataire") {
    return <p className="p-6 text-red-600">Accès réservé aux prestataires.</p>;
  }

  return (
    <div className="max-w-xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Publier une prestation</h2>

      {message && <p className="mb-4 text-sm">{message}</p>}

      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          name="type_prestation"
          placeholder="Type de prestation"
          value={form.type_prestation}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />

        <textarea
          name="description"
          placeholder="Description"
          value={form.description}
          onChange={handleChange}
          rows={3}
          className="w-full p-2 border rounded"
        />

        <input
          type="datetime-local"
          name="date_heure"
          value={form.date_heure}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />

        <input
          type="number"
          name="duree_estimee"
          placeholder="Durée estimée (en minutes)"
          value={form.duree_estimee}
          onChange={handleChange}
          className="w-full p-2 border rounded"
        />

        <input
          type="number"
          name="tarif"
          placeholder="Tarif (€)"
          value={form.tarif}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />

        <button
          type="submit"
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Publier
        </button>
      </form>
    </div>
  );
}
