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
    adresse_postale: "",
    rgpd_consent: false
  });

  const [errors, setErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState("");
  const navigate = useNavigate();

  const validate = () => {
    const newErrors = {};
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    const phoneRegex = /^(\+?\d{1,3})?[\s.-]?\(?\d{2,4}\)?[\s.-]?\d{2,4}[\s.-]?\d{2,4}$/;

    if (!formData.nom) newErrors.nom = "Le nom est requis";
    if (!formData.prenom) newErrors.prenom = "Le prénom est requis";

    if (!emailRegex.test(formData.email)) newErrors.email = "Email invalide";
    if (!passwordRegex.test(formData.password)) newErrors.password = "Mot de passe trop faible";
    if (formData.password !== formData.password_confirmation) newErrors.password_confirmation = "Les mots de passe ne correspondent pas";

    if (formData.telephone && !phoneRegex.test(formData.telephone)) newErrors.telephone = "Téléphone invalide";
    if (!formData.rgpd_consent) newErrors.rgpd_consent = "Vous devez accepter les conditions RGPD";

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (e) => {
    const { name, type, value, checked } = e.target;
    const val = type === "checkbox" ? checked : value;

    setFormData((prev) => ({
      ...prev,
      [name]: val
    }));

    // validation instantanée par champ
    setErrors((prevErrors) => {
      const newErrors = { ...prevErrors };

      switch (name) {
        case "nom":
          if (!val) newErrors.nom = "Le nom est requis";
          else delete newErrors.nom;
          break;

        case "prenom":
          if (!val) newErrors.prenom = "Le prénom est requis";
          else delete newErrors.prenom;
          break;

        case "email":
          if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) newErrors.email = "Email invalide";
          else delete newErrors.email;
          break;

        case "password":
          if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(val))
            newErrors.password = "Mot de passe trop faible";
          else delete newErrors.password;
          break;

        case "password_confirmation":
          if (val !== formData.password) newErrors.password_confirmation = "Les mots de passe ne correspondent pas";
          else delete newErrors.password_confirmation;
          break;

        case "telephone":
          if (val && !/^(\+?\d{1,3})?[\s.-]?\(?\d{2,4}\)?[\s.-]?\d{2,4}[\s.-]?\d{2,4}$/.test(val))
            newErrors.telephone = "Téléphone invalide";
          else delete newErrors.telephone;
          break;

        case "rgpd_consent":
          if (!val) newErrors.rgpd_consent = "Vous devez accepter les conditions RGPD";
          else delete newErrors.rgpd_consent;
          break;

        default:
          break;
      }

      return newErrors;
    });
  };


  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validate()) return;

    const dataToSend = {
      ...formData,
      role: "client"
    };

    try {
      await api.post("/register", dataToSend);
      setSuccessMessage("Inscription réussie ! Vérifiez votre email.");
      setTimeout(() => navigate("/login"), 3000);
    } catch (error) {
      const message = error.response?.data?.message || "Erreur serveur.";
      alert(message);
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10">
      <div className="flex justify-center gap-4 mb-6">
        <button
          onClick={() => navigate("/register-livreur")}
          className="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded"
        >
          Je suis un livreur
        </button>
        <button
          onClick={() => navigate("/register-commercant")}
          className="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded"
        >
          Je suis un commerçant
        </button>
        <button
          onClick={() => navigate("/register-prestataire")}
          className="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded"
        >
          Je suis un prestataire
        </button>
      </div>

      <form onSubmit={handleSubmit} className="space-y-4">
        <h2 className="text-2xl font-bold text-center">Inscription Client</h2>

        {successMessage && <p className="text-green-600 text-center">{successMessage}</p>}

        <input name="nom" placeholder="Nom" value={formData.nom} onChange={handleChange} required className="w-full p-2 border rounded" />
        {errors.nom && <p className="text-red-500 text-sm">{errors.nom}</p>}
        <input name="prenom" placeholder="Prénom" value={formData.prenom} onChange={handleChange} required className="w-full p-2 border rounded" />
        {errors.prenom && <p className="text-red-500 text-sm">{errors.prenom}</p>}

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
          <input type="checkbox" id="rgpd_consent" name="rgpd_consent" checked={formData.rgpd_consent} onChange={handleChange} className="w-4 h-4" />
          <label htmlFor="rgpd_consent">J'accepte la politique de confidentialité (RGPD)</label>
        </div>
        {errors.rgpd_consent && <p className="text-red-500 text-sm">{errors.rgpd_consent}</p>}

        <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
          S'inscrire
        </button>
      </form>
    </div>
  );
}
