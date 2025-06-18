import { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import api from "../../services/api";

export default function UserDetails() {
  const { id } = useParams();
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get(`/utilisateurs/${id}`)
      .then(res => setUser(res.data))
      .catch(() => alert("Utilisateur introuvable"))
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) return <div className="p-4">Chargement...</div>;
  if (!user) return <div className="p-4 text-red-600">Utilisateur non trouvé.</div>;

  return (
    <div className="max-w-3xl mx-auto p-6 bg-white shadow-md rounded">
      <h2 className="text-2xl font-bold mb-4">Détails de l'utilisateur</h2>

      <div className="space-y-3">
        <Detail label="Nom" value={user.nom} />
        <Detail label="Prénom" value={user.prenom} />
        <Detail label="Email" value={user.email} />
        <Detail label="Téléphone" value={user.telephone} />
        <Detail label="Rôle" value={user.role} />
        <Detail label="Pays" value={user.pays} />
        <Detail label="Adresse postale" value={user.adresse_postale} />
        <Detail label="Identifiant unique" value={user.identifiant} />
        <Detail label="Créé le" value={new Date(user.created_at).toLocaleString()} />
      </div>

      <div className="mt-6 flex gap-4">
        <Link
          to={`/admin/utilisateurs/${user.id}/edit`}
          className="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
        >
          Modifier
        </Link>
        <Link
          to="/admin/utilisateurs"
          className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Retour
        </Link>
      </div>
    </div>
  );
}

function Detail({ label, value }) {
  return (
    <div>
      <strong className="block text-gray-700">{label}</strong>
      <span className="text-gray-900">{value || "—"}</span>
    </div>
  );
}
