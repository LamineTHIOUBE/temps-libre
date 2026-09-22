<?php

require_once __DIR__ . "/config/databases.php";

$messageErreur = "";
$commandeId = null;


/* =========================================
   TRAITEMENT DE LA COMMANDE
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nomClient = trim($_POST["nom_client"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");
    $adresse = trim($_POST["adresse"] ?? "");
    $ville = trim($_POST["ville"] ?? "");

    $panierJson = $_POST["panier_json"] ?? "";


    /* =====================================
       VALIDATION DES INFORMATIONS CLIENT
    ====================================== */

    if (
        $nomClient === "" ||
        $email === "" ||
        $telephone === "" ||
        $adresse === ""
    ) {

        $messageErreur =
            "Veuillez remplir tous les champs obligatoires.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $messageErreur =
            "Veuillez saisir une adresse email valide.";

    } elseif ($panierJson === "") {

        $messageErreur =
            "Votre panier est vide.";

    } else {

        $panier = json_decode($panierJson, true);


        /* =================================
           VÉRIFICATION DU PANIER
        ================================== */

        if (!is_array($panier) || count($panier) === 0) {

            $messageErreur =
                "Votre panier est vide ou invalide.";

        } else {

            try {

                $conn->beginTransaction();


                $sousTotal = 0;

                $detailsCommande = [];


                /* =============================
                   VÉRIFICATION DES PRODUITS
                ============================== */

                foreach ($panier as $article) {

                    $produitId = filter_var(
                        $article["id"] ?? null,
                        FILTER_VALIDATE_INT
                    );

                    $quantite = filter_var(
                        $article["quantite"] ?? null,
                        FILTER_VALIDATE_INT
                    );


                    if (!$produitId || !$quantite || $quantite < 1) {

                        throw new Exception(
                            "Un produit du panier est invalide."
                        );
                    }


                    /*
                     * On récupère les vraies informations
                     * depuis la base de données.
                     *
                     * FOR UPDATE bloque la ligne pendant
                     * la transaction afin d'éviter les
                     * problèmes de stock.
                     */

                    $stmt = $conn->prepare("
                        SELECT
                            id,
                            nom,
                            prix,
                            stock
                        FROM produits
                        WHERE id = ?
                        FOR UPDATE
                    ");

                    $stmt->execute([$produitId]);

                    $produit = $stmt->fetch(PDO::FETCH_ASSOC);


                    if (!$produit) {

                        throw new Exception(
                            "Un produit de votre panier n'existe plus."
                        );
                    }


                    /* =============================
                       VÉRIFICATION DU STOCK
                    ============================== */

                    if ($quantite > (int) $produit["stock"]) {

                        throw new Exception(
                            "Stock insuffisant pour le produit : "
                            . $produit["nom"]
                        );
                    }


                    /* =============================
                       CALCUL DU PRIX RÉEL
                    ============================== */

                    $prixUnitaire = (float) $produit["prix"];

                    $totalLigne = $prixUnitaire * $quantite;

                    $sousTotal += $totalLigne;


                    $detailsCommande[] = [

                        "produit_id" => (int) $produit["id"],

                        "nom_produit" => $produit["nom"],

                        "prix_unitaire" => $prixUnitaire,

                        "quantite" => $quantite,

                        "total_ligne" => $totalLigne

                    ];
                }


                /* =============================
                   FRAIS DE LIVRAISON
                ============================== */

                $livraison = 1000;

                $total = $sousTotal + $livraison;


                /* =============================
                   CRÉATION DE LA COMMANDE
                ============================== */

                $stmtCommande = $conn->prepare("
                    INSERT INTO commandes (
                        nom_client,
                        email,
                        telephone,
                        adresse,
                        ville,
                        sous_total,
                        livraison,
                        total,
                        statut
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')
                ");


                $stmtCommande->execute([

                    $nomClient,

                    $email,

                    $telephone,

                    $adresse,

                    $ville !== "" ? $ville : null,

                    $sousTotal,

                    $livraison,

                    $total

                ]);


                $commandeId = $conn->lastInsertId();


                /* =============================
                   INSERTION DES DÉTAILS
                ============================== */

                $stmtDetail = $conn->prepare("
                    INSERT INTO details_commande (
                        commande_id,
                        produit_id,
                        nom_produit,
                        prix_unitaire,
                        quantite,
                        total_ligne
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ");


                /* =============================
                   MISE À JOUR DU STOCK
                ============================== */

                $stmtStock = $conn->prepare("
                    UPDATE produits
                    SET stock = stock - ?
                    WHERE id = ?
                ");


                foreach ($detailsCommande as $detail) {

                    $stmtDetail->execute([

                        $commandeId,

                        $detail["produit_id"],

                        $detail["nom_produit"],

                        $detail["prix_unitaire"],

                        $detail["quantite"],

                        $detail["total_ligne"]

                    ]);


                    $stmtStock->execute([

                        $detail["quantite"],

                        $detail["produit_id"]

                    ]);
                }


                /* =============================
                   VALIDATION DE LA TRANSACTION
                ============================== */

                $conn->commit();


                /*
                 * Redirection pour éviter qu'un
                 * rafraîchissement recrée la commande.
                 */

                header(
                    "Location: order.php?success=1&id="
                    . urlencode($commandeId)
                );

                exit;


            } catch (Exception $e) {

                if ($conn->inTransaction()) {

                    $conn->rollBack();

                }

                $messageErreur =
                    $e->getMessage();

            }
        }
    }
}


/* =========================================
   RÉCUPÉRATION DU NUMÉRO DE COMMANDE
========================================= */

$commandeSucces = isset($_GET["success"])
    && $_GET["success"] === "1";

$commandeIdSucces = $_GET["id"] ?? null;

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Finaliser ma commande | Temps Libre</title>

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

    <!-- CSS de la page -->
   <link rel="stylesheet" href="css/commande.css">

</head>

<body>


<?php require_once __DIR__ . "/includes/header.php"; ?>


<main class="commande-page">

    <div class="container">

    <?php if ($messageErreur !== ""): ?>

    <div class="commande-message commande-message-erreur">

        <i class="fa-solid fa-circle-exclamation"></i>

        <span>
            <?= htmlspecialchars($messageErreur) ?>
        </span>

    </div>

<?php endif; ?>


<?php if ($commandeSucces && $commandeIdSucces): ?>

    <div
        class="commande-message commande-message-succes"
        id="commande-succes"
    >

        <i class="fa-solid fa-circle-check"></i>

        <div>

            <strong>
                Commande enregistrée avec succès !
            </strong>

            <p>
                Votre numéro de commande est :
                <strong>
                    #<?= htmlspecialchars($commandeIdSucces) ?>
                </strong>
            </p>

        </div>

    </div>

<?php endif; ?>


        <!-- =========================================
             EN-TÊTE DE LA PAGE
        ========================================== -->

        <div class="commande-header">

            <span class="commande-sur-titre">
                Finalisation
            </span>

            <h1>
                Finaliser ma commande
            </h1>

            <p>
                Renseignez vos informations puis vérifiez votre commande
                avant de la confirmer.
            </p>

        </div>


        <!-- =========================================
             CONTENU PRINCIPAL
        ========================================== -->

        <div class="commande-layout">


            <!-- =====================================
                 COLONNE GAUCHE
                 INFORMATIONS CLIENT
            ====================================== -->

            <section class="commande-formulaire">

                <div class="commande-bloc-titre">

                    <div class="commande-bloc-icone">

                        <i class="fa-solid fa-user"></i>

                    </div>

                    <div>

                        <h2>
                            Informations de livraison
                        </h2>

                        <p>
                            Indiquez les informations nécessaires
                            pour recevoir votre commande.
                        </p>

                    </div>

                </div>


                <form
                    id="commande-form"
                    method="POST"
                    action=""
                >


                    <!-- Nom complet -->

                    <div class="form-group">

                        <label for="nom_client">

                            Nom complet

                            <span>*</span>

                        </label>

                        <div class="input-container">

                            <i class="fa-solid fa-user"></i>

                            <input
                                type="text"
                                id="nom_client"
                                name="nom_client"
                                placeholder="Votre nom complet"
                                required
                            >

                        </div>

                    </div>


                    <!-- Email -->

                    <div class="form-group">

                        <label for="email">

                            Adresse email

                            <span>*</span>

                        </label>

                        <div class="input-container">

                            <i class="fa-solid fa-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="exemple@email.com"
                                required
                            >

                        </div>

                    </div>


                    <!-- Téléphone -->

                    <div class="form-group">

                        <label for="telephone">

                            Téléphone

                            <span>*</span>

                        </label>

                        <div class="input-container">

                            <i class="fa-solid fa-phone"></i>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                placeholder="Ex : 77 123 45 67"
                                required
                            >

                        </div>

                    </div>


                    <!-- Adresse -->

                    <div class="form-group">

                        <label for="adresse">

                            Adresse de livraison

                            <span>*</span>

                        </label>

                        <div class="input-container">

                            <i class="fa-solid fa-location-dot"></i>

                            <input
                                type="text"
                                id="adresse"
                                name="adresse"
                                placeholder="Quartier, rue, numéro..."
                                required
                            >

                        </div>

                    </div>


                    <!-- Ville -->

                    <div class="form-group">

                        <label for="ville">

                            Ville

                        </label>

                        <div class="input-container">

                            <i class="fa-solid fa-city"></i>

                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                placeholder="Ex : Dakar"
                            >

                        </div>

                    </div>


                    <!-- Champs cachés utilisés plus tard -->

                    <input
                        type="hidden"
                        id="panier_json"
                        name="panier_json"
                    >

                    <input
                        type="hidden"
                        id="sous_total"
                        name="sous_total"
                    >

                    <input
                        type="hidden"
                        id="total"
                        name="total"
                    >


                    <!-- Bouton confirmation -->

                    <button
                        type="submit"
                        class="btn-confirmer-commande"
                        id="btn-confirmer-commande"
                    >

                        <i class="fa-solid fa-check"></i>

                        Confirmer la commande

                    </button>


                </form>

            </section>


            <!-- =====================================
                 COLONNE DROITE
                 RÉCAPITULATIF
            ====================================== -->

            <aside class="commande-recapitulatif">

                <div class="commande-bloc-titre">

                    <div class="commande-bloc-icone">

                        <i class="fa-solid fa-bag-shopping"></i>

                    </div>

                    <div>

                        <h2>
                            Votre commande
                        </h2>

                        <p>
                            Vérifiez les produits sélectionnés.
                        </p>

                    </div>

                </div>


                <!-- Produits ajoutés par JavaScript -->

                <div id="commande-produits">

                </div>


                <!-- Aucun produit -->

                <div
                    id="commande-vide"
                    class="commande-vide"
                    style="display: none;"
                >

                    <i class="fa-solid fa-cart-shopping"></i>

                    <p>
                        Votre panier est vide.
                    </p>

                    <a href="produits.php">

                        Voir nos produits

                    </a>

                </div>


                <!-- Totaux -->

                <div
                    id="commande-totaux"
                    class="commande-totaux"
                >

                    <div class="commande-total-ligne">

                        <span>
                            Sous-total
                        </span>

                        <strong id="affichage-sous-total">
                            0 FCFA
                        </strong>

                    </div>


                    <div class="commande-total-ligne">

                        <span>
                            Livraison
                        </span>

                        <strong>
                            1 000 FCFA
                        </strong>

                    </div>


                    <div class="commande-separateur"></div>


                    <div class="commande-total-final">

                        <span>
                            Total
                        </span>

                        <strong id="affichage-total">
                            0 FCFA
                        </strong>

                    </div>

                </div>


                <!-- Retour panier -->

                <a
                    href="panier.php"
                    class="retour-panier"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Retour au panier

                </a>


            </aside>


        </div>

    </div>

</main>


<?php require_once __DIR__ . "/includes/footer.php"; ?>


<!-- JavaScript futur -->
<script src="js/order.js"></script>


</body>

</html>