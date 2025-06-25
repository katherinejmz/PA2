import { Routes, Route } from 'react-router-dom';
import AdminLayout from '../layouts/AdminLayout';
import Dashboard from '../pages/admin/Dashboard';
import UsersList from "../pages/admin/UsersList";
import EditUserForm from "../pages/admin/EditUserForm";
import UserDetails from "../pages/admin/UserDetails";
import AnnoncesList from "../pages/admin/AnnoncesList";
import EntrepotsList from "../pages/admin/EntrepotsList";

export default function AdminRoutes() {
  return (
    <Routes>
      <Route path="/" element={<AdminLayout />}>
        <Route index element={<Dashboard />} />
        <Route path="utilisateurs" element={<UsersList />} />
        <Route path="utilisateurs/:id/edit" element={<EditUserForm />} />
        <Route path="utilisateurs/:id" element={<UserDetails />} />
        <Route path="annonces" element={<AnnoncesList />} />
        <Route path="entrepots" element={<EntrepotsList />} />
      </Route>
    </Routes>
  );
}
