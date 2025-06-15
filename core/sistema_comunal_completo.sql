--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2025-06-14 23:46:14

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 5 (class 2615 OID 74865)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 246 (class 1255 OID 74866)
-- Name: actualizar_fecha_actualizacion(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.actualizar_fecha_actualizacion() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
  RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_fecha_actualizacion() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 231 (class 1259 OID 75021)
-- Name: carga_familiar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carga_familiar (
    id_carga integer NOT NULL,
    id_habitante integer,
    id_jefe integer,
    parentesco character varying(50),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.carga_familiar OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 75020)
-- Name: carga_familiar_id_carga_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carga_familiar_id_carga_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carga_familiar_id_carga_seq OWNER TO postgres;

--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 230
-- Name: carga_familiar_id_carga_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carga_familiar_id_carga_seq OWNED BY public.carga_familiar.id_carga;


--
-- TOC entry 239 (class 1259 OID 75094)
-- Name: comentario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comentario (
    id_comentario integer NOT NULL,
    id_comunicado integer,
    id_usuario integer,
    contenido text,
    fecha_comentario date,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.comentario OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 75093)
-- Name: comentario_id_comentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comentario_id_comentario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comentario_id_comentario_seq OWNER TO postgres;

--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 238
-- Name: comentario_id_comentario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comentario_id_comentario_seq OWNED BY public.comentario.id_comentario;


--
-- TOC entry 237 (class 1259 OID 75076)
-- Name: comunicado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comunicado (
    id_comunicado integer NOT NULL,
    id_usuario integer,
    titulo character varying(100),
    contenido text,
    fecha_publicacion date,
    estado character varying(20),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.comunicado OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 75075)
-- Name: comunicado_id_comunicado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comunicado_id_comunicado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comunicado_id_comunicado_seq OWNER TO postgres;

--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 236
-- Name: comunicado_id_comunicado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comunicado_id_comunicado_seq OWNED BY public.comunicado.id_comunicado;


--
-- TOC entry 233 (class 1259 OID 75042)
-- Name: concepto_pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.concepto_pago (
    id_concepto integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.concepto_pago OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 75041)
-- Name: concepto_pago_id_concepto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.concepto_pago_id_concepto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.concepto_pago_id_concepto_seq OWNER TO postgres;

--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 232
-- Name: concepto_pago_id_concepto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.concepto_pago_id_concepto_seq OWNED BY public.concepto_pago.id_concepto;


--
-- TOC entry 241 (class 1259 OID 75117)
-- Name: evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.evento (
    id_evento integer NOT NULL,
    titulo character varying(100),
    descripcion text,
    fecha_evento date,
    lugar character varying(100),
    creado_por integer,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.evento OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 75116)
-- Name: evento_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.evento_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.evento_id_evento_seq OWNER TO postgres;

--
-- TOC entry 5079 (class 0 OID 0)
-- Dependencies: 240
-- Name: evento_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.evento_id_evento_seq OWNED BY public.evento.id_evento;


--
-- TOC entry 220 (class 1259 OID 74883)
-- Name: habitante; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.habitante (
    id_habitante integer NOT NULL,
    id_persona integer,
    fecha_ingreso date,
    condicion character varying(50),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.habitante OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 74882)
-- Name: habitante_id_habitante_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.habitante_id_habitante_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.habitante_id_habitante_seq OWNER TO postgres;

--
-- TOC entry 5080 (class 0 OID 0)
-- Dependencies: 219
-- Name: habitante_id_habitante_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.habitante_id_habitante_seq OWNED BY public.habitante.id_habitante;


--
-- TOC entry 223 (class 1259 OID 74909)
-- Name: habitante_vivienda; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.habitante_vivienda (
    id_habitante integer NOT NULL,
    id_vivienda integer NOT NULL,
    es_jefe_familia boolean,
    fecha_inicio date,
    fecha_salida date
);


ALTER TABLE public.habitante_vivienda OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 75156)
-- Name: indicador_gestion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.indicador_gestion (
    id_indicador integer NOT NULL,
    nombre character varying(100),
    descripcion text,
    valor numeric(10,2),
    fecha_registro date,
    generado_por integer,
    activo boolean DEFAULT true,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.indicador_gestion OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 75155)
