import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import RoleRoute from "./RoleRoute";
import Login from "../pages/Login";
import Register from "../pages/Register";
import RegisterCommercant from "../pages/RegisterCommercant";
import RegisterLivreur from "../pages/RegisterLivreur";
import RegisterPrestataire from "../pages/RegisterPrestataire";
import Profil from "../pages/Profil";
import EditProfil from "../pages/EditProfil";
import ChangePassword from "../pages/ChangePassword";
import ResetPassword from "../pages/ResetPassword";
import MainLayout from "../layouts/MainLayout";
import PrivateRoute from "./PrivateRoute";
import Annonces from "../pages/Annonces";
import CreerAnnonce from "../pages/CreerAnnonce";
import AnnonceDetail from "../pages/AnnonceDetail";
import AdresseLivraison from "../pages/AdresseLivraison";
import Paiement from "../pages/Paiement";
import DetailsService from "../pages/DetailsService";
import MesAnnonces from "../pages/MesAnnonces";
import AnnoncesDisponibles from "../pages/AnnoncesDisponibles";
import MesLivraisons from "../pages/MesLivraisons";
import Planning from "../pages/Planning";
import MesPrestations from "../pages/MesPrestations";
import Interventions from "../pages/Interventions";
import Factures from "../pages/Factures";
import PublierPrestation from "../pages/PublierPrestation";
import Notifications from "../pages/Notifications";
import CataloguePrestations from "../pages/CataloguePrestations";
import MesTrajets from "../pages/MesTrajets";
import MesEtapes from "../pages/MesEtapes";
import ValidationCodeBox from "../pages/ValidationCodeBox";
import SuiviAnnonce from "../pages/SuiviAnnonce";

export default function AppRouter() {
  return (
    <Router>
      <MainLayout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/register-commercant" element={<RegisterCommercant />} />
          <Route path="/register-livreur" element={<RegisterLivreur />} />
          <Route path="/register-prestataire" element={<RegisterPrestataire />} />
          <Route
            path="/annonces"
            element={
              <PrivateRoute>
                <RoleRoute role={["client", "commercant"]}>
                  <Annonces />
                </RoleRoute>
              </PrivateRoute>
            }
          />
          <Route
            path="/annonces/:id"
            element={
              <PrivateRoute>
                <AnnonceDetail />
              </PrivateRoute>
            }
          />
          <Route
            path="/annonces/creer"
            element={
              <PrivateRoute>
                <RoleRoute role={["client", "commercant"]}>
                  <CreerAnnonce />
                </RoleRoute>
              </PrivateRoute>
            }
          />
          <Route
            path="/mes-annonces"
            element={
              <PrivateRoute>
                <RoleRoute role={["client", "commercant"]}>
                  <MesAnnonces />
                </RoleRoute>
              </PrivateRoute>
            }
          />
          <Route path="/adresse-livraison/:commandeId" element={<AdresseLivraison />} />
          <Route
            path="/paiement/:commandeId"
            element={
              <PrivateRoute>
                <Paiement />
              </PrivateRoute>
            }
          />
          <Route
            path="/details-service/:commandeId"
            element={
              <PrivateRoute>
                <DetailsService />
              </PrivateRoute>
            }
          />
          <Route
            path="/monprofil"
            element={
              <PrivateRoute>
                <Profil />
              </PrivateRoute>
            }
          />

          <Route
            path="/profil/edit"
            element={
              <PrivateRoute>
                <EditProfil />
              </PrivateRoute>
            }
          />

          <Route
            path="/profil/motdepasse"
            element={
              <PrivateRoute>
                <ChangePassword />
              </PrivateRoute>
            }
          />

          <Route
            path="/profil/resetpassword"
            element={
              <PrivateRoute>
                <ResetPassword />
              </PrivateRoute>
            }
          />

          <Route
            path="/mes-trajets"
            element={
              <PrivateRoute>
                <RoleRoute role="livreur">
                  <MesTrajets />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/mes-etapes"
            element={
              <PrivateRoute>
                <RoleRoute role="livreur">
                  <MesEtapes />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/annonces-disponibles"
            element={
              <PrivateRoute>
                <AnnoncesDisponibles />
              </PrivateRoute>
            }
          />

          <Route
            path="/mes-livraisons"
            element={
              <PrivateRoute>
                <MesLivraisons />
              </PrivateRoute>
            }
          />

          <Route
            path="/planning"
            element={
              <PrivateRoute>
                <RoleRoute role={["prestataire"]}>
                  <Planning />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/mes-prestations"
            element={
              <PrivateRoute>
                <RoleRoute role={["client", "prestataire"]}>
                  <MesPrestations />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/interventions"
            element={
              <PrivateRoute>
                <RoleRoute role={["client", "prestataire"]}>
                  <Interventions />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/factures"
            element={
              <PrivateRoute>
                <RoleRoute role={["prestataire"]}>
                  <Factures />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/prestations/publier"
            element={
              <PrivateRoute>
                <RoleRoute role="prestataire">
                  <PublierPrestation />
                </RoleRoute>
              </PrivateRoute>
            }
          />


          <Route
            path="/notifications"
            element={
              <PrivateRoute>
                <Notifications />
              </PrivateRoute>
            }
          />

          <Route
            path="/prestations/catalogue"
            element={
              <PrivateRoute>
                <RoleRoute role="client">
                  <CataloguePrestations />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route 
            path="/etapes/:etapeId/validation-code" 
            element={
              <PrivateRoute>
                <ValidationCodeBox />
              </PrivateRoute>
            } 
          />

          <Route 
            path="/annonces/:annonceId/suivi"
            element={
              <PrivateRoute>
                <SuiviAnnonce />
              </PrivateRoute>
            } 
          />

        </Routes>
      </MainLayout>
    </Router>
  );
}
