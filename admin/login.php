<?php

session_start();

require_once __DIR__ . '/../config/databases.php';


/* =========================================================
   SI L'ADMIN EST DÉJÀ CONNECTÉ
   ========================================================= */

if (isset($_SESSION["admin_id"])) {

    header("Location: messages.php");

    exit;

}


/* =========================================================
   VARIABLES
   ========================================================= */

$erreur = "";


/* =========================================================
   TRAITEMENT DU FORMULAIRE
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    $motDePasse = $_POST["mot_de_passe"] ?? "";


    if (
        empty($email) ||
        empty($motDePasse)
    ) {

        $erreur =
            "Veuillez remplir tous les champs.";

    }

    else {

        try {

            /* =============================================
               RECHERCHE DE L'ADMINISTRATEUR
               ============================================= */

            $stmt = $conn->prepare("
                SELECT
                    id,
                    nom,
                    email,
                    mot_de_passe
                FROM admins
                WHERE email = ?
                LIMIT 1
            ");

            $stmt->execute([$email]);

            $admin =
                $stmt->fetch(PDO::FETCH_ASSOC);


            /* =============================================
               VÉRIFICATION DU MOT DE PASSE
               ============================================= */

            if (
                $admin &&
                password_verify(
                    $motDePasse,
                    $admin["mot_de_passe"]
                )
            ) {

                /* =========================================
                   CRÉATION DE LA SESSION
                   ========================================= */

                session_regenerate_id(true);


                $_SESSION["admin_id"] =
                    $admin["id"];

                $_SESSION["admin_nom"] =
                    $admin["nom"];

                $_SESSION["admin_email"] =
                    $admin["email"];


                /* =========================================
                   REDIRECTION
                   ========================================= */

                header(
                    "Location: messages.php"
                );

                exit;

            }

            else {

                $erreur =
                    "Adresse e-mail ou mot de passe incorrect.";

            }

        }

        catch (PDOException $e) {

            $erreur =
                "Une erreur est survenue. Veuillez réessayer.";

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
        Connexion | Temps Libre
    </title>


    <!-- Google Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
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
        href="../css/admin-login.css"
    >

</head>


<body>


    <main class="login-page">


        <section class="login-card">


            <!-- =================================================
                 LOGO
                 ================================================= -->

            <div class="login-logo">

                <img
                    src="../images/logo/logo.png"
                    alt="Logo Temps Libre"
                >

            </div>


            <!-- =================================================
                 TITRE
                 ================================================= -->

            <div class="login-title">

                <span>
                    Administration
                </span>

                <h1>
                    Bienvenue
                </h1>

                <p>
                    Connectez-vous pour accéder
                    à votre espace d'administration.
                </p>

            </div>


            <!-- =================================================
                 MESSAGE D'ERREUR
                 ================================================= -->

            <?php if (!empty($erreur)): ?>

                <div class="login-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>

                        <?= htmlspecialchars($erreur) ?>

                    </span>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FORMULAIRE
                 ================================================= -->

            <form
                method="POST"
                class="login-form"
                autocomplete="off"
            >


                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">

                        Adresse e-mail

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-regular fa-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="admin@tempslibre.sn"
                            required
                        >

                    </div>

                </div>


                <!-- MOT DE PASSE -->

                <div class="form-group">

                    <label for="mot_de_passe">

                        Mot de passe

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            placeholder="Votre mot de passe"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Afficher le mot de passe"
                        >

                            <i class="fa-regular fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- BOUTON -->

                <button
                    type="submit"
                    class="btn-login"
                >

                    <span>
                        Se connecter
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </button>


            </form>


            <!-- =================================================
                 RETOUR AU SITE
                 ================================================= -->

            <a
                href="../index.php"
                class="back-site"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Retour au site

            </a>


        </section>


    </main>


    <!-- =====================================================
         JAVASCRIPT
         ===================================================== -->

    <script>

        const togglePassword =
            document.getElementById(
                "togglePassword"
            );

        const password =
            document.getElementById(
                "mot_de_passe"
            );


        if (
            togglePassword &&
            password
        ) {

            togglePassword.addEventListener(
                "click",
                function () {

                    if (
                        password.type === "password"
                    ) {

                        password.type = "text";

                        this.innerHTML =
                            '<i class="fa-regular fa-eye-slash"></i>';

                    }

                    else {

                        password.type = "password";

                        this.innerHTML =
                            '<i class="fa-regular fa-eye"></i>';

                    }

                }
            );

        }

    </script>


</body>

</html>
