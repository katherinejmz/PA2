export default function Home() {
  return (
    <section className="min-h-screen bg-gradient-to-br from-green-100 to-white flex items-center justify-center px-4">
      <div className="text-center">
        <h1 className="text-4xl sm:text-5xl font-bold text-green-800 mb-6 drop-shadow-md">
          Bienvenue sur <span className="text-primary">EcoDeli</span>
        </h1>
        <p className="text-lg sm:text-xl text-gray-700 max-w-xl mx-auto">
          Rejoignez une communauté de livraison collaborative, locale et responsable 🌍
        </p>
        <div className="font-sans text-xl text-primary">Tailwind fonctionne </div>
        
      </div>
    </section>
  );
}
