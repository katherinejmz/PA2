import { Link } from "react-router-dom";

export default function Navbar() {
  return (
    <nav className="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow">
      <div className="text-xl font-bold">EcoDeli</div>
      <ul className="flex space-x-6">
        <li><Link to="/" className="hover:text-gray-200">Accueil</Link></li>
        <li><Link to="/login" className="hover:text-gray-200">Connexion</Link></li>
        <li><Link to="/register" className="hover:text-gray-200">Inscription</Link></li>
      </ul>
    </nav>
  );
}
