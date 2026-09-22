<?php

require_once __DIR__ . "/config/databases.php";

/*
|--------------------------------------------------------------------------
| Récupération de l'identifiant du produit
|--------------------------------------------------------------------------
*/

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: produits.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Récupération du produit
|--------------------------------------------------------------------------
*/

$sql = "
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
    WHERE id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

$produit = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Produit introuvable
|--------------------------------------------------------------------------
*/

if (!$produit) {
    header("Location: produits.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Récupération des produits similaires
|--------------------------------------------------------------------------
|
| On recherche les produits appartenant à la même catégorie
| que le produit actuellement consulté.
| Le produit actuel est exclu.
|
*/

$produitsSimilaires = [];

if (!empty($produit["categorie"])) {

    $sqlSimilaires = "
        SELECT
            id,
            nom,
            description,
            prix,
            image,
            stock,
            categorie
        FROM produits
        WHERE categorie = ?
        AND id != ?
        ORDER BY date_creation DESC
        LIMIT 4
    ";

    $stmtSimilaires = $conn->prepare($sqlSimilaires);

    $stmtSimilaires->execute([
        $produit["categorie"],
        $produit["id"]
    ]);

    $produitsSimilaires = $stmtSimilaires->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($produit["nom"]) ?> | Temps Libre
    </title>

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

    <!-- CSS de la fiche produit -->
    <link
        rel="stylesheet"
        href="css/produit.css"
    >

</head>


<body>

<?php require_once __DIR__ . "/includes/header.php"; ?>


<main class="produit-page">

    <!-- ========================================
         FIL D'ARIANE
    ======================================== -->

    <div class="container">

        <div class="breadcrumb-produit">

            <a href="index.php">
                Accueil
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <a href="produits.php">
                Produits
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <span>
                <?= htmlspecialchars($produit["nom"]) ?>
            </span>

        </div>


        <!-- ========================================
             FICHE PRODUIT
        ======================================== -->

        <section class="produit-detail">

            <!-- IMAGE -->

            <div class="produit-image">

                <?php if (!empty($produit["image"])): ?>

                    <img
                        src="images/<?= rawurlencode(trim($produit["image"])) ?>"
                        alt="<?= htmlspecialchars($produit["nom"]) ?>"
                    >

                <?php else: ?>

                    <div class="image-placeholder">

                        <i class="fa-solid fa-image"></i>

                        <span>
                            Image indisponible
                        </span>

                    </div>

                <?php endif; ?>

            </div>


            <!-- INFORMATIONS -->

            <div class="produit-informations">

                <?php if (!empty($produit["categorie"])): ?>

                    <span class="produit-categorie">
                        <?= htmlspecialchars($produit["categorie"]) ?>
                    </span>

                <?php endif; ?>


                <h1>
                    <?= htmlspecialchars($produit["nom"]) ?>
                </h1>


                <div class="produit-prix">

                    <?= number_format(
                        (float) $produit["prix"],
                        0,
                        ",",
                        " "
                    ) ?>

                    FCFA

                </div>


                <!-- STOCK -->

                <?php if ((int) $produit["stock"] > 0): ?>

                    <div class="produit-stock disponible">

                        <i class="fa-solid fa-circle-check"></i>

                        Produit disponible

                    </div>

                <?php else: ?>

                    <div class="produit-stock rupture">

                        <i class="fa-solid fa-circle-xmark"></i>

                        Produit en rupture de stock

                    </div>

                <?php endif; ?>


                <!-- DESCRIPTION -->

                <div class="produit-description">

                    <h2>
                        Description
                    </h2>

                    <p>
                        <?= nl2br(
                            htmlspecialchars($produit["description"])
                        ) ?>
                    </p>

                </div>


                <!-- QUANTITÉ -->

                <?php if ((int) $produit["stock"] > 0): ?>

                    <div class="quantite-container">

                        <label for="quantite">
                            Quantité
                        </label>

                        <div class="quantite-control">

                            <button
                                type="button"
                                id="moins"
                                aria-label="Diminuer la quantité"
                            >
                                <i class="fa-solid fa-minus"></i>
                            </button>

                            <input
                                type="number"
                                id="quantite"
                                value="1"
                                min="1"
                                max="<?= (int) $produit["stock"] ?>"
                            >

                            <button
                                type="button"
                                id="plus"
                                aria-label="Augmenter la quantité"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </button>

                        </div>

                    </div>


                    <!-- AJOUTER AU PANIER -->

                    <button
                        type="button"
                        class="btn-ajouter-panier"
                        id="ajouterPanier"
                        data-id="<?= (int) $produit["id"] ?>"
                        data-nom="<?= htmlspecialchars($produit["nom"], ENT_QUOTES) ?>"
                        data-prix="<?= (float) $produit["prix"] ?>"
                        data-image="<?= htmlspecialchars($produit["image"] ?? "", ENT_QUOTES) ?>"
                    >
                     

                        <i class="fa-solid fa-cart-shopping"></i>

                        Ajouter au panier

                    </button>

                <?php else: ?>

                    <button
                        type="button"
                        class="btn-ajouter-panier indisponible"
                        disabled
                    >

                        <i class="fa-solid fa-ban"></i>

                        Produit indisponible

                    </button>

                <?php endif; ?>


                <!-- RETOUR -->

                <a
                    href="produits.php"
                    class="retour-produits"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Retour aux produits

                </a>

            </div>

        </section>

        <?php if (!empty($produitsSimilaires)): ?>

<section class="produits-similaires">

    <div class="container">

        <div class="similaires-header">

            <span class="similaires-eyebrow">
                À DÉCOUVRIR
            </span>

            <h2>
                Vous pourriez aussi aimer
            </h2>

            <p>
                Découvrez d'autres saveurs de Temps Libre.
            </p>

        </div>


        <div class="similaires-grid">

            <?php foreach ($produitsSimilaires as $similaire): ?>

                <article class="produit-similaire">

                    <!-- IMAGE -->

                    <a
                        href="produit.php?id=<?= (int) $similaire["id"] ?>"
                        class="similaire-image"
                    >

                        <?php
                        $imageSimilaire = trim(
                            $similaire["image"] ?? ""
                        );

                        $cheminImageSimilaire =
                            __DIR__ . "/images/" . $imageSimilaire;
                        ?>

                        <?php if (
                            !empty($imageSimilaire)
                            && file_exists($cheminImageSimilaire)
                        ): ?>

                            <img
                                src="images/<?= rawurlencode($imageSimilaire) ?>"
                                alt="<?= htmlspecialchars($similaire["nom"]) ?>"
                            >

                        <?php else: ?>

                            <div class="similaire-placeholder">

                                <i class="fa-solid fa-bottle-water"></i>

                            </div>

                        <?php endif; ?>


                        <!-- STOCK -->

                        <?php if ((int) $similaire["stock"] > 0): ?>

                            <span class="similaire-stock">
                                Disponible
                            </span>

                        <?php else: ?>

                            <span class="similaire-stock rupture">
                                Rupture
                            </span>

                        <?php endif; ?>

                    </a>


                    <!-- INFORMATIONS -->

                    <div class="similaire-body">

                        <?php if (!empty($similaire["categorie"])): ?>

                            <span class="similaire-categorie">
                                <?= htmlspecialchars(
                                    $similaire["categorie"]
                                ) ?>
                            </span>

                        <?php endif; ?>


                        <h3>
                            <?= htmlspecialchars(
                                $similaire["nom"]
                            ) ?>
                        </h3>


                        <p>
                            <?= htmlspecialchars(
                                mb_strimwidth(
                                    $similaire["description"] ?? "",
                                    0,
                                    100,
                                    "..."
                                )
                            ) ?>
                        </p>


                        <div class="similaire-footer">

                            <div class="similaire-prix">

                                <?= number_format(
                                    (float) $similaire["prix"],
                                    0,
                                    ",",
                                    " "
                                ) ?>

                                <span>
                                    FCFA
                                </span>

                            </div>


                            <a
                                href="produit.php?id=<?= (int) $similaire["id"] ?>"
                                class="btn-similaire"
                            >

                                Voir

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>

    </div>

</main>


<?php require_once __DIR__ . "/includes/footer.php"; ?>


<script src="js/produit.js"></script>

</body>

</html>