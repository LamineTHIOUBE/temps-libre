<?php

require_once __DIR__ . "/config/databases.php";

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon panier | Temps Libre</title>

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

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- CSS panier -->
    <link
        rel="stylesheet"
        href="css/panier.css"
    >

</head>


<body>



<?php require_once __DIR__ . "/includes/header.php"; ?>


<main class="panier-page">

    <div class="container">

        <!-- ========================================
             TITRE
        ======================================== -->

        <div class="panier-header">

            <span class="panier-sur-titre">
                Votre sélection
            </span>

            <h1>
                Mon panier
            </h1>

            <p>
                Retrouvez ici les produits que vous souhaitez commander.
            </p>

        </div>


        <!-- ========================================
             CONTENU DU PANIER
        ======================================== -->

        <div id="panier-container">

            <!-- Le contenu sera généré par panier.js -->

        </div>


        <!-- ========================================
             PANIER VIDE
        ======================================== -->

        <div
            id="panier-vide"
            class="panier-vide"
            style="display: none;"
        >

            <div class="panier-vide-icon">

                <i class="fa-solid fa-cart-shopping"></i>

            </div>

            <h2>
                Votre panier est vide
            </h2>

            <p>
                Vous n'avez encore ajouté aucun produit à votre panier.
            </p>

            <a
                href="produits.php"
                class="btn-retour-produits"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Découvrir nos produits

            </a>

        </div>


    </div>

</main>


<?php require_once __DIR__ . "/includes/footer.php"; ?>


<!-- JavaScript panier -->
<script src="js/panier.js"></script>

</body>

</html>