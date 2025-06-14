import { useAuth } from "../context/AuthContext";
import { Navigate } from "react-router-dom";
import PropTypes from "prop-types";

export default function RoleRoute({ children, role }) {
  const { user } = useAuth();

  if (!user) return <Navigate to="/login" replace />;
  if (user.role !== role) return <Navigate to="/monprofil" replace />;

  return children;
}

RoleRoute.propTypes = {
  children: PropTypes.node.isRequired,
  role: PropTypes.string.isRequired,
};
