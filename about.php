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
     <!--Notre CSS-->
    <link rel="stylesheet" href="css/style.css">

     <body>
    
<?php
include('includes/header.php');
?>

<section class="about-hero">

    <div class="about-overlay"></div>

    <div class="container">

        <div class="hero-content">

            <h1>
                À propos de
                <span>Temps Libre</span>
            </h1>

            <p>
                Depuis plusieurs années, nous transformons
                les richesses naturelles du Sénégal en boissons
                artisanales, saines et savoureuses.
            </p>

            <div class="hero-buttons">

                <a href="produits.php" class="btn btn-green">
                    Découvrir nos produits
                </a>

                <a href="contact.php" class="btn btn-yellow">
                    Nous contacter
                </a>

            </div>

        </div>

    </div>

</section>


<!--=================== NOTRE HISTOIRE ===================-->

<section class="about-home">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- Image -->

            <div class="col-lg-5">

                <div class="about-image">

                    <img src="images/histoire1.png"
                         alt="Temps Libre">

                </div>

            </div>

            <!-- Texte -->

            <div class="col-lg-7">

                <div class="about-content">

                    <span class="subtitle">
                        NOTRE HISTOIRE
                    </span>

                    <h2>
                        Une passion pour les
                        <br>
                        produits naturels
                    </h2>

                    <p>
                        Temps Libre est une entreprise sénégalaise spécialisée
                        dans la transformation de produits locaux en jus
                        naturels.
                    </p>

                    <p>
                        Notre ambition est de valoriser les richesses de notre
                        terroir en proposant des boissons saines,
                        authentiques et accessibles à tous.
                    </p>

                    <p>
                        Chaque bouteille est élaborée avec des ingrédients
                        soigneusement sélectionnés afin de préserver les
                        saveurs naturelles et les qualités nutritionnelles.
                    </p>

                    <a href="apropos.php" class="btn-about">
                        En savoir plus
                    </a>

                </div>

            </div>

        </div>

    </div>

    <img src="images/leaf.jpg"
         class="leaf-decoration"
         alt="">

</section>


<!--==================== MISSION - VISION - VALEURS ====================-->

<section class="mission-section">

    <div class="container">

        <div class="row gx-5 gy-4">

            <!-- Mission -->

            <div class="col-lg-4 col-md-6">

                <div class="mission-card">

                    <div class="icon green">

                        <i class="fa-solid fa-bullseye"></i>

                    </div>

                    <h3>Notre Mission</h3>

                    <p>

                        Offrir des boissons naturelles de qualité,
                        fabriquées localement avec passion.

                    </p>

                    <span class="line green-line"></span>

                </div>

            </div>

            <!-- Vision -->

            <div class="col-lg-4 col-md-6">

                <div class="mission-card">

                    <div class="icon yellow">

                        <i class="fa-solid fa-earth-africa"></i>

                    </div>

                    <h3>Notre Vision</h3>

                    <p>

                        Devenir une référence dans la transformation
                        agroalimentaire au Sénégal et en Afrique.

                    </p>

                    <span class="line yellow-line"></span>

                </div>

            </div>

            <!-- Valeurs -->

            <div class="col-lg-4 col-md-6">

                <div class="mission-card">

                    <div class="icon red">

                        <i class="fa-regular fa-heart"></i>

                    </div>

                    <h3>Nos Valeurs</h3>

                    <ul>

                        <li><i class="fa-solid fa-circle-check"></i> Qualité</li>

                        <li><i class="fa-solid fa-circle-check"></i> Authenticité</li>

                        <li><i class="fa-solid fa-circle-check"></i> Innovation</li>

                        <li><i class="fa-solid fa-circle-check"></i> Respect du client</li>

                        <li><i class="fa-solid fa-circle-check"></i> Respect de la nature</li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>






<?php

include ('includes/footer.php');

?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    
  </body>
</html>