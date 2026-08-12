<?php

require "config/database.php";


$sql = "SELECT * FROM produits";


$stmt = $conn->prepare($sql);


$stmt->execute();


$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Boutique | Temps Libre</title>


<!-- Bootstrap -->

<link 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
rel="stylesheet">



<!-- Font Awesome -->

<link 
rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



<!-- CSS principal -->

<link rel="stylesheet" href="assets/css/style.css">


</head>


<body>



<!-- ================================
HEADER
================================ -->


<header class="header">


<nav class="navbar navbar-expand-lg bg-white">


<div class="container">


<!-- Logo -->

<a class="navbar-brand" href="index.php">


<img src="assets/images/logo.png" 
alt="Temps Libre"
class="logo">


</a>




<button 
class="navbar-toggler menu-toggle"
type="button">

<span class="navbar-toggler-icon"></span>

</button>




<div class="collapse navbar-collapse menu-principal">


<ul class="navbar-nav mx-auto">


<li class="nav-item">
<a class="nav-link" href="index.php">
Accueil
</a>
</li>


<li class="nav-item">
<a class="nav-link" href="apropos.php">
À propos
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="produits.php">
Produits
</a>
</li>



<li class="nav-item">
<a class="nav-link" href="contact.php">
Contact
</a>
</li>


</ul>




<!-- Panier -->


<div class="cart-area">


<a href="panier.php" class="cart-icon">


<i class="fa-solid fa-cart-shopping"></i>


<span id="cart-count">
0
</span>


</a>



<a href="panier.php" 
class="btn commander">

Commander

</a>


</div>



</div>


</div>


</nav>


</header>






<!-- ================================
BANNIERE BOUTIQUE
================================ -->


<section class="shop-banner">


<div class="container text-center">


<h1>
Nos produits naturels
</h1>


<p>
Découvrez les saveurs authentiques du terroir sénégalais
</p>


</div>


</section>







<!-- ================================
PRODUITS
================================ -->


<div class="row g-4">


<?php foreach($produits as $produit): ?>


<div class="col-lg-4 col-md-6">


<div class="product-card">


<img src="uploads/produits/<?php echo $produit['image']; ?>"
class="product-img">


<div class="product-content">


<h3>

<?= $produit['nom']; ?>

</h3>


<p>

<?= $produit['description']; ?>

</p>


<div class="price">

<?= number_format($produit['prix'],0,' ',' '); ?> FCFA

</div>



<button 
class="btn add-cart"


onclick="ajouterAuPanier({

id:<?= $produit['id']; ?>,

nom:'<?= $produit['nom']; ?>',

prix:<?= $produit['prix']; ?>,

image:'uploads/produits/<?= $produit['image']; ?>'


})">


Ajouter au panier

</button>



</div>


</div>


</div>



<?php endforeach; ?>


</div>






<!-- FOOTER -->


<footer>


<div class="container text-center">


<p>
© Temps Libre - Tous droits réservés
</p>


</div>


</footer>






<!-- JS -->

<script src="assets/js/produits.js"></script>

<script src="assets/js/script.js"></script>



</body>

</html>