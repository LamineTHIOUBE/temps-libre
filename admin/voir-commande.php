<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/databases.php';

/* =========================================================
   RÉCUPÉRÉR L'ID DE LA COMMANDE
========================================================= */

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: commandes.php');
    exit;
}


/* =========================================================
   RÉCUPÉRER LA COMMANDE
========================================================= */

$stmt = $conn->prepare("
    SELECT
        id,
        nom_client,
        email,
        telephone,
        adresse,
        ville,
        sous_total,
        livraison,
        total,
        statut,
        date_commande
    FROM commandes
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$commande = $stmt->fetch(PDO::FETCH_ASSOC);


/* =========================================================
   VÉRIFIER QUE LA COMMANDE EXISTE
========================================================= */

if (!$commande) {
    header('Location: commandes.php');
    exit;
}

/* =========================================================
   MODIFICATION DU STATUT
========================================================= */

$statutsAutorises = [
    'en_attente',
    'confirmee',
    'en_preparation',
    'livree',
    'annulee'
];

/* Création du token CSRF */
if (empty($_SESSION['csrf_commande'])) {
    $_SESSION['csrf_commande'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_commande'];

$messageSucces = '';
$messageErreur = '';

/* Message après redirection */
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $messageSucces = "Le statut de la commande a été modifié avec succès.";
}


/* Traitement du formulaire */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';
    $nouveauStatut = $_POST['nouveau_statut'] ?? '';

    /* Vérification CSRF */
    if (!hash_equals($csrfToken, $token)) {

        $messageErreur = "Requête invalide. Veuillez réessayer.";

    } elseif (!in_array($nouveauStatut, $statutsAutorises, true)) {

        $messageErreur = "Statut invalide.";

    } else {

        try {

            $conn->beginTransaction();

            /* Récupérer le statut actuel */
            $stmtCommande = $conn->prepare("
                SELECT id, statut
                FROM commandes
                WHERE id = ?
                FOR UPDATE
            ");

            $stmtCommande->execute([$id]);

            $commandeActuelle = $stmtCommande->fetch(PDO::FETCH_ASSOC);

            if (!$commandeActuelle) {
                throw new Exception("Commande introuvable.");
            }

            $ancienStatut = $commandeActuelle['statut'];

            /* Une commande livrée ou annulée est définitive */
            if ($ancienStatut === 'livree' || $ancienStatut === 'annulee') {

                throw new Exception(
                    "Cette commande est déjà " .
                    ($ancienStatut === 'livree' ? "livrée" : "annulée") .
                    " et ne peut plus être modifiée."
                );
            }

            /* Transitions autorisées */
            $transitions = [
                'en_attente' => ['confirmee', 'annulee'],
                'confirmee' => ['en_preparation', 'annulee'],
                'en_preparation' => ['livree', 'annulee']
            ];

            if (
                $nouveauStatut !== $ancienStatut &&
                !in_array(
                    $nouveauStatut,
                    $transitions[$ancienStatut] ?? [],
                    true
                )
            ) {
                throw new Exception(
                    "Cette transition de statut n'est pas autorisée."
                );
            }

            /*
             * Si la commande est annulée,
             * les produits sont remis en stock.
             */
            if ($nouveauStatut === 'annulee') {

                $stmtDetails = $conn->prepare("
                    SELECT produit_id, quantite
                    FROM details_commande
                    WHERE commande_id = ?
                    ORDER BY produit_id
                    FOR UPDATE
                ");

                $stmtDetails->execute([$id]);

                $detailsStock = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

                foreach ($detailsStock as $detail) {

                    $stmtProduit = $conn->prepare("
                        SELECT id
                        FROM produits
                        WHERE id = ?
                        FOR UPDATE
                    ");

                    $stmtProduit->execute([
                        $detail['produit_id']
                    ]);

                    if (!$stmtProduit->fetch()) {
                        throw new Exception(
                            "Le produit associé à la commande est introuvable."
                        );
                    }

                    $stmtStock = $conn->prepare("
                        UPDATE produits
                        SET stock = stock + ?
                        WHERE id = ?
                    ");

                    $stmtStock->execute([
                        $detail['quantite'],
                        $detail['produit_id']
                    ]);
                }
            }

            /* Mise à jour du statut */
            $stmtUpdate = $conn->prepare("
                UPDATE commandes
                SET statut = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $nouveauStatut,
                $id
            ]);

            $conn->commit();

            /* Redirection pour éviter une double soumission */
            header(
                "Location: voir-commande.php?id=" .
                $id .
                "&success=1"
            );

            exit;

        } catch (Exception $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $messageErreur = $e->getMessage();
        }
    }
}

/* =========================================================
   RÉCUPÉRER LES PRODUITS DE LA COMMANDE
========================================================= */

$stmtDetails = $conn->prepare("
    SELECT
        id,
        produit_id,
        nom_produit,
        prix_unitaire,
        quantite,
        total_ligne
    FROM details_commande
    WHERE commande_id = ?
    ORDER BY id ASC
");

$stmtDetails->execute([$id]);

$details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   STATUT
========================================================= */

function libelleStatutCommande(string $statut): string
{
    switch ($statut) {

        case 'en_attente':
            return 'En attente';

        case 'confirmee':
            return 'Confirmée';

        case 'en_preparation':
            return 'En préparation';

        case 'livree':
            return 'Livrée';

        case 'annulee':
            return 'Annulée';

        default:
            return $statut;
    }
}


function classeStatutCommande(string $statut): string
{
    switch ($statut) {

        case 'en_attente':
            return 'statut-attente';

        case 'confirmee':
            return 'statut-confirmee';

        case 'en_preparation':
            return 'statut-preparation';

        case 'livree':
            return 'statut-livree';

        case 'annulee':
            return 'statut-annulee';

        default:
            return '';
    }
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Commande #<?= (int) $commande['id'] ?> - Temps Libre
    </title>


    <!-- Google Fonts -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- CSS général -->

    <link
        rel="stylesheet"
        href="../css/style.css">


    <!-- CSS détail commande -->

    <link
        rel="stylesheet"
        href="../css/admin-voir-commande.css">

</head>


<body>


<div class="admin-container">


    <!-- =====================================================
         HEADER ADMIN
    ====================================================== -->

    <header class="admin-header">

        <div class="admin-logo">

            <img
                src="../images/logo/logo.png"
                alt="Temps Libre">

        </div>


        <div class="admin-header-actions">

            <a
                href="commandes.php"
                class="btn-retour">

                <i class="fa-solid fa-arrow-left"></i>

                Retour aux commandes

            </a>

            <button type="button" class="btn-imprimer-commande" onclick="window.print()">
    <i class="fa-solid fa-print"></i>
    Imprimer
</button>


            <a
                href="logout.php"
                class="btn-deconnexion">

                <i class="fa-solid fa-right-from-bracket"></i>

                Déconnexion

            </a>


            <div class="admin-user">

                <i class="fa-solid fa-user-circle"></i>

                <span>
                    <?= htmlspecialchars(
                        $_SESSION['admin_nom'] ?? 'Administrateur'
                    ) ?>
                </span>

            </div>

        </div>

    </header>



    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <main class="admin-main">


        <!-- =================================================
             TITRE
        ================================================== -->

       <div class="admin-title-block">

    <div>

        <h1>
            <i class="fa-solid fa-receipt"></i>
            Commande #<?= (int) $commande['id'] ?>
        </h1>

        <p>
            Détails et informations de la commande.
        </p>

    </div>

    <div class="commande-statut-header">

        <span
            class="statut-badge <?= classeStatutCommande($commande['statut']) ?>">

            <?= libelleStatutCommande($commande['statut']) ?>

        </span>

    </div>

</div>


<!-- =====================================================
     MESSAGES
====================================================== -->

<?php if (!empty($messageSucces)): ?>

    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= htmlspecialchars($messageSucces) ?>
    </div>

<?php endif; ?>


<?php if (!empty($messageErreur)): ?>

    <div class="alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <?= htmlspecialchars($messageErreur) ?>
    </div>

<?php endif; ?>


<!-- =====================================================
     MODIFICATION DU STATUT
====================================================== -->

<section class="commande-section statut-commande-section">

    <div class="section-header">

        <h2>
            <i class="fa-solid fa-arrows-rotate"></i>
            Modifier le statut
        </h2>

    </div>


    <form
        method="POST"
        action="voir-commande.php?id=<?= (int) $commande['id'] ?>"
        class="statut-form"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken) ?>"
        >


        <div class="statut-form-row">

            <div class="statut-select-container">

                <label for="nouveau_statut">
                    Nouveau statut
                </label>

                <select
    name="nouveau_statut"
    id="nouveau_statut"
    class="form-select"
    required
>

    <option value="" selected disabled>
        -- Choisir un statut --
    </option>

    <option value="en_attente">
        En attente
    </option>

    <option value="confirmee">
        Confirmée
    </option>

    <option value="en_preparation">
        En préparation
    </option>

    <option value="livree">
        Livrée
    </option>

    <option value="annulee">
        Annulée
    </option>

</select>

            </div>


            <div class="statut-button-container">

                <button
                    type="submit"
                    class="btn-modifier-statut"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Modifier le statut

                </button>

            </div>

        </div>

    </form>

</section>



        <!-- =================================================
             INFORMATIONS CLIENT
        ================================================== -->

        <section class="commande-section">

            <div class="section-header">

                <h2>

                    <i class="fa-solid fa-user"></i>

                    Informations du client

                </h2>

            </div>


            <div class="client-details">

                <div class="detail-item">

                    <span class="detail-label">
                        Nom complet
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $commande['nom_client']
                        ) ?>
                    </strong>

                </div>


                <div class="detail-item">

                    <span class="detail-label">
                        Email
                    </span>

                    <strong>

                        <a
                            href="mailto:<?= htmlspecialchars(
                                $commande['email']
                            ) ?>">

                            <?= htmlspecialchars(
                                $commande['email']
                            ) ?>

                        </a>

                    </strong>

                </div>


                <div class="detail-item">

                    <span class="detail-label">
                        Téléphone
                    </span>

                    <strong>

                        <a
                            href="tel:<?= htmlspecialchars(
                                $commande['telephone']
                            ) ?>">

                            <?= htmlspecialchars(
                                $commande['telephone']
                            ) ?>

                        </a>

                    </strong>

                </div>


                <div class="detail-item">

                    <span class="detail-label">
                        Ville
                    </span>

                    <strong>

                        <?= !empty($commande['ville'])
                            ? htmlspecialchars($commande['ville'])
                            : 'Non renseignée'
                        ?>

                    </strong>

                </div>


                <div class="detail-item detail-full">

                    <span class="detail-label">
                        Adresse de livraison
                    </span>

                    <strong>

                        <?= htmlspecialchars(
                            $commande['adresse']
                        ) ?>

                    </strong>

                </div>

            </div>

        </section>



        <!-- =================================================
             PRODUITS
        ================================================== -->

        <section class="commande-section">

            <div class="section-header">

                <h2>

                    <i class="fa-solid fa-box-open"></i>

                    Produits commandés

                </h2>

                <span>
                    <?= count($details) ?> produit(s)
                </span>

            </div>


            <?php if (!empty($details)): ?>

                <div class="table-responsive">

                    <table class="commande-details-table">

                        <thead>

                            <tr>

                                <th>
                                    Produit
                                </th>

                                <th>
                                    Prix unitaire
                                </th>

                                <th>
                                    Quantité
                                </th>

                                <th>
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($details as $detail): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $detail['nom_produit']
                                            ) ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?= number_format(
                                            (float) $detail['prix_unitaire'],
                                            0,
                                            ',',
                                            ' '
                                        ) ?>

                                        FCFA

                                    </td>


                                    <td>

                                        <span class="quantite-badge">

                                            <?= (int) $detail['quantite'] ?>

                                        </span>

                                    </td>


                                    <td>

                                        <strong class="ligne-total">

                                            <?= number_format(
                                                (float) $detail['total_ligne'],
                                                0,
                                                ',',
                                                ' '
                                            ) ?>

                                            FCFA

                                        </strong>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="details-vide">

                    <i class="fa-solid fa-box-open"></i>

                    <p>
                        Aucun produit associé à cette commande.
                    </p>

                </div>

            <?php endif; ?>

        </section>



        <!-- =================================================
             RÉCAPITULATIF
        ================================================== -->

        <section class="commande-section recap-section">

            <div class="section-header">

                <h2>

                    <i class="fa-solid fa-calculator"></i>

                    Récapitulatif

                </h2>

            </div>


            <div class="recap-content">

                <div class="recap-line">

                    <span>
                        Sous-total
                    </span>

                    <strong>

                        <?= number_format(
                            (float) $commande['sous_total'],
                            0,
                            ',',
                            ' '
                        ) ?>

                        FCFA

                    </strong>

                </div>


                <div class="recap-line">

                    <span>
                        Livraison
                    </span>

                    <strong>

                        <?= number_format(
                            (float) $commande['livraison'],
                            0,
                            ',',
                            ' '
                        ) ?>

                        FCFA

                    </strong>

                </div>


                <div class="recap-line recap-total">

                    <span>
                        Total
                    </span>

                    <strong>

                        <?= number_format(
                            (float) $commande['total'],
                            0,
                            ',',
                            ' '
                        ) ?>

                        FCFA

                    </strong>

                </div>

            </div>

        </section>



        <!-- =================================================
             INFORMATIONS COMMANDE
        ================================================== -->

        <section class="commande-section">

            <div class="section-header">

                <h2>

                    <i class="fa-solid fa-circle-info"></i>

                    Informations de la commande

                </h2>

            </div>


            <div class="commande-meta">

                <div class="meta-item">

                    <span>
                        Numéro
                    </span>

                    <strong>
                        #<?= (int) $commande['id'] ?>
                    </strong>

                </div>


                <div class="meta-item">

                    <span>
                        Date
                    </span>

                    <strong>

                        <?= date(
                            'd/m/Y',
                            strtotime($commande['date_commande'])
                        ) ?>

                    </strong>

                </div>


                <div class="meta-item">

                    <span>
                        Heure
                    </span>

                    <strong>

                        <?= date(
                            'H:i',
                            strtotime($commande['date_commande'])
                        ) ?>

                    </strong>

                </div>


                <div class="meta-item">

                    <span>
                        Statut
                    </span>

                    <strong>

                        <?= libelleStatutCommande(
                            $commande['statut']
                        ) ?>

                    </strong>

                </div>

            </div>

        </section>



        <!-- =================================================
             RETOUR
        ================================================== -->

        <div class="commande-actions">

            <a
                href="commandes.php"
                class="btn-retour-commandes">

                <i class="fa-solid fa-arrow-left"></i>

                Retour à la liste des commandes

            </a>

        </div>


    </main>

</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>