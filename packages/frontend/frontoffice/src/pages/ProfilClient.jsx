import { useAuth } from "../context/AuthContext";

export default function ProfilClient() {
  const { user } = useAuth();

  if (!user) {
    return <p className="text-center mt-10">Aucun utilisateur connecté.</p>;
  }

  return (
    <div className="max-w-md mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4 text-center">Mon profil</h2>
      <ul className="space-y-2">
        <li><strong>Nom :</strong> {user.nom}</li>
        <li><strong>Prénom :</strong> {user.prenom}</li>
        <li><strong>Email :</strong> {user.email}</li>
        <li><strong>Rôle :</strong> {user.role}</li>
      </ul>
    </div>
  );
}
