import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api", // Changer si backend est ailleurs
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default api;
