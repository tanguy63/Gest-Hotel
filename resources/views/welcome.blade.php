
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="GEST-HOSTEL est une plateforme professionnelle de gestion hôtelière : chambres, réservations, clients, paiements, personnel et suivi de votre établissement.">

    <title>GEST-HOSTEL | La gestion hôtelière intelligente</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .brand {
            color: #2457A7;
        }

        .brand-bg {
            background-color: #2457A7;
        }

        .hero-bg {
            background:
                radial-gradient(circle at 85% 15%, rgba(36, 87, 167, .12), transparent 30%),
                linear-gradient(135deg, #f8fbff 0%, #ffffff 55%, #edf4ff 100%);
        }

        .section-label {
            color: #2457A7;
            letter-spacing: .18em;
        }

        .feature-card {
            transition: all .3s ease;
        }

        .feature-card:hover {
            transform: translateY(-7px);
            border-color: #bfd4f5;
            box-shadow: 0 20px 45px rgba(36, 87, 167, .09);
        }

        .photo-card img {
            transition: transform .6s ease;
        }

        .photo-card:hover img {
            transform: scale(1.06);
        }

        .soft-shadow {
            box-shadow: 0 25px 70px rgba(36, 87, 167, .12);
        }

        .mobile-menu {
            display: none;
        }

        .mobile-menu.open {
            display: block;
        }
    </style>
</head>

<body class="bg-white text-slate-800 antialiased">

{{-- ======================================================
     NAVIGATION
====================================================== --}}

<header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur-xl">

    <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

        <a href="{{ route('home') }}" class="flex items-center gap-3">

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#2457A7] text-2xl font-extrabold text-white shadow-lg shadow-blue-200">
                G
            </div>

            <div>
                <div class="text-xl font-extrabold tracking-tight text-slate-900">
                    GEST<span class="text-[#2457A7]">-HOSTEL</span>
                </div>

                <div class="text-[9px] font-semibold tracking-[.2em] text-slate-400">
                    HOTEL MANAGEMENT SYSTEM
                </div>
            </div>

        </a>

        <div class="hidden items-center gap-8 lg:flex">

            <a href="#accueil" class="text-sm font-medium text-slate-600 hover:text-[#2457A7]">
                Accueil
            </a>

            <a href="#fonctionnalites" class="text-sm font-medium text-slate-600 hover:text-[#2457A7]">
                Fonctionnalités
            </a>

            <a href="#etablissements" class="text-sm font-medium text-slate-600 hover:text-[#2457A7]">
                Établissements
            </a>

            <a href="#equipe" class="text-sm font-medium text-slate-600 hover:text-[#2457A7]">
                Équipe
            </a>

            <a href="#apropos" class="text-sm font-medium text-slate-600 hover:text-[#2457A7]">
                À propos
            </a>

        </div>

        <div class="hidden items-center gap-3 sm:flex">

            <a href="{{ route('login') }}"
               class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Connexion
            </a>

            <a href="{{ route('inscription') }}"
               class="rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-800">
                Créer un compte
            </a>

        </div>

        <button id="menuButton"
                type="button"
                aria-label="Ouvrir le menu"
                aria-expanded="false"
                class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-xl text-slate-700 sm:hidden">
            ☰
        </button>

    </nav>

    <div id="mobileMenu" class="mobile-menu border-t border-slate-100 bg-white px-5 py-5 sm:hidden">

        <div class="flex flex-col gap-4 text-sm font-medium">

            <a href="#accueil">Accueil</a>
            <a href="#fonctionnalites">Fonctionnalités</a>
            <a href="#etablissements">Établissements</a>
            <a href="#equipe">Équipe</a>
            <a href="#apropos">À propos</a>

            <hr class="border-slate-100">

            <a href="{{ route('login') }}" class="text-[#2457A7]">
                Se connecter
            </a>

            <a href="{{ route('inscription') }}"
               class="rounded-xl bg-[#2457A7] px-5 py-3 text-center font-bold text-white">
                Créer mon compte
            </a>

        </div>

    </div>

</header>


<main>

{{-- ======================================================
     HERO
====================================================== --}}

<section id="accueil" class="hero-bg overflow-hidden">

    <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-2 lg:px-8 lg:py-28">

        <div>

            <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-semibold text-[#2457A7]">

                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                LA GESTION HÔTELIÈRE SIMPLIFIÉE

            </div>

            <h1 class="max-w-2xl text-4xl leading-tight font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">

                Votre établissement mérite une

                <span class="text-[#2457A7]">
                    gestion professionnelle.
                </span>

            </h1>

            <p class="mt-7 max-w-xl text-base leading-8 text-slate-600">

                GEST-HOSTEL centralise la gestion de votre hôtel, auberge, résidence ou établissement d'hébergement.

                Gérez vos chambres, vos réservations, vos clients, vos paiements et votre personnel depuis une seule plateforme.

            </p>

            <div class="mt-9 flex flex-col gap-4 sm:flex-row">

                <a href="{{ route('inscription') }}"
                   class="inline-flex items-center justify-center gap-3 rounded-xl bg-[#2457A7] px-7 py-4 text-sm font-bold text-white shadow-xl shadow-blue-200 transition hover:-translate-y-1 hover:bg-blue-800">

                    Découvrir GEST-HOSTEL

                    <span>→</span>

                </a>

                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-7 py-4 text-sm font-bold text-slate-700 transition hover:border-[#2457A7] hover:text-[#2457A7]">

                    Se connecter

                </a>

            </div>

            <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-xs font-medium text-slate-500">

                <span>✓ Gestion centralisée</span>
                <span>✓ Accès sécurisé</span>
                <span>✓ Plusieurs profils utilisateurs</span>

            </div>

            <div class="mt-9 flex items-center gap-3">

                <div class="flex -space-x-2">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-blue-100 text-sm">🏨</div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-green-100 text-sm">🛎️</div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-orange-100 text-sm">🔑</div>

                </div>

                <p class="text-xs text-slate-500">
                    Une solution pensée pour les professionnels de l'hébergement.
                </p>

            </div>

        </div>


        {{-- VISUEL HERO --}}

        <div class="relative">

            <div class="absolute -inset-5 rounded-[2rem] bg-blue-100/70 blur-3xl"></div>

            <div class="relative">

                <div class="overflow-hidden rounded-[2rem] border-8 border-white shadow-2xl">

                    <img
                        src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85"
                        alt="Hôtel moderne et élégant"
                        class="h-[430px] w-full object-cover sm:h-[520px]"
                    >

                </div>

                {{-- Carte flottante --}}

                <div class="absolute -bottom-8 -left-3 rounded-2xl border border-slate-100 bg-white p-5 shadow-xl sm:-left-8">

                    <div class="flex items-center gap-3">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">
                            📊
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">GESTION CENTRALISÉE</p>
                            <p class="text-sm font-bold text-slate-800">Votre activité en un seul endroit</p>
                        </div>

                    </div>

                </div>

                <div class="absolute -right-2 top-8 rounded-xl bg-white p-4 shadow-xl sm:-right-6">

                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">✓</span>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Accès sécurisé</p>
                            <p class="text-[10px] text-slate-400">Selon les rôles</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ======================================================
     BANDEAU AVANTAGES
====================================================== --}}

<section class="border-y border-slate-100 bg-white">

    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-5 py-9 md:grid-cols-4 lg:px-8">

        <div class="text-center">
            <div class="text-2xl">🏨</div>
            <p class="mt-2 text-sm font-bold text-slate-800">Établissements</p>
            <p class="mt-1 text-xs text-slate-500">Gestion dédiée</p>
        </div>

        <div class="text-center">
            <div class="text-2xl">🛏️</div>
            <p class="mt-2 text-sm font-bold text-slate-800">Chambres</p>
            <p class="mt-1 text-xs text-slate-500">Organisation simplifiée</p>
        </div>

        <div class="text-center">
            <div class="text-2xl">👥</div>
            <p class="mt-2 text-sm font-bold text-slate-800">Collaborateurs</p>
            <p class="mt-1 text-xs text-slate-500">Accès par rôle</p>
        </div>

        <div class="text-center">
            <div class="text-2xl">💳</div>
            <p class="mt-2 text-sm font-bold text-slate-800">Finances</p>
            <p class="mt-1 text-xs text-slate-500">Suivi des paiements</p>
        </div>

    </div>

</section>


{{-- ======================================================
     PRESENTATION
====================================================== --}}

<section id="apropos" class="bg-white py-24">

    <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 lg:grid-cols-2 lg:px-8">

        <div class="relative">

            <div class="overflow-hidden rounded-3xl shadow-xl">

                <img
                    src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1000&q=85"
                    alt="Réception professionnelle d'un hôtel"
                    class="h-[430px] w-full object-cover"
                >

            </div>

            <div class="absolute -bottom-7 -right-3 rounded-2xl border border-slate-100 bg-white p-5 shadow-xl sm:-right-7">

                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">
                        🏢
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">VOTRE ESPACE</p>
                        <p class="text-sm font-bold text-slate-800">Votre établissement</p>
                    </div>

                </div>

            </div>

        </div>


        <div>

            <span class="section-label text-xs font-bold">
                À PROPOS DE LA PLATEFORME
            </span>

            <h2 class="mt-4 text-3xl leading-tight font-extrabold text-slate-900 sm:text-4xl">

                Une seule plateforme pour organiser toutes vos opérations.

            </h2>

            <p class="mt-6 text-sm leading-8 text-slate-500">

                GEST-HOSTEL a pour objectif de simplifier les opérations quotidiennes des établissements d'hébergement.

                Au lieu de disperser les informations entre plusieurs outils, vous disposez d'un espace centralisé pour organiser votre activité.

            </p>

            <div class="mt-8 space-y-5">

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">🗂️</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Informations organisées</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Vos clients, chambres et réservations sont gérés depuis votre espace.</p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-xl">🔐</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Accès adaptés aux responsabilités</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Les permissions dépendent du rôle attribué à chaque collaborateur.</p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-xl">📈</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Meilleure visibilité sur l'activité</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Consultez les informations essentielles depuis votre tableau de bord.</p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ======================================================
     FONCTIONNALITES
====================================================== --}}

