import { useAuth } from "../context/AuthContext";
import { useEffect, useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function ProfilClient() {
  const { user, token, logout } = useAuth();
  const [formData, setFormData] = useState({
    nom: "", prenom: "", email: "", pays: "", telephone: "", adresse_postale: "",
    password: "", password_confirmation: ""
  });
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      setFormData((prev) => ({
        ...prev,
        nom: user.nom || "",
        prenom: user.prenom || "",
        email: user.email || "",
        pays: user.pays || "",
        telephone: user.telephone || "",
        adresse_postale: user.adresse_postale || "",
      }));
    }
  }, [user]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleUpdate = async (e) => {
    e.preventDefault();

    if (formData.password && formData.password.length < 8) {
      setMessage("Le mot de passe doit contenir au moins 8 caractères.");
      return;
    }

    if (formData.password !== formData.password_confirmation) {
      setMessage("La confirmation du mot de passe ne correspond pas.");
      return;
    }

    try {
      await api.patch(`/utilisateurs/${user.id}`, formData, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setMessage("Profil mis à jour avec succès !");
      setFormData((prev) => ({
        ...prev,
        password: "",
        password_confirmation: ""
      }));
    } catch (error) {
      console.error(error);
      setMessage("Erreur lors de la mise à jour du profil.");
    }
  };

  const handleDelete = async () => {
    if (!window.confirm("Confirmez-vous la suppression de votre compte ?")) return;
    try {
      await api.delete(`/utilisateurs/${user.id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      logout();
      navigate("/register");
    } catch (error) {
      console.error(error);
      setMessage("Erreur lors de la suppression du compte.");
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
      <h2 className="text-2xl font-bold mb-4 text-center">Mon profil</h2>

      {message && <p className="mb-4 text-center text-sm text-red-600">{message}</p>}

      <form onSubmit={handleUpdate} className="space-y-4">
        <input name="nom" value={formData.nom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom" />
        <input name="prenom" value={formData.prenom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Prénom" />
        <input name="email" value={formData.email} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Email" />
        <input name="pays" value={formData.pays} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Pays" />
        <input name="telephone" value={formData.telephone} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Téléphone" />
        <input name="adresse_postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Adresse postale" />

        <hr className="my-4" />

        <input name="password" type="password" value={formData.password} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nouveau mot de passe (optionnel)" />
        <input name="password_confirmation" type="password" value={formData.password_confirmation} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Confirmer le mot de passe" />

        <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Mettre à jour</button>
      </form>

      <hr className="my-6" />

      <button onClick={handleDelete} className="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
        Supprimer mon compte
      </button>
    </div>
    
  );
}
