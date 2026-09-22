<?php

/* =========================================================
   PROTECTION DE L'ADMINISTRATION
   ========================================================= */

require_once __DIR__ . '/auth.php';


/* =========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================= */

require_once __DIR__ . '/../config/databases.php';


/* =========================================================
   STATISTIQUES DES MESSAGES
   ========================================================= */

try {

    /* -----------------------------------------------------
       TOTAL DES MESSAGES
       ----------------------------------------------------- */

    $stmtTotal = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
    ");

    $totalMessages = (int) $stmtTotal->fetchColumn();


    /* -----------------------------------------------------
       NOUVEAUX MESSAGES
       ----------------------------------------------------- */

    $stmtNouveaux = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
        WHERE statut = 'nouveau'
    ");

    $messagesNouveaux =
        (int) $stmtNouveaux->fetchColumn();


    /* -----------------------------------------------------
       MESSAGES LUS
       ----------------------------------------------------- */

    $stmtLus = $conn->query("
        SELECT COUNT(*)
        FROM messages_contact
        WHERE statut = 'lu'
    ");

    $messagesLus =
        (int) $stmtLus->fetchColumn();


    /* -----------------------------------------------------
       MESSAGES TRAITÉS
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
   LES 5 DERNIERS MESSAGES
   ========================================================= */

try {

    $stmtDerniersMessages = $conn->query("
        SELECT
            id,
            nom,
            email,
            sujet,
            date_envoi,
            statut
        FROM messages_contact
        ORDER BY date_envoi DESC
        LIMIT 5
    ");

    $derniersMessages =
        $stmtDerniersMessages->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {

    $derniersMessages = [];

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
        Tableau de bord | Temps Libre
    </title>


    <!-- =====================================================
         GOOGLE FONTS
         ===================================================== -->

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


    <!-- =====================================================
         FONT AWESOME
         ===================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =====================================================
         CSS
         ===================================================== -->

    <link
        rel="stylesheet"
        href="../css/admin-dashboard.css"
    >

</head>


<body>


<div class="dashboard">


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="dashboard-header">


        <!-- LOGO -->

        <div class="dashboard-brand">


            <div class="dashboard-logo">

                <img
                    src="../images/logo/logo.png"
                    alt="Logo Temps Libre"
                >

            </div>


            <div class="dashboard-brand-text">

                <strong>
                    Temps Libre
                </strong>

                <span>
                    Administration
                </span>

            </div>


        </div>


        <!-- ADMINISTRATEUR -->

        <div class="dashboard-user">


            <div class="dashboard-user-info">

                <span>
                    Connecté
                </span>

                <strong>
                    Administrateur
                </strong>

            </div>


            <!-- DÉCONNEXION -->

            <a
                href="logout.php"
                class="btn-logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Déconnexion

            </a>


        </div>


    </header>



    <!-- =====================================================
         CONTENU PRINCIPAL
         ===================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             TITRE
             ================================================= -->

        <div class="dashboard-title">

            <span>
                TABLEAU DE BORD
            </span>

            <h1>
                Bienvenue dans votre administration
            </h1>

            <p>
                Gérez votre site Temps Libre depuis cet espace.
            </p>

        </div>



        <!-- =================================================
             STATISTIQUES
             ================================================= -->

        <section class="dashboard-stats">


            <!-- =================================================
                 TOTAL DES MESSAGES
                 ================================================= -->

            <a
                href="messages.php"
                class="dashboard-stat-card"
            >

                <div class="dashboard-stat-icon total">

                    <i class="fa-regular fa-envelope"></i>

                </div>


                <div class="dashboard-stat-content">

                    <span>
                        Total des messages
                    </span>

                    <strong>
                        <?= $totalMessages ?>
                    </strong>

                    <small>
                        Tous les messages reçus
                    </small>

                </div>


            </a>



            <!-- =================================================
                 NOUVEAUX MESSAGES
                 ================================================= -->

            <a
                href="messages.php?statut=nouveau"
                class="dashboard-stat-card"
            >

                <div class="dashboard-stat-icon nouveau">

                    <i class="fa-solid fa-envelope-open-text"></i>

                </div>


                <div class="dashboard-stat-content">

                    <span>
                        Nouveaux
                    </span>

                    <strong>
                        <?= $messagesNouveaux ?>
                    </strong>

                    <small>
                        Messages à consulter
                    </small>

                </div>


            </a>



            <!-- =================================================
                 MESSAGES LUS
                 ================================================= -->

            <a
                href="messages.php?statut=lu"
                class="dashboard-stat-card"
            >

                <div class="dashboard-stat-icon lu">

                    <i class="fa-solid fa-eye"></i>

                </div>


                <div class="dashboard-stat-content">

                    <span>
                        Messages lus
                    </span>

                    <strong>
                        <?= $messagesLus ?>
                    </strong>

                    <small>
                        Messages déjà consultés
                    </small>

                </div>


            </a>



            <!-- =================================================
                 MESSAGES TRAITÉS
                 ================================================= -->

            <a
                href="messages.php?statut=traite"
                class="dashboard-stat-card"
            >

                <div class="dashboard-stat-icon traite">

                    <i class="fa-solid fa-circle-check"></i>

                </div>


                <div class="dashboard-stat-content">

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


            </a>


        </section>


        <!-- =====================================================
     ALERTE NOUVEAUX MESSAGES
     ===================================================== -->

<?php if ($messagesNouveaux > 0): ?>

    <div class="new-messages-alert">

        <div class="new-messages-alert-icon">

            <i class="fa-solid fa-bell"></i>

        </div>


        <div class="new-messages-alert-content">

            <strong>
                <?= $messagesNouveaux ?>
                nouveau<?= $messagesNouveaux > 1 ? "x" : "" ?>
                message<?= $messagesNouveaux > 1 ? "s" : "" ?>
                reçu<?= $messagesNouveaux > 1 ? "s" : "" ?>
            </strong>

            <p>
                Vous avez des messages qui n'ont pas encore été consultés.
            </p>

        </div>


        <a
            href="messages.php?statut=nouveau"
            class="new-messages-alert-button"
        >

            Voir les messages

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

<?php endif; ?>



        <!-- =================================================
             ACCÈS RAPIDES
             ================================================= -->

        <section class="quick-access">


            <!-- TITRE -->

            <div class="section-heading">

                <span>
                    GESTION
                </span>

                <h2>
                    Accès rapides
                </h2>

            </div>



            <!-- GRILLE -->

            <div class="quick-grid">


                <!-- =================================================
                     MESSAGES
                     ================================================= -->

                <a
                    href="messages.php"
                    class="quick-card"
                >

                    <div class="quick-icon">

                        <i class="fa-regular fa-envelope"></i>

                    </div>


                    <div>

                        <h3>
                            Messages
                        </h3>

                        <p>
                            Consulter et gérer les messages reçus.
                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right quick-arrow"></i>

                </a>



                <!-- =================================================
                     PRODUITS
                     ================================================= -->

                <a
                    href="products.php"
                    class="quick-card"
                >

                    <div class="quick-icon">

                        <i class="fa-solid fa-box-open"></i>

                    </div>


                    <div>

                        <h3>
                            Produits
                        </h3>

                        <p>
                            Gérer les produits de Temps Libre.
                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right quick-arrow"></i>

                </a>



                <!-- =================================================
                     AJOUTER UN PRODUIT
                     ================================================= -->

                <a
                    href="ajouter-produit.php"
                    class="quick-card"
                >

                    <div class="quick-icon">

                        <i class="fa-solid fa-plus"></i>

                    </div>


                    <div>

                        <h3>
                            Ajouter un produit
                        </h3>

                        <p>
                            Ajouter un nouveau produit.
                        </p>

                    </div>


                    <i class="fa-solid fa-arrow-right quick-arrow"></i>

                </a>


            </div>


        </section>


        <!-- =====================================================
     DERNIERS MESSAGES
     ===================================================== -->

<section class="recent-messages">


    <!-- EN-TÊTE -->

    <div class="recent-messages-header">

        <div>

            <span>
                MESSAGES
            </span>

            <h2>
                Derniers messages
            </h2>

            <p>
                Les 5 derniers messages reçus.
            </p>

        </div>


        <a
            href="messages.php"
            class="btn-all-messages"
        >

            Voir tous les messages

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>



    <!-- LISTE -->

    <div class="recent-messages-card">


        <?php if (!empty($derniersMessages)): ?>


            <?php foreach ($derniersMessages as $message): ?>


                <a
                    href="voir-message.php?id=<?= (int) $message["id"] ?>"
                    class="recent-message-row"
                >


                    <!-- AVATAR -->

                    <div class="recent-avatar">

                        <?= strtoupper(
                            mb_substr(
                                $message["nom"],
                                0,
                                1
                            )
                        ) ?>

                    </div>



                    <!-- INFORMATIONS -->

                    <div class="recent-message-info">

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



                    <!-- SUJET -->

                    <div class="recent-message-subject">

                        <strong>

                            <?= htmlspecialchars(
                                $message["sujet"]
                            ) ?>

                        </strong>

                    </div>



                    <!-- DATE -->

                    <div class="recent-message-date">

                        <?= date(
                            "d/m/Y",
                            strtotime(
                                $message["date_envoi"]
                            )
                        ) ?>

                    </div>



                    <!-- STATUT -->

                    <div>

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
                            class="recent-status <?= htmlspecialchars(
                                $classeStatut
                            ) ?>"
                        >

                            <?= $texteStatut ?>

                        </span>

                    </div>



                    <!-- FLÈCHE -->

                    <i
                        class="fa-solid fa-chevron-right recent-arrow"
                    ></i>


                </a>


            <?php endforeach; ?>


        <?php else: ?>


            <div class="recent-empty">

                <i class="fa-regular fa-envelope"></i>

                <h3>
                    Aucun message
                </h3>

                <p>
                    Aucun message n'a encore été reçu.
                </p>

            </div>


        <?php endif; ?>


    </div>


</section>


    </main>


</div>


</body>

</html>