import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function Planning() {
  const { token } = useAuth();
  const [plannings, setPlannings] = useState([]);
  const [date, setDate] = useState("");
  const [heureDebut, setHeureDebut] = useState("");
  const [heureFin, setHeureFin] = useState("");

  const fetchPlannings = async () => {
    try {
      const res = await api.get("/plannings", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setPlannings(res.data);
    } catch (err) {
      console.error("Erreur lors du chargement des créneaux :", err);
    }
  };

  useEffect(() => {
    fetchPlannings();
  }, [token]);

  const ajouterCreneau = async (e) => {
    e.preventDefault();
    try {
      await api.post(
        "/plannings",
        {
          date_disponible: date,
          heure_debut: heureDebut,
          heure_fin: heureFin,
        },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      setDate("");
      setHeureDebut("");
      setHeureFin("");
      fetchPlannings();
    } catch (err) {
      console.error("Erreur lors de l'ajout :", err);
    }
  };

  const supprimerCreneau = async (id) => {
    try {
      await api.delete(`/plannings/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      fetchPlannings();
    } catch (err) {
      console.error("Erreur lors de la suppression :", err);
    }
  };

  return (
    <div className="max-w-3xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Mon planning de disponibilités</h2>

      <form onSubmit={ajouterCreneau} className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <input
          type="date"
          value={date}
          onChange={(e) => setDate(e.target.value)}
          className="p-2 border rounded"
          required
        />
        <input
          type="time"
          value={heureDebut}
          onChange={(e) => setHeureDebut(e.target.value)}
          className="p-2 border rounded"
          required
        />
        <input
          type="time"
          value={heureFin}
          onChange={(e) => setHeureFin(e.target.value)}
          className="p-2 border rounded"
          required
        />
        <button
          type="submit"
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Ajouter
        </button>
      </form>

      {plannings.length === 0 ? (
        <p>Aucun créneau pour le moment.</p>
      ) : (
        <ul className="space-y-2">
          {plannings.map((c) => (
            <li key={c.id} className="flex items-center justify-between border p-3 rounded bg-white shadow-sm">
              <span>
                📅 {c.date_disponible} — ⏰ {c.heure_debut} → {c.heure_fin}
              </span>
              <button
                onClick={() => supprimerCreneau(c.id)}
                className="text-red-500 hover:underline text-sm"
              >
                Supprimer
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
