<?php

/* =========================================================
   PROTECTION ADMIN
   ========================================================= */

require_once __DIR__ . '/auth.php';


/* =========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================= */

require_once __DIR__ . '/../config/databases.php';


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

    die(
        "Erreur lors de la récupération des produits."
    );

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

    <title>
        Produits | Administration Temps Libre
    </title>


    <!-- Google Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

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
    href="../css/admin-products.css"
>
    >

</head>


<body>


<div class="admin-products-container">


    <!-- =================================================
         HEADER
         ================================================= -->

    <header class="admin-products-header">


        <div class="admin-brand">


            <img
                src="../images/logo/logo.png"
                alt="Logo Temps Libre"
                class="admin-logo"
            >


            <div>

                <strong>
                    Temps Libre
                </strong>

                <span>
                    Administration
                </span>

            </div>


        </div>


        <div class="admin-actions">


            <a
                href="index.php"
                class="btn-dashboard"
            >

                <i class="fa-solid fa-house"></i>

                Tableau de bord

            </a>


            <a
                href="deconnexion.php"
                class="btn-logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Déconnexion

            </a>


        </div>


    </header>



    <!-- =================================================
         CONTENU
         ================================================= -->

    <main class="products-main">


        <div class="products-title">


            <div>

                <span>
                    BOUTIQUE
                </span>

                <h1>
                    Gestion des produits
                </h1>

                <p>
                    Gérez les produits disponibles dans votre boutique.
                </p>

            </div>


            <a
                href="ajouter-produit.php"
                class="btn-add-product"
            >

                <i class="fa-solid fa-plus"></i>

                Ajouter un produit

            </a>


        </div>



        <!-- =================================================
             PRODUITS
             ================================================= -->

        <section class="products-card">


            <div class="products-card-header">


                <div>

                    <h2>
                        Tous les produits
                    </h2>

                    <p>
                        <?= count($produits) ?>
                        produit<?= count($produits) > 1 ? 's' : '' ?>
                        enregistré<?= count($produits) > 1 ? 's' : '' ?>
                    </p>

                </div>


            </div>



            <?php if (!empty($produits)): ?>


                <div class="products-table-wrapper">


                    <table class="products-table">


                        <thead>

                            <tr>

                                <th>
                                    Produit
                                </th>

                                <th>
                                    Catégorie
                                </th>

                                <th>
                                    Prix
                                </th>

                                <th>
                                    Stock
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php foreach ($produits as $produit): ?>


                                <tr>


                                    <!-- PRODUIT -->

                                    <td>

                                        <div class="product-cell">

<div class="product-image">

    <?php
    $image = trim($produit["image"]);
    $fichier = __DIR__ . "/../images/" . $image;

    if (file_exists($fichier)):
    ?>

        <img
            src="../images/<?= htmlspecialchars($image) ?>"
            alt="<?= htmlspecialchars($produit["nom"]) ?>"
        >

    <?php else: ?>

        <i class="fa-solid fa-image"></i>

    <?php endif; ?>

</div>

                                            


                                            <div>

                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $produit["nom"]
                                                    ) ?>

                                                </strong>


                                                <span>

                                                    <?= htmlspecialchars(
                                                        mb_substr(
                                                            $produit["description"],
                                                            0,
                                                            60
                                                        )
                                                    ) ?>

                                                    <?php if (
                                                        mb_strlen(
                                                            $produit["description"]
                                                        ) > 60
                                                    ): ?>

                                                        ...

                                                    <?php endif; ?>

                                                </span>

                                            </div>


                                        </div>

                                    </td>



                                    <!-- CATÉGORIE -->

                                    <td>

                                        <span class="product-category">

                                            <?= htmlspecialchars(
                                                $produit["categorie"]
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- PRIX -->

                                    <td>

                                        <strong class="product-price">

                                            <?= number_format(
                                                (float) $produit["prix"],
                                                0,
                                                ",",
                                                " "
                                            ) ?>

                                            FCFA

                                        </strong>

                                    </td>



                                    <!-- STOCK -->

                                    <td>

                                        <?php if (
                                            $produit["stock"] > 0
                                        ): ?>

                                            <span class="stock available">

                                                <?= (int) $produit["stock"] ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="stock empty">

                                                Rupture

                                            </span>

                                        <?php endif; ?>

                                    </td>



                                    <!-- DATE -->

                                    <td>

                                        <span class="product-date">

                                            <?= date(
                                                "d/m/Y",
                                                strtotime(
                                                    $produit["date_creation"]
                                                )
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- ACTIONS -->

                                    <td>

                                        <div class="product-actions">


                                            <a
                                                href="modifier-produit.php?id=<?= (int) $produit["id"] ?>"
                                                class="btn-edit"
                                                title="Modifier"
                                            >

                                                <i class="fa-solid fa-pen"></i>

                                            </a>


                                            <a
                                                href="supprimer-produit.php?id=<?= (int) $produit["id"] ?>"
                                                class="btn-delete"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');"
                                            >

                                                <i class="fa-regular fa-trash-can"></i>

                                            </a>


                                        </div>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>


                    </table>


                </div>


            <?php else: ?>


                <div class="empty-products">


                    <i class="fa-solid fa-box-open"></i>


                    <h3>
                        Aucun produit
                    </h3>


                    <p>
                        Aucun produit n'est actuellement enregistré.
                    </p>


                    <a
                        href="ajouter-produit.php"
                        class="btn-add-product"
                    >

                        <i class="fa-solid fa-plus"></i>

                        Ajouter le premier produit

                    </a>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>


</body>

</html>