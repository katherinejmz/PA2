// src/pages/Paiement.jsx
import { useParams } from "react-router-dom";

export default function Paiement() {
  const { commandeId } = useParams();

  return (
    <div className="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
      <h1 className="text-2xl font-bold mb-4">Paiement de la commande #{commandeId}</h1>
      <p>Page de paiement à venir...</p>
    </div>
  );
}
