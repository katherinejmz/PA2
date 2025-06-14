import { useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";

export default function MonProfilRedirect() {
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (!user) {
      navigate("/login");
      return;
    }

    switch (user.role) {
      case "client":
        navigate("/profil-client");
        break;
      case "commercant":
        navigate("/profil-commercant");
        break;
      case "livreur":
        navigate("/profil-livreur");
        break;
      case "prestataire":
        navigate("/profil-prestataire");
        break;
      default:
        navigate("/login");
    }
  }, [user, navigate]);

  return null;
}
