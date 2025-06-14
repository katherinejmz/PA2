import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";

export default function TutorielOverlay() {
  const { user } = useAuth();
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    if (!user || user.role !== "client") return;

    const alreadySeen = localStorage.getItem("tutorielVu_client_" + user.id);
    if (!alreadySeen) {
      setVisible(true);
    }
  }, [user]);

  const handleClose = () => {
    localStorage.setItem("tutorielVu_client_" + user.id, "true");
    setVisible(false);
  };

  if (!visible) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-80 z-[9999] flex items-center justify-center">
      <div className="bg-white w-full max-w-2xl p-8 rounded-lg shadow-lg text-center space-y-4">
        <h2 className="text-3xl font-bold text-green-900">Bienvenue sur EcoDeli !</h2>
        <p className="text-gray-700 text-lg">
          Ce tutoriel vous guide pour utiliser notre plateforme :
        </p>
        <ul className="text-left text-base list-disc pl-6 space-y-2 text-gray-700">
          <li>Créez une annonce pour demander une livraison ou un service</li>
          <li>Consultez vos commandes et prestations en temps réel</li>
          <li>Évaluez les prestataires ou livreurs après chaque mission</li>
          <li>Recevez des notifications personnalisées</li>
        </ul>
        <button
          onClick={handleClose}
          className="mt-4 bg-green-700 text-white px-6 py-3 rounded hover:bg-green-800"
        >
          J’ai compris
        </button>
      </div>
    </div>
  );
}
