<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/databases.php';

/* ================================
   RECHERCHE / FILTRES
================================ */

$recherche = trim($_GET['recherche'] ?? '');
$statut = $_GET['statut'] ?? '';

$statutsAutorises = [
    'en_attente',
    'confirmee',
    'en_preparation',
    'livree',
    'annulee'
];

if (!in_array($statut, $statutsAutorises, true)) {
    $statut = '';
}


/* ================================
   STATISTIQUES
================================ */

$stmtTotal = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
");

$totalCommandes = (int) $stmtTotal->fetchColumn();


$stmtAttente = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
    WHERE statut = 'en_attente'
");

$totalAttente = (int) $stmtAttente->fetchColumn();


$stmtConfirmees = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
    WHERE statut = 'confirmee'
");

$totalConfirmees = (int) $stmtConfirmees->fetchColumn();


$stmtPreparation = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
    WHERE statut = 'en_preparation'
");

$totalPreparation = (int) $stmtPreparation->fetchColumn();


$stmtLivrees = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
    WHERE statut = 'livree'
");

$totalLivrees = (int) $stmtLivrees->fetchColumn();


$stmtAnnulees = $conn->query("
    SELECT COUNT(*) 
    FROM commandes
    WHERE statut = 'annulee'
");

$totalAnnulees = (int) $stmtAnnulees->fetchColumn();


/* ================================
   REQUÊTE COMMANDES
================================ */

$sql = "
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
    WHERE 1 = 1
";

$params = [];


/* Recherche */

if ($recherche !== '') {

    $sql .= "
        AND (
            CAST(id AS CHAR) LIKE ?
            OR nom_client LIKE ?
            OR email LIKE ?
            OR telephone LIKE ?
        )
    ";

    $motCle = '%' . $recherche . '%';

    $params[] = $motCle;
    $params[] = $motCle;
    $params[] = $motCle;
    $params[] = $motCle;
}


/* Filtre statut */

if ($statut !== '') {

    $sql .= " AND statut = ?";

    $params[] = $statut;
}


/* Tri */

$sql .= "
    ORDER BY date_commande DESC
";


$stmt = $conn->prepare($sql);
$stmt->execute($params);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* ================================
   FONCTION STATUT
================================ */

function libelleStatut(string $statut): string
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


function classeStatut(string $statut): string
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

    <title>Gestion des commandes - Temps Libre</title>


    <!-- Google Fonts -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
          rel="stylesheet">


    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">


    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- CSS général -->

    <link rel="stylesheet"
          href="../css/style.css">


    <!-- CSS commandes -->

    <link rel="stylesheet"
          href="../css/admin-commandes.css">

</head>


<body>


<div class="admin-container">


    <!-- ========================================
         HEADER ADMIN
    ======================================== -->

    <header class="admin-header">


        <div class="admin-logo">

            <img src="../images/logo/logo.png"
                 alt="Temps Libre">

        </div>


        <div class="admin-header-actions">


            <a href="../index.php"
               class="btn-retour">

                <i class="fa-solid fa-arrow-left"></i>

                Retour au site

            </a>


            <a href="logout.php"
               class="btn-deconnexion">

                <i class="fa-solid fa-right-from-bracket"></i>

                Déconnexion

            </a>


            <div class="admin-user">

                <i class="fa-solid fa-user-circle"></i>

                <span>
                    <?= htmlspecialchars($_SESSION['admin_nom'] ?? 'Administrateur') ?>
                </span>

            </div>


        </div>


    </header>


    <!-- ========================================
         STATISTIQUES
    ======================================== -->

    <section class="admin-stats">


        <!-- Total -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-cart-shopping"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    Total commandes
                </span>

                <strong>
                    <?= $totalCommandes ?>
                </strong>

            </div>

        </div>


        <!-- En attente -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    En attente
                </span>

                <strong>
                    <?= $totalAttente ?>
                </strong>

            </div>

        </div>


        <!-- Confirmées -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    Confirmées
                </span>

                <strong>
                    <?= $totalConfirmees ?>
                </strong>

            </div>

        </div>


        <!-- Préparation -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-box-open"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    En préparation
                </span>

                <strong>
                    <?= $totalPreparation ?>
                </strong>

            </div>

        </div>


        <!-- Livrées -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-truck"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    Livrées
                </span>

                <strong>
                    <?= $totalLivrees ?>
                </strong>

            </div>

        </div>


        <!-- Annulées -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div class="stat-content">

                <span class="stat-label">
                    Annulées
                </span>

                <strong>
                    <?= $totalAnnulees ?>
                </strong>

            </div>

        </div>


    </section>


    <!-- ========================================
         CONTENU PRINCIPAL
    ======================================== -->

    <main class="admin-main">


        <!-- Titre -->

        <div class="admin-title-block">

            <div>

                <h1>

                    <i class="fa-solid fa-cart-shopping"></i>

                    Gestion des commandes

                </h1>

                <p>
                    Consultez et gérez les commandes de vos clients.
                </p>

            </div>

        </div>


        <!-- ========================================
             FILTRES
        ======================================== -->

        <div class="filters-card">


            <form method="GET"
                  action="commandes.php"
                  class="filters-form">


                <!-- Recherche -->

                <div class="filter-search">

                    <label for="recherche">
                        Recherche
                    </label>

                    <div class="search-input">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            id="recherche"
                            name="recherche"
                            placeholder="N° commande, nom, email, téléphone..."
                            value="<?= htmlspecialchars($recherche) ?>"
                        >

                    </div>

                </div>


                <!-- Statut -->

                <div class="filter-status">

                    <label for="statut">
                        Statut
                    </label>

                    <select
                        id="statut"
                        name="statut"
                    >

                        <option value="">
                            Tous les statuts
                        </option>

                        <option value="en_attente"
                            <?= $statut === 'en_attente' ? 'selected' : '' ?>>
                            En attente
                        </option>

                        <option value="confirmee"
                            <?= $statut === 'confirmee' ? 'selected' : '' ?>>
                            Confirmée
                        </option>

                        <option value="en_preparation"
                            <?= $statut === 'en_preparation' ? 'selected' : '' ?>>
                            En préparation
                        </option>

                        <option value="livree"
                            <?= $statut === 'livree' ? 'selected' : '' ?>>
                            Livrée
                        </option>

                        <option value="annulee"
                            <?= $statut === 'annulee' ? 'selected' : '' ?>>
                            Annulée
                        </option>

                    </select>

                </div>


                <!-- Bouton -->

                <div class="filter-button">

                    <button type="submit">

                        <i class="fa-solid fa-filter"></i>

                        Filtrer

                    </button>

                </div>


            </form>


        </div>


        <!-- ========================================
             TABLEAU COMMANDES
        ======================================== -->

        <div class="messages-card commandes-card">


            <div class="card-header-custom">

                <div>

                    <h2>

                        <i class="fa-solid fa-list"></i>

                        Liste des commandes

                    </h2>

                    <span>
                        <?= count($commandes) ?> commande(s)
                    </span>

                </div>

            </div>


            <?php if (count($commandes) > 0): ?>


                <div class="table-responsive">

                    <table class="admin-table">


                        <thead>

                            <tr>

                                <th>
                                    N°
                                </th>

                                <th>
                                    Client
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php foreach ($commandes as $commande): ?>


                            <tr>


                                <!-- Numéro -->

                                <td>

                                    <strong class="commande-numero">

                                        #<?= (int) $commande['id'] ?>

                                    </strong>

                                </td>


                                <!-- Client -->

                                <td>

                                    <div class="client-info">

                                        <strong>
                                            <?= htmlspecialchars($commande['nom_client']) ?>
                                        </strong>

                                        <?php if (!empty($commande['ville'])): ?>

                                            <small>

                                                <i class="fa-solid fa-location-dot"></i>

                                                <?= htmlspecialchars($commande['ville']) ?>

                                            </small>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <!-- Contact -->

                                <td>

                                    <div class="contact-info">

                                        <span>

                                            <i class="fa-solid fa-envelope"></i>

                                            <?= htmlspecialchars($commande['email']) ?>

                                        </span>

                                        <span>

                                            <i class="fa-solid fa-phone"></i>

                                            <?= htmlspecialchars($commande['telephone']) ?>

                                        </span>

                                    </div>

                                </td>


                                <!-- Total -->

                                <td>

                                    <strong class="commande-total">

                                        <?= number_format(
                                            (float) $commande['total'],
                                            0,
                                            ',',
                                            ' '
                                        ) ?>

                                        FCFA

                                    </strong>

                                </td>


                                <!-- Statut -->

                                <td>

                                    <span class="statut-badge <?= classeStatut($commande['statut']) ?>">

                                        <?= libelleStatut($commande['statut']) ?>

                                    </span>

                                </td>


                                <!-- Date -->

                                <td>

                                    <div class="date-info">

                                        <strong>

                                            <?= date(
                                                'd/m/Y',
                                                strtotime($commande['date_commande'])
                                            ) ?>

                                        </strong>

                                        <small>

                                            <?= date(
                                                'H:i',
                                                strtotime($commande['date_commande'])
                                            ) ?>

                                        </small>

                                    </div>

                                </td>


                                <!-- Action -->

                                <td>

                                    <a
                                        href="voir-commande.php?id=<?= (int) $commande['id'] ?>"
                                        class="btn-voir-commande"
                                        title="Voir la commande"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                        Voir

                                    </a>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                        </tbody>


                    </table>

                </div>


            <?php else: ?>


                <!-- ========================================
                     AUCUNE COMMANDE
                ======================================== -->

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="fa-solid fa-cart-shopping"></i>

                    </div>

                    <h3>
                        Aucune commande trouvée
                    </h3>

                    <p>

                        <?php if ($recherche !== '' || $statut !== ''): ?>

                            Aucune commande ne correspond à vos critères de recherche.

                        <?php else: ?>

                            Vous n'avez encore reçu aucune commande.

                        <?php endif; ?>

                    </p>


                    <?php if ($recherche !== '' || $statut !== ''): ?>

                        <a
                            href="commandes.php"
                            class="btn-reset"
                        >

                            <i class="fa-solid fa-rotate-left"></i>

                            Réinitialiser les filtres

                        </a>

                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </div>


    </main>


</div>


<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>