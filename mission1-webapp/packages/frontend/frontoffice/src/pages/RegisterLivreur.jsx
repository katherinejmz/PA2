import { useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function RegisterLivreur() {
  const [formData, setFormData] = useState({
    nom: "",
    prenom: "",
    email: "",
    password: "",
    password_confirmation: "",
    pays: "",
    telephone: "",
    adresse_postale: "",
    piece_identite: "",
    permis_conduire: ""
  });

  const [errors, setErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState("");
  const navigate = useNavigate();

  const validate = () => {
    const newErrors = {};
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!emailRegex.test(formData.email)) newErrors.email = "Email invalide";
    if (!passwordRegex.test(formData.password)) newErrors.password = "Mot de passe trop faible";
    if (formData.password !== formData.password_confirmation) newErrors.password_confirmation = "Les mots de passe ne correspondent pas";
    if (!formData.piece_identite) newErrors.piece_identite = "Pièce d'identité requise";

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
    setErrors({ ...errors, [e.target.name]: null });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validate()) return;

    const dataToSend = {
      ...formData,
      role: "livreur"
    };

    try {
      await api.post("/register", dataToSend);
      setSuccessMessage("Inscription réussie ! Vous allez être redirigé...");
      setTimeout(() => navigate("/login"), 2000);
    } catch (error) {
      const message = error.response?.data?.message || "Erreur serveur.";
      alert(message);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto mt-10 space-y-4">
      <h2 className="text-2xl font-bold text-center">Inscription Livreur</h2>

      {successMessage && <p className="text-green-600 text-center">{successMessage}</p>}

      <input name="nom" placeholder="Nom" value={formData.nom} onChange={handleChange} required className="w-full p-2 border rounded" />
      <input name="prenom" placeholder="Prénom" value={formData.prenom} onChange={handleChange} required className="w-full p-2 border rounded" />
      <input name="email" placeholder="Email" value={formData.email} onChange={handleChange} required className="w-full p-2 border rounded" />
      {errors.email && <p className="text-red-500 text-sm">{errors.email}</p>}

      <input name="password" type="password" placeholder="Mot de passe" value={formData.password} onChange={handleChange} required className="w-full p-2 border rounded" />
      {errors.password && <p className="text-red-500 text-sm">{errors.password}</p>}

      <input name="password_confirmation" type="password" placeholder="Confirmation du mot de passe" value={formData.password_confirmation} onChange={handleChange} required className="w-full p-2 border rounded" />
      {errors.password_confirmation && <p className="text-red-500 text-sm">{errors.password_confirmation}</p>}

      <input name="pays" placeholder="Pays" value={formData.pays} onChange={handleChange} className="w-full p-2 border rounded" />
      <input name="telephone" placeholder="Téléphone" value={formData.telephone} onChange={handleChange} className="w-full p-2 border rounded" />
      <input name="adresse_postale" placeholder="Adresse postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" />

      <hr className="my-4" />

      <input name="piece_identite" placeholder="Numéro de pièce d'identité" value={formData.piece_identite} onChange={handleChange} required className="w-full p-2 border rounded" />
      {errors.piece_identite && <p className="text-red-500 text-sm">{errors.piece_identite}</p>}

      <input name="permis_conduire" placeholder="Numéro de permis de conduire (optionnel)" value={formData.permis_conduire} onChange={handleChange} className="w-full p-2 border rounded" />

      <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        S'inscrire
      </button>
    </form>
  );
}
