import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useState } from "react";
import { Transition } from "@headlessui/react";

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [menuOpen, setMenuOpen] = useState(false);

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  return (
    <header className="bg-white text-green-900 shadow-md sticky top-0 z-50 border-b border-gray-200">
      <nav className="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
        {/* Logo */}
        <Link to="/" className="text-2xl font-bold hover:text-lime-300 transition">
          EcoDeli
        </Link>

        {/* Menu desktop */}
        <ul className="hidden sm:flex gap-6 text-sm font-medium items-center">
          <li><Link to="/" className="hover:text-lime-300 transition">Accueil</Link></li>

          {!user ? (
            <>
              <li><Link to="/login" className="hover:text-lime-300 transition">Connexion</Link></li>
              <li><Link to="/register" className="hover:text-lime-300 transition">Inscription</Link></li>
            </>
          ) : (
            <>
              <li>
                <Link
                  to={
                    user?.role === "client"
                      ? "/profil-client"
                      : user?.role === "commercant"
                      ? "/profil-commercant"
                      : user?.role === "livreur"
                      ? "/profil-livreur"
                      : user?.role === "prestataire"
                      ? "/profil-prestataire"
                      : "/profil"
                  }
                  className="hover:text-lime-300 transition"
                >
                  Mon profil
                </Link>
              </li>

              {["client", "commercant", "prestataire"].includes(user?.role) ? (
                <>
                  <li><Link to="/annonces" className="block hover:text-lime-300">Annonces</Link></li>
                  <li><Link to="/mes-annonces" className="block hover:text-lime-300">Mes annonces</Link></li>
                </>
              ) : user?.role === "livreur" ? (
                <li><Link to="/annonces-disponibles" className="block hover:text-lime-300">Annonces disponibles</Link>
                <Link to="/mes-livraisons" className="hover:text-lime-300 transition">Mes livraisons</Link></li>
                
              ) : null}

              <li>
                <button onClick={handleLogout} className="hover:text-red-300 transition">
                  Se déconnecter
                </button>
              </li>
            </>
          )}
        </ul>

        {/* Menu burger (mobile only) */}
        <div className="sm:hidden">
          <button
            onClick={() => setMenuOpen(!menuOpen)}
            className="flex items-center gap-2 border border-white px-3 py-1 rounded-md hover:bg-white hover:text-green-700 transition"
          >
            {/* Icon burger */}
            <svg className="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
              <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span className="text-sm font-medium">Menu</span>
          </button>
        </div>
      </nav>

      {/* Menu mobile animé */}
      <Transition
        show={menuOpen}
        enter="transition ease-out duration-300"
        enterFrom="transform opacity-0 -translate-y-2"
        enterTo="transform opacity-100 translate-y-0"
        leave="transition ease-in duration-200"
        leaveFrom="transform opacity-100 translate-y-0"
        leaveTo="transform opacity-0 -translate-y-2"
      >
        <div className="sm:hidden bg-white px-6 pb-4 space-y-2 text-sm font-medium text-green-900">
          <ul>
            <li><Link to="/" className="block hover:text-lime-300">Accueil</Link></li>

            {!user ? (
              <>
                <li><Link to="/login" className="block hover:text-lime-300">Connexion</Link></li>
                <li><Link to="/register" className="block hover:text-lime-300">Inscription</Link></li>
              </>
            ) : (
              <>
                <li>
                  <Link
                    to={
                      user?.role === "client"
                        ? "/profil-client"
                        : user?.role === "commercant"
                        ? "/profil-commercant"
                        : user?.role === "livreur"
                        ? "/profil-livreur"
                        : user?.role === "prestataire"
                        ? "/profil-prestataire"
                        : "/profil"
                    }
                    className="block hover:text-lime-300"
                  >
                    Mon profil
                  </Link>
                </li>

                {["client", "commercant", "prestataire"].includes(user?.role) ? (
                  <>
                    <li><Link to="/annonces" className="hover:text-lime-300 transition">Annonces</Link></li>
                    <li><Link to="/mes-annonces" className="hover:text-lime-300 transition">Mes annonces</Link></li>
                  </>
                ) : user?.role === "livreur" ? (
                  <li><Link to="/annonces-disponibles" className="hover:text-lime-300 transition">Annonces disponibles</Link></li>
                ) : null}
                <li>
                  <button onClick={handleLogout} className="block hover:text-red-300">
                    Se déconnecter
                  </button>
                </li>
              </>
            )}
          </ul>
        </div>
      </Transition>
    </header>
  );
}
