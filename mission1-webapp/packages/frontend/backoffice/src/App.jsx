import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import AdminRoutes from './routes/AdminRoutes';
import PrivateRoute from './routes/PrivateRoute';
import RoleRoute from './routes/RoleRoute';

export default function App() {
  return (
    <Router>
      <Routes>
        {/* Page de login */}
        <Route path="/login" element={<Login />} />

        {/* Espace admin protégé */}
        <Route
          path="/admin/*"
          element={
            <PrivateRoute>
              <RoleRoute role="admin">
                <AdminRoutes />
              </RoleRoute>
            </PrivateRoute>
          }
        />

        {/* Redirection automatique */}
        <Route path="*" element={<Navigate to="/admin" />} />
      </Routes>
    </Router>
  );
}
