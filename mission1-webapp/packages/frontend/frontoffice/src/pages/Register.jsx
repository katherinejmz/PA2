import { useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function Register() {
  const [formData, setFormData] = useState({
    nom: "",
    prenom: "",
    email: "",
    password: "",
    password_confirmation: "",
    pays: "",
    telephone: "",
    adresse_postale: ""
  });

  const [errors, setErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState("");
  const navigate = useNavigate();

  const validate = () => {
    const newErrors = {};
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^(\+?\d{1,3})?[\s.-]?\(?\d{2,4}\)?[\s.-]?\d{2,4}[\s.-]?\d{2,4}$/;

    if (!emailRegex.test(formData.email)) newErrors.email = "Email invalide";
    if (!passwordRegex.test(formData.password)) newErrors.password = "Mot de passe trop faible";
    if (formData.password !== formData.password_confirmation) newErrors.password_confirmation = "Les mots de passe ne correspondent pas";
    if (formData.telephone && !phoneRegex.test(formData.telephone)) newErrors.telephone = "Téléphone invalide";

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

    const dataToSend = { ...formData, role: "client" };

    try {
      await api.post("/register", dataToSend);
      setSuccessMessage("Inscription réussie ! Vous allez être redirigé...");
      setTimeout(() => navigate("/login"), 2000);
    } catch (error) {
      const message = error.response?.data?.message || "Erreur serveur.";
      alert(message);
    }
  };

  const handleCheckboxChange = (e) => {
    if (e.target.checked) {
      navigate("/register-commercant");
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto mt-10 space-y-4">
      <h2 className="text-2xl font-bold text-center">Inscription client</h2>

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
      {errors.telephone && <p className="text-red-500 text-sm">{errors.telephone}</p>}
      <input name="adresse_postale" placeholder="Adresse postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" />

      <div className="flex items-center space-x-2">
        <input type="checkbox" id="entreprise" onChange={handleCheckboxChange} className="w-4 h-4" />
        <label htmlFor="entreprise">Je suis une entreprise</label>
      </div>

      <div className="flex items-center space-x-2">
        <input type="checkbox" id="livreur" onChange={(e) => {
          if (e.target.checked) navigate("/register-livreur");
        }} className="w-4 h-4" />
        <label htmlFor="livreur">Je suis un livreur</label>
      </div>

      <div className="flex items-center space-x-2">
        <input type="checkbox" id="prestataire" onChange={(e) => {
          if (e.target.checked) navigate("/register-prestataire");
        }} className="w-4 h-4" />
        <label htmlFor="prestataire">Je suis un prestataire</label>
      </div>


      <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        S'inscrire
      </button>
    </form>
  );
}
