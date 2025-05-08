import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import ProfilClient from "../pages/ProfilClient";
import MainLayout from "../layouts/MainLayout";
import PrivateRoute from "./PrivateRoute";
import Annonces from "../pages/Annonces";
import CreerAnnonce from "../pages/CreerAnnonce";
import AnnonceDetail from "../pages/AnnonceDetail";
import AdresseLivraison from "../pages/AdresseLivraison";
import Paiement from "../pages/Paiement";
import DetailsService from "../pages/DetailsService";

export default function AppRouter() {
  return (
    <Router>
      <MainLayout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route
            path="/profil"
            element={
              <PrivateRoute>
                <ProfilClient />
              </PrivateRoute>
            }
          />
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

        </Routes>
      </MainLayout>
    </Router>
  );
}
