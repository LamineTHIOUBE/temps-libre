<?php

/* =========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================= */

require_once __DIR__ . '/config/databases.php';


/* =========================================================
   VARIABLES
   ========================================================= */

$succes = "";
$erreur = "";


/* =========================================================
   TRAITEMENT DU FORMULAIRE
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");

    $email = trim($_POST["email"] ?? "");

    $telephone = trim($_POST["telephone"] ?? "");

    $sujet = trim($_POST["sujet"] ?? "");

    $message = trim($_POST["message"] ?? "");


    /* =====================================================
       VALIDATION
       ===================================================== */

    if ($nom === "") {

        $erreur = "Veuillez saisir votre nom complet.";

    }

    elseif ($email === "") {

        $erreur = "Veuillez saisir votre adresse e-mail.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erreur = "Veuillez saisir une adresse e-mail valide.";

    }

    elseif ($sujet === "") {

        $erreur = "Veuillez sélectionner un sujet.";

    }

    elseif ($message === "") {

        $erreur = "Veuillez saisir votre message.";

    }

    elseif (mb_strlen($message) < 10) {

        $erreur = "Votre message doit contenir au moins 10 caractères.";

    }


    /* =====================================================
       ENREGISTREMENT MYSQL
       ===================================================== */

    if ($erreur === "") {

        try {

            $sql = "
                INSERT INTO messages_contact
                (
                    nom,
                    email,
                    telephone,
                    sujet,
                    message
                )
                VALUES
                (
                    :nom,
                    :email,
                    :telephone,
                    :sujet,
                    :message
                )
            ";

            $stmt = $conn->prepare($sql);

            $stmt->execute([

                ":nom" => $nom,

                ":email" => $email,

                ":telephone" => $telephone,

                ":sujet" => $sujet,

                ":message" => $message

            ]);


            $succes =
                "Votre message a bien été envoyé. " .
                "Nous vous répondrons dans les meilleurs délais.";


            /* Vider les champs après succès */

            $nom = "";
            $email = "";
            $telephone = "";
            $sujet = "";
            $message = "";

        }

        catch (PDOException $e) {

            $erreur =
                "Une erreur est survenue lors de l'envoi de votre message.";

        }

    }

}

?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temps Libre</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

     <!--Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
     
    <!-- CSS principal --> 
     <link rel="stylesheet" href="css/style.css" >
    <!--Notre CSS-->
    <link rel="stylesheet" href="css/contact.css">
    

</head>
  <body class="page-contact">
     <?php
         include('includes/header.php');
     ?>



<main>

    <!-- ========================================
         HERO CONTACT
    ======================================== -->

    <section class="contact-hero">

        <div class="container">

            <div class="contact-hero-content">

                <!-- Petit texte au-dessus du titre -->
                <span class="contact-hero-subtitle">
                    <i class="fa-solid fa-leaf"></i>
                    RESTONS EN CONTACT
                </span>


                <!-- Titre principal -->
                <h1>
                    Contactez-<span>nous</span>
                </h1>


                <!-- Description -->
                <p>
                    Une question, une commande ou simplement envie
                    d'en savoir plus sur Temps Libre ?
                    Notre équipe est à votre écoute.
                </p>

            </div>

        </div>

    </section>


