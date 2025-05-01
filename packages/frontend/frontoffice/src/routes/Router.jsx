import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import ProfilClient from "../pages/ProfilClient";
import MainLayout from "../layouts/MainLayout";
import PrivateRoute from "./PrivateRoute";
import Annonces from "../pages/Annonces";
import CreerAnnonce from "../pages/CreerAnnonce";

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
            path="/annonces/creer"
            element={
              <PrivateRoute>
                <CreerAnnonce />
              </PrivateRoute>
            }
          />
        </Routes>
      </MainLayout>
    </Router>
  );
}
