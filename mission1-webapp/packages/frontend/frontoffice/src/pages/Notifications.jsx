import { useEffect, useState } from "react";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function Notifications() {
  const { token, user } = useAuth();
  const [notifications, setNotifications] = useState([]);
  const [verificationSent, setVerificationSent] = useState(false);

  const fetchNotifications = async () => {
    try {
      const res = await api.get("/notifications", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setNotifications(res.data);
    } catch (err) {
      console.error("Erreur lors du chargement des notifications :", err);
    }
  };

  const handleResendVerification = async () => {
    try {
      await api.post("/email/verification-notification", null, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setVerificationSent(true);
    } catch (err) {
      console.error("Erreur lors de l'envoi de l'email :", err);
      alert("Une erreur est survenue.");
    }
  };

  useEffect(() => {
    fetchNotifications();
  }, [token]);

  return (
    <div className="max-w-3xl mx-auto mt-10 px-4">
      <h2 className="text-2xl font-bold mb-4">Mes notifications</h2>

      {/* Alerte si l'email n'est pas vérifié */}
      {user && !user.email_verified_at && (
        <div className="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded">
          <p>
            Votre adresse email n'est pas encore vérifiée.
            {!verificationSent ? (
              <>
                {" "}
                <button
                  onClick={handleResendVerification}
                  className="ml-2 text-blue-700 underline hover:text-blue-900"
                >
                  Renvoyer l'email de vérification
                </button>
              </>
            ) : (
              <span className="ml-2 text-green-700">Email envoyé !</span>
            )}
          </p>
        </div>
      )}

      {notifications.length === 0 ? (
        <p>Aucune notification pour le moment.</p>
      ) : (
        <ul className="space-y-4">
          {notifications.map((notif) => (
            <li key={notif.id} className="p-4 border rounded bg-white shadow">
              <h3 className="font-semibold text-green-800">{notif.titre}</h3>
              {notif.contenu && <p className="text-gray-700">{notif.contenu}</p>}
              <p className="text-sm text-gray-400 mt-1">
                {new Date(notif.created_at).toLocaleString()}
              </p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