<section id="fonctionnalites" class="bg-slate-50 py-24">

    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">

            <span class="section-label text-xs font-bold">
                FONCTIONNALITÉS
            </span>

            <h2 class="mt-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Tous les outils essentiels à votre gestion
            </h2>

            <p class="mt-5 text-sm leading-8 text-slate-500">
                Des modules complémentaires pour gérer les principales activités de votre établissement.
            </p>

        </div>


        <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Chambres --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=85"
                         alt="Chambre d'hôtel moderne"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">🛏️</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Gestion des chambres
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Organisez vos chambres, créez vos catégories et centralisez leurs informations.
                    </p>

                </div>

            </article>


            {{-- Réservations --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=85"
                         alt="Établissement hôtelier"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-2xl">📅</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Gestion des réservations
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Enregistrez les réservations, les dates de séjour et les informations des clients.
                    </p>

                </div>

            </article>


            {{-- Clients --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=800&q=85"
                         alt="Accueil et service client"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">👥</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Gestion des clients
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Centralisez les coordonnées et les informations de vos clients.
                    </p>

                </div>

            </article>


            {{-- Paiements --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=85"
                         alt="Gestion financière et comptabilité"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-2xl">💳</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Paiements et reçus
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Enregistrez les avances, les paiements et consultez les reçus associés aux opérations.
                    </p>

                </div>

            </article>


            {{-- Personnel --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=800&q=85"
                         alt="Équipe professionnelle au travail"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-2xl">👨‍💼</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Gestion du personnel
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Ajoutez vos collaborateurs, gérez leurs rôles et contrôlez leurs accès.
                    </p>

                </div>

            </article>


            {{-- Paramètres --}}

            <article class="feature-card overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="photo-card h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=800&q=85"
                         alt="Espace de travail professionnel"
                         class="h-full w-full object-cover">
                </div>

                <div class="p-7">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-50 text-2xl">⚙️</div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Paramètres de l'établissement
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Gérez les informations et les paramètres de votre établissement.
                    </p>

                </div>

            </article>

        </div>

    </div>

</section>


{{-- ======================================================
     MULTI-ETABLISSEMENTS
====================================================== --}}

<section id="etablissements" class="overflow-hidden bg-white py-24">

    <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 lg:grid-cols-2 lg:px-8">

        <div>

            <span class="section-label text-xs font-bold">
                GESTION MULTI-ÉTABLISSEMENTS
            </span>

            <h2 class="mt-4 text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                Organisez vos établissements dans un même environnement.
            </h2>

            <p class="mt-6 text-sm leading-8 text-slate-500">
                GEST-HOSTEL permet à un utilisateur autorisé d'être associé à plusieurs établissements et de travailler dans l'espace actif auquel il a accès.
            </p>

            <div class="mt-8 space-y-5">

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">🏢</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Espaces par établissement</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Chaque établissement dispose de ses propres informations et opérations.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-xl">🔒</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Séparation des données</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Les accès aux données sont contrôlés selon l'établissement autorisé.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-xl">👤</div>

                    <div>
                        <h3 class="font-bold text-slate-800">Responsabilités distinctes</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Chaque collaborateur dispose d'un rôle adapté à ses responsabilités.
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Illustration SVG --}}

        <div class="relative rounded-3xl bg-blue-50 p-6 sm:p-10">

            <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-xl">

                <div class="flex items-center justify-between border-b border-slate-100 pb-5">

                    <div>
                        <p class="text-xs text-slate-400">ESPACE PROFESSIONNEL</p>
                        <h3 class="mt-1 font-bold text-slate-800">Mes établissements</h3>
                    </div>

                    <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-[#2457A7]">
                        GEST-HOSTEL
                    </span>

                </div>

                <div class="mt-6 space-y-4">

                    <div class="flex items-center gap-4 rounded-xl border border-blue-100 bg-blue-50/60 p-4">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-2xl shadow-sm">🏨</div>

                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Établissement principal</p>
                            <p class="mt-1 text-xs text-slate-500">Espace de gestion</p>
                        </div>

                        <span class="h-3 w-3 rounded-full bg-green-500"></span>

                    </div>

                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 p-4">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-2xl">🏢</div>

                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Autre établissement associé</p>
                            <p class="mt-1 text-xs text-slate-500">Selon vos autorisations</p>
                        </div>

                        <span class="h-3 w-3 rounded-full bg-blue-400"></span>

                    </div>

                </div>

                <div class="mt-6 rounded-xl bg-slate-50 p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-xl">🔐</div>

                        <div>
                            <p class="text-xs font-bold text-slate-800">Accès contrôlé</p>
                            <p class="mt-1 text-[10px] text-slate-500">Les permissions dépendent de votre rôle.</p>
                        </div>

                    </div>

                </div>

            </div>

            <div class="absolute -bottom-5 -right-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#2457A7] text-3xl text-white shadow-xl sm:-right-5">
                ✓
            </div>

        </div>

    </div>

</section>


{{-- ======================================================
     GESTION DU PERSONNEL
====================================================== --}}

<section id="equipe" class="bg-slate-50 py-24">

    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">

            <span class="section-label text-xs font-bold">
                TRAVAIL EN ÉQUIPE
            </span>

            <h2 class="mt-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Chaque collaborateur a son rôle.
            </h2>

            <p class="mt-5 text-sm leading-8 text-slate-500">
                Organisez les responsabilités de votre équipe avec des profils adaptés aux missions de chacun.
            </p>

        </div>


        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl">👑</div>
                <h3 class="mt-5 font-bold text-slate-900">Propriétaire</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Supervision et contrôle de l'établissement.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-2xl">🧑‍💼</div>
                <h3 class="mt-5 font-bold text-slate-900">Administrateur</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Organisation des opérations et du personnel.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-50 text-2xl">🛎️</div>
                <h3 class="mt-5 font-bold text-slate-900">Agent d'exploitation</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Accueil, clients et réservations.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-2xl">🧮</div>
                <h3 class="mt-5 font-bold text-slate-900">Comptable</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Suivi des paiements et opérations financières.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-50 text-2xl">🧹</div>
                <h3 class="mt-5 font-bold text-slate-900">Entretien</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Suivi opérationnel des chambres.</p>
            </div>

        </div>

        <p class="mt-7 text-center text-xs text-slate-400">
            Les droits d'accès sont définis selon le profil attribué à chaque utilisateur.
        </p>

    </div>

</section>


{{-- ======================================================
     ESSAI GRATUIT
====================================================== --}}

<section class="relative overflow-hidden bg-[#2457A7] py-24 text-white">

    <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border-[50px] border-white/5"></div>
    <div class="absolute -bottom-40 -left-20 h-96 w-96 rounded-full border-[50px] border-white/5"></div>

    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-5 lg:grid-cols-2 lg:px-8">

        <div>

            <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold tracking-widest text-blue-100">
                COMMENCEZ MAINTENANT
            </span>

            <h2 class="mt-7 text-3xl leading-tight font-extrabold sm:text-5xl">
                Découvrez GEST-HOSTEL pendant 7 jours.
            </h2>

            <p class="mt-6 max-w-xl text-sm leading-8 text-blue-100">
                Créez votre espace, vérifiez votre adresse e-mail et profitez de votre période d'essai gratuite pour découvrir la plateforme.
            </p>

            <a href="{{ route('inscription') }}"
               class="mt-9 inline-flex items-center gap-3 rounded-xl bg-white px-7 py-4 text-sm font-bold text-[#2457A7] shadow-xl transition hover:-translate-y-1 hover:bg-blue-50">

                Créer mon compte gratuitement

                <span>→</span>

            </a>

            <p class="mt-5 text-xs text-blue-100">
                Période d'essai de 7 jours après vérification de l'adresse e-mail.
            </p>

        </div>


        {{-- Illustration SVG originale --}}

        <div class="rounded-3xl border border-white/10 bg-white/10 p-6 backdrop-blur sm:p-10">

            <svg viewBox="0 0 500 340" xmlns="http://www.w3.org/2000/svg" class="w-full" role="img" aria-label="Illustration d'un établissement hôtelier">

                <rect x="65" y="55" width="370" height="250" rx="20" fill="white"/>

                <rect x="95" y="85" width="310" height="190" rx="12" fill="#EFF6FF"/>

                <rect x="120" y="110" width="110" height="135" rx="8" fill="#2457A7"/>

                <rect x="140" y="130" width="30" height="35" rx="4" fill="#BFDBFE"/>
                <rect x="180" y="130" width="30" height="35" rx="4" fill="#BFDBFE"/>

                <rect x="140" y="185" width="70" height="60" rx="4" fill="#DBEAFE"/>

                <rect x="255" y="110" width="120" height="65" rx="8" fill="#DBEAFE"/>

                <rect x="275" y="130" width="80" height="12" rx="6" fill="#2457A7" opacity=".8"/>

                <rect x="255" y="195" width="120" height="50" rx="8" fill="#2457A7"/>

                <rect x="275" y="210" width="80" height="8" rx="4" fill="white" opacity=".8"/>

                <circle cx="365" cy="65" r="35" fill="#22C55E"/>

                <path d="M349 65 L360 76 L382 52" stroke="white" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

                <rect x="175" y="275" width="150" height="12" rx="6" fill="#2457A7" opacity=".2"/>

                <circle cx="80" cy="270" r="15" fill="#FBBF24"/>
                <circle cx="420" cy="230" r="12" fill="#FBBF24"/>

            </svg>

            <div class="mt-5 text-center">
                <p class="text-lg font-bold">Votre établissement, mieux organisé.</p>
                <p class="mt-2 text-xs text-blue-100">Une plateforme, plusieurs outils de gestion.</p>
            </div>

        </div>

    </div>

</section>


{{-- ======================================================
     COMMENT CA MARCHE
====================================================== --}}

<section class="bg-white py-24">

    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="text-center">

            <span class="section-label text-xs font-bold">
                COMMENT ÇA MARCHE ?
            </span>

            <h2 class="mt-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Démarrez en trois étapes.
            </h2>

        </div>


        <div class="mt-16 grid gap-10 md:grid-cols-3">

            <div class="text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-extrabold text-[#2457A7]">
                    01
                </div>

                <h3 class="mt-6 font-bold text-slate-900">Créez votre compte</h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Renseignez les informations de votre établissement et inscrivez-vous.
                </p>

            </div>


            <div class="text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-extrabold text-[#2457A7]">
                    02
                </div>

                <h3 class="mt-6 font-bold text-slate-900">Configurez votre espace</h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Vérifiez votre adresse e-mail puis organisez vos chambres et votre équipe.
                </p>

            </div>


            <div class="text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-extrabold text-[#2457A7]">
                    03
                </div>

                <h3 class="mt-6 font-bold text-slate-900">Gérez votre activité</h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Suivez vos réservations, clients et paiements depuis votre tableau de bord.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- ======================================================
     CTA FINAL
====================================================== --}}

<section class="bg-blue-50 py-20">

    <div class="mx-auto max-w-4xl px-5 text-center">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl shadow-lg">
            🏨
        </div>

        <h2 class="mt-7 text-3xl font-extrabold text-slate-900 sm:text-4xl">
            Prêt à passer à une gestion plus professionnelle ?
        </h2>

        <p class="mx-auto mt-5 max-w-2xl text-sm leading-8 text-slate-500">
            Rejoignez GEST-HOSTEL et simplifiez l'organisation quotidienne de votre établissement.
        </p>

        <div class="mt-9 flex flex-col justify-center gap-4 sm:flex-row">

            <a href="{{ route('inscription') }}"
               class="rounded-xl bg-[#2457A7] px-8 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-800">
                Créer mon compte
            </a>

            <a href="{{ route('login') }}"
               class="rounded-xl border border-slate-200 bg-white px-8 py-4 text-sm font-bold text-slate-700 transition hover:border-[#2457A7] hover:text-[#2457A7]">
                J'ai déjà un compte
            </a>

        </div>

    </div>

</section>

</main>


{{-- ======================================================
     FOOTER
====================================================== --}}

<footer class="bg-slate-950 text-white">

    <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8">

        <div class="grid gap-12 md:grid-cols-3">

            <div class="md:col-span-1">

                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#2457A7] text-xl font-extrabold">
                        G
                    </div>

                    <span class="text-xl font-extrabold">
                        GEST-HOSTEL
                    </span>

                </a>

                <p class="mt-5 max-w-sm text-sm leading-7 text-slate-400">
                    La plateforme de gestion conçue pour accompagner les professionnels de l'hébergement dans leurs activités quotidiennes.
                </p>

            </div>


            <div>

                <h3 class="font-bold">Navigation</h3>

                <div class="mt-5 flex flex-col gap-3 text-sm text-slate-400">

                    <a href="#accueil" class="hover:text-white">Accueil</a>
                    <a href="#apropos" class="hover:text-white">À propos</a>
                    <a href="#fonctionnalites" class="hover:text-white">Fonctionnalités</a>
                    <a href="#etablissements" class="hover:text-white">Multi-établissements</a>
                    <a href="#equipe" class="hover:text-white">Gestion du personnel</a>

                </div>

            </div>


            <div>

                <h3 class="font-bold">Votre espace</h3>

                <div class="mt-5 flex flex-col gap-3 text-sm text-slate-400">

                    <a href="{{ route('inscription') }}" class="hover:text-white">Créer un compte</a>
                    <a href="{{ route('login') }}" class="hover:text-white">Connexion</a>

                </div>

                <div class="mt-7 rounded-xl border border-slate-800 bg-slate-900 p-4">

                    <p class="text-xs font-bold text-white">
                        GEST-HOSTEL
                    </p>

                    <p class="mt-2 text-xs leading-6 text-slate-400">
                        Simplifiez votre gestion hôtelière.
                    </p>

                </div>

            </div>

        </div>


        <div class="mt-12 border-t border-slate-800 pt-7 text-center text-xs text-slate-500">

            © {{ date('Y') }} GEST-HOSTEL. Tous droits réservés.

            <span class="mx-2">|</span>

            Conçu pour les professionnels de l'hébergement.

        </div>

    </div>

</footer>


{{-- ======================================================
     MENU MOBILE
====================================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (menuButton && mobileMenu) {

            menuButton.addEventListener('click', function () {

                mobileMenu.classList.toggle('open');

                const isOpen = mobileMenu.classList.contains('open');

                menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                menuButton.textContent = isOpen ? '✕' : '☰';

            });

            mobileMenu.querySelectorAll('a').forEach(function (link) {

                link.addEventListener('click', function () {

                    mobileMenu.classList.remove('open');

                    menuButton.setAttribute('aria-expanded', 'false');

                    menuButton.textContent = '☰';

                });

            });

        }

    });
</script>

</body>
</html>
