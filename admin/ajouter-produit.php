<?php

/* =========================================================
   AUTHENTIFICATION
   ========================================================= */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/databases.php';


/* =========================================================
   VARIABLES
   ========================================================= */

$erreur = "";
$succes = "";

$nom = "";
$description = "";
$prix = "";
$stock = "";
$categorie = "";


/* =========================================================
   TRAITEMENT DU FORMULAIRE
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $prix = trim($_POST["prix"] ?? "");
    $stock = trim($_POST["stock"] ?? "");
    $categorie = trim($_POST["categorie"] ?? "");


    /* =====================================================
       VÉRIFICATION
       ===================================================== */

    if (
        $nom === "" ||
        $description === "" ||
        $prix === "" ||
        $stock === "" ||
        $categorie === ""
    ) {

        $erreur = "Veuillez remplir tous les champs obligatoires.";

    }

    elseif (!is_numeric($prix) || $prix < 0) {

        $erreur = "Veuillez saisir un prix valide.";

    }

    elseif (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== "0") {

        $erreur = "Veuillez saisir un stock valide.";

    }

    elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

        $erreur = "Veuillez sélectionner une image.";

    }

    else {

        /* =================================================
           IMAGE
           ================================================= */

        $image = $_FILES["image"];

        $nomImage = basename($image["name"]);

        $extension = strtolower(
            pathinfo($nomImage, PATHINFO_EXTENSION)
        );


        /* =================================================
           EXTENSIONS AUTORISÉES
           ================================================= */

        $extensionsAutorisees = [
            "png",
            "jpg",
            "jpeg",
            "webp"
        ];


        if (!in_array($extension, $extensionsAutorisees, true)) {

            $erreur =
                "Format d'image non autorisé. Utilisez PNG, JPG, JPEG ou WEBP.";

        }

        elseif ($image["size"] > 5 * 1024 * 1024) {

            $erreur =
                "L'image ne doit pas dépasser 5 Mo.";

        }

        else {

            /* =============================================
               DOSSIER DES IMAGES
               ============================================= */

            $dossierImages =
                __DIR__ . '/../images/';


            if (!is_dir($dossierImages)) {

                mkdir(
                    $dossierImages,
                    0755,
                    true
                );

            }


            /* =============================================
               NOM UNIQUE
               ============================================= */

            $nomFichier =
                uniqid('produit_', true)
                . '.'
                . $extension;


            $cheminDestination =
                $dossierImages
                . $nomFichier;


            /* =============================================
               DÉPLACEMENT DE L'IMAGE
               ============================================= */

            if (
                move_uploaded_file(
                    $image["tmp_name"],
                    $cheminDestination
                )
            ) {

                try {

                    /* =====================================
                       INSERTION
                       ===================================== */

                    $sql = "
                        INSERT INTO produits (
                            nom,
                            description,
                            prix,
                            image,
                            stock,
                            categorie
                        )
                        VALUES (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                        )
                    ";


                    $stmt =
                        $conn->prepare($sql);


                    $stmt->execute([
                        $nom,
                        $description,
                        $prix,
                        $nomFichier,
                        $stock,
                        $categorie
                    ]);


                    $succes =
                        "Produit ajouté avec succès.";


                    /* =====================================
                       RÉINITIALISER LE FORMULAIRE
                       ===================================== */

                    $nom = "";
                    $description = "";
                    $prix = "";
                    $stock = "";
                    $categorie = "";


                }

                catch (PDOException $e) {

                    /* Supprimer l'image si
                       l'insertion échoue */

                    if (file_exists($cheminDestination)) {

                        unlink($cheminDestination);

                    }


                    $erreur =
                        "Erreur lors de l'enregistrement du produit.";

                }

            }

            else {

                $erreur =
                    "Impossible d'enregistrer l'image.";

            }

        }

    }

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
        Ajouter un produit | Administration Temps Libre
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
        href="../css/admin-add-product.css"
    >

</head>


<body>


