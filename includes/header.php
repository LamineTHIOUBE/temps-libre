<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Votre Site</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top menu-principal">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <img src="images\logo\logo.png" alt="Logo" class="logo">
        </a>

        <!-- Bouton mobile -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarTempsLibre">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarTempsLibre">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">À propos</a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link" href="products.php">Produits</a>
                 </li>


                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

            </ul>

            <!-- Partie droite -->
            <div class="d-flex align-items-center">

                <a href="panier.php" class="panier me-3 position-relative">

                    <i class="fa-solid fa-cart-shopping"></i>

                      <span id="cart-count">
                            0
                      </span>

                </a>

                <a href="order.php" class="btn commander-btn">

                    Commander

                </a>

            </div>

        </div>

    </div>

</nav>