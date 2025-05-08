import { useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import api from "../services/api";

export default function AdresseLivraison() {
  const { token } = useAuth();
  const { commandeId } = useParams();
  const navigate = useNavigate();

  const [form, setForm] = useState({
    adresse: "",
    ville: "",
    code_postal: "",
    pays: "",
  });

  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      await api.post(
        "/adresses-livraison",
        {
          commande_id: commandeId,
          ...form,
        },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      navigate(`/paiement/${commandeId}`);
    } catch (err) {
      console.error(err);
      setError("Erreur lors de l'enregistrement de l'adresse.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md mx-auto mt-10 bg-white shadow-md rounded p-6">
      <h2 className="text-2xl font-bold mb-6 text-center">Adresse de livraison</h2>

      {error && <div className="text-red-600 mb-4">{error}</div>}

      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          name="adresse"
          placeholder="Adresse"
          value={form.adresse}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />
        <input
          type="text"
          name="ville"
          placeholder="Ville"
          value={form.ville}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />
        <input
          type="text"
          name="code_postal"
          placeholder="Code postal"
          value={form.code_postal}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />
        <input
          type="text"
          name="pays"
          placeholder="Pays"
          value={form.pays}
          onChange={handleChange}
          required
          className="w-full p-2 border rounded"
        />

        <button
          type="submit"
          disabled={loading}
          className="w-full bg-emerald-600 text-white p-2 rounded hover:bg-emerald-700 disabled:opacity-60"
        >
          {loading ? "Envoi en cours..." : "Valider et passer au paiement"}
        </button>
      </form>
    </div>
  );
}
