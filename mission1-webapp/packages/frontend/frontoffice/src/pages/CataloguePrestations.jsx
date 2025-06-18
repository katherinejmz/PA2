import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function CataloguePrestations() {
  const { token, user } = useAuth();
  const [prestations, setPrestations] = useState([]);
  const [message, setMessage] = useState("");

  const fetchPrestations = async () => {
    try {
      const res = await api.get("/prestations/catalogue", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setPrestations(res.data);
    } catch (err) {
      console.error("Erreur lors du chargement des prestations :", err);
    }
  };

  useEffect(() => {
    fetchPrestations();
  }, [token]);

  const reserverPrestation = async (id) => {
    try {
      await api.patch(`/prestations/${id}/reserver`, null, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setMessage("✅ Prestation réservée !");
      fetchPrestations();
    } catch (err) {
      console.error("Erreur lors de la réservation :", err);
      setMessage("❌ Erreur lors de la réservation.");
    }
  };

  if (user?.role !== "client") {
    return <p className="p-6 text-red-600">Accès réservé aux clients.</p>;
  }

  return (
    <div className="max-w-4xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Prestations disponibles</h2>

      {message && <p className="mb-4 text-sm">{message}</p>}

      {prestations.length === 0 ? (
        <p>Aucune prestation disponible pour le moment.</p>
      ) : (
        <ul className="space-y-4">
          {prestations.map((p) => (
            <li key={p.id} className="p-4 border rounded bg-white shadow">
              <h3 className="text-lg font-semibold text-blue-600 capitalize">
                {p.type_prestation}
              </h3>
              <p className="text-gray-700">{p.description}</p>
              <p className="text-sm text-gray-500">
                📅 {new Date(p.date_heure).toLocaleString()} • ⏱ {p.duree_estimee ?? "?"} min • 💶 {p.tarif} €
              </p>
              <p className="text-sm text-gray-500">
                Proposé par : {p.prestataire?.utilisateur?.prenom} {p.prestataire?.utilisateur?.nom}
              </p>
              <button
                onClick={() => reserverPrestation(p.id)}
                className="mt-2 px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700"
              >
                Réserver
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
