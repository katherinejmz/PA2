import { useAuth } from "../context/AuthContext";
import { Navigate } from "react-router-dom";
import PropTypes from "prop-types";

export default function RoleRoute({ role, children }) {
  const { user } = useAuth();

  if (!user) return null;

  if (Array.isArray(role)) {
    return role.includes(user.role) ? children : <Navigate to="/" />;
  }

  return user.role === role ? children : <Navigate to="/" />;
}


RoleRoute.propTypes = {
  children: PropTypes.node.isRequired,
  role: PropTypes.string.isRequired,
};
