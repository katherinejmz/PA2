import { useAuth } from "../context/AuthContext";
import { useEffect, useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function ProfilPrestataire() {
  const { user, token, logout } = useAuth();
  const [formData, setFormData] = useState({
    nom: "", prenom: "", email: "", pays: "", telephone: "", adresse_postale: "",
    domaine: "", description: "", password: "", password_confirmation: ""
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
        adresse_postale: user.adresse_postale,
      }));

      api.get(`/prestataires/${user.id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
        .then(res => {
          setFormData((prev) => ({
            ...prev,
            domaine: res.data.domaine,
            description: res.data.description || ""
          }));
        })
        .catch(() => setMessage("Impossible de charger les infos prestataire."));
    }
  }, [user]);

  const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

  const handleUpdate = async (e) => {
    e.preventDefault();
    if (formData.password && formData.password.length < 8) return setMessage("Mot de passe trop court.");
    if (formData.password !== formData.password_confirmation) return setMessage("Confirmation incorrecte.");

    try {
      await api.patch(`/utilisateurs/${user.id}`, formData, {
        headers: { Authorization: `Bearer ${token}` }
      });

      await api.patch(`/prestataires/${user.id}`, {
        domaine: formData.domaine,
        description: formData.description
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      setMessage("Profil mis à jour !");
      setFormData((prev) => ({ ...prev, password: "", password_confirmation: "" }));
    } catch {
      setMessage("Erreur lors de la mise à jour.");
    }
  };

  const handleDelete = async () => {
    if (!window.confirm("Confirmez-vous la suppression ?")) return;
    try {
      await api.delete(`/utilisateurs/${user.id}`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      logout();
      navigate("/register");
    } catch {
      setMessage("Erreur lors de la suppression.");
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
      <h2 className="text-2xl font-bold mb-4 text-center">Mon profil prestataire</h2>
      {message && <p className="text-center text-red-600">{message}</p>}

      <form onSubmit={handleUpdate} className="space-y-4">
        <input name="nom" value={formData.nom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom" />
        <input name="prenom" value={formData.prenom} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Prénom" />
        <input name="email" value={formData.email} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Email" />
        <input name="pays" value={formData.pays} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Pays" />
        <input name="telephone" value={formData.telephone} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Téléphone" />
        <input name="adresse_postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Adresse postale" />

        <input name="domaine" value={formData.domaine} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Domaine de prestation" />
        <textarea name="description" value={formData.description} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Description" />

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
