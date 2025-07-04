PGDMP      #                }         
   ecodeli_pa    17.4    17.4 �    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            �           1262    24881 
   ecodeli_pa    DATABASE     p   CREATE DATABASE ecodeli_pa WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en-US';
    DROP DATABASE ecodeli_pa;
                     postgres    false                        2615    28141    public    SCHEMA     2   -- *not* creating schema, since initdb creates it
 2   -- *not* dropping schema, since initdb creates it
                     postgres    false            �           0    0    SCHEMA public    COMMENT         COMMENT ON SCHEMA public IS '';
                        postgres    false    5            �           0    0    SCHEMA public    ACL     +   REVOKE USAGE ON SCHEMA public FROM PUBLIC;
                        postgres    false    5            �            1259    28721    adresses_livraison    TABLE     �  CREATE TABLE public.adresses_livraison (
    id bigint NOT NULL,
    commande_id bigint NOT NULL,
    adresse character varying(255) NOT NULL,
    ville character varying(255) NOT NULL,
    code_postal character varying(255) NOT NULL,
    pays character varying(255) NOT NULL,
    instructions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 &   DROP TABLE public.adresses_livraison;
       public         heap r       postgres    false    5            �            1259    28720    adresses_livraison_id_seq    SEQUENCE     �   CREATE SEQUENCE public.adresses_livraison_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.adresses_livraison_id_seq;
       public               postgres    false    5    253            �           0    0    adresses_livraison_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.adresses_livraison_id_seq OWNED BY public.adresses_livraison.id;
          public               postgres    false    252            �            1259    28508    annonce_utilisateur    TABLE     �   CREATE TABLE public.annonce_utilisateur (
    id bigint NOT NULL,
    annonce_id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    accepte_le timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
 '   DROP TABLE public.annonce_utilisateur;
       public         heap r       postgres    false    5            �            1259    28507    annonce_utilisateur_id_seq    SEQUENCE     �   CREATE SEQUENCE public.annonce_utilisateur_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.annonce_utilisateur_id_seq;
       public               postgres    false    5    229            �           0    0    annonce_utilisateur_id_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.annonce_utilisateur_id_seq OWNED BY public.annonce_utilisateur.id;
          public               postgres    false    228            �            1259    28448    annonces    TABLE     �  CREATE TABLE public.annonces (
    id bigint NOT NULL,
    type character varying(255) NOT NULL,
    titre character varying(255) NOT NULL,
    description text NOT NULL,
    prix_propose numeric(8,2) NOT NULL,
    photo character varying(255),
    id_client bigint NOT NULL,
    id_commercant bigint,
    id_prestataire bigint,
    lieu_depart character varying(255),
    lieu_arrivee character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    statut character varying(255) DEFAULT 'en_attente'::character varying NOT NULL,
    CONSTRAINT annonces_statut_check CHECK (((statut)::text = ANY ((ARRAY['en_attente'::character varying, 'acceptee'::character varying, 'en_cours'::character varying, 'livree'::character varying])::text[]))),
    CONSTRAINT annonces_type_check CHECK (((type)::text = ANY ((ARRAY['livraison_client'::character varying, 'produit_livre'::character varying, 'service'::character varying])::text[])))
);
    DROP TABLE public.annonces;
       public         heap r       postgres    false    5            �            1259    28447    annonces_id_seq    SEQUENCE     x   CREATE SEQUENCE public.annonces_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.annonces_id_seq;
       public               postgres    false    222    5            �           0    0    annonces_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.annonces_id_seq OWNED BY public.annonces.id;
          public               postgres    false    221            �            1259    28630    boxes    TABLE       CREATE TABLE public.boxes (
    id bigint NOT NULL,
    entrepot_id bigint NOT NULL,
    code_box character varying(255) NOT NULL,
    est_occupe boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.boxes;
       public         heap r       postgres    false    5            �            1259    28629    boxes_id_seq    SEQUENCE     u   CREATE SEQUENCE public.boxes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.boxes_id_seq;
       public               postgres    false    5    243            �           0    0    boxes_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.boxes_id_seq OWNED BY public.boxes.id;
          public               postgres    false    242            �            1259    28481    cache    TABLE     �   CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache;
       public         heap r       postgres    false    5            �            1259    28488    cache_locks    TABLE     �   CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache_locks;
       public         heap r       postgres    false    5            �            1259    28738    clients    TABLE       CREATE TABLE public.clients (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    adresse character varying(255) NOT NULL,
    telephone character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.clients;
       public         heap r       postgres    false    5            �            1259    28737    clients_id_seq    SEQUENCE     w   CREATE SEQUENCE public.clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.clients_id_seq;
       public               postgres    false    5    255            �           0    0    clients_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.clients_id_seq OWNED BY public.clients.id;
          public               postgres    false    254            �            1259    28643    colis    TABLE     \  CREATE TABLE public.colis (
    id bigint NOT NULL,
    commande_id bigint NOT NULL,
    box_id bigint,
    livreur_id bigint,
    etat character varying(255) DEFAULT 'en_attente'::character varying NOT NULL,
    date_depot timestamp(0) without time zone,
    date_retrait timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT colis_etat_check CHECK (((etat)::text = ANY ((ARRAY['en_attente'::character varying, 'en_depot'::character varying, 'en_cours'::character varying, 'livre'::character varying])::text[])))
);
    DROP TABLE public.colis;
       public         heap r       postgres    false    5            �            1259    28642    colis_id_seq    SEQUENCE     u   CREATE SEQUENCE public.colis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.colis_id_seq;
       public               postgres    false    5    245            �           0    0    colis_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.colis_id_seq OWNED BY public.colis.id;
          public               postgres    false    244            �            1259    28528 	   commandes    TABLE     P  CREATE TABLE public.commandes (
    id bigint NOT NULL,
    annonce_id bigint NOT NULL,
    client_id bigint NOT NULL,
    montant numeric(8,2) NOT NULL,
    statut character varying(255) DEFAULT 'en_attente'::character varying NOT NULL,
    achete_le timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT commandes_statut_check CHECK (((statut)::text = ANY ((ARRAY['en_attente'::character varying, 'paye'::character varying, 'annule'::character varying])::text[])))
);
    DROP TABLE public.commandes;
       public         heap r       postgres    false    5            �            1259    28527    commandes_id_seq    SEQUENCE     y   CREATE SEQUENCE public.commandes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.commandes_id_seq;
       public               postgres    false    5    231            �           0    0    commandes_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.commandes_id_seq OWNED BY public.commandes.id;
          public               postgres    false    230                       1259    28767    commercants    TABLE       CREATE TABLE public.commercants (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    nom_entreprise character varying(255) NOT NULL,
    siret character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.commercants;
       public         heap r       postgres    false    5                       1259    28766    commercants_id_seq    SEQUENCE     {   CREATE SEQUENCE public.commercants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.commercants_id_seq;
       public               postgres    false    5    259            �           0    0    commercants_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.commercants_id_seq OWNED BY public.commercants.id;
          public               postgres    false    258            �            1259    28683    communications    TABLE     A  CREATE TABLE public.communications (
    id bigint NOT NULL,
    expediteur_id bigint NOT NULL,
    destinataire_id bigint NOT NULL,
    annonce_id bigint,
    message text NOT NULL,
    lu_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 "   DROP TABLE public.communications;
       public         heap r       postgres    false    5            �            1259    28682    communications_id_seq    SEQUENCE     ~   CREATE SEQUENCE public.communications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.communications_id_seq;
       public               postgres    false    249    5            �           0    0    communications_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.communications_id_seq OWNED BY public.communications.id;
          public               postgres    false    248            �            1259    28621 	   entrepots    TABLE     J  CREATE TABLE public.entrepots (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    adresse character varying(255) NOT NULL,
    ville character varying(255) NOT NULL,
    code_postal character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.entrepots;
       public         heap r       postgres    false    5            �            1259    28620    entrepots_id_seq    SEQUENCE     y   CREATE SEQUENCE public.entrepots_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.entrepots_id_seq;
       public               postgres    false    241    5            �           0    0    entrepots_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.entrepots_id_seq OWNED BY public.entrepots.id;
          public               postgres    false    240            �            1259    28667    etapes_livraison    TABLE     p  CREATE TABLE public.etapes_livraison (
    id bigint NOT NULL,
    colis_id bigint NOT NULL,
    statut character varying(255) NOT NULL,
    commentaire text,
    date_etape timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT etapes_livraison_statut_check CHECK (((statut)::text = ANY ((ARRAY['préparation'::character varying, 'déposé'::character varying, 'en transit'::character varying, 'en attente'::character varying, 'livré'::character varying, 'échoué'::character varying])::text[])))
);
 $   DROP TABLE public.etapes_livraison;
       public         heap r       postgres    false    5            �            1259    28666    etapes_livraison_id_seq    SEQUENCE     �   CREATE SEQUENCE public.etapes_livraison_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.etapes_livraison_id_seq;
       public               postgres    false    5    247            �           0    0    etapes_livraison_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.etapes_livraison_id_seq OWNED BY public.etapes_livraison.id;
          public               postgres    false    246            �            1259    28584    evaluations    TABLE     /  CREATE TABLE public.evaluations (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    client_id bigint NOT NULL,
    annonce_id bigint NOT NULL,
    note smallint NOT NULL,
    commentaire text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.evaluations;
       public         heap r       postgres    false    5            �           0    0    COLUMN evaluations.note    COMMENT     :   COMMENT ON COLUMN public.evaluations.note IS 'de 1 à 5';
          public               postgres    false    237            �            1259    28583    evaluations_id_seq    SEQUENCE     {   CREATE SEQUENCE public.evaluations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.evaluations_id_seq;
       public               postgres    false    237    5            �           0    0    evaluations_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.evaluations_id_seq OWNED BY public.evaluations.id;
          public               postgres    false    236            �            1259    28608    factures    TABLE     j  CREATE TABLE public.factures (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    montant_total numeric(10,2) NOT NULL,
    chemin_pdf character varying(255) NOT NULL,
    date_emission timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.factures;
       public         heap r       postgres    false    5            �            1259    28607    factures_id_seq    SEQUENCE     x   CREATE SEQUENCE public.factures_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.factures_id_seq;
       public               postgres    false    239    5            �           0    0    factures_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.factures_id_seq OWNED BY public.factures.id;
          public               postgres    false    238                       1259    28752    livreurs    TABLE     F  CREATE TABLE public.livreurs (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    piece_identite character varying(255) NOT NULL,
    permis_conduire character varying(255),
    valide boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.livreurs;
       public         heap r       postgres    false    5                        1259    28751    livreurs_id_seq    SEQUENCE     x   CREATE SEQUENCE public.livreurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.livreurs_id_seq;
       public               postgres    false    5    257            �           0    0    livreurs_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.livreurs_id_seq OWNED BY public.livreurs.id;
          public               postgres    false    256            �            1259    28143 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap r       postgres    false    5            �            1259    28142    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public               postgres    false    218    5            �           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public               postgres    false    217            �            1259    28707    notifications    TABLE     c  CREATE TABLE public.notifications (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    titre character varying(255) NOT NULL,
    contenu text,
    cible_type character varying(255),
    cible_id bigint,
    lu_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 !   DROP TABLE public.notifications;
       public         heap r       postgres    false    5            �            1259    28706    notifications_id_seq    SEQUENCE     }   CREATE SEQUENCE public.notifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.notifications_id_seq;
       public               postgres    false    251    5            �           0    0    notifications_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.notifications_id_seq OWNED BY public.notifications.id;
          public               postgres    false    250            �            1259    28563 	   paiements    TABLE     �  CREATE TABLE public.paiements (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    commande_id bigint,
    montant numeric(10,2) NOT NULL,
    sens character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    reference character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT paiements_sens_check CHECK (((sens)::text = ANY ((ARRAY['credit'::character varying, 'debit'::character varying])::text[]))),
    CONSTRAINT paiements_type_check CHECK (((type)::text = ANY ((ARRAY['stripe'::character varying, 'portefeuille'::character varying, 'virement'::character varying, 'remboursement'::character varying])::text[])))
);
    DROP TABLE public.paiements;
       public         heap r       postgres    false    5            �            1259    28562    paiements_id_seq    SEQUENCE     y   CREATE SEQUENCE public.paiements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.paiements_id_seq;
       public               postgres    false    5    235            �           0    0    paiements_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.paiements_id_seq OWNED BY public.paiements.id;
          public               postgres    false    234            �            1259    28496    personal_access_tokens    TABLE     �  CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE public.personal_access_tokens;
       public         heap r       postgres    false    5            �            1259    28495    personal_access_tokens_id_seq    SEQUENCE     �   CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE public.personal_access_tokens_id_seq;
       public               postgres    false    227    5            �           0    0    personal_access_tokens_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;
          public               postgres    false    226            �            1259    28548    portefeuilles    TABLE     �   CREATE TABLE public.portefeuilles (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    solde numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 !   DROP TABLE public.portefeuilles;
       public         heap r       postgres    false    5            �            1259    28547    portefeuilles_id_seq    SEQUENCE     }   CREATE SEQUENCE public.portefeuilles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.portefeuilles_id_seq;
       public               postgres    false    5    233            �           0    0    portefeuilles_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.portefeuilles_id_seq OWNED BY public.portefeuilles.id;
          public               postgres    false    232                       1259    28797    prestataires    TABLE     -  CREATE TABLE public.prestataires (
    id bigint NOT NULL,
    utilisateur_id bigint NOT NULL,
    domaine character varying(255) NOT NULL,
    description text,
    valide boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
     DROP TABLE public.prestataires;
       public         heap r       postgres    false    5                       1259    28796    prestataires_id_seq    SEQUENCE     |   CREATE SEQUENCE public.prestataires_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.prestataires_id_seq;
       public               postgres    false    5    261            �           0    0    prestataires_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.prestataires_id_seq OWNED BY public.prestataires.id;
          public               postgres    false    260            �            1259    28472    sessions    TABLE     �   CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);
    DROP TABLE public.sessions;
       public         heap r       postgres    false    5            �            1259    28436    utilisateurs    TABLE     �  CREATE TABLE public.utilisateurs (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    pays character varying(255),
    telephone character varying(255),
    adresse_postale character varying(255),
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT utilisateurs_role_check CHECK (((role)::text = ANY ((ARRAY['client'::character varying, 'commercant'::character varying, 'prestataire'::character varying, 'livreur'::character varying, 'backoffice'::character varying, 'admin'::character varying])::text[])))
);
     DROP TABLE public.utilisateurs;
       public         heap r       postgres    false    5            �            1259    28435    utilisateurs_id_seq    SEQUENCE     |   CREATE SEQUENCE public.utilisateurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.utilisateurs_id_seq;
       public               postgres    false    5    220            �           0    0    utilisateurs_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.utilisateurs_id_seq OWNED BY public.utilisateurs.id;
          public               postgres    false    219            �           2604    28724    adresses_livraison id    DEFAULT     ~   ALTER TABLE ONLY public.adresses_livraison ALTER COLUMN id SET DEFAULT nextval('public.adresses_livraison_id_seq'::regclass);
 D   ALTER TABLE public.adresses_livraison ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    252    253    253            �           2604    28511    annonce_utilisateur id    DEFAULT     �   ALTER TABLE ONLY public.annonce_utilisateur ALTER COLUMN id SET DEFAULT nextval('public.annonce_utilisateur_id_seq'::regclass);
 E   ALTER TABLE public.annonce_utilisateur ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    228    229    229            �           2604    28451    annonces id    DEFAULT     j   ALTER TABLE ONLY public.annonces ALTER COLUMN id SET DEFAULT nextval('public.annonces_id_seq'::regclass);
 :   ALTER TABLE public.annonces ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    221    222            �           2604    28633    boxes id    DEFAULT     d   ALTER TABLE ONLY public.boxes ALTER COLUMN id SET DEFAULT nextval('public.boxes_id_seq'::regclass);
 7   ALTER TABLE public.boxes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    242    243    243            �           2604    28741 
   clients id    DEFAULT     h   ALTER TABLE ONLY public.clients ALTER COLUMN id SET DEFAULT nextval('public.clients_id_seq'::regclass);
 9   ALTER TABLE public.clients ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    255    254    255            �           2604    28646    colis id    DEFAULT     d   ALTER TABLE ONLY public.colis ALTER COLUMN id SET DEFAULT nextval('public.colis_id_seq'::regclass);
 7   ALTER TABLE public.colis ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    244    245    245            �           2604    28531    commandes id    DEFAULT     l   ALTER TABLE ONLY public.commandes ALTER COLUMN id SET DEFAULT nextval('public.commandes_id_seq'::regclass);
 ;   ALTER TABLE public.commandes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    231    230    231            �           2604    28770    commercants id    DEFAULT     p   ALTER TABLE ONLY public.commercants ALTER COLUMN id SET DEFAULT nextval('public.commercants_id_seq'::regclass);
 =   ALTER TABLE public.commercants ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    258    259    259            �           2604    28686    communications id    DEFAULT     v   ALTER TABLE ONLY public.communications ALTER COLUMN id SET DEFAULT nextval('public.communications_id_seq'::regclass);
 @   ALTER TABLE public.communications ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    248    249    249            �           2604    28624    entrepots id    DEFAULT     l   ALTER TABLE ONLY public.entrepots ALTER COLUMN id SET DEFAULT nextval('public.entrepots_id_seq'::regclass);
 ;   ALTER TABLE public.entrepots ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    240    241    241            �           2604    28670    etapes_livraison id    DEFAULT     z   ALTER TABLE ONLY public.etapes_livraison ALTER COLUMN id SET DEFAULT nextval('public.etapes_livraison_id_seq'::regclass);
 B   ALTER TABLE public.etapes_livraison ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    247    246    247            �           2604    28587    evaluations id    DEFAULT     p   ALTER TABLE ONLY public.evaluations ALTER COLUMN id SET DEFAULT nextval('public.evaluations_id_seq'::regclass);
 =   ALTER TABLE public.evaluations ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    236    237    237            �           2604    28611    factures id    DEFAULT     j   ALTER TABLE ONLY public.factures ALTER COLUMN id SET DEFAULT nextval('public.factures_id_seq'::regclass);
 :   ALTER TABLE public.factures ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    238    239    239            �           2604    28755    livreurs id    DEFAULT     j   ALTER TABLE ONLY public.livreurs ALTER COLUMN id SET DEFAULT nextval('public.livreurs_id_seq'::regclass);
 :   ALTER TABLE public.livreurs ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    257    256    257            �           2604    28146    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    218    217    218            �           2604    28710    notifications id    DEFAULT     t   ALTER TABLE ONLY public.notifications ALTER COLUMN id SET DEFAULT nextval('public.notifications_id_seq'::regclass);
 ?   ALTER TABLE public.notifications ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    251    250    251            �           2604    28566    paiements id    DEFAULT     l   ALTER TABLE ONLY public.paiements ALTER COLUMN id SET DEFAULT nextval('public.paiements_id_seq'::regclass);
 ;   ALTER TABLE public.paiements ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    235    234    235            �           2604    28499    personal_access_tokens id    DEFAULT     �   ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);
 H   ALTER TABLE public.personal_access_tokens ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226    227            �           2604    28551    portefeuilles id    DEFAULT     t   ALTER TABLE ONLY public.portefeuilles ALTER COLUMN id SET DEFAULT nextval('public.portefeuilles_id_seq'::regclass);
 ?   ALTER TABLE public.portefeuilles ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    232    233    233            �           2604    28800    prestataires id    DEFAULT     r   ALTER TABLE ONLY public.prestataires ALTER COLUMN id SET DEFAULT nextval('public.prestataires_id_seq'::regclass);
 >   ALTER TABLE public.prestataires ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    260    261    261            �           2604    28439    utilisateurs id    DEFAULT     r   ALTER TABLE ONLY public.utilisateurs ALTER COLUMN id SET DEFAULT nextval('public.utilisateurs_id_seq'::regclass);
 >   ALTER TABLE public.utilisateurs ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219    220            �          0    28721    adresses_livraison 
   TABLE DATA           �   COPY public.adresses_livraison (id, commande_id, adresse, ville, code_postal, pays, instructions, created_at, updated_at) FROM stdin;
    public               postgres    false    253         �          0    28508    annonce_utilisateur 
   TABLE DATA           Y   COPY public.annonce_utilisateur (id, annonce_id, utilisateur_id, accepte_le) FROM stdin;
    public               postgres    false    229   �      �          0    28448    annonces 
   TABLE DATA           �   COPY public.annonces (id, type, titre, description, prix_propose, photo, id_client, id_commercant, id_prestataire, lieu_depart, lieu_arrivee, created_at, updated_at, statut) FROM stdin;
    public               postgres    false    222   �      �          0    28630    boxes 
   TABLE DATA           ^   COPY public.boxes (id, entrepot_id, code_box, est_occupe, created_at, updated_at) FROM stdin;
    public               postgres    false    243   	      �          0    28481    cache 
   TABLE DATA           7   COPY public.cache (key, value, expiration) FROM stdin;
    public               postgres    false    224   &      �          0    28488    cache_locks 
   TABLE DATA           =   COPY public.cache_locks (key, owner, expiration) FROM stdin;
    public               postgres    false    225   C      �          0    28738    clients 
   TABLE DATA           a   COPY public.clients (id, utilisateur_id, adresse, telephone, created_at, updated_at) FROM stdin;
    public               postgres    false    255   `      �          0    28643    colis 
   TABLE DATA           |   COPY public.colis (id, commande_id, box_id, livreur_id, etat, date_depot, date_retrait, created_at, updated_at) FROM stdin;
    public               postgres    false    245   }      �          0    28528 	   commandes 
   TABLE DATA           r   COPY public.commandes (id, annonce_id, client_id, montant, statut, achete_le, created_at, updated_at) FROM stdin;
    public               postgres    false    231   �      �          0    28767    commercants 
   TABLE DATA           h   COPY public.commercants (id, utilisateur_id, nom_entreprise, siret, created_at, updated_at) FROM stdin;
    public               postgres    false    259   &      �          0    28683    communications 
   TABLE DATA           �   COPY public.communications (id, expediteur_id, destinataire_id, annonce_id, message, lu_at, created_at, updated_at) FROM stdin;
    public               postgres    false    249   �      �          0    28621 	   entrepots 
   TABLE DATA           a   COPY public.entrepots (id, nom, adresse, ville, code_postal, created_at, updated_at) FROM stdin;
    public               postgres    false    241   �      �          0    28667    etapes_livraison 
   TABLE DATA           q   COPY public.etapes_livraison (id, colis_id, statut, commentaire, date_etape, created_at, updated_at) FROM stdin;
    public               postgres    false    247   �      �          0    28584    evaluations 
   TABLE DATA           {   COPY public.evaluations (id, utilisateur_id, client_id, annonce_id, note, commentaire, created_at, updated_at) FROM stdin;
    public               postgres    false    237   �      �          0    28608    factures 
   TABLE DATA           x   COPY public.factures (id, utilisateur_id, montant_total, chemin_pdf, date_emission, created_at, updated_at) FROM stdin;
    public               postgres    false    239   �      �          0    28752    livreurs 
   TABLE DATA           w   COPY public.livreurs (id, utilisateur_id, piece_identite, permis_conduire, valide, created_at, updated_at) FROM stdin;
    public               postgres    false    257         �          0    28143 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public               postgres    false    218   T      �          0    28707    notifications 
   TABLE DATA           �   COPY public.notifications (id, utilisateur_id, titre, contenu, cible_type, cible_id, lu_at, created_at, updated_at) FROM stdin;
    public               postgres    false    251   �      �          0    28563 	   paiements 
   TABLE DATA           |   COPY public.paiements (id, utilisateur_id, commande_id, montant, sens, type, reference, created_at, updated_at) FROM stdin;
    public               postgres    false    235         �          0    28496    personal_access_tokens 
   TABLE DATA           �   COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
    public               postgres    false    227   (      �          0    28548    portefeuilles 
   TABLE DATA           Z   COPY public.portefeuilles (id, utilisateur_id, solde, created_at, updated_at) FROM stdin;
    public               postgres    false    233   J      �          0    28797    prestataires 
   TABLE DATA           p   COPY public.prestataires (id, utilisateur_id, domaine, description, valide, created_at, updated_at) FROM stdin;
    public               postgres    false    261   g      �          0    28472    sessions 
   TABLE DATA           _   COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
    public               postgres    false    223   �      �          0    28436    utilisateurs 
   TABLE DATA           �   COPY public.utilisateurs (id, nom, prenom, email, email_verified_at, password, role, pays, telephone, adresse_postale, remember_token, created_at, updated_at, deleted_at) FROM stdin;
    public               postgres    false    220   �      �           0    0    adresses_livraison_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.adresses_livraison_id_seq', 3, true);
          public               postgres    false    252            �           0    0    annonce_utilisateur_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.annonce_utilisateur_id_seq', 2, true);
          public               postgres    false    228            �           0    0    annonces_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.annonces_id_seq', 5, true);
          public               postgres    false    221            �           0    0    boxes_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.boxes_id_seq', 1, false);
          public               postgres    false    242            �           0    0    clients_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.clients_id_seq', 1, false);
          public               postgres    false    254            �           0    0    colis_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.colis_id_seq', 1, false);
          public               postgres    false    244            �           0    0    commandes_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.commandes_id_seq', 7, true);
          public               postgres    false    230            �           0    0    commercants_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.commercants_id_seq', 1, true);
          public               postgres    false    258            �           0    0    communications_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.communications_id_seq', 1, false);
          public               postgres    false    248            �           0    0    entrepots_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.entrepots_id_seq', 1, false);
          public               postgres    false    240            �           0    0    etapes_livraison_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.etapes_livraison_id_seq', 1, false);
          public               postgres    false    246            �           0    0    evaluations_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.evaluations_id_seq', 1, false);
          public               postgres    false    236            �           0    0    factures_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.factures_id_seq', 1, false);
          public               postgres    false    238            �           0    0    livreurs_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.livreurs_id_seq', 1, true);
          public               postgres    false    256            �           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 41, true);
          public               postgres    false    217            �           0    0    notifications_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.notifications_id_seq', 1, false);
          public               postgres    false    250            �           0    0    paiements_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.paiements_id_seq', 1, false);
          public               postgres    false    234            �           0    0    personal_access_tokens_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 41, true);
          public               postgres    false    226                        0    0    portefeuilles_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.portefeuilles_id_seq', 1, false);
          public               postgres    false    232                       0    0    prestataires_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.prestataires_id_seq', 1, false);
          public               postgres    false    260                       0    0    utilisateurs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.utilisateurs_id_seq', 7, true);
          public               postgres    false    219            �           2606    28735 8   adresses_livraison adresses_livraison_commande_id_unique 
   CONSTRAINT     z   ALTER TABLE ONLY public.adresses_livraison
    ADD CONSTRAINT adresses_livraison_commande_id_unique UNIQUE (commande_id);
 b   ALTER TABLE ONLY public.adresses_livraison DROP CONSTRAINT adresses_livraison_commande_id_unique;
       public                 postgres    false    253            �           2606    28728 *   adresses_livraison adresses_livraison_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.adresses_livraison
    ADD CONSTRAINT adresses_livraison_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.adresses_livraison DROP CONSTRAINT adresses_livraison_pkey;
       public                 postgres    false    253            �           2606    28526 H   annonce_utilisateur annonce_utilisateur_annonce_id_utilisateur_id_unique 
   CONSTRAINT     �   ALTER TABLE ONLY public.annonce_utilisateur
    ADD CONSTRAINT annonce_utilisateur_annonce_id_utilisateur_id_unique UNIQUE (annonce_id, utilisateur_id);
 r   ALTER TABLE ONLY public.annonce_utilisateur DROP CONSTRAINT annonce_utilisateur_annonce_id_utilisateur_id_unique;
       public                 postgres    false    229    229            �           2606    28514 ,   annonce_utilisateur annonce_utilisateur_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY public.annonce_utilisateur
    ADD CONSTRAINT annonce_utilisateur_pkey PRIMARY KEY (id);
 V   ALTER TABLE ONLY public.annonce_utilisateur DROP CONSTRAINT annonce_utilisateur_pkey;
       public                 postgres    false    229            �           2606    28456    annonces annonces_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.annonces
    ADD CONSTRAINT annonces_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.annonces DROP CONSTRAINT annonces_pkey;
       public                 postgres    false    222            �           2606    28636    boxes boxes_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.boxes
    ADD CONSTRAINT boxes_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.boxes DROP CONSTRAINT boxes_pkey;
       public                 postgres    false    243            �           2606    28494    cache_locks cache_locks_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);
 F   ALTER TABLE ONLY public.cache_locks DROP CONSTRAINT cache_locks_pkey;
       public                 postgres    false    225            �           2606    28487    cache cache_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);
 :   ALTER TABLE ONLY public.cache DROP CONSTRAINT cache_pkey;
       public                 postgres    false    224            �           2606    28745    clients clients_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.clients DROP CONSTRAINT clients_pkey;
       public                 postgres    false    255            �           2606    28650    colis colis_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.colis
    ADD CONSTRAINT colis_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.colis DROP CONSTRAINT colis_pkey;
       public                 postgres    false    245            �           2606    28536    commandes commandes_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT commandes_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.commandes DROP CONSTRAINT commandes_pkey;
       public                 postgres    false    231            �           2606    28774    commercants commercants_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.commercants
    ADD CONSTRAINT commercants_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.commercants DROP CONSTRAINT commercants_pkey;
       public                 postgres    false    259            �           2606    28690 "   communications communications_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_pkey PRIMARY KEY (id);
 L   ALTER TABLE ONLY public.communications DROP CONSTRAINT communications_pkey;
       public                 postgres    false    249            �           2606    28628    entrepots entrepots_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.entrepots
    ADD CONSTRAINT entrepots_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.entrepots DROP CONSTRAINT entrepots_pkey;
       public                 postgres    false    241            �           2606    28676 &   etapes_livraison etapes_livraison_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.etapes_livraison
    ADD CONSTRAINT etapes_livraison_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY public.etapes_livraison DROP CONSTRAINT etapes_livraison_pkey;
       public                 postgres    false    247            �           2606    28591    evaluations evaluations_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.evaluations
    ADD CONSTRAINT evaluations_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.evaluations DROP CONSTRAINT evaluations_pkey;
       public                 postgres    false    237            �           2606    28614    factures factures_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.factures
    ADD CONSTRAINT factures_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.factures DROP CONSTRAINT factures_pkey;
       public                 postgres    false    239            �           2606    28760    livreurs livreurs_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.livreurs
    ADD CONSTRAINT livreurs_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.livreurs DROP CONSTRAINT livreurs_pkey;
       public                 postgres    false    257            �           2606    28148    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public                 postgres    false    218            �           2606    28714     notifications notifications_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.notifications DROP CONSTRAINT notifications_pkey;
       public                 postgres    false    251            �           2606    28572    paiements paiements_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.paiements DROP CONSTRAINT paiements_pkey;
       public                 postgres    false    235            �           2606    28503 2   personal_access_tokens personal_access_tokens_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_pkey;
       public                 postgres    false    227            �           2606    28506 :   personal_access_tokens personal_access_tokens_token_unique 
   CONSTRAINT     v   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);
 d   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_token_unique;
       public                 postgres    false    227            �           2606    28554     portefeuilles portefeuilles_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.portefeuilles
    ADD CONSTRAINT portefeuilles_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.portefeuilles DROP CONSTRAINT portefeuilles_pkey;
       public                 postgres    false    233            �           2606    28561 1   portefeuilles portefeuilles_utilisateur_id_unique 
   CONSTRAINT     v   ALTER TABLE ONLY public.portefeuilles
    ADD CONSTRAINT portefeuilles_utilisateur_id_unique UNIQUE (utilisateur_id);
 [   ALTER TABLE ONLY public.portefeuilles DROP CONSTRAINT portefeuilles_utilisateur_id_unique;
       public                 postgres    false    233            �           2606    28805    prestataires prestataires_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.prestataires
    ADD CONSTRAINT prestataires_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.prestataires DROP CONSTRAINT prestataires_pkey;
       public                 postgres    false    261            �           2606    28478    sessions sessions_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.sessions DROP CONSTRAINT sessions_pkey;
       public                 postgres    false    223            �           2606    28446 &   utilisateurs utilisateurs_email_unique 
   CONSTRAINT     b   ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_email_unique UNIQUE (email);
 P   ALTER TABLE ONLY public.utilisateurs DROP CONSTRAINT utilisateurs_email_unique;
       public                 postgres    false    220            �           2606    28444    utilisateurs utilisateurs_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.utilisateurs DROP CONSTRAINT utilisateurs_pkey;
       public                 postgres    false    220            �           1259    28504 8   personal_access_tokens_tokenable_type_tokenable_id_index    INDEX     �   CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);
 L   DROP INDEX public.personal_access_tokens_tokenable_type_tokenable_id_index;
       public                 postgres    false    227    227            �           1259    28480    sessions_last_activity_index    INDEX     Z   CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);
 0   DROP INDEX public.sessions_last_activity_index;
       public                 postgres    false    223            �           1259    28479    sessions_user_id_index    INDEX     N   CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);
 *   DROP INDEX public.sessions_user_id_index;
       public                 postgres    false    223                       2606    28729 9   adresses_livraison adresses_livraison_commande_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.adresses_livraison
    ADD CONSTRAINT adresses_livraison_commande_id_foreign FOREIGN KEY (commande_id) REFERENCES public.commandes(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.adresses_livraison DROP CONSTRAINT adresses_livraison_commande_id_foreign;
       public               postgres    false    231    4819    253            �           2606    28515 :   annonce_utilisateur annonce_utilisateur_annonce_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.annonce_utilisateur
    ADD CONSTRAINT annonce_utilisateur_annonce_id_foreign FOREIGN KEY (annonce_id) REFERENCES public.annonces(id) ON DELETE CASCADE;
 d   ALTER TABLE ONLY public.annonce_utilisateur DROP CONSTRAINT annonce_utilisateur_annonce_id_foreign;
       public               postgres    false    222    4800    229            �           2606    28520 >   annonce_utilisateur annonce_utilisateur_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.annonce_utilisateur
    ADD CONSTRAINT annonce_utilisateur_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 h   ALTER TABLE ONLY public.annonce_utilisateur DROP CONSTRAINT annonce_utilisateur_utilisateur_id_foreign;
       public               postgres    false    229    4798    220            �           2606    28457 #   annonces annonces_id_client_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.annonces
    ADD CONSTRAINT annonces_id_client_foreign FOREIGN KEY (id_client) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.annonces DROP CONSTRAINT annonces_id_client_foreign;
       public               postgres    false    220    4798    222            �           2606    28462 '   annonces annonces_id_commercant_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.annonces
    ADD CONSTRAINT annonces_id_commercant_foreign FOREIGN KEY (id_commercant) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 Q   ALTER TABLE ONLY public.annonces DROP CONSTRAINT annonces_id_commercant_foreign;
       public               postgres    false    220    222    4798            �           2606    28467 (   annonces annonces_id_prestataire_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.annonces
    ADD CONSTRAINT annonces_id_prestataire_foreign FOREIGN KEY (id_prestataire) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 R   ALTER TABLE ONLY public.annonces DROP CONSTRAINT annonces_id_prestataire_foreign;
       public               postgres    false    222    220    4798                       2606    28637    boxes boxes_entrepot_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.boxes
    ADD CONSTRAINT boxes_entrepot_id_foreign FOREIGN KEY (entrepot_id) REFERENCES public.entrepots(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.boxes DROP CONSTRAINT boxes_entrepot_id_foreign;
       public               postgres    false    4831    243    241                       2606    28746 &   clients clients_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 P   ALTER TABLE ONLY public.clients DROP CONSTRAINT clients_utilisateur_id_foreign;
       public               postgres    false    255    4798    220                       2606    28656    colis colis_box_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.colis
    ADD CONSTRAINT colis_box_id_foreign FOREIGN KEY (box_id) REFERENCES public.boxes(id) ON DELETE SET NULL;
 D   ALTER TABLE ONLY public.colis DROP CONSTRAINT colis_box_id_foreign;
       public               postgres    false    243    4833    245                       2606    28651    colis colis_commande_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.colis
    ADD CONSTRAINT colis_commande_id_foreign FOREIGN KEY (commande_id) REFERENCES public.commandes(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.colis DROP CONSTRAINT colis_commande_id_foreign;
       public               postgres    false    4819    245    231                       2606    28661    colis colis_livreur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.colis
    ADD CONSTRAINT colis_livreur_id_foreign FOREIGN KEY (livreur_id) REFERENCES public.utilisateurs(id) ON DELETE SET NULL;
 H   ALTER TABLE ONLY public.colis DROP CONSTRAINT colis_livreur_id_foreign;
       public               postgres    false    245    4798    220            �           2606    28537 &   commandes commandes_annonce_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT commandes_annonce_id_foreign FOREIGN KEY (annonce_id) REFERENCES public.annonces(id) ON DELETE CASCADE;
 P   ALTER TABLE ONLY public.commandes DROP CONSTRAINT commandes_annonce_id_foreign;
       public               postgres    false    4800    222    231            �           2606    28542 %   commandes commandes_client_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT commandes_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 O   ALTER TABLE ONLY public.commandes DROP CONSTRAINT commandes_client_id_foreign;
       public               postgres    false    4798    220    231                       2606    28775 .   commercants commercants_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.commercants
    ADD CONSTRAINT commercants_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 X   ALTER TABLE ONLY public.commercants DROP CONSTRAINT commercants_utilisateur_id_foreign;
       public               postgres    false    259    220    4798            	           2606    28701 0   communications communications_annonce_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_annonce_id_foreign FOREIGN KEY (annonce_id) REFERENCES public.annonces(id) ON DELETE CASCADE;
 Z   ALTER TABLE ONLY public.communications DROP CONSTRAINT communications_annonce_id_foreign;
       public               postgres    false    249    4800    222            
           2606    28696 5   communications communications_destinataire_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_destinataire_id_foreign FOREIGN KEY (destinataire_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 _   ALTER TABLE ONLY public.communications DROP CONSTRAINT communications_destinataire_id_foreign;
       public               postgres    false    4798    249    220                       2606    28691 3   communications communications_expediteur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_expediteur_id_foreign FOREIGN KEY (expediteur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 ]   ALTER TABLE ONLY public.communications DROP CONSTRAINT communications_expediteur_id_foreign;
       public               postgres    false    4798    249    220                       2606    28677 2   etapes_livraison etapes_livraison_colis_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.etapes_livraison
    ADD CONSTRAINT etapes_livraison_colis_id_foreign FOREIGN KEY (colis_id) REFERENCES public.colis(id) ON DELETE CASCADE;
 \   ALTER TABLE ONLY public.etapes_livraison DROP CONSTRAINT etapes_livraison_colis_id_foreign;
       public               postgres    false    245    247    4835                        2606    28602 *   evaluations evaluations_annonce_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.evaluations
    ADD CONSTRAINT evaluations_annonce_id_foreign FOREIGN KEY (annonce_id) REFERENCES public.annonces(id) ON DELETE CASCADE;
 T   ALTER TABLE ONLY public.evaluations DROP CONSTRAINT evaluations_annonce_id_foreign;
       public               postgres    false    237    4800    222                       2606    28597 )   evaluations evaluations_client_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.evaluations
    ADD CONSTRAINT evaluations_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.evaluations DROP CONSTRAINT evaluations_client_id_foreign;
       public               postgres    false    220    237    4798                       2606    28592 .   evaluations evaluations_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.evaluations
    ADD CONSTRAINT evaluations_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 X   ALTER TABLE ONLY public.evaluations DROP CONSTRAINT evaluations_utilisateur_id_foreign;
       public               postgres    false    4798    237    220                       2606    28615 (   factures factures_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.factures
    ADD CONSTRAINT factures_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 R   ALTER TABLE ONLY public.factures DROP CONSTRAINT factures_utilisateur_id_foreign;
       public               postgres    false    4798    239    220                       2606    28761 (   livreurs livreurs_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.livreurs
    ADD CONSTRAINT livreurs_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 R   ALTER TABLE ONLY public.livreurs DROP CONSTRAINT livreurs_utilisateur_id_foreign;
       public               postgres    false    220    4798    257                       2606    28715 2   notifications notifications_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 \   ALTER TABLE ONLY public.notifications DROP CONSTRAINT notifications_utilisateur_id_foreign;
       public               postgres    false    251    220    4798            �           2606    28578 '   paiements paiements_commande_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_commande_id_foreign FOREIGN KEY (commande_id) REFERENCES public.commandes(id) ON DELETE SET NULL;
 Q   ALTER TABLE ONLY public.paiements DROP CONSTRAINT paiements_commande_id_foreign;
       public               postgres    false    4819    231    235            �           2606    28573 *   paiements paiements_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 T   ALTER TABLE ONLY public.paiements DROP CONSTRAINT paiements_utilisateur_id_foreign;
       public               postgres    false    235    220    4798            �           2606    28555 2   portefeuilles portefeuilles_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.portefeuilles
    ADD CONSTRAINT portefeuilles_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 \   ALTER TABLE ONLY public.portefeuilles DROP CONSTRAINT portefeuilles_utilisateur_id_foreign;
       public               postgres    false    4798    220    233                       2606    28806 0   prestataires prestataires_utilisateur_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.prestataires
    ADD CONSTRAINT prestataires_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
 Z   ALTER TABLE ONLY public.prestataires DROP CONSTRAINT prestataires_utilisateur_id_foreign;
       public               postgres    false    261    4798    220            �   v   x�3�4�4Q(:��83%5/9U!7�*���3=?/��8�������ӭ((���id`d�k D
��VFVFf�ĸ�8M�3�R����������1�Y��*XZ�0�&����� �)EI      �   :   x�3�4�4�4202�5 "KC+SS+#3.#NcNs��������������9W� �	�      �     x���aj� F�S����M��=�Bp��kq5�'�=z�&�BWZ�Q?�ydd�O&��LɄ֯���;��QG"�Rr~!ռ�c���ľ{G8岤�~vTU�D��е:Ft��q���4�s`T	�x��v��,��k;k�l���};�zc���TQ��vP�����������y~.�a���gF4�"�� )�.��̺�~z7�c�0�$S\d���|�`
�Nw��SD�0��/��'xh�����?�dzK�ʞD����f;��(�o�ɰ      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �   |   x���11kx�?�`��)J��*�_A�CP�VZ����zｮ�@H�A���>�f����ݪ{E�,U�z�<�)hT��9�2�jU�㿒���dtT��ʼ�m���y �	gx�      �   M   x�3�4��ITp�/-�,,MUH,�,�L��K�0426153�00004�4202�50�54Q04�22�25�&����� ��      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �   0   x�3�4�45�A�?�4N##S]3]CC+S+cKlb\1z\\\ �r�      �   �  x�}���0�o�ؒr����aM3��8K��e_Z
���w�#+zy�Uha�r��X�y��9�a�ӱ�6������M' �S�1��|�A}�d���pT�	�~�C��xV3�v�>|!�"QYMH���o{��Zv�D��_��~K�M�Q��_�]t��F�����xp��}�-��e0m���ֵVL�T���1W���(�I�O^��h���h>�;�|l���d��,�柩����e2Wj��w�t��q)�yj.%"��)V��q���[)2u>G�
R'Au9�)��ߊ*��҄���d������NV�(G�ke��da��g�z�*W�(���u�~s�7=?^?�~�̡\H���캞�#��t- �i�o7�B�,���$h��c�?��e�      �      x������ � �      �      x������ � �      �   	  x��YɎ]�]�~��e 5�X|�|@��*
��#6b��s�&�vSWh5��>�Xu�����_߽��/c��ۻw���?�V�����F������_��������Ş�Lmjju�b÷N�=���T����2i�ZZ��o��ß~��w;��$�H�����Ůvȓ�r�O�4�2�N�!�r���c6�pTêL�΋Wz�dW;����kpa�g])�HU8�.L%�u��V�I�ȉ5G�u��mX7i��=,�j�=	��Cш������*C��ڬ�D��#��V���I*f@z'-�=���sg�H�g_������s�Y�i��B--��k�F�)5����M��|.m���ßG��k�A��T��F6MhS�F�B���C*>E�<�\��w���ֳv�o�K_�3�k��m��дb};p�R�u�hI�3m��굜�9��.q�7�����,��p]�7��}Z���ae�3;k�^��cU�L��?s��-���!t���,���#�\��M�6�Х�ĬY+����"$�`9�٨%M�l��Y�1�x��c�򹹯������7W�/D]d�M�ӚL��v��J�F̚t��υ��\b�A��#�[�g���/p?�1��vG�Y�\k�R)3�K��@�����V�����)cb�88��jz$洁��Kw���!�;z�%]�fY��6�F`V2�Z%z
���������I��i������b8����Q��Oet2vG����t
pS�Q��e���t#�Z`W���0�P�T����f�ͦ]���å��K��Ƙ�Q;���N�:�D*T��|��� �,��9O���8Fymi���W��o6�γ�?��
M��ٜS�6dIkV�O��H��JuN���;�tW;䆦��;l/р�Y�룤U������[�����!�!
z�:b/�E:q�X$���\�9zHy��ݙ�,R��24s_4'k���)1�ְq�'%�j�J�5�3P~z��j�ғ
���J�z����܊���V|��b��5������t@ac�:8[������m̹�Qؒ�#WM9z��IZ�j	�E-�E4�l��c��_��9n~��s�����_(���i�k /I. ��Y��ʩ�	*U���};!;H��*w�L-���7�x�HG<	m%J��!��b>|�Y<򜭣�ST��W�!��9g�}3��if~P�=�f2�i�B�kƝ��׀;��*t���C2�&�ұ.0��y�-�7SέNA����:ה(�\��8 �,0���֦�Z���� '�!N�p�'	�h�(o����z����уo��nM�6:Vv�Ra٪���l�
�$� ��^�`yS����6��M�C��v<ːX�L�F"�u9��3V�'�
���p��ux	yI��^R<҇P��wƃlw��g)I�:��Y�
\�u�R�@��L��rJ�Hl�}]a����_\��2���!7�t�#(%�l�C�!g�X3W��o�4^�B�Ð*3��#��"����\~�~P�.����!�9�ȈqiI'\2�P/H�^4��X����J���E��-�ݤ~�K�A�U�j�<�ā���p�� Z3���5l8�gjY9 �\��,�� �p:��K��!7[~qFڝT��[�r��:6+#��y|�f���}"�j��9}�~�G�ڡ77{Q�3#���0>$5BJk	��}!�?�4T�,pt���|
g��]u�۠�V�riiw��� ti&d�OQm�>pih]�X�gR���+�|�gDf�9� m�u���u�bW;�� Q�V�1̬��.(5"Y�N�!��S52ܟr����a�h�b�	?�v�Cn���Hy�8�JgÅ����	��h(�ʽ!�	�3��
�$n��{t_�ץv��_�8�	L"Cic� %0E�pX��k$>�8V�'S��{����<Tw�Co���.������.��C�M�B���$9=LFUVP;�iD���l�΀��g�0�]�fk��� �3$�c��daq"r)pg�	a�:TzF`����|ɍ�p�&/^1>=�� �*�w=&�k8B�u(��"�IN)�,����sb��j��s��O?�^=�||���Co�������z2,�p9�bj��#TPoPkX��b�TO�A5p[���ϫh�O:��;z؍��Eď�f;�on�J��	L�b;�B~��T�sb֎ �0%66p1�0⯆�<�QwG{6�d�@�F�FF�����L<�,D(w.��_�T˚g��|��e�����l�.��ǻ?����      �      x������ � �      �      x������ � �      �      x������ � �      �     x�u��r�0ůӧࢗ�H��UU*�Ei�Zuz լ|�o�ϱ/���;�-�a�g���89@� y�9a��R�8���1P����˒ �A��)�Z=]C����3^������B�Z;*^IS����=��'��w��!Ns
���� #��� AU�dZ BEb�H!�FNBF�I���Xeu���Q�j�M{�]���0Hh��eI�gi�?����<��h�蝾�y�,��d0��rj����2�p9sR���h����c�?�H1��Cb��4$B���Yq�����S�VMh`*�	���&�g3����t��]�Β�!_�����Cr?~XO�WN�#��;���Vm�:@�#&,�- @��p����-zO7[5�����$>f��.G���ÏR9/�&�������{
?9�²�A?�n�I=�Obkw�[dL�[\`��' � �,J��F�P[5a� ��BEMc�f������z:�0�g(#���n�i-�d�6Z���m6�C�:�ă��"Z2��' tߋa��9%�3��bh4�����Tti�cE�S4�"��)/葓�R8����p�?�xB6�m=\�c��l��h�8����U�9����1xh?Q��] �7�\ri��o�i(��V��d�;�%��T�gh�� 3�	�8� J�ې������l�r����e�c�� 3��-�^����>�O��'�tm�G�l�4��o6�y�wI�%d6=o�`���]�@�������,_]]�wq�d     