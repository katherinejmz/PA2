import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useState } from "react";
import { Transition } from "@headlessui/react";


export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [menuOpen, setMenuOpen] = useState(false);

  const [prestationsOpen, setPrestationsOpen] = useState(false);
  const [annoncesOpen, setAnnoncesOpen] = useState(false);
  

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
              <Link to="/monprofil" className="hover:text-lime-300 transition">Mon Profil</Link>

              {/* Menu Prestations - client ou prestataire */}
              {["client", "prestataire"].includes(user?.role) && (
                <li className="relative">
                  <button
                    onClick={() => setPrestationsOpen(!prestationsOpen)}
                    onBlur={() => setTimeout(() => setPrestationsOpen(false), 150)} // ferme après clic
                    className="hover:text-lime-300 transition"
                  >
                    Prestations ▾
                  </button>

                  {prestationsOpen && (
                    <ul
                      className="absolute right-0 z-50 bg-white shadow-lg mt-2 rounded text-sm text-green-900 min-w-[200px] border"
                      onMouseLeave={() => setPrestationsOpen(false)}
                    >
                      {user.role === "client" && (
                        <>
                          <li><Link to="/mes-prestations" className="block px-4 py-2 hover:bg-lime-100">Mes prestations</Link></li>
                          <li><Link to="/prestations/catalogue" className="block px-4 py-2 hover:bg-lime-100">Voir les prestations</Link></li>
                          <li><Link to="/interventions" className="block px-4 py-2 hover:bg-lime-100">Interventions</Link></li>
                        </>
                      )}
                      {user.role === "prestataire" && (
                        <>
                          <li><Link to="/mes-prestations" className="block px-4 py-2 hover:bg-lime-100">Mes prestations</Link></li>
                          <li><Link to="/prestations/publier" className="block px-4 py-2 hover:bg-lime-100">Publier une prestation</Link></li>
                          <li><Link to="/interventions" className="block px-4 py-2 hover:bg-lime-100">Interventions</Link></li>
                          <li><Link to="/planning" className="block px-4 py-2 hover:bg-lime-100">Planning</Link></li>
                          <li><Link to="/factures" className="block px-4 py-2 hover:bg-lime-100">Factures</Link></li>
                        </>
                      )}
                    </ul>
                  )}
                </li>
              )}

              {/* Menu Annonces */}
              {["client", "commercant", "livreur"].includes(user?.role) && (
                <li className="relative">
                  <button
                    onClick={() => setAnnoncesOpen(!annoncesOpen)}
                    onBlur={() => setTimeout(() => setAnnoncesOpen(false), 150)}
                    className="hover:text-lime-300 transition"
                  >
                    Annonces ▾
                  </button>

                  {annoncesOpen && (
                    <ul
                      className="absolute right-0 z-50 bg-white shadow-lg mt-2 rounded text-sm text-green-900 min-w-[200px] border"
                      onMouseLeave={() => setAnnoncesOpen(false)}
                    >
                      {["client", "commercant"].includes(user.role) && (
                        <>
                          <li><Link to="/annonces" className="block px-4 py-2 hover:bg-lime-100">Annonces</Link></li>
                          <li><Link to="/mes-annonces" className="block px-4 py-2 hover:bg-lime-100">Mes annonces</Link></li>
                        </>
                      )}
                      {user.role === "livreur" && (
                        <>
                          <li><Link to="/annonces-disponibles" className="block px-4 py-2 hover:bg-lime-100">Annonces disponibles</Link></li>
                          <li><Link to="/mes-livraisons" className="block px-4 py-2 hover:bg-lime-100">Mes livraisons</Link></li>
                        </>
                      )}
                    </ul>
                  )}
                </li>
              )}

              {/*user?.role === "client" && (
                <>
                  <li><Link to="/annonces" className="hover:text-lime-300">Annonces</Link></li>
                  <li><Link to="/mes-annonces" className="hover:text-lime-300">Mes annonces</Link></li>
                </>
              )*/}

              {/*user?.role === "prestataire" && (
                <>
                  <li></li>
                </>
              )*/}

              {/*user?.role === "commercant" && (
                <>
                  <li><Link to="/annonces" className="hover:text-lime-300">Annonces</Link></li>
                  <li><Link to="/mes-annonces" className="hover:text-lime-300">Mes annonces</Link></li>
                </>
              )*/}

              {/*user?.role === "livreur" && (
                <>
                  <li><Link to="/annonces-disponibles" className="hover:text-lime-300">Annonces disponibles</Link></li>
                  <li><Link to="/mes-livraisons" className="hover:text-lime-300">Mes livraisons</Link></li>
                </>
              )*/}

              {user && (
                <li>
                  <Link to="/notifications" className="hover:text-lime-300">
                    Notifications
                  </Link>
                </li>
              )}

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
                <Link to="/monprofil" className="hover:text-lime-300 transition">Mon Profil</Link>

                {user?.role === "client" && (
                  <>
                    <li><Link to="/annonces" className="hover:text-lime-300">Annonces</Link></li>
                    <li><Link to="/mes-annonces" className="hover:text-lime-300">Mes annonces</Link></li>
                    <li><Link to="/mes-prestations" className="hover:text-lime-300">Prestations</Link></li>
                    <li><Link to="/interventions" className="hover:text-lime-300">Interventions</Link></li>
                    <li><Link to="/prestations/creer" className="hover:text-lime-300">Créer une prestation</Link></li>
                  </>
                )}

                {user?.role === "prestataire" && (
                  <>
                    <li><Link to="/planning" className="hover:text-lime-300">Planning</Link></li>
                    <li><Link to="/mes-prestations" className="hover:text-lime-300">Prestations</Link></li>
                    <li><Link to="/interventions" className="hover:text-lime-300">Interventions</Link></li>
                    <li><Link to="/factures" className="hover:text-lime-300">Factures</Link></li>
                  </>
                )}

                {user?.role === "commercant" && (
                  <>
                    <li><Link to="/annonces" className="hover:text-lime-300">Annonces</Link></li>
                    <li><Link to="/mes-annonces" className="hover:text-lime-300">Mes annonces</Link></li>
                  </>
                )}

                {user?.role === "livreur" && (
                  <>
                    <li><Link to="/annonces-disponibles" className="hover:text-lime-300">Annonces disponibles</Link></li>
                    <li><Link to="/mes-livraisons" className="hover:text-lime-300">Mes livraisons</Link></li>
                  </>
                )}


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
