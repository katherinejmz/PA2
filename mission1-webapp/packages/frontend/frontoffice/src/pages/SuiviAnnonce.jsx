import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";

export default function SuiviAnnonce() {
  const { annonceId } = useParams();
  const { token, user } = useAuth();
  const [annonce, setAnnonce] = useState(null);
  const [code, setCode] = useState("");
  const [message, setMessage] = useState("");
  const [etatCode, setEtatCode] = useState(null);
  const [loading, setLoading] = useState(false);

  const fetchAnnonce = async () => {
    try {
      const res = await api.get(`/annonces/${annonceId}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setAnnonce(res.data);
    } catch (err) {
      console.error("Erreur chargement annonce:", err);
    }
  };

  useEffect(() => {
    if (token) {
      fetchAnnonce();
    }
  }, [token, annonceId]);

  if (!annonce || !user) return <p className="text-center mt-10">Chargement...</p>;

  const isCommercant = user.role === "commercant";

  const etapeDepotCommercant = annonce.etapes_livraison?.find(
    (etape) =>
      etape.est_commercant === true &&
      etape.codes?.some((c) => c.type === "depot" && !c.utilise)
  );

  const etapeDepotClient = annonce.etapes_livraison?.find(
    (etape) =>
      etape.est_client === true &&
      etape.codes?.some((c) => c.type === "depot" && !c.utilise)
  );

  const etapeRetraitClient = annonce.etapes_livraison?.find(
    (etape) =>
      etape.est_client === true &&
      etape.codes?.some((c) => c.type === "retrait" && !c.utilise)
  );

  const etapeFinalePourClient = annonce.etapes_livraison?.find(
    (etape) =>
      etape.est_client === false &&
      etape.lieu_arrivee === annonce.entrepot_arrivee?.ville &&
      etape.statut === "terminee" &&
      etape.codes?.some((c) => c.type === "retrait")
  );

  const codeRetraitClientFinal = etapeFinalePourClient?.codes?.find(
    (c) => c.type === "retrait"
  );

  const validerCode = async (type, etape_id) => {
    setLoading(true);
    setEtatCode(null);
    setMessage("");

    try {
      await api.post(
        "/valider-code-box",
        { code, type, etape_id },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      setEtatCode("success");
      setMessage("✅ Code validé avec succès.");
      setCode("");
      await fetchAnnonce();
    } catch (err) {
      setEtatCode("error");
      setMessage(err.response?.data?.message || "Erreur lors de la validation.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">
      <h2 className="text-2xl font-bold mb-4">Suivi de l'annonce : {annonce.titre}</h2>
      <p className="mb-2">Description : {annonce.description}</p>
      <p className="mb-4">Statut : {annonce.statut}</p>

      {!isCommercant && etapeDepotClient && (
        <EtapeForm
          titre="🚚 Dépôt initial"
          code={code}
          setCode={setCode}
          loading={loading}
          valider={() => validerCode("depot", etapeDepotClient.id)}
          message={message}
          etatCode={etatCode}
        />
      )}

      {isCommercant && etapeDepotCommercant && (
        <EtapeForm
          titre="🏪 Dépôt du commerçant"
          code={code}
          setCode={setCode}
          loading={loading}
          valider={() => validerCode("depot", etapeDepotCommercant.id)}
          message={message}
          etatCode={etatCode}
        />
      )}

      {!isCommercant && etapeRetraitClient && (
        <EtapeForm
          titre="📦 Retrait (étape client)"
          code={code}
          setCode={setCode}
          loading={loading}
          valider={() => validerCode("retrait", etapeRetraitClient.id)}
          message={message}
          etatCode={etatCode}
          isRetrait
        />
      )}

      {!isCommercant && etapeFinalePourClient && (
        <div className="mt-10">
          <h3 className="text-lg font-semibold mb-2">📦 Retrait du colis final</h3>
          {codeRetraitClientFinal?.utilise ? (
            <p className="text-green-600 font-semibold">
              ✅ Colis déjà retiré par le client.
            </p>
          ) : (
            <EtapeForm
              titre=""
              code={code}
              setCode={setCode}
              loading={loading}
              valider={() => validerCode("retrait", etapeFinalePourClient.id)}
              message={message}
              etatCode={etatCode}
              isRetrait
            />
          )}
        </div>
      )}
    </div>
  );
}

function EtapeForm({ titre, code, setCode, loading, valider, message, etatCode, isRetrait }) {
  return (
    <div className="mt-10">
      {titre && <h3 className="text-lg font-semibold mb-2">{titre}</h3>}
      <form
        onSubmit={(e) => {
          e.preventDefault();
          valider();
        }}
        className="space-y-4"
      >
        <label className="block font-medium">Code {isRetrait ? "de retrait" : "de dépôt"}</label>
        <input
          type="text"
          value={code}
          onChange={(e) => setCode(e.target.value)}
          className="p-2 border rounded w-full"
          required
        />
        <button
          type="submit"
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          disabled={loading}
        >
          {loading
            ? "Vérification..."
            : isRetrait
            ? "Retirer le colis"
            : "Valider le dépôt"}
        </button>
        {etatCode === "success" && <p className="text-green-600">{message}</p>}
        {etatCode === "error" && <p className="text-red-600">{message}</p>}
      </form>
    </div>
  );
}
