import { useState } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";

export default function Login() {
  const [identifiant, setIdentifiant] = useState("");
  const [password, setPassword] = useState("");
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await login(identifiant, password);
      navigate("/admin");
    } catch (error) {
      const message = error.response?.data?.message || error.message || "Erreur inconnue";
      alert("Connexion échouée : " + message);
      console.error(error);
    }
  };


  return (
    <form onSubmit={handleSubmit} className="max-w-sm mx-auto mt-10 space-y-4">
      <h2 className="text-xl font-semibold">Connexion administrateur</h2>
      <input
        type="text"
        placeholder="Identifiant"
        value={identifiant}
        onChange={(e) => setIdentifiant(e.target.value)}
        className="w-full p-2 border rounded"
        required
      />
      <input
        type="password"
        placeholder="Mot de passe"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        className="w-full p-2 border rounded"
        required
      />
      <button
        type="submit"
        className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700"
      >
        Se connecter
      </button>
    </form>
  );
}
