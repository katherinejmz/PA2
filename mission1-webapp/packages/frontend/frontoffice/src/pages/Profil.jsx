import { useAuth } from "../context/AuthContext";
import { useEffect, useState } from "react";
import api from "../services/api";
import { Link } from "react-router-dom";

export default function Profil() {
  const { user, token, logout } = useAuth();
  const [message, setMessage] = useState("");
  const [twoFactorEnabled, setTwoFactorEnabled] = useState(false);
  const [qrCode, setQrCode] = useState(null);

  useEffect(() => {
    const load2FAStatus = async () => {
      try {
        const res = await api.get("/user/2fa-status", {
          headers: { Authorization: `Bearer ${token}` },
        });
        setTwoFactorEnabled(res.data.enabled);
      } catch {
        setTwoFactorEnabled(false);
      }
    };

    load2FAStatus();
  }, [token]);

  const enable2FA = async () => {
    try {
      const res = await api.post("/user/2fa/enable", {}, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setQrCode(res.data.qr);
      setTwoFactorEnabled(true);
    } catch {
      setMessage("Erreur lors de l'activation du 2FA.");
    }
  };

  const disable2FA = async () => {
    try {
      await api.post("/user/2fa/disable", {}, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setQrCode(null);
      setTwoFactorEnabled(false);
    } catch {
      setMessage("Erreur lors de la désactivation du 2FA.");
    }
  };

  const handleDelete = async () => {
    if (!window.confirm("Confirmez-vous la suppression de votre compte ?")) return;
    try {
      await api.delete(`/utilisateurs/${user.id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      logout();
    } catch {
      setMessage("Erreur lors de la suppression de compte.");
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-4">
      <h2 className="text-2xl font-bold text-center">Mon profil</h2>
      {message && <p className="text-center text-red-600">{message}</p>}

      <div className="space-y-2">
        <p><strong>Nom :</strong> {user.nom}</p>
        <p><strong>Prénom :</strong> {user.prenom}</p>
        <p><strong>Email :</strong> {user.email}</p>
        <p><strong>Téléphone :</strong> {user.telephone}</p>
        <p><strong>Pays :</strong> {user.pays}</p>
        <p><strong>Adresse :</strong> {user.adresse_postale}</p>
      </div>

      <div className="text-center mt-4">
        <Link to="/profil/edit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Modifier mes informations
        </Link>
      </div>

      <hr className="my-6" />

      <div className="space-y-3">
        <p className="text-center font-semibold">Double authentification (2FA)</p>
        <p className="text-center text-sm text-gray-600">Statut : {twoFactorEnabled ? "✅ Activée" : "❌ Désactivée"}</p>
        {qrCode && <img src={qrCode} alt="QR Code" className="mx-auto" />}
        <div className="flex space-x-2">
          <button onClick={enable2FA} className="w-1/2 bg-green-600 text-white py-2 rounded">Activer 2FA</button>
          <button onClick={disable2FA} className="w-1/2 bg-gray-500 text-white py-2 rounded">Désactiver</button>
        </div>
      </div>

      <hr className="my-6" />

      <button onClick={handleDelete} className="w-full bg-red-600 text-white py-2 rounded">
        Supprimer mon compte
      </button>
    </div>
  );
}