</main>




    <!-- ========================================
         SECTION CONTACT
    ======================================== -->

    <section class="contact-section">

        <div class="container">

            <div class="row g-5">


                <!-- ========================================
                     COLONNE INFORMATIONS
                ======================================== -->

                <div class="col-lg-5">

                    <div class="contact-info">

                        <span class="section-label">
                            Parlons-nous
                        </span>

                        <h2>
                            Nous sommes à votre écoute
                        </h2>

                        <p class="contact-info-intro">
                            Vous avez une question sur nos produits,
                            une demande particulière ou souhaitez
                            simplement nous contacter ?
                            Notre équipe Temps Libre est à votre écoute.
                        </p>


                        <!-- Adresse -->

                        <div class="contact-info-item">

                            <div class="contact-icon">

                                <i class="fa-solid fa-location-dot"></i>

                            </div>

                            <div class="contact-info-text">

                                <h3>
                                    Notre adresse
                                </h3>

                                <p>
                                    Patte d'Oie, Dakar<br>
                                    Près de la Clinique Naby Choucair
                                </p>

                            </div>

                        </div>


                        <!-- Téléphone -->

                        <div class="contact-info-item">

                            <div class="contact-icon">

                                <i class="fa-solid fa-phone"></i>

                            </div>

                            <div class="contact-info-text">

                                <h3>
                                    Téléphone
                                </h3>

                                <p>
                                    +221 76 290 72 82
                                </p>

                            </div>

                        </div>


                        <!-- Email -->

                        <div class="contact-info-item">

                            <div class="contact-icon">

                                <i class="fa-solid fa-envelope"></i>

                            </div>

                            <div class="contact-info-text">

                                <h3>
                                    Email
                                </h3>

                                <p>
                                    contact@tempslibre.sn
                                </p>

                            </div>

                        </div>


                        <!-- Horaires -->

                        <div class="contact-info-item">

                            <div class="contact-icon">

                                <i class="fa-solid fa-clock"></i>

                            </div>

                            <div class="contact-info-text">

                                <h3>
                                    Horaires d'ouverture
                                </h3>

                                <p>
                                    Lundi – Samedi<br>
                                    08h00 – 18h00
                                </p>

                            </div>

                        </div>


                        <!-- Réseaux sociaux -->

                        <div class="contact-social">

                            <h3>
                                Suivez-nous
                            </h3>

                            <div class="social-links">

                                <a href="#"
                                   aria-label="Facebook">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>

                                <a href="#"
                                   aria-label="Instagram">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>

                                <a href="#"
                                   aria-label="WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>

                                <a href="#"
                                   aria-label="TikTok">
                                    <i class="fa-brands fa-tiktok"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ========================================
                     COLONNE FORMULAIRE
                ======================================== -->

                <div class="col-lg-7">

                    <div class="contact-form-wrapper">

                        <!-- En-tête du formulaire -->

                        <div class="contact-form-header">

                            <span class="section-label">
                                Écrivez-nous
                            </span>

                            <h2>
                                Envoyez-nous un message
                            </h2>

                            <p>
                                Remplissez le formulaire ci-dessous
                                et notre équipe vous répondra dans
                                les meilleurs délais.
                            </p>

                        </div>


                        <!-- ========================================
                             FORMULAIRE
                        ======================================== -->

                        <form
                            action="contact.php"
                            method="POST"
                            class="contact-form"
                            id="contactForm"
                        >

                  

                             <!-- =========================================
                           MESSAGE PHP
                              ========================================= -->

    <?php if ($succes !== ""): ?>

        <div class="php-message php-success">

            <i class="fa-solid fa-circle-check"></i>

            <div>

                <strong>Message envoyé avec succès !</strong> 
                <p> <?= htmlspecialchars($succes) ?> </p>

            </div>

        </div>

    <?php endif; ?>


    <?php if ($erreur !== ""): ?>

        <div class="php-message php-error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <div>

                <strong>Attention</strong>

                <p>
                    <?= htmlspecialchars($erreur) ?>
                </p>

            </div>

        </div>

    <?php endif; ?>


    <!-- =========================================
         NOM
         ========================================= -->

    <div class="form-group">

        <label for="nom">
            Nom complet
            <span>*</span>
        </label>

        <div class="input-wrapper">

            <i class="fa-regular fa-user"></i>

            <input
                type="text"
                id="nom"
                name="nom"
                placeholder="Votre nom complet"
                required
            >

        </div>

    </div>


    <!-- Le reste de vos champs continue ici -->



                            


                            <!-- Email -->

                            <div class="form-group">

                                <label for="email">
                                    Adresse e-mail
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-envelope"></i>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="votre@email.com"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- Téléphone -->

                            <div class="form-group">

                                <label for="telephone">
                                    Téléphone
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-phone"></i>

                                    <input
                                        type="tel"
                                        id="telephone"
                                        name="telephone"
                                        placeholder="+221 XX XXX XX XX"
                                    >

                                </div>

                            </div>


                            <!-- Sujet -->

                            <div class="form-group">

                                <label for="sujet">
                                    Sujet
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-comment-dots"></i>

                                    <select
                                        id="sujet"
                                        name="sujet"
                                        required
                                    >

                                        <option value="">
                                            Choisissez un sujet
                                        </option>

                                        <option value="Commande">
                                            Commande
                                        </option>

                                        <option value="Produits">
                                            Informations sur nos produits
                                        </option>

                                        <option value="Livraison">
                                            Livraison
                                        </option>

                                        <option value="Partenariat">
                                            Partenariat
                                        </option>

                                        <option value="Autre">
                                            Autre demande
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <!-- Message -->

                            <div class="form-group">

                                <label for="message">
                                    Votre message
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper textarea-wrapper">

                                    <i class="fa-regular fa-message"></i>

                                    <textarea
                                        id="message"
                                        name="message"
                                        rows="6"
                                        placeholder="Écrivez votre message ici..."
                                        required
                                    ></textarea>

                                </div>

                            </div>


                            <!-- Bouton -->

                            <div class="form-submit">

                              
                                <button
                                  type="submit"
                                  class="btn-contact"
                                  id="btnEnvoyer"
                                   >

                                     <span>
                                        Envoyer le message
                                     </span>

                                       <i class="fa-solid fa-paper-plane"></i>

                                </button>
                                   >

                            </div>


                            <!-- Note de confidentialité -->

                            <p class="form-note">

                                <i class="fa-solid fa-lock"></i>

                                Vos informations restent
                                confidentielles.

                            </p>


                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    
    <!-- ========================================
         SECTION LOCALISATION
    ======================================== -->

    <section class="location-section">

        <div class="container">


            <!-- En-tête de la section -->

            <div class="section-heading">

                <span class="section-label">
                    NOTRE LOCALISATION
                </span>

                <h2>
                    Où nous <span>trouver ?</span>
                </h2>

                <p>
                    Retrouvez Temps Libre à Dakar, dans le quartier
                    de la Patte d'Oie, près de la Clinique Naby Choucair.
                </p>

            </div>


            <!-- ========================================
                 CARTE + INFORMATIONS
            ======================================== -->

            <div class="location-content">


                <!-- Carte -->

                <div class="map-wrapper">

                    <div class="map-placeholder">

                        <i class="fa-solid fa-map-location-dot"></i>

                        <h3>
                            Temps Libre
                        </h3>

                        <p>
                            Patte d'Oie, Dakar
                        </p>

                        <span>
                            Près de la Clinique Naby Choucair
                        </span>

                    </div>

                </div>


                <!-- Petit bloc adresse -->

                <div class="location-card">

                    <div class="location-card-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <div class="location-card-content">

                        <span>
                            NOTRE ADRESSE
                        </span>

                        <h3>
                            Temps Libre
                        </h3>

                        <p>
                            Patte d'Oie<br>
                            Dakar, Sénégal
                        </p>

                        <p>
                            <strong>
                                Repère :
                            </strong>
                            près de la Clinique Naby Choucair
                        </p>

                    </div>

                </div>


            </div>

        </div>

    </section>

    <!-- ========================================
         CTA FINAL
    ======================================== -->

    <section class="contact-cta">

        <div class="container">

            <div class="contact-cta-content">


                <!-- Texte du CTA -->

                <div class="contact-cta-text">

                    <span class="contact-cta-label">
                        LE GOÛT AUTHENTIQUE DE LA NATURE
                    </span>

                    <h2>
                        Envie de découvrir nos
                        <span>jus naturels ?</span>
                    </h2>

                    <p>
                        Découvrez notre sélection de boissons
                        préparées avec des produits locaux
                        et profitez de saveurs authentiques
                        du Sénégal.
                    </p>

                </div>


                <!-- Bouton -->

                <div class="contact-cta-button">

                    <a href="produits.php">

                        Découvrir nos produits

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>


            </div>

        </div>

    </section>













   
   <?php include 'includes/footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  
<!-- JavaScript Contact -->
<script src="js/contact.js"></script>

  </body>
</html>