-- Name: indicador_gestion_id_indicador_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.indicador_gestion_id_indicador_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.indicador_gestion_id_indicador_seq OWNER TO postgres;

--
-- TOC entry 5081 (class 0 OID 0)
-- Dependencies: 244
-- Name: indicador_gestion_id_indicador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.indicador_gestion_id_indicador_seq OWNED BY public.indicador_gestion.id_indicador;


--
-- TOC entry 228 (class 1259 OID 74990)
-- Name: lider_calle; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lider_calle (
    id_habitante integer NOT NULL,
    sector character varying(100) NOT NULL,
    zona character varying(100),
    fecha_designacion date,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.lider_calle OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 75004)
-- Name: lider_comunal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lider_comunal (
    id_habitante integer NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date,
    observaciones text,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.lider_comunal OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 75055)
-- Name: pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pago (
    id_pago integer NOT NULL,
    id_usuario integer,
    id_concepto integer,
    monto numeric(10,2),
    fecha_pago date,
    estado_pago character varying(20),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.pago OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 75054)
-- Name: pago_id_pago_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pago_id_pago_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pago_id_pago_seq OWNER TO postgres;

--
-- TOC entry 5082 (class 0 OID 0)
-- Dependencies: 234
-- Name: pago_id_pago_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pago_id_pago_seq OWNED BY public.pago.id_pago;


--
-- TOC entry 243 (class 1259 OID 75135)
-- Name: participacion_evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participacion_evento (
    id_participacion integer NOT NULL,
    id_evento integer,
    id_usuario integer,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.participacion_evento OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 75134)
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.participacion_evento_id_participacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.participacion_evento_id_participacion_seq OWNER TO postgres;

--
-- TOC entry 5083 (class 0 OID 0)
-- Dependencies: 242
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.participacion_evento_id_participacion_seq OWNED BY public.participacion_evento.id_participacion;


--
-- TOC entry 218 (class 1259 OID 74868)
-- Name: persona; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.persona (
    id_persona integer NOT NULL,
    cedula character varying(19),
    nombres character varying(100) NOT NULL,
    apellidos character varying(100) NOT NULL,
    fecha_nacimiento date,
    sexo character varying(10),
    telefono character varying(20),
    direccion text,
    correo character varying(100),
    estado character varying(20),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.persona OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 74867)
-- Name: persona_id_persona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.persona_id_persona_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.persona_id_persona_seq OWNER TO postgres;

--
-- TOC entry 5084 (class 0 OID 0)
-- Dependencies: 217
-- Name: persona_id_persona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.persona_id_persona_seq OWNED BY public.persona.id_persona;


--
-- TOC entry 225 (class 1259 OID 74925)
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rol (
    id_rol integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 74924)
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_id_rol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rol_id_rol_seq OWNER TO postgres;

--
-- TOC entry 5085 (class 0 OID 0)
-- Dependencies: 224
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- TOC entry 227 (class 1259 OID 74970)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    id_persona integer,l
    id_rol integer,
    contrasena character varying(255) NOT NULL,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    estado character varying(20),
    activo boolean DEFAULT true,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 74969)
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuario_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 5086 (class 0 OID 0)
-- Dependencies: 226
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- TOC entry 222 (class 1259 OID 74899)
-- Name: vivienda; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vivienda (
    id_vivienda integer NOT NULL,
    direccion character varying(255),
    numero character varying(20),
    tipo character varying(50),
    sector character varying(100),
    estado character varying(20),
    activo boolean DEFAULT true,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.vivienda OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 74898)
