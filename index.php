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
    

</head>
  <body>
    
<?php
include('includes/header.php');
?>




<section class="hero">

<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

    <!-- Indicateurs -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <!-- Images -->
    <div class="carousel-inner">

        <!-- Image 1 -->
        <div class="carousel-item active">
            <img src="images/hero1.png" class="d-block w-100" alt="Jus naturel Sénégal">
        </div>

        <!-- Image 2 -->
        <div class="carousel-item">
            <img src="images/hero2.png" class="d-block w-100" alt="Produits naturels">
        </div>

        <!-- Image 3 -->
        <div class="carousel-item">
            <img src="images/hero3.png" class="d-block w-100" alt="Terroir sénégalais">
        </div>

    </div>


    <!-- Contenu Hero -->
    <div class="hero-content">

        <h1>
            Le goût authentique de la 
            <span>nature sénégalaise</span>
        </h1>

        <p>
            Temps Libre transforme les richesses de notre terroir en jus naturels,
            sains et délicieux élaborés avec passion à Dakar.
        </p>


        <div class="hero-buttons">

           <a href="produits.php" class="btn btn-success">
               Découvrir nos produits
           </a>

           <a href="order.php" class="btn btn-warning">
              Commander maintenant
           </a>

        </div>

    </div>


</div>

</section>

<section class="stats-section">

    <div class="container">

        <div class="row g-4 justify-content-center">


            <!-- Carte 1 -->
            <div class="col-lg-3 col-md-6">

                <div class="stat-card">

                    <i class="fa-solid fa-leaf"></i>

                    <div>
                        <h4>10+ produits naturels</h4>
                    </div>

                </div>

            </div>



            <!-- Carte 2 -->
            <div class="col-lg-3 col-md-6">

                <div class="stat-card">

                    <i class="fa-solid fa-apple-whole pomme"></i>

                    <div>
                        <h4>100% locaux</h4>
                    </div>

                </div>

            </div>




            <!-- Carte 3 -->
            <div class="col-lg-3 col-md-6">

                <div class="stat-card">

                    <i class="fa-solid fa-users"></i>

                    <div>
                        <h4>500+ clients satisfaits</h4>
                    </div>

                </div>

            </div>




            <!-- Carte 4 -->
            <div class="col-lg-3 col-md-6">

                <div class="stat-card">

                    <i class="fa-solid fa-award"></i>

                    <div>
                        <h4>5 ans d'expérience</h4>
                    </div>

                </div>

            </div>



        </div>

    </div>

</section>

<section class="about-section">

    <div class="container">

        <div class="row align-items-center g-5">


            <!-- COLONNE IMAGE -->
            <div class="col-lg-6">

                <div class="about-image">

                    <img src="images/histoire.png" 
                    alt="Temps Libre histoire">
                     <div class="badge-artisanale">
                         <span>Fabrication</span>
                        <strong>100% artisanale</strong>
                     </div>

                </div>

            </div>



            <!-- COLONNE TEXTE -->
            <div class="col-lg-6">

                <div class="about-content">

                    <h5>
                        Qui sommes-nous ?
                    </h5>

                    <h2>
                        Notre histoire
                    </h2>


                    <p>
                        Depuis plusieurs années, Temps Libre valorise les richesses 
                        du terroir sénégalais à travers la transformation de fruits 
                        locaux en boissons naturelles, saines et savoureuses.
                    </p>


                    <p>
                        Installés à Patte d'Oie, près de la Clinique de Naby Choucair 
                        à Dakar, nous sélectionnons soigneusement les meilleurs 
                        ingrédients pour vous offrir le meilleur de la nature.
                    </p>


                    <a href="about.php" class="btn-about">
                        En savoir plus 
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>


                </div>

            </div>


        </div>

    </div>

</section>


<section class="products-section">

    <div class="container">

        <div class="section-title">

            <span>NOS PRODUITS</span>

            <h2>Nos jus naturels</h2>

        </div>

        <div class="row g-4 justify-content-center">

            <!-- Produit 1 -->
            <div class="col-lg col-md-4 col-sm-6">

                <div class="product-card">

                    <div class="product-image">
                        <img src="images/bouye.png" alt="">
                    </div>

                    <div class="product-body">

                        <h4>Jus de Bouye</h4>

                        <p>Riche en calcium et en fibres</p>

                        <div class="price">
                            1 500 FCFA
                        </div>

                        <a href="produit.php" class="btn-product">
                            Voir le produit
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

            </div>

            <!-- Produit 2 -->
            <div class="col-lg col-md-4 col-sm-6">

                <div class="product-card">

                    <div class="product-image">
                        <img src="images/bissap.png" alt="">
                    </div>

                    <div class="product-body">

                        <h4>Jus de Bissap</h4>

                        <p>Rafraîchissant et antioxydant</p>

                        <div class="price">
                            1 500 FCFA
                        </div>

                        <a href="produit.php" class="btn-product">
                            Voir le produit
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

            </div>

            <!-- Produit 3 -->

            <div class="col-lg col-md-4 col-sm-6">

                <div class="product-card">

                    <div class="product-image">
                        <img src="images/gingembre.png" alt="">
                    </div>

                    <div class="product-body">

                        <h4>Jus de Gingembre</h4>

                        <p>Stimulant et tonifiant</p>

                        <div class="price">
                            1 500 FCFA
                        </div>

                        <a href="produit.php" class="btn-product">
                            Voir le produit
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

            </div>

            <!-- Produit 4 -->

            <div class="col-lg col-md-4 col-sm-6">

                <div class="product-card">

                    <div class="product-image">
                        <img src="images/morenga.png" alt="">
                    </div>

                    <div class="product-body">

                        <h4>Jus de Morenga</h4>

                        <p>Riche en vitamines et fer</p>

                        <div class="price">
                            1 500 FCFA
                        </div>

                        <a href="produit.php" class="btn-product">
                            Voir le produit
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

            </div>

            <!-- Produit 5 -->

            <div class="col-lg col-md-4 col-sm-6">

                <div class="product-card">

                    <div class="product-image">
                        <img src="images/caressol.png" alt="">
                    </div>

                    <div class="product-body">

                        <h4>Jus de Caressol</h4>

                        <p>Saveur naturelle et exotique</p>

                        <div class="price">
                            1 500 FCFA
                        </div>

                        <a href="produit.php" class="btn-product">
                            Voir le produit
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="text-center mt-5">

            <a href="produits.php" class="btn-all-products">

                Voir tous les produits

                <i class="fa-solid fa-angle-right"></i>

            </a>

        </div>

    </div>

