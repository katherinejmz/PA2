import Navbar from "../components/Navbar";
import TutorielOverlay from "../components/TutorielOverlay";

export default function MainLayout({ children }) {
  return (
    <div className="bg-gray-100 min-h-screen flex flex-col">
      <Navbar />

      {/* Affiche le tutoriel dès qu'un utilisateur client est connecté */}
      <TutorielOverlay />

      <main className="flex-grow container mx-auto px-6 py-8 bg-white rounded shadow-md mt-4">
        {children}
      </main>

      <footer className="bg-white shadow mt-12">
        <div className="container mx-auto px-4 py-4 text-center text-sm text-gray-500">
          © {new Date().getFullYear()} EcoDeli - Tous droits réservés.
        </div>
      </footer>
    </div>
  );
}