-- Name: vivienda_id_vivienda_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vivienda_id_vivienda_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vivienda_id_vivienda_seq OWNER TO postgres;

--
-- TOC entry 5087 (class 0 OID 0)
-- Dependencies: 221
-- Name: vivienda_id_vivienda_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vivienda_id_vivienda_seq OWNED BY public.vivienda.id_vivienda;


--
-- TOC entry 4794 (class 2604 OID 75024)
-- Name: carga_familiar id_carga; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_familiar ALTER COLUMN id_carga SET DEFAULT nextval('public.carga_familiar_id_carga_seq'::regclass);


--
-- TOC entry 4810 (class 2604 OID 75097)
-- Name: comentario id_comentario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentario ALTER COLUMN id_comentario SET DEFAULT nextval('public.comentario_id_comentario_seq'::regclass);


--
-- TOC entry 4806 (class 2604 OID 75079)
-- Name: comunicado id_comunicado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunicado ALTER COLUMN id_comunicado SET DEFAULT nextval('public.comunicado_id_comunicado_seq'::regclass);


--
-- TOC entry 4798 (class 2604 OID 75045)
-- Name: concepto_pago id_concepto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.concepto_pago ALTER COLUMN id_concepto SET DEFAULT nextval('public.concepto_pago_id_concepto_seq'::regclass);


--
-- TOC entry 4814 (class 2604 OID 75120)
-- Name: evento id_evento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evento ALTER COLUMN id_evento SET DEFAULT nextval('public.evento_id_evento_seq'::regclass);


--
-- TOC entry 4772 (class 2604 OID 74886)
-- Name: habitante id_habitante; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante ALTER COLUMN id_habitante SET DEFAULT nextval('public.habitante_id_habitante_seq'::regclass);


--
-- TOC entry 4822 (class 2604 OID 75159)
-- Name: indicador_gestion id_indicador; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indicador_gestion ALTER COLUMN id_indicador SET DEFAULT nextval('public.indicador_gestion_id_indicador_seq'::regclass);


--
-- TOC entry 4802 (class 2604 OID 75058)
-- Name: pago id_pago; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago ALTER COLUMN id_pago SET DEFAULT nextval('public.pago_id_pago_seq'::regclass);


--
-- TOC entry 4818 (class 2604 OID 75138)
-- Name: participacion_evento id_participacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento ALTER COLUMN id_participacion SET DEFAULT nextval('public.participacion_evento_id_participacion_seq'::regclass);


--
-- TOC entry 4768 (class 2604 OID 74871)
-- Name: persona id_persona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona ALTER COLUMN id_persona SET DEFAULT nextval('public.persona_id_persona_seq'::regclass);


--
-- TOC entry 4780 (class 2604 OID 74928)
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- TOC entry 4784 (class 2604 OID 74973)
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- TOC entry 4776 (class 2604 OID 74902)
-- Name: vivienda id_vivienda; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vivienda ALTER COLUMN id_vivienda SET DEFAULT nextval('public.vivienda_id_vivienda_seq'::regclass);