</section>


<!-- ================== Nos engagements ================== -->
<section class="engagements">
    <div class="container">

        <div class="row g-4 justify-content-center">

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-seedling"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>100% Naturel</h6>
                        <p>Sans colorant<br>ni conservateur</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-apple-whole"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>Fruits locaux</h6>
                        <p>Sélectionnés avec<br>soin au Sénégal</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-bottle-water"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>Fabrication artisanale</h6>
                        <p>Préparés avec passion<br>et savoir-faire</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>Qualité garantie</h6>
                        <p>Respect des normes<br>d'hygiène</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>Livraison rapide</h6>
                        <p>Livraison à Dakar<br>et ses environs</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="engagement-item">
                    <div class="engagement-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <div class="engagement-text">
                        <h6>Paiement sécurisé</h6>
                        <p>Vos paiements<br>sont protégés</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ===================== PROCESSUS ===================== -->

<section class="processus py-5">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-subtitle">
                Notre processus
            </span>

            <h2 class="section-title">
                De la nature à votre bouteille
            </h2>

        </div>

        <div class="processus-wrapper">

            <!-- Etape 1 -->
            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>

                <h6>1. Récolte</h6>

                <p>Récolte des meilleurs fruits locaux</p>

            </div>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <!-- Etape 2 -->

            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <h6>2. Sélection</h6>

                <p>Tri et sélection rigoureuse</p>

            </div>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <!-- Etape 3 -->

            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-droplet"></i>
                </div>

                <h6>3. Transformation</h6>

                <p>Transformation artisanale et naturelle</p>

            </div>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <!-- Etape 4 -->

            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-bottle-water"></i>
                </div>

                <h6>4. Conditionnement</h6>

                <p>Mise en bouteille hygiénique</p>

            </div>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <!-- Etape 5 -->

            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <h6>5. Contrôle qualité</h6>

                <p>Vérification à chaque étape</p>

            </div>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <!-- Etape 6 -->

            <div class="step">

                <div class="step-icon">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>

                <h6>6. Livraison</h6>

                <p>Livraison rapide et sécurisée</p>

            </div>

        </div>

    </div>

</section>

<!--==================== AVIS + CTA ====================-->

<section class="clients-cta py-5">

    <div class="container">

        <div class="row g-4 align-items-stretch">

            <!-- ================= Avis ================= -->

            <div class="col-lg-6">

                <span class="section-subtitle">
                    ILS NOUS FONT CONFIANCE
                </span>

                <h2 class="section-title mb-4">
                    Avis de nos clients
                </h2>

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="testimonial-card">

                            <div class="stars">
                                ★★★★★
                            </div>

                            <p>
                                "Des jus délicieux et très naturels.
                                Ma famille adore le bissap !"
                            </p>

                            <div class="client">

                                <img src="images/avatar1.jpg" alt="">

                                <span>Aissatou D.</span>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="testimonial-card">

                            <div class="stars">
                                ★★★★★
                            </div>

                            <p>
                                "Qualité irréprochable et
                                service toujours au top."
                            </p>

                            <div class="client">

                                <img src="images/avatar2.jpg" alt="">

                                <span>Mamadou L.</span>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="testimonial-card">

                            <div class="stars">
                                ★★★★★
                            </div>

                            <p>
                                "Le jus de moringa est devenu
                                mon préféré."
                            </p>

                            <div class="client">

                                <img src="images/avatar3.jpg" alt="">

                                <span>Fatou B.</span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ================= CTA ================= -->

            <div class="col-lg-6">

                <div class="commande-box">

                    <div class="commande-content">

                        <h2>
                            Envie de commander ?
                        </h2>

                        <p>
                            Commandez vos jus préférés en quelques clics
                            et faites-vous livrer partout à Dakar.
                        </p>

                        <a href="order.php" class="btn-commande">
                            Commander maintenant
                        </a>

                    </div>

                    

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

