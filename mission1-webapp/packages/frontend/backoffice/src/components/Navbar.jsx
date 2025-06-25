import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function Navbar() {
  const { token, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <nav className="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
      <div className="text-lg font-bold">
        <Link to="/admin">EcoDeli Admin</Link>
      </div>

      <div className="flex gap-4 items-center">
        {!token ? (
          <Link
            to="/login"
            className="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white"
          >
            Se connecter
          </Link>
        ) : (
          <>
            <Link to="/admin" className="hover:text-gray-300 transition">
              Dashboard
            </Link>

            <Link to="/admin/utilisateurs" className="hover:text-gray-300 transition">
              Utilisateurs
            </Link>

            <Link to="/admin/annonces" className="hover:text-gray-300 transition">
              Annonces
            </Link>

            <Link to="/admin/entrepots" className="hover:text-gray-300 transition">
              Entrepots
            </Link>

            <button
              onClick={handleLogout}
              className="bg-red-500 hover:bg-red-600 px-4 py-2 rounded"
            >
              Déconnexion
            </button>
          </>
        )}
      </div>
    </nav>
  );
}
