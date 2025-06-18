import { useState } from "react";
import api from "../services/api";
import { useNavigate } from "react-router-dom";

export default function RegisterPrestataire() {
  const [formData, setFormData] = useState({
    nom: "",
    prenom: "",
    email: "",
    password: "",
    password_confirmation: "",
    pays: "",
    telephone: "",
    adresse_postale: "",
    domaine: "",
    description: "",
    rgpd_consent: false
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
    if (!formData.domaine) newErrors.domaine = "Le domaine est requis";
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

    setErrors((prevErrors) => {
      const newErrors = { ...prevErrors };

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      const phoneRegex = /^(\+?\d{1,3})?[\s.-]?\(?\d{2,4}\)?[\s.-]?\d{2,4}[\s.-]?\d{2,4}$/;

      switch (name) {
        case "nom":
          !val ? (newErrors.nom = "Le nom est requis") : delete newErrors.nom;
          break;

        case "prenom":
          !val ? (newErrors.prenom = "Le prénom est requis") : delete newErrors.prenom;
          break;

        case "email":
          !emailRegex.test(val) ? (newErrors.email = "Email invalide") : delete newErrors.email;
          break;

        case "password":
          !passwordRegex.test(val)
            ? (newErrors.password = "Mot de passe trop faible")
            : delete newErrors.password;
          if (formData.password_confirmation && formData.password_confirmation !== val) {
            newErrors.password_confirmation = "Les mots de passe ne correspondent pas";
          } else {
            delete newErrors.password_confirmation;
          }
          break;

        case "password_confirmation":
          val !== formData.password
            ? (newErrors.password_confirmation = "Les mots de passe ne correspondent pas")
            : delete newErrors.password_confirmation;
          break;

        case "telephone":
          if (val && !phoneRegex.test(val)) newErrors.telephone = "Téléphone invalide";
          else delete newErrors.telephone;
          break;

        case "piece_identite":
          !val ? (newErrors.piece_identite = "Numéro de pièce d'identité requis") : delete newErrors.piece_identite;
          break;

        case "nom_entreprise":
          !val ? (newErrors.nom_entreprise = "Nom de l'entreprise requis") : delete newErrors.nom_entreprise;
          break;

        case "siret":
          !val ? (newErrors.siret = "SIRET requis") : delete newErrors.siret;
          break;

        case "domaine":
          !val ? (newErrors.domaine = "Le domaine est requis") : delete newErrors.domaine;
          break;

        case "rgpd_consent":
          !val ? (newErrors.rgpd_consent = "Vous devez accepter les conditions RGPD") : delete newErrors.rgpd_consent;
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

    const data = {
      ...formData,
      role: "prestataire"
    };

    try {
      await api.post("/register", data);
      setSuccessMessage("Inscription réussie ! Vérifiez votre email.");
      setTimeout(() => navigate("/login"), 3000);
    } catch (error) {
      const message = error.response?.data?.message || "Erreur serveur.";
      alert(message);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto mt-10 space-y-4">
      <h2 className="text-2xl font-bold text-center">Inscription Prestataire</h2>

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
      <input name="adresse_postale" placeholder="Adresse postale" value={formData.adresse_postale} onChange={handleChange} className="w-full p-2 border rounded" />

      <input name="domaine" placeholder="Domaine de service" value={formData.domaine} onChange={handleChange} required className="w-full p-2 border rounded" />
      {errors.domaine && <p className="text-red-500 text-sm">{errors.domaine}</p>}

      <textarea name="description" placeholder="Description (optionnelle)" value={formData.description} onChange={handleChange} className="w-full p-2 border rounded" />

      <div className="flex items-center space-x-2">
        <input type="checkbox" id="rgpd_consent" name="rgpd_consent" checked={formData.rgpd_consent} onChange={handleChange} className="w-4 h-4" />
        <label htmlFor="rgpd_consent">J'accepte la politique de confidentialité (RGPD)</label>
      </div>
      {errors.rgpd_consent && <p className="text-red-500 text-sm">{errors.rgpd_consent}</p>}

      <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        S'inscrire
      </button>
    </form>
  );
}