--
-- TOC entry 5054 (class 0 OID 75021)
-- Dependencies: 231
-- Data for Name: carga_familiar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carga_familiar (id_carga, id_habitante, id_jefe, parentesco, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5062 (class 0 OID 75094)
-- Dependencies: 239
-- Data for Name: comentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comentario (id_comentario, id_comunicado, id_usuario, contenido, fecha_comentario, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5060 (class 0 OID 75076)
-- Dependencies: 237
-- Data for Name: comunicado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comunicado (id_comunicado, id_usuario, titulo, contenido, fecha_publicacion, estado, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5056 (class 0 OID 75042)
-- Dependencies: 233
-- Data for Name: concepto_pago; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.concepto_pago (id_concepto, nombre, descripcion, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5064 (class 0 OID 75117)
-- Dependencies: 241
-- Data for Name: evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.evento (id_evento, titulo, descripcion, fecha_evento, lugar, creado_por, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5043 (class 0 OID 74883)
-- Dependencies: 220
-- Data for Name: habitante; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.habitante (id_habitante, id_persona, fecha_ingreso, condicion, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5046 (class 0 OID 74909)
-- Dependencies: 223
-- Data for Name: habitante_vivienda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.habitante_vivienda (id_habitante, id_vivienda, es_jefe_familia, fecha_inicio, fecha_salida) FROM stdin;
\.


--
-- TOC entry 5068 (class 0 OID 75156)
-- Dependencies: 245
-- Data for Name: indicador_gestion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.indicador_gestion (id_indicador, nombre, descripcion, valor, fecha_registro, generado_por, activo, fecha_creacion, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5051 (class 0 OID 74990)
-- Dependencies: 228
-- Data for Name: lider_calle; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lider_calle (id_habitante, sector, zona, fecha_designacion, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5052 (class 0 OID 75004)
-- Dependencies: 229
-- Data for Name: lider_comunal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lider_comunal (id_habitante, fecha_inicio, fecha_fin, observaciones, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5058 (class 0 OID 75055)
-- Dependencies: 235
-- Data for Name: pago; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pago (id_pago, id_usuario, id_concepto, monto, fecha_pago, estado_pago, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5066 (class 0 OID 75135)
-- Dependencies: 243
-- Data for Name: participacion_evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participacion_evento (id_participacion, id_evento, id_usuario, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5041 (class 0 OID 74868)
-- Dependencies: 218
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona (id_persona, cedula, nombres, apellidos, fecha_nacimiento, sexo, telefono, direccion, correo, estado, activo, fecha_registro, fecha_actualizacion) FROM stdin;
1	29760658	Denilson	Cardiet	2002-03-15	M	04262859506	campeche 655	Cualquier@gmail.com	Trabajando	t	2025-05-30 06:39:00	2025-05-30 06:39:00
4	66666666	Denilson	Cardiet	2002-03-15	M	04262859506	campeche 655	Cualquier@gmail.com	Trabajando	t	2025-05-30 06:45:58	2025-05-30 06:45:58
5	545258245	Denilson	Cardiet	2002-03-15		04262859506		Cualquier@gmail.com	Trabajando	t	2025-05-30 06:46:28	2025-05-30 06:46:28
7	45455555454	Denilson	Cardiet	2002-03-15		04262859506		Cualquier@gmail.com	Trabajando	t	2025-05-30 06:47:50	2025-05-30 06:47:50
8	789456123	Denilson	Cardiet	2024-03-12	M	04262859506	campeche 655	Cualquier@gmail.com	Trabajando	t	2025-05-30 07:05:54	2025-05-30 07:05:54
9	1234567217784	Denilson	Cardiet	2024-03-12	M	04262859506	asas	Cualquier@gmail.com	Trabajando	f	2025-05-30 07:07:25	2025-06-01 12:29:03.114541
\.


--
-- TOC entry 5048 (class 0 OID 74925)
-- Dependencies: 225
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rol (id_rol, nombre, descripcion, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5050 (class 0 OID 74970)
-- Dependencies: 227
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (id_usuario, id_persona, id_rol, contrasena, fecha_registro, estado, activo, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5045 (class 0 OID 74899)
-- Dependencies: 222
-- Data for Name: vivienda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vivienda (id_vivienda, direccion, numero, tipo, sector, estado, activo, fecha_registro, fecha_actualizacion) FROM stdin;
\.


--
-- TOC entry 5088 (class 0 OID 0)
-- Dependencies: 230
-- Name: carga_familiar_id_carga_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carga_familiar_id_carga_seq', 1, false);


--
-- TOC entry 5089 (class 0 OID 0)
-- Dependencies: 238
-- Name: comentario_id_comentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comentario_id_comentario_seq', 1, false);


--
-- TOC entry 5090 (class 0 OID 0)
-- Dependencies: 236
-- Name: comunicado_id_comunicado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comunicado_id_comunicado_seq', 1, false);


--
-- TOC entry 5091 (class 0 OID 0)
-- Dependencies: 232
-- Name: concepto_pago_id_concepto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.concepto_pago_id_concepto_seq', 1, false);


--
-- TOC entry 5092 (class 0 OID 0)
-- Dependencies: 240
-- Name: evento_id_evento_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.evento_id_evento_seq', 1, false);


--
-- TOC entry 5093 (class 0 OID 0)
-- Dependencies: 219
-- Name: habitante_id_habitante_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.habitante_id_habitante_seq', 1, false);


--
-- TOC entry 5094 (class 0 OID 0)
-- Dependencies: 244
-- Name: indicador_gestion_id_indicador_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.indicador_gestion_id_indicador_seq', 1, false);


--
-- TOC entry 5095 (class 0 OID 0)
-- Dependencies: 234
-- Name: pago_id_pago_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pago_id_pago_seq', 1, false);


--
-- TOC entry 5096 (class 0 OID 0)
-- Dependencies: 242
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.participacion_evento_id_participacion_seq', 1, false);


--
-- TOC entry 5097 (class 0 OID 0)
-- Dependencies: 217
-- Name: persona_id_persona_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.persona_id_persona_seq', 9, true);


--
-- TOC entry 5098 (class 0 OID 0)
-- Dependencies: 224
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 1, false);


--
-- TOC entry 5099 (class 0 OID 0)
-- Dependencies: 226
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 1, false);


--
-- TOC entry 5100 (class 0 OID 0)
-- Dependencies: 221
-- Name: vivienda_id_vivienda_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vivienda_id_vivienda_seq', 1, false);


--
-- TOC entry 4847 (class 2606 OID 75029)
-- Name: carga_familiar carga_familiar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_familiar
    ADD CONSTRAINT carga_familiar_pkey PRIMARY KEY (id_carga);


--
-- TOC entry 4855 (class 2606 OID 75104)
-- Name: comentario comentario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentario
    ADD CONSTRAINT comentario_pkey PRIMARY KEY (id_comentario);


--
-- TOC entry 4853 (class 2606 OID 75086)
-- Name: comunicado comunicado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunicado
    ADD CONSTRAINT comunicado_pkey PRIMARY KEY (id_comunicado);


--
-- TOC entry 4849 (class 2606 OID 75052)
-- Name: concepto_pago concepto_pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.concepto_pago
    ADD CONSTRAINT concepto_pago_pkey PRIMARY KEY (id_concepto);


--
-- TOC entry 4857 (class 2606 OID 75127)
-- Name: evento evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evento
    ADD CONSTRAINT evento_pkey PRIMARY KEY (id_evento);


--
-- TOC entry 4831 (class 2606 OID 74891)
-- Name: habitante habitante_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante
    ADD CONSTRAINT habitante_pkey PRIMARY KEY (id_habitante);


--
-- TOC entry 4835 (class 2606 OID 74913)
-- Name: habitante_vivienda habitante_vivienda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante_vivienda
    ADD CONSTRAINT habitante_vivienda_pkey PRIMARY KEY (id_habitante, id_vivienda);


--
-- TOC entry 4861 (class 2606 OID 75166)
-- Name: indicador_gestion indicador_gestion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indicador_gestion
    ADD CONSTRAINT indicador_gestion_pkey PRIMARY KEY (id_indicador);


--
-- TOC entry 4843 (class 2606 OID 74997)
-- Name: lider_calle lider_calle_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lider_calle
    ADD CONSTRAINT lider_calle_pkey PRIMARY KEY (id_habitante);


--
-- TOC entry 4845 (class 2606 OID 75013)
-- Name: lider_comunal lider_comunal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lider_comunal
    ADD CONSTRAINT lider_comunal_pkey PRIMARY KEY (id_habitante);


--
-- TOC entry 4851 (class 2606 OID 75063)
-- Name: pago pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_pkey PRIMARY KEY (id_pago);


--
-- TOC entry 4859 (class 2606 OID 75143)
-- Name: participacion_evento participacion_evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT participacion_evento_pkey PRIMARY KEY (id_participacion);


--
-- TOC entry 4827 (class 2606 OID 82169)
-- Name: persona persona_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT persona_cedula_key UNIQUE (cedula);


--
-- TOC entry 4829 (class 2606 OID 74878)
-- Name: persona persona_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT persona_pkey PRIMARY KEY (id_persona);


--
-- TOC entry 4837 (class 2606 OID 74937)
-- Name: rol rol_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_nombre_key UNIQUE (nombre);


--
-- TOC entry 4839 (class 2606 OID 74935)
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- TOC entry 4841 (class 2606 OID 74978)
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 4833 (class 2606 OID 74907)
-- Name: vivienda vivienda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vivienda
    ADD CONSTRAINT vivienda_pkey PRIMARY KEY (id_vivienda);


--
-- TOC entry 4887 (class 2620 OID 75040)
-- Name: carga_familiar trg_actualizar_fecha_carga_familiar; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_carga_familiar BEFORE UPDATE ON public.carga_familiar FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4891 (class 2620 OID 75115)
-- Name: comentario trg_actualizar_fecha_comentario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_comentario BEFORE UPDATE ON public.comentario FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4890 (class 2620 OID 75092)
-- Name: comunicado trg_actualizar_fecha_comunicado; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_comunicado BEFORE UPDATE ON public.comunicado FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4888 (class 2620 OID 75053)
-- Name: concepto_pago trg_actualizar_fecha_concepto_pago; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_concepto_pago BEFORE UPDATE ON public.concepto_pago FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4892 (class 2620 OID 75133)
-- Name: evento trg_actualizar_fecha_evento; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_evento BEFORE UPDATE ON public.evento FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4881 (class 2620 OID 74897)
-- Name: habitante trg_actualizar_fecha_habitante; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_habitante BEFORE UPDATE ON public.habitante FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4894 (class 2620 OID 75172)
-- Name: indicador_gestion trg_actualizar_fecha_indicador; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_indicador BEFORE UPDATE ON public.indicador_gestion FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4885 (class 2620 OID 75003)
-- Name: lider_calle trg_actualizar_fecha_lider_calle; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_lider_calle BEFORE UPDATE ON public.lider_calle FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4886 (class 2620 OID 75019)
-- Name: lider_comunal trg_actualizar_fecha_lider_comunal; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_lider_comunal BEFORE UPDATE ON public.lider_comunal FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4889 (class 2620 OID 75074)
-- Name: pago trg_actualizar_fecha_pago; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_pago BEFORE UPDATE ON public.pago FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4893 (class 2620 OID 75154)
-- Name: participacion_evento trg_actualizar_fecha_participacion; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_participacion BEFORE UPDATE ON public.participacion_evento FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4880 (class 2620 OID 74881)
-- Name: persona trg_actualizar_fecha_persona; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_persona BEFORE UPDATE ON public.persona FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4883 (class 2620 OID 74938)
-- Name: rol trg_actualizar_fecha_rol; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_rol BEFORE UPDATE ON public.rol FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4884 (class 2620 OID 74989)
-- Name: usuario trg_actualizar_fecha_usuario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_usuario BEFORE UPDATE ON public.usuario FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4882 (class 2620 OID 74908)
-- Name: vivienda trg_actualizar_fecha_vivienda; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_actualizar_fecha_vivienda BEFORE UPDATE ON public.vivienda FOR EACH ROW EXECUTE FUNCTION public.actualizar_fecha_actualizacion();


--
-- TOC entry 4869 (class 2606 OID 75030)
-- Name: carga_familiar carga_familiar_id_habitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_familiar
    ADD CONSTRAINT carga_familiar_id_habitante_fkey FOREIGN KEY (id_habitante) REFERENCES public.habitante(id_habitante);


--
-- TOC entry 4870 (class 2606 OID 75035)
-- Name: carga_familiar carga_familiar_id_jefe_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_familiar
    ADD CONSTRAINT carga_familiar_id_jefe_fkey FOREIGN KEY (id_jefe) REFERENCES public.habitante(id_habitante);


--
-- TOC entry 4874 (class 2606 OID 75105)
-- Name: comentario comentario_id_comunicado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentario
    ADD CONSTRAINT comentario_id_comunicado_fkey FOREIGN KEY (id_comunicado) REFERENCES public.comunicado(id_comunicado);


--
-- TOC entry 4875 (class 2606 OID 75110)
-- Name: comentario comentario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentario
    ADD CONSTRAINT comentario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4873 (class 2606 OID 75087)
-- Name: comunicado comunicado_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunicado
    ADD CONSTRAINT comunicado_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4876 (class 2606 OID 75128)
-- Name: evento evento_creado_por_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evento
    ADD CONSTRAINT evento_creado_por_fkey FOREIGN KEY (creado_por) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4862 (class 2606 OID 74892)
-- Name: habitante habitante_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante
    ADD CONSTRAINT habitante_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.persona(id_persona);


--
-- TOC entry 4863 (class 2606 OID 74914)
-- Name: habitante_vivienda habitante_vivienda_id_habitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante_vivienda
    ADD CONSTRAINT habitante_vivienda_id_habitante_fkey FOREIGN KEY (id_habitante) REFERENCES public.habitante(id_habitante);


--
-- TOC entry 4864 (class 2606 OID 74919)
-- Name: habitante_vivienda habitante_vivienda_id_vivienda_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitante_vivienda
    ADD CONSTRAINT habitante_vivienda_id_vivienda_fkey FOREIGN KEY (id_vivienda) REFERENCES public.vivienda(id_vivienda);


--
-- TOC entry 4879 (class 2606 OID 75167)
-- Name: indicador_gestion indicador_gestion_generado_por_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indicador_gestion
    ADD CONSTRAINT indicador_gestion_generado_por_fkey FOREIGN KEY (generado_por) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4867 (class 2606 OID 74998)
-- Name: lider_calle lider_calle_id_habitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lider_calle
    ADD CONSTRAINT lider_calle_id_habitante_fkey FOREIGN KEY (id_habitante) REFERENCES public.habitante(id_habitante);


--
-- TOC entry 4868 (class 2606 OID 75014)
-- Name: lider_comunal lider_comunal_id_habitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lider_comunal
    ADD CONSTRAINT lider_comunal_id_habitante_fkey FOREIGN KEY (id_habitante) REFERENCES public.habitante(id_habitante);


--
-- TOC entry 4871 (class 2606 OID 75069)
-- Name: pago pago_id_concepto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_id_concepto_fkey FOREIGN KEY (id_concepto) REFERENCES public.concepto_pago(id_concepto);


--
-- TOC entry 4872 (class 2606 OID 75064)
-- Name: pago pago_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4877 (class 2606 OID 75144)
-- Name: participacion_evento participacion_evento_id_evento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT participacion_evento_id_evento_fkey FOREIGN KEY (id_evento) REFERENCES public.evento(id_evento);


--
-- TOC entry 4878 (class 2606 OID 75149)
-- Name: participacion_evento participacion_evento_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT participacion_evento_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4865 (class 2606 OID 82177)
-- Name: usuario usuario_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.persona(id_persona) NOT VALID;


--
-- TOC entry 4866 (class 2606 OID 74984)
-- Name: usuario usuario_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol);


--
-- TOC entry 5074 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;


-- Completed on 2025-06-14 23:46:14

--
-- PostgreSQL database dump complete
--

