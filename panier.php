<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon panier | Temps Libre</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/panier.css">

</head>

<body>

<?php include 'includes/header.php'; ?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">

    <div class="container">

        <nav>

            <a href="index.php">Accueil</a>

            <span>/</span>

            <span>Mon panier</span>

        </nav>

        <h1>

            <i class="fa-solid fa-cart-shopping"></i>

            Mon panier

        </h1>

    </div>

</section>

<!-- Contenu principal -->
<section class="cart-section">

    <div class="container">

        <div class="row">

            <!-- Liste des produits -->
            <div class="col-lg-8">

                <div class="cart-card">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Produit</th>

                                <th>Prix</th>

                                <th>Quantité</th>

                                <th>Total</th>

                                <th></th>

                            </tr>

                        </thead>

                        <tbody id="cart-items">

                            <!-- Les produits seront ajoutés ici -->

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Résumé -->
            <div class="col-lg-4">

                <div class="summary-card">

                    <h3>Résumé de la commande</h3>

                    <hr>

                    <div class="summary-line">

                        <span>Sous-total</span>

                        <span id="subtotal">0 FCFA</span>

                    </div>

                    <div class="summary-line">

                        <span>Livraison</span>

                        <span id="shipping">1000 FCFA</span>

                    </div>

                    <hr>

                    <div class="summary-total">

                        <span>Total</span>

                        <span id="total">1000 FCFA</span>

                    </div>

                    <a href="commande.php" class="btn-checkout">

                        Passer la commande

                    </a>

                    <a href="produits.php" class="btn-continue">

                        Continuer les achats

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script src="js/panier.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>