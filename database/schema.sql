
SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

CREATE TYPE public.confirmation_status_enum AS ENUM (
    'PENDING',
    'HADIR',
    'TIDAK_HADIR'
);

SET default_tablespace = '';

SET default_table_access_method = heap;

CREATE TABLE public.attendances (
    id bigint NOT NULL,
    invitation_id bigint NOT NULL,
    checked_in_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE public.attendances ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.attendances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);

CREATE TABLE public.invitations (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    confirmation_status public.confirmation_status_enum DEFAULT 'PENDING'::public.confirmation_status_enum NOT NULL,
    confirmed_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE public.invitations ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.invitations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);

CREATE TABLE public.lucky_spin (
    id bigint NOT NULL,
    attendance_id bigint NOT NULL,
    spin_order integer NOT NULL,
    prize_name character varying(150),
    notes text,
    won_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE public.lucky_spin ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.lucky_spin_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;

CREATE TABLE public.users (
    id bigint NOT NULL,
    badge_id character varying(50) NOT NULL,
    name character varying(150) NOT NULL,
    department character varying(100),
    "position" character varying(150),
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    email character varying(150)
);

ALTER TABLE public.users ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);

ALTER TABLE ONLY public.attendances
    ADD CONSTRAINT attendances_invitation_id_key UNIQUE (invitation_id);

ALTER TABLE ONLY public.attendances
    ADD CONSTRAINT attendances_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.invitations
    ADD CONSTRAINT invitations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.invitations
    ADD CONSTRAINT invitations_user_id_key UNIQUE (user_id);

ALTER TABLE ONLY public.lucky_spin
    ADD CONSTRAINT lucky_spin_attendance_id_key UNIQUE (attendance_id);

ALTER TABLE ONLY public.lucky_spin
    ADD CONSTRAINT lucky_spin_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.lucky_spin
    ADD CONSTRAINT lucky_spin_spin_order_key UNIQUE (spin_order);

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_badge_id_key UNIQUE (badge_id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

CREATE INDEX idx_invitations_status ON public.invitations USING btree (confirmation_status);

CREATE INDEX idx_lucky_spin_attendance ON public.lucky_spin USING btree (attendance_id);

CREATE INDEX idx_users_badge_id ON public.users USING btree (badge_id);

ALTER TABLE ONLY public.attendances
    ADD CONSTRAINT attendances_invitation_id_fkey FOREIGN KEY (invitation_id) REFERENCES public.invitations(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.invitations
    ADD CONSTRAINT invitations_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.lucky_spin
    ADD CONSTRAINT lucky_spin_attendance_id_fkey FOREIGN KEY (attendance_id) REFERENCES public.attendances(id) ON DELETE CASCADE;

