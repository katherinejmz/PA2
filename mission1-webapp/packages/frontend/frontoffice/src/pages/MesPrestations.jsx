import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function MesPrestations() {
  const { token, user } = useAuth();
  const [prestations, setPrestations] = useState([]);

  const fetchPrestations = async () => {
    try {
      const res = await api.get("/prestations", {
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

  const changerStatut = async (id, statut) => {
    try {
      await api.patch(`/prestations/${id}/statut`, { statut }, {
        headers: { Authorization: `Bearer ${token}` },
      });
      fetchPrestations();
    } catch (err) {
      console.error("Erreur lors du changement de statut :", err);
    }
  };

  console.log("User ID:", user.id);
  console.log("Prestations reçues :", prestations);


  return (
    <div className="max-w-4xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Mes prestations</h2>

      {prestations.length === 0 ? (
        <p>Aucune prestation trouvée.</p>
      ) : (
        <ul className="space-y-4">
          {prestations.map((prestation) => (
            <li key={prestation.id} className="p-4 border rounded bg-white shadow">
              <h3 className="text-lg font-semibold text-blue-600 capitalize">
                {prestation.type_prestation}
              </h3>
              <p className="text-gray-700">{prestation.description}</p>
              <p className="text-sm text-gray-500">
                📅 Le {new Date(prestation.date_heure).toLocaleString()} • ⏱️ {prestation.duree_estimee ?? "?"} min • 💶 {prestation.tarif} €
              </p>
              <p className="text-sm text-gray-500">
                {user.role === "client"
                  ? `Prestataire : ${prestation.prestataire?.nom ?? 'N/A'}`
                  : `Client : ${prestation.client?.nom ?? 'N/A'}`}
              </p>
              <p className={`text-sm font-semibold mt-1 ${
                prestation.statut === "en_attente"
                  ? "text-yellow-500"
                  : prestation.statut === "acceptée"
                  ? "text-green-600"
                  : prestation.statut === "refusée"
                  ? "text-red-500"
                  : "text-gray-700"
              }`}>
                Statut : {prestation.statut}
              </p>

              {user.role === "prestataire" && prestation.statut === "en_attente" && (
                <div className="mt-3 flex gap-3">
                  <button
                    onClick={() => changerStatut(prestation.id, "acceptée")}
                    className="px-4 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                  >
                    Accepter
                  </button>
                  <button
                    onClick={() => changerStatut(prestation.id, "refusée")}
                    className="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"
                  >
                    Refuser
                  </button>
                </div>
              )}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
