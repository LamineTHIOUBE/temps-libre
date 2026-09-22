<?php

/* =========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================= */

require_once __DIR__ . '/config/databases.php';


/* =========================================================
   RÉCUPÉRER LES PRODUITS
   ========================================================= */

try {

    $stmt = $conn->query("
        SELECT
            id,
            nom,
            description,
            prix,
            image,
            stock,
            categorie,
            date_creation
        FROM produits
        ORDER BY date_creation DESC
    ");

    $produits =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {

    $produits = [];

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Produits | Temps Libre</title>


    <!-- Google Fonts -->

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="css/produits.css"
    >

</head>


<body class="page-produits">


<?php include __DIR__ . '/includes/header.php'; ?>


<!-- =========================================================
     HERO PRODUITS
     ========================================================= -->

<section class="shop-hero">

    <div class="shop-hero-content">

        <span class="shop-eyebrow">
            Boutique Temps Libre
        </span>

        <h1>
            Découvrez nos
            <span>jus naturels</span>
        </h1>

        <p>
            Des saveurs authentiques préparées à partir
            des richesses du terroir sénégalais.
        </p>

        <a
            href="#produits"
            class="btn-shop"
        >

            Voir nos produits

            <i class="fa-solid fa-arrow-down"></i>

        </a>

    </div>

</section>


<!-- =========================================================
     INTRO
     ========================================================= -->

<section class="shop-intro">

    <div class="container-shop">

        <span>
            NOS PRODUITS
        </span>

        <h2>
            Choisissez votre saveur
        </h2>

        <p>
            Retrouvez tous les produits actuellement
            disponibles dans notre boutique.
        </p>

    </div>

</section>


<!-- =========================================================
     PRODUITS
     ========================================================= -->

<section
    class="shop-products"
    id="produits"
>

    <div class="container-shop">


        <?php if (!empty($produits)): ?>


            <div class="products-grid">


                <?php foreach ($produits as $produit): ?>


                    <article class="shop-card">


                        <!-- IMAGE -->

                        <div class="shop-card-image">

                            <?php
                            $image = trim($produit["image"]);
                            $cheminImage =
                                __DIR__ . '/images/' . $image;
                            ?>


                            <?php if (
                                !empty($image)
                                && file_exists($cheminImage)
                            ): ?>

                                <img
                                    src="images/<?= htmlspecialchars($image) ?>"
                                    alt="<?= htmlspecialchars($produit["nom"]) ?>"
                                >

                            <?php else: ?>

                                <div class="image-placeholder">

                                    <i class="fa-solid fa-image"></i>

                                </div>

                            <?php endif; ?>


                            <!-- CATÉGORIE -->

                            <span class="shop-category">

                                <?= htmlspecialchars(
                                    $produit["categorie"]
                                ) ?>

                            </span>


                            <!-- STOCK -->

                            <?php if ((int) $produit["stock"] > 0): ?>

                                <span class="stock-badge available">

                                    Disponible

                                </span>

                            <?php else: ?>

                                <span class="stock-badge unavailable">

                                    Rupture

                                </span>

                            <?php endif; ?>


                        </div>


                        <!-- CONTENU -->

                        <div class="shop-card-body">


                            <h3>

                                <?= htmlspecialchars(
                                    $produit["nom"]
                                ) ?>

                            </h3>


                            <p>

                                <?= htmlspecialchars(
                                    $produit["description"]
                                ) ?>

                            </p>


                            <div class="shop-card-footer">


                                <div class="shop-price">

                                    <?= number_format(
                                        (float) $produit["prix"],
                                        0,
                                        ",",
                                        " "
                                    ) ?>

                                    <span>
                                        FCFA
                                    </span>

                                </div>


                                <?php if ((int) $produit["stock"] > 0): ?>

                                    <a
                                        href="produit.php?id=<?= (int) $produit["id"] ?>"
                                        class="btn-product-detail"
                                    >

                                        Voir

                                        <i class="fa-solid fa-arrow-right"></i>

                                    </a>

                                <?php else: ?>

                                    <span class="btn-product-disabled">

                                        Indisponible

                                    </span>

                                <?php endif; ?>


                            </div>


                        </div>


                    </article>


                <?php endforeach; ?>


            </div>


        <?php else: ?>


            <div class="shop-empty">

                <i class="fa-solid fa-bottle-water"></i>

                <h3>
                    Aucun produit disponible
                </h3>

                <p>
                    Notre boutique sera bientôt remplie
                    de nouvelles saveurs.
                </p>

            </div>


        <?php endif; ?>


    </div>

</section>


<!-- =========================================================
     CTA
     ========================================================= -->

<section class="shop-cta">

    <div class="container-shop">

        <div class="shop-cta-box">

            <div>

                <span>
                    Une envie particulière ?
                </span>

                <h2>
                    Commandez vos jus préférés
                </h2>

                <p>
                    Contactez Temps Libre pour passer votre
                    commande et organiser votre livraison.
                </p>

            </div>


            <a
                href="contact.php"
                class="btn-order"
            >

                Commander maintenant

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

</section>


<?php include __DIR__ . '/includes/footer.php'; ?>


</body>

</html>