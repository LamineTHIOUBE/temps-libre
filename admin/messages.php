<?php

/* =========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================= */

   require_once __DIR__ . '/auth.php';
   require_once __DIR__ . '/../config/databases.php';

   /* =========================================================
   PROTECTION CSRF
   ========================================================= */

   if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] = bin2hex(
        random_bytes(32)
    );

}

$csrfToken = $_SESSION['csrf_token'];

/* =========================================================
   RECHERCHE ET FILTRE
   ========================================================= */

   $recherche = trim($_GET["recherche"] ?? "");

   $statutFiltre = $_GET["statut"] ?? "";

  
/* =========================================================
   STATISTIQUES DES MESSAGES
   ========================================================= */

try {

    /* -----------------------------------------------------
       TOTAL
       ----------------------------------------------------- */

    $stmtTotal = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
    ");

    $totalMessages = (int) $stmtTotal->fetchColumn();


    /* -----------------------------------------------------
       NOUVEAUX
       ----------------------------------------------------- */

    $stmtNouveaux = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
        WHERE statut = 'nouveau'
    ");

    $messagesNouveaux =
        (int) $stmtNouveaux->fetchColumn();


    /* -----------------------------------------------------
       LUS
       ----------------------------------------------------- */

    $stmtLus = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
        WHERE statut = 'lu'
    ");

    $messagesLus =
        (int) $stmtLus->fetchColumn();


    /* -----------------------------------------------------
       TRAITÉS
       ----------------------------------------------------- */

    $stmtTraites = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
        WHERE statut = 'traite'
    ");

    $messagesTraites =
        (int) $stmtTraites->fetchColumn();

}

catch (PDOException $e) {

    $totalMessages = 0;

    $messagesNouveaux = 0;

    $messagesLus = 0;

    $messagesTraites = 0;

}



/* =========================================================
   RECHERCHE ET FILTRE
   ========================================================= */

$recherche = trim($_GET["recherche"] ?? "");

$statutFiltre = $_GET["statut"] ?? "";


/* =========================================================
   RÉCUPÉRER LES MESSAGES
   ========================================================= */

try {

    $sql = "
        SELECT
            id,
            nom,
            email,
            telephone,
            sujet,
            message,
            date_envoi,
            statut

        FROM messages_contact

        WHERE 1 = 1
    ";


    $params = [];


    /* =====================================================
       RECHERCHE
       ===================================================== */

    if ($recherche !== "") {

        $sql .= "
            AND (
                nom LIKE ?
                OR email LIKE ?
                OR sujet LIKE ?
            )
        ";

        $motRecherche = "%" . $recherche . "%";

        $params[] = $motRecherche;
        $params[] = $motRecherche;
        $params[] = $motRecherche;

    }


    /* =====================================================
       FILTRE PAR STATUT
       ===================================================== */

    if ($statutFiltre !== "") {

        $sql .= "
            AND statut = ?
        ";

        $params[] = $statutFiltre;

    }


    /* =====================================================
       TRI
       ===================================================== */

    $sql .= "
        ORDER BY date_envoi DESC
    ";


    /* =====================================================
       EXÉCUTION
       ===================================================== */

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    $messages =
        $stmt->fetchAll(PDO::FETCH_ASSOC);


    /* =====================================================
       STATISTIQUES DE LA LISTE FILTRÉE
       ===================================================== */

    $totalMessages =
        count($messages);


    $nouveauxMessages = 0;

    $messagesLus = 0;

    $messagesTraites = 0;


    foreach ($messages as $message) {

        if ($message["statut"] === "nouveau") {

            $nouveauxMessages++;

        }

        elseif ($message["statut"] === "lu") {

            $messagesLus++;

        }

        elseif ($message["statut"] === "traite") {

            $messagesTraites++;

        }

    }

}
catch (PDOException $e) {

    die(
        "Erreur lors de la récupération des messages."
    );

}



/* =========================================================
   MARQUER UN MESSAGE COMME LU
   ========================================================= */

/* =========================================================
   MARQUER UN MESSAGE COMME LU
   ========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && ($_POST["action"] ?? "") === "lu"
) {

    $idMessage = (int) ($_POST["id"] ?? 0);

    $token = $_POST["csrf_token"] ?? "";


    /* Vérification CSRF */

    if (
        empty($csrfToken)
        || !hash_equals($csrfToken, $token)
    ) {

        die("Requête non autorisée.");

    }


    if ($idMessage <= 0) {

        die("Message invalide.");

    }


    try {

        $stmt = $conn->prepare("
            UPDATE messages_contact
            SET statut = 'lu'
            WHERE id = ?
            AND statut = 'nouveau'
        ");

        $stmt->execute([
            $idMessage
        ]);


        header("Location: messages.php");

        exit;


    } catch (PDOException $e) {

        die(
            "Erreur lors de la modification du message."
        );

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
        Messages | Administration Temps Libre
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

     <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- CSS principal --> 
     <link rel="stylesheet" href="css/style.css" >

    <!-- CSS -->

   <link
    rel="stylesheet"
    href="../css/admin-messages.css"
    >

</head>


<body>



    <!-- =====================================================
         ADMINISTRATION
         ===================================================== -->

    <div class="admin-container">


        <!-- =================================================
             HEADER
             ================================================= -->

        <header class="admin-header">

            

                 <div class="admin-logo"> 
                    <img src="../images/logo/logo.png" alt="Logo Temps Libre" > 
                 </div>
                

            


            <a
                href="../contact.php"
                class="btn-retour"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Retour au site

            </a>

    
            <a
               href="logout.php"
               class="btn-logout"
               onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
                      Déconnexion
            </a>


            <div class="admin-user">

    <div class="admin-user-icon">

        <i class="fa-solid fa-user"></i>

    </div>

    <div class="admin-user-info">

        <span>
            Connecté en tant que
        </span>

        <strong>
            <?= htmlspecialchars($_SESSION["admin_nom"]) ?>
        </strong>

    </div>

</div>



        </header>


        
<!-- =====================================================
     STATISTIQUES
     ===================================================== -->

<section class="admin-stats">


    <!-- TOTAL -->

    <div class="stat-card stat-total">

        <div class="stat-icon">

            <i class="fa-solid fa-envelope"></i>

        </div>

        <div class="stat-content">

            <span>
                Total
            </span>

            <strong>
                <?= $totalMessages ?>
            </strong>

            <small>
                Messages reçus
            </small>

        </div>

    </div>


    <!-- NOUVEAUX -->

    <div class="stat-card stat-nouveau">

        <div class="stat-icon">

            <i class="fa-solid fa-envelope-open-text"></i>

        </div>

        <div class="stat-content">

            <span>
                Nouveaux
            </span>

            <strong>
                <?= $messagesNouveaux ?>
            </strong>

            <small>
                À consulter
            </small>

        </div>

    </div>


    <!-- LUS -->

    <div class="stat-card stat-lu">

        <div class="stat-icon">

            <i class="fa-solid fa-eye"></i>

        </div>

        <div class="stat-content">

            <span>
                Lus
            </span>

            <strong>
                <?= $messagesLus ?>
            </strong>

            <small>
                Messages consultés
            </small>

        </div>

    </div>


    <!-- TRAITÉS -->

    <div class="stat-card stat-traite">

        <div class="stat-icon">

            <i class="fa-solid fa-circle-check"></i>

        </div>

        <div class="stat-content">

            <span>
                Traités
            </span>

            <strong>
                <?= $messagesTraites ?>
            </strong>

            <small>
                Demandes terminées
            </small>

        </div>

    </div>


</section>


        <!-- =================================================
             CONTENU
             ================================================= -->

        <main class="admin-main">


            <div class="page-title">

                <div>

                    <span class="eyebrow">
                        Gestion des contacts
                    </span>

                    <h2>
                        Messages reçus
                    </h2>

                    <p>
                        Consultez et gérez les messages
                        envoyés depuis votre formulaire de contact.
                    </p>

                </div>

            </div>

            <!-- =====================================================
     RECHERCHE ET FILTRES
     ===================================================== -->

<form
    method="GET"
    class="messages-filters"
>

    <!-- Recherche -->

    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            name="recherche"
            value="<?= htmlspecialchars($recherche) ?>"
            placeholder="Rechercher un nom, e-mail ou sujet..."
        >

    </div>


    <!-- Filtre statut -->

    <div class="filter-box">

        <label for="statut">
            Statut
        </label>

        <select
            name="statut"
            id="statut"
        >

            <option value="">
                Tous les messages
            </option>

            <option
                value="nouveau"
                <?= $statutFiltre === "nouveau" ? "selected" : "" ?>
            >
                Nouveaux
            </option>

            <option
                value="lu"
                <?= $statutFiltre === "lu" ? "selected" : "" ?>
            >
                Lus
            </option>

            <option
                value="traite"
                <?= $statutFiltre === "traite" ? "selected" : "" ?>
            >
                Traités
            </option>

        </select>

    </div>


    <!-- Bouton rechercher -->

    <button
        type="submit"
        class="btn-search"
    >

        <i class="fa-solid fa-magnifying-glass"></i>

        Rechercher

    </button>


    <!-- Réinitialiser -->

    <?php if ($recherche !== "" || $statutFiltre !== ""): ?>

        <a
            href="messages.php"
            class="btn-reset"
        >

            <i class="fa-solid fa-xmark"></i>

            Réinitialiser

        </a>

    <?php endif; ?>

</form>


            


            <!-- =================================================
                 TABLEAU
                 ================================================= -->

            <section class="messages-card">


                <div class="messages-card-header">

                    <div>

                        <h3>
                            Liste des messages
                        </h3>

                        <p>
                            Les plus récents apparaissent en premier.
                        </p>

                    </div>

                </div>


                <?php if (empty($messages)): ?>


                    <div class="empty-messages">

                        <i class="fa-regular fa-envelope-open"></i>

                        <h3>
                            Aucun message
                        </h3>

                        <p>
                            Vous n'avez reçu aucun message
                            pour le moment.
                        </p>

                    </div>


                <?php else: ?>


                    <div class="table-wrapper">

                        <table class="messages-table">

                            <thead>

                                <tr>

                                    <th>
                                        Contact
                                    </th>

                                    <th>
                                        Sujet
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach (
                                    $messages
                                    as $message
                                ): ?>


                                    <tr>


                                        <!-- CONTACT -->

                                        <td>

                                            <div class="contact-cell">

                                                <div class="avatar">

                                                    <?= strtoupper(
                                                        mb_substr(
                                                            $message["nom"],
                                                            0,
                                                            1
                                                        )
                                                    ) ?>

                                                </div>


                                                <div>

                                                    <strong>

                                                        <?= htmlspecialchars(
                                                            $message["nom"]
                                                        ) ?>

                                                    </strong>

                                                    <span>

                                                        <?= htmlspecialchars(
                                                            $message["email"]
                                                        ) ?>

                                                    </span>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- SUJET -->

                                        <td>

                                            <span class="subject">

                                                <?= htmlspecialchars(
                                                    $message["sujet"]
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- DATE -->

                                        <td>

                                            <span class="date">

                                                <?= date(
                                                    "d/m/Y",
                                                    strtotime(
                                                        $message["date_envoi"]
                                                    )
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- STATUT -->

                                        <td>

                                            <?php

                                            $classeStatut =
                                                $message["statut"];

                                            $texteStatut =
                                                $message["statut"];

                                            if (
                                                $message["statut"]
                                                === "nouveau"
                                            ) {

                                                $texteStatut =
                                                    "Nouveau";

                                            }

                                            elseif (
                                                $message["statut"]
                                                === "lu"
                                            ) {

                                                $texteStatut =
                                                    "Lu";

                                            }

                                            elseif (
                                                $message["statut"]
                                                === "traite"
                                            ) {

                                                $texteStatut =
                                                    "Traité";

                                            }

                                            ?>


                                            <span
                                                class="status <?= htmlspecialchars(
                                                    $classeStatut
                                                ) ?>"
                                            >

                                                <?= $texteStatut ?>

                                            </span>

                                        </td>


                                        <!-- ACTION -->

                                        <td>

                                            <a
                                                href="voir-message.php?id=<?= (int) $message["id"] ?>"
                                                class="btn-voir"
                                            >

                                                <i class="fa-regular fa-eye"></i>

                                                Voir

                                            </a>

                                             <?php if ($message["statut"] === "nouveau"): ?>

    <form
        method="POST"
        class="form-lu"
    >

        <input
            type="hidden"
            name="action"
            value="lu"
        >

        <input
            type="hidden"
            name="id"
            value="<?= (int) $message["id"] ?>"
        >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken) ?>"
        >

        <button
            type="submit"
            class="btn-lu"
            title="Marquer comme lu"
        >

            <i class="fa-solid fa-check"></i>

        </button>

    </form>

<?php endif; ?>

                                        </td>


                                        

                                        


                                    </tr>


                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                <?php endif; ?>


            </section>


        </main>


    </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


</body>

</html>