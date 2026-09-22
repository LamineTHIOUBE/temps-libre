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
   RÉCUPÉRER L'ID DU MESSAGE
   ========================================================= */

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


/* =========================================================
   VÉRIFICATION
   ========================================================= */

if (!$id) {

    header("Location: messages.php");

    exit;

}



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    $token = $_POST["csrf_token"] ?? "";


    /* =====================================================
       VÉRIFICATION CSRF
       ===================================================== */

       if (
        empty($csrfToken)
        || !hash_equals($csrfToken, $token)
    ) {

        die("Requête non autorisée.");

    }


    /* =====================================================
       MARQUER COMME TRAITÉ
       ===================================================== */

    if ($action === "traiter") {

        try {

            $stmtTraite = $conn->prepare("
                UPDATE messages_contact
                SET statut = 'traite'
                WHERE id = ?
            ");

            $stmtTraite->execute([$id]);


            header(
                "Location: voir-message.php?id=" . $id
            );

            exit;

        }

        catch (PDOException $e) {

            $erreur =
                "Impossible de modifier le statut du message.";

        }

    }


    /* =====================================================
       SUPPRIMER LE MESSAGE
       ===================================================== */

    elseif ($action === "supprimer") {

        try {

            $stmtSupprimer = $conn->prepare("
                DELETE FROM messages_contact
                WHERE id = ?
            ");

            $stmtSupprimer->execute([$id]);


            header("Location: messages.php");

            exit;

        }

        catch (PDOException $e) {

            $erreur =
                "Impossible de supprimer le message.";

        }

    }

}



/* =========================================================
   RÉCUPÉRER LE MESSAGE
   ========================================================= */

try {

    $stmt = $conn->prepare("
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
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);

    $message = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$message) {

        header("Location: messages.php");

        exit;

    }

}

catch (PDOException $e) {

    die(
        "Erreur lors de la récupération du message."
    );

}


/* =========================================================
   MARQUER AUTOMATIQUEMENT COMME LU
   ========================================================= */

if ($message["statut"] === "nouveau") {

    try {

        $stmtUpdate = $conn->prepare("
            UPDATE messages_contact
            SET statut = 'lu'
            WHERE id = ?
        ");

        $stmtUpdate->execute([$id]);

        $message["statut"] = "lu";

    }

    catch (PDOException $e) {

        // On continue même si la mise à jour échoue.

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
        Message | Administration Temps Libre
    </title>


    <!-- Google Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
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

    <link rel="stylesheet" href="../css/admin-message.css" >

</head>


<body>


    <div class="admin-message-container">


        <!-- =================================================
             HEADER
             ================================================= -->

        <header class="admin-message-header">

            <a
                href="messages.php"
                class="back-link"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Retour aux messages

            </a>


            
                <div class="admin-title">

                   <img src="../images/logo/logo.png" alt="Logo Temps Libre">

                
                </div>



                <div>

                    <strong>
                        Temps Libre
                    </strong>

                    <span>
                        Administration
                    </span>

                </div>

            

        </header>


        <!-- =================================================
             CONTENU
             ================================================= -->

        <main class="message-main">


            <div class="message-page-title">

                <span>
                    Message reçu
                </span>

                <h1>
                    Détails du message
                </h1>

            </div>


            <!-- =================================================
                 CARTE MESSAGE
                 ================================================= -->

            <section class="message-detail-card">


                <!-- CONTACT -->

                <div class="message-contact">

                    <div class="message-avatar">

                        <?= strtoupper(
                            mb_substr(
                                $message["nom"],
                                0,
                                1
                            )
                        ) ?>

                    </div>


                    <div class="message-contact-info">

                        <h2>

                            <?= htmlspecialchars(
                                $message["nom"]
                            ) ?>

                        </h2>

                        <a
                            href="mailto:<?= htmlspecialchars(
                                $message["email"]
                            ) ?>"
                        >

                            <?= htmlspecialchars(
                                $message["email"]
                            ) ?>

                        </a>

                    </div>


                    <span
                        class="message-status <?= htmlspecialchars(
                            $message["statut"]
                        ) ?>"
                    >

                        <?php if (
                            $message["statut"] === "nouveau"
                        ): ?>

                            Nouveau

                        <?php elseif (
                            $message["statut"] === "lu"
                        ): ?>

                            Lu

                        <?php elseif (
                            $message["statut"] === "traite"
                        ): ?>

                            Traité

                        <?php else: ?>

                            <?= htmlspecialchars(
                                $message["statut"]
                            ) ?>

                        <?php endif; ?>

                    </span>

                </div>


                <!-- INFORMATIONS -->

                <div class="message-info-grid">


                    <div class="message-info-item">

                        <span>
                            <i class="fa-solid fa-tag"></i>
                            Sujet
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $message["sujet"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="message-info-item">

                        <span>
                            <i class="fa-regular fa-calendar"></i>
                            Date
                        </span>

                        <strong>

                            <?= date(
                                "d/m/Y à H:i",
                                strtotime(
                                    $message["date_envoi"]
                                )
                            ) ?>

                        </strong>

                    </div>


                    <div class="message-info-item">

                        <span>
                            <i class="fa-solid fa-phone"></i>
                            Téléphone
                        </span>

                        <strong>

                            <?=
                                !empty(
                                    $message["telephone"]
                                )
                                ? htmlspecialchars(
                                    $message["telephone"]
                                )
                                : "Non renseigné"
                            ?>

                        </strong>

                    </div>


                </div>


                <!-- MESSAGE -->

                <div class="message-content">

                    <div class="message-content-title">

                        <i class="fa-regular fa-message"></i>

                        <h3>
                            Message
                        </h3>

                    </div>


                    <div class="message-text">

                        <?= nl2br(
                            htmlspecialchars(
                                $message["message"]
                            )
                        ) ?>

                    </div>

                </div>


                <!-- ACTIONS -->

                <div class="message-actions">

                    <a
                        href="mailto:<?= htmlspecialchars(
                            $message["email"]
                        ) ?>"
                        class="btn-repondre"
                    >

                        <i class="fa-solid fa-reply"></i>

                        Répondre par e-mail

                    </a>

                
<?php if ($message["statut"] !== "traite"): ?>

    <form
        method="POST"
        class="form-traiter"
    >

        <input
            type="hidden"
            name="action"
            value="traiter"
        >

        <input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars($csrfToken) ?>"
            >

        <button
            type="submit"
            class="btn-traiter"
        >

            <i class="fa-solid fa-check"></i>

            Marquer comme traité

        </button>

    </form>

<?php endif; ?>


<form
    method="POST"
    class="form-supprimer"
    onsubmit="return confirmerSuppression();"
>

    <input
        type="hidden"
        name="action"
        value="supprimer"
    >

    <input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars($csrfToken) ?>"
      >

    <button
        type="submit"
        class="btn-supprimer"
    >

        <i class="fa-regular fa-trash-can"></i>

        Supprimer

    </button>

</form>



                    <a
                        href="messages.php"
                        class="btn-retour-messages"
                    >

                        Retour aux messages

                    </a>

                </div>


            </section>


        </main>


    </div>


    
<script>

function confirmerSuppression() {

    return confirm(
        "Êtes-vous sûr de vouloir supprimer définitivement ce message ?"
    );

}

</script>



</body>

</html>
