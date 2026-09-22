<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Temps Libre</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top menu-principal">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="index.php">

            <img
                src="images/logo/logo.png"
                alt="Logo Temps Libre"
                class="logo"
            >

        </a>


        <!-- Bouton mobile -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarTempsLibre"
            aria-controls="navbarTempsLibre"
            aria-expanded="false"
            aria-label="Ouvrir le menu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- Menu -->
        <div
            class="collapse navbar-collapse"
            id="navbarTempsLibre"
        >

            <ul class="navbar-nav mx-auto">


                <!-- Accueil -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>"
                        href="index.php"
                    >
                        Accueil
                    </a>

                </li>


                <!-- À propos -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>"
                        href="about.php"
                    >
                        À propos
                    </a>

                </li>


                <!-- Produits -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= $currentPage === 'produits.php' ? 'active' : '' ?>"
                        href="produits.php"
                    >
                        Produits
                    </a>

                </li>


                <!-- Contact -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>"
                        href="contact.php"
                    >
                        Contact
                    </a>

                </li>

            </ul>


            <!-- Partie droite -->
            <div class="d-flex align-items-center">


                <!-- Panier -->
                <a
                    href="panier.php"
                    class="panier me-3 position-relative"
                >

                    <i class="fa-solid fa-cart-shopping"></i>

                    <span id="cart-count">
                        0
                    </span>

                </a>


                <!-- Commander -->
                <a
                    href="order.php"
                    class="btn commander-btn"
                >
                    Commander
                </a>

            </div>

        </div>

    </div>

</nav>
