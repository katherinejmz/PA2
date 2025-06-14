import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import RoleRoute from "./RoleRoute";
import Login from "../pages/Login";
import Register from "../pages/Register";
import RegisterCommercant from "../pages/RegisterCommercant";
import RegisterLivreur from "../pages/RegisterLivreur";
import RegisterPrestataire from "../pages/RegisterPrestataire";
import MonProfilRedirect from "../pages/MonProfilRedirect";
import ProfilClient from "../pages/ProfilClient";
import ProfilCommercant from "../pages/ProfilCommercant";
import ProfilLivreur from "../pages/ProfilLivreur";
import ProfilPrestataire from "../pages/ProfilPrestataire";
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
                <Annonces />
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
                <CreerAnnonce />
              </PrivateRoute>
            }
          />
          <Route
            path="/mes-annonces"
            element={
              <PrivateRoute>
                <MesAnnonces />
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
                <MonProfilRedirect />
              </PrivateRoute>
            }
          />

          <Route
            path="/profil-client"
            element={
              <PrivateRoute>
                <RoleRoute role="client">
                  <ProfilClient />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/profil-commercant"
            element={
              <PrivateRoute>
                <RoleRoute role="commercant">
                  <ProfilCommercant />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/profil-livreur"
            element={
              <PrivateRoute>
                <RoleRoute role="livreur">
                  <ProfilLivreur />
                </RoleRoute>
              </PrivateRoute>
            }
          />

          <Route
            path="/profil-prestataire"
            element={
              <PrivateRoute>
                <RoleRoute role="prestataire">
                  <ProfilPrestataire />
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


        </Routes>
      </MainLayout>
    </Router>
  );
}
