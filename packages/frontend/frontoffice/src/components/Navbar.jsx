import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  return (
    <nav className="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow">
      <div className="text-xl font-bold">EcoDeli</div>
      <ul className="flex space-x-6 items-center">
        <li><Link to="/" className="hover:text-gray-200">Accueil</Link></li>

        {!user && (
          <>
            <li><Link to="/login" className="hover:text-gray-200">Connexion</Link></li>
            <li><Link to="/register" className="hover:text-gray-200">Inscription</Link></li>
          </>
        )}

        {user && (
          <>
            <li><Link to="/profil" className="hover:text-gray-200">Mon profil</Link></li>
            <li><Link to="/annonces" className="hover:text-gray-200">Annonces</Link></li>
            <li>
              <button onClick={handleLogout} className="hover:text-gray-300">Se déconnecter</button>
            </li>
          </>
        )}
      </ul>
    </nav>
  );
}
