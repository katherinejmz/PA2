import { Link, useNavigate, useLocation } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useState, useEffect } from "react";
import { Transition } from "@headlessui/react";

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    if (user?.role === "livreur" && location.pathname === "/") {
      navigate("/mes-trajets");
    }
  }, [user, location, navigate]);

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  const getMenuItems = () => {
    if (!user) return [];

    const common = [
      { path: "/monprofil", label: "Mon Profil" },
      { path: "/notifications", label: "Notifications" },
    ];

    if (user.role === "client") {
      return [
        ...common,
        { path: "/annonces", label: "Annonces" },
        { path: "/mes-annonces", label: "Mes annonces" },
        { path: "/prestations/catalogue", label: "Prestations" },
        { path: "/interventions", label: "Interventions" },
      ];
    }

    if (user.role === "prestataire") {
      return [
        ...common,
        { path: "/mes-prestations", label: "Mes prestations" },
        { path: "/prestations/publier", label: "Publier une prestation" },
        { path: "/interventions", label: "Interventions" },
        { path: "/planning", label: "Planning" },
        { path: "/factures", label: "Factures" },
      ];
    }

    if (user.role === "commercant") {
      return [
        ...common,
        { path: "/annonces", label: "Annonces" },
        { path: "/mes-annonces", label: "Mes annonces" },
      ];
    }

    if (user.role === "livreur") {
      return [
        ...common,
        { path: "/mes-trajets", label: "Mes trajets" },
        { path: "/annonces-disponibles", label: "Annonces disponibles" },
        { path: "/mes-etapes", label: "Mes étapes" },
      ];
    }

    return common;
  };

  const menuItems = getMenuItems();

  return (
    <header className="bg-white text-green-900 shadow-md sticky top-0 z-50 border-b border-gray-200">
      <nav className="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
        <Link to="/" className="text-2xl font-bold hover:text-lime-300 transition">
          EcoDeli
        </Link>

        <ul className="hidden sm:flex gap-6 text-sm font-medium items-center">
          <li><Link to="/" className="hover:text-lime-300 transition">Accueil</Link></li>

          {!user ? (
            <>
              <li><Link to="/login" className="hover:text-lime-300 transition">Connexion</Link></li>
              <li><Link to="/register" className="hover:text-lime-300 transition">Inscription</Link></li>
            </>
          ) : (
            <>
              {menuItems.map((item) => (
                <li key={item.path}>
                  <Link to={item.path} className="hover:text-lime-300 transition">{item.label}</Link>
                </li>
              ))}
              <li>
                <button onClick={handleLogout} className="hover:text-red-300 transition">
                  Se déconnecter
                </button>
              </li>
            </>
          )}
        </ul>

        <div className="sm:hidden">
          <button
            onClick={() => setMenuOpen(!menuOpen)}
            className="flex items-center gap-2 border border-white px-3 py-1 rounded-md hover:bg-white hover:text-green-700 transition"
          >
            <svg className="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
              <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span className="text-sm font-medium">Menu</span>
          </button>
        </div>
      </nav>

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
                {menuItems.map((item) => (
                  <li key={item.path}><Link to={item.path} className="block hover:text-lime-300">{item.label}</Link></li>
                ))}
                <li>
                  <button onClick={handleLogout} className="block hover:text-red-300">Se déconnecter</button>
                </li>
              </>
            )}
          </ul>
        </div>
      </Transition>
    </header>
  );
}
