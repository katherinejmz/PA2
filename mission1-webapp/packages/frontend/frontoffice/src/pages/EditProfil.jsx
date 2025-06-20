import { useAuth } from "../context/AuthContext";
import { useEffect, useState } from "react";
import api from "../services/api";
import { useNavigate, Link } from "react-router-dom";

export default function EditProfil() {
  const { user, token, updateUser } = useAuth();
  const navigate = useNavigate();
  const [formData, setFormData] = useState({});
  const [message, setMessage] = useState("");

  useEffect(() => {
    if (!user || !token) return;
    setFormData({
      nom: user.nom, prenom: user.prenom, email: user.email,
      pays: user.pays, telephone: user.telephone, adresse_postale: user.adresse_postale,
      piece_identite: "", permis_conduire: "",
      nom_entreprise: "", siret: "",
      domaine: "", description: ""
    });

    const loadRoleData = async () => {
      try {
        const res = await api.get(`/${user.role}s/${user.id}`, {
          headers: { Authorization: `Bearer ${token}` }
        });
        setFormData((prev) => ({ ...prev, ...res.data }));
      } catch {
        setMessage("Erreur lors du chargement des données spécifiques.");
      }
    };

    loadRoleData();
  }, [user, token]);

  const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");

    const utilisateurData = {
      nom: formData.nom,
      prenom: formData.prenom,
      email: formData.email,
      pays: formData.pays,
      telephone: formData.telephone,
      adresse_postale: formData.adresse_postale,
    };

    try {
      // PATCH utilisateur
      const res = await api.patch(`/utilisateurs/${user.id}`, utilisateurData, {
        headers: { Authorization: `Bearer ${token}` },
      });

      // 🔄 MAJ user dans localStorage
      const updatedUser = {
        ...user,
        ...utilisateurData,
      };
      updateUser(utilisateurData);

      // PATCH données spécifiques selon le rôle
      if (user.role !== "client") {
        const roleData = {};
        if (user.role === "livreur") {
          roleData.piece_identite = formData.piece_identite;
          roleData.permis_conduire = formData.permis_conduire;
        } else if (user.role === "commercant") {
          roleData.nom_entreprise = formData.nom_entreprise;
          roleData.siret = formData.siret;
        } else if (user.role === "prestataire") {
          roleData.domaine = formData.domaine;
          roleData.description = formData.description;
        }

        await api.patch(`/${user.role}s/${user.id}`, roleData, {
          headers: { Authorization: `Bearer ${token}` },
        });
      }

      setMessage("✅ Profil mis à jour avec succès.");
      setTimeout(() => setMessage(""), 5000);
    } catch (error) {
      console.error("Erreur de mise à jour :", error);
      if (error.response?.data?.message) {
        setMessage(`❌ ${error.response.data.message}`);
      } else if (error.response?.data?.errors) {
        const messages = Object.values(error.response.data.errors).flat();
        setMessage(`❌ ${messages[0]}`);
      } else {
        setMessage("❌ Erreur inconnue lors de la mise à jour.");
      }
    }
  };


  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
      <h2 className="text-2xl font-bold mb-4 text-center">Modifier mon profil</h2>
      {message && <p className="text-center text-red-600">{message}</p>}
      <form onSubmit={handleSubmit} className="space-y-4">
        <input name="nom" value={formData.nom || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom" />
        <input name="prenom" value={formData.prenom || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Prénom" />
        <input name="email" value={formData.email || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Email" />
        <input name="pays" value={formData.pays || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Pays" />
        <input name="telephone" value={formData.telephone || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Téléphone" />
        <input name="adresse_postale" value={formData.adresse_postale || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Adresse" />

        {user.role === "livreur" && (
          <>
            <input name="piece_identite" value={formData.piece_identite || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="N° pièce d'identité" />
            <input name="permis_conduire" value={formData.permis_conduire || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Permis (optionnel)" />
          </>
        )}
        {user.role === "commercant" && (
          <>
            <input name="nom_entreprise" value={formData.nom_entreprise || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Nom entreprise" />
            <input name="siret" value={formData.siret || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="SIRET" />
          </>
        )}
        {user.role === "prestataire" && (
          <>
            <input name="domaine" value={formData.domaine || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Domaine" />
            <textarea name="description" value={formData.description || ""} onChange={handleChange} className="w-full p-2 border rounded" placeholder="Description" />
          </>
        )}

        <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded">Enregistrer les modifications</button>
      </form>

      <div className="mt-4 text-center">
        <Link to="/monprofil" className="text-sm text-blue-600 hover:underline">← Retour au profil</Link><br />
        <Link to="/profil/motdepasse" className="text-sm text-blue-600 hover:underline">Modifier mon mot de passe</Link>
      </div>
    </div>
  );
}
