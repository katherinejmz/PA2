import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../../services/api";

export default function EditUserForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get(`/utilisateurs/${id}`)
      .then(res => setUser(res.data))
      .catch(() => alert("Utilisateur introuvable"))
      .finally(() => setLoading(false));
  }, [id]);

  const handleChange = (e) => {
    setUser({ ...user, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setErrors({});
    api.patch(`/utilisateurs/${id}`, user)
      .then(() => {
        alert("Utilisateur mis à jour !");
        navigate("/admin/utilisateurs");
      })
      .catch((err) => {
        if (err.response?.data?.errors) {
          setErrors(err.response.data.errors);
        } else {
          alert("Erreur lors de la mise à jour.");
        }
      });
  };

  if (loading) return <div className="p-4">Chargement...</div>;
  if (!user) return null;

  return (
    <div className="max-w-3xl mx-auto p-6 bg-white shadow-md rounded">
      <h2 className="text-2xl font-bold mb-6">Modifier l'utilisateur</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block font-semibold">Nom</label>
          <input
            type="text"
            name="nom"
            value={user.nom || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.nom && <p className="text-red-600 text-sm">{errors.nom[0]}</p>}
        </div>

        <div>
          <label className="block font-semibold">Prénom</label>
          <input
            type="text"
            name="prenom"
            value={user.prenom || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.prenom && <p className="text-red-600 text-sm">{errors.prenom[0]}</p>}
        </div>

        <div>
          <label className="block font-semibold">Email</label>
          <input
            type="email"
            name="email"
            value={user.email || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.email && <p className="text-red-600 text-sm">{errors.email[0]}</p>}
        </div>

        <div>
          <label className="block font-semibold">Téléphone</label>
          <input
            type="text"
            name="telephone"
            value={user.telephone || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
          {errors.telephone && <p className="text-red-600 text-sm">{errors.telephone[0]}</p>}
        </div>

        <div>
          <label className="block font-semibold">Adresse Postale</label>
          <input
            type="text"
            name="adresse_postale"
            value={user.adresse_postale || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
        </div>

        <div>
          <label className="block font-semibold">Pays</label>
          <input
            type="text"
            name="pays"
            value={user.pays || ""}
            onChange={handleChange}
            className="w-full border p-2 rounded"
          />
        </div>

        <div>
            <label className="block font-semibold">Rôle</label>
            <select
                name="role"
                value={user.role}
                onChange={handleChange}
                className="w-full border p-2 rounded"
            >
                <option value="client">Client</option>
                <option value="commercant">Commerçant</option>
                <option value="livreur">Livreur</option>
                <option value="prestataire">Prestataire</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Enregistrer
        </button>
      </form>
    </div>
  );
}
