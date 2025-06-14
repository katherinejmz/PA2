import { useAuth } from "../context/AuthContext";
import { useEffect, useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function ProfilCommercant() {
  const { user, token, logout } = useAuth();
  const [formData, setFormData] = useState({
    nom: "", prenom: "", email: "", pays: "", telephone: "", adresse_postale: "",
    nom_entreprise: "", siret: "", password: "", password_confirmation: ""
  });
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      setFormData((prev) => ({
        ...prev,
        nom: user.nom,
        prenom: user.prenom,
        email: user.email,
        pays: user.pays,
        telephone: user.telephone,
        adresse_postale: user.adresse_postale
      }));

      // Charger les données du commerçant
      api.get(`/commercants/${user.id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      .then(res => {
        setFormData((prev) => ({
          ...prev,
          nom_entreprise: res.data.nom_entreprise,
          siret: res.data.siret
        }));
      })
      .catch(() => setMessage("Impossible de charger les infos commerçant."));
    }
  }, [user]);

  const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

  const handleUpdate = async (e) => {
    e.preventDefault();
    if (formData.password && formData.password.length < 8) {
      return setMessage("Le mot de passe doit contenir au moins 8 caractères.");
    }
    if (formData.password !== formData.password_confirmation) {
      return setMessage("La confirmation du mot de passe ne correspond pas.");
    }

    try {
      // Mise à jour utilisateur
      await api.patch(`/utilisateurs/${user.id}`, formData, {
        headers: { Authorization: `Bearer ${token}` },
      });

      // Mise à jour commerçant
      await api.patch(`/commercants/${user.id}`, {
        nom_entreprise: formData.nom_entreprise,
        siret: formData.siret
      }, {
        headers: { Authorization: `Bearer ${token}` },
      });

      setMessage("Profil mis à jour !");
      setFormData((prev) => ({ ...prev, password: "", password_confirmation: "" }));
    } catch {
      setMessage("Erreur lors de la mise à jour.");
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
    } catch {
      setMessage("Erreur lors de la suppression.");
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
      <h2 className="text-2xl font-bold mb-4 text-center">Mon profil commerçant</h2>
      {message && <p className="text-center text-red-600">{message}</p>}

      <form onSubmit={handleUpdate} className="space-y-4">
        <input name="nom" value={formData.nom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom" />
        <input name="prenom" value={formData.prenom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Prénom" />
        <input name="email" value={formData.email} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Email" />
        <input name="pays" value={formData.pays} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Pays" />
        <input name="telephone" value={formData.telephone} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Téléphone" />
        <input name="adresse_postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Adresse postale" />

        <input name="nom_entreprise" value={formData.nom_entreprise} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom entreprise" />
        <input name="siret" value={formData.siret} onChange={handleChange} className="w-full p-2 border rounded" placeholder="SIRET" />

        <hr className="my-4" />
        <input name="password" type="password" value={formData.password} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Mot de passe (optionnel)" />
        <input name="password_confirmation" type="password" value={formData.password_confirmation} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Confirmation" />

        <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded">Mettre à jour</button>
      </form>

      <hr className="my-6" />
      <button onClick={handleDelete} className="w-full bg-red-600 text-white py-2 rounded">Supprimer mon compte</button>
    </div>
  );
}