<div class="admin-product-container">


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="admin-product-header">


        <div class="admin-product-brand">

            <img
                src="../images/logo/logo.png"
                alt="Logo Temps Libre"
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


        <a
            href="products.php"
            class="btn-retour"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Retour aux produits

        </a>


    </header>



    <!-- =====================================================
         CONTENU
         ===================================================== -->

    <main class="admin-product-main">


        <div class="product-page-title">

            <span>
                Gestion des produits
            </span>

            <h1>
                Ajouter un produit
            </h1>

            <p>
                Ajoutez un nouveau produit à la boutique Temps Libre.
            </p>

        </div>



        <!-- =================================================
             MESSAGES
             ================================================= -->

        <?php if ($erreur !== ""): ?>

            <div class="alert alert-error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <?= htmlspecialchars($erreur) ?>

            </div>

        <?php endif; ?>


        <?php if ($succes !== ""): ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check"></i>

                <?= htmlspecialchars($succes) ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             FORMULAIRE
             ================================================= -->

        <form
            method="POST"
            enctype="multipart/form-data"
            class="product-form"
        >


            <!-- NOM -->

            <div class="form-group">

                <label for="nom">

                    Nom du produit
                    <span>*</span>

                </label>

                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= htmlspecialchars($nom) ?>"
                    placeholder="Ex : Jus de Bouye"
                    required
                >

            </div>



            <!-- DESCRIPTION -->

            <div class="form-group">

                <label for="description">

                    Description
                    <span>*</span>

                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Decrivez le produit..."
                    required
                ><?= htmlspecialchars($description) ?></textarea>

            </div>



            <!-- PRIX + STOCK -->

            <div class="form-row">


                <div class="form-group">

                    <label for="prix">

                        Prix
                        <span>*</span>

                    </label>

                    <div class="input-with-icon">

                        <i class="fa-solid fa-money-bill-wave"></i>

                        <input
                            type="number"
                            id="prix"
                            name="prix"
                            value="<?= htmlspecialchars($prix) ?>"
                            min="0"
                            step="1"
                            placeholder="Ex : 1500"
                            required
                        >

                        <span class="unit">
                            FCFA
                        </span>

                    </div>

                </div>



                <div class="form-group">

                    <label for="stock">

                        Stock
                        <span>*</span>

                    </label>

                    <div class="input-with-icon">

                        <i class="fa-solid fa-boxes-stacked"></i>

                        <input
                            type="number"
                            id="stock"
                            name="stock"
                            value="<?= htmlspecialchars($stock) ?>"
                            min="0"
                            step="1"
                            placeholder="Ex : 50"
                            required
                        >

                        <span class="unit">
                            unités
                        </span>

                    </div>

                </div>


            </div>



            <!-- CATÉGORIE -->

            <div class="form-group">

                <label for="categorie">

                    Catégorie
                    <span>*</span>

                </label>

                <select
                    id="categorie"
                    name="categorie"
                    required
                >

                    <option value="">
                        Sélectionner une catégorie
                    </option>

                    <option
                        value="Jus naturels"
                        <?= $categorie === "Jus naturels" ? "selected" : "" ?>
                    >
                        Jus naturels
                    </option>

                    <option
                        value="Jus locaux"
                        <?= $categorie === "Jus locaux" ? "selected" : "" ?>
                    >
                        Jus locaux
                    </option>

                    <option
                        value="Produits artisanaux"
                        <?= $categorie === "Produits artisanaux" ? "selected" : "" ?>
                    >
                        Produits artisanaux
                    </option>

                </select>

            </div>



            <!-- IMAGE -->

            <div class="form-group">

                <label for="image">

                    Image du produit
                    <span>*</span>

                </label>

                <div class="file-input">

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".png,.jpg,.jpeg,.webp"
                        required
                    >

                    <label for="image">

                        <i class="fa-solid fa-cloud-arrow-up"></i>

                        <strong>
                            Choisir une image
                        </strong>

                        <small>
                            PNG, JPG, JPEG ou WEBP — 5 Mo maximum
                        </small>

                    </label>

                </div>

            </div>



            <!-- ACTIONS -->

            <div class="form-actions">

                <a
                    href="products.php"
                    class="btn-annuler"
                >

                    Annuler

                </a>


                <button
                    type="submit"
                    class="btn-enregistrer"
                >

                    <i class="fa-solid fa-plus"></i>

                    Ajouter le produit

                </button>

            </div>


        </form>


    </main>


</div>


</body>

</html>