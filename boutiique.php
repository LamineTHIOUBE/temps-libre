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


<section class="products-section">


<div class="container">


<div class="row g-4">





<!-- PRODUIT 1 -->

<div class="col-lg-4 col-md-6">


<div class="product-card">


<img src="assets/images/bissap.jpg"
class="product-img"
alt="Jus de bissap">


<div class="product-content">


<h3>
Jus de Bissap
</h3>


<p>
Une boisson naturelle riche en saveurs.
</p>


<div class="price">

1500 FCFA

</div>



<button 
class="btn add-cart"

onclick="ajouterAuPanier({

id:1,

nom:'Jus de Bissap',

prix:1500,

image:'assets/images/bissap.jpg'

})">


Ajouter au panier

</button>


</div>


</div>


</div>








<!-- PRODUIT 2 -->


<div class="col-lg-4 col-md-6">


<div class="product-card">


<img src="assets/images/gingembre.jpg"
class="product-img"
alt="Jus gingembre">



<div class="product-content">


<h3>
Jus de Gingembre
</h3>


<p>
Un goût intense et naturel.
</p>



<div class="price">

1500 FCFA

</div>



<button 
class="btn add-cart"

onclick="ajouterAuPanier({

id:2,

nom:'Jus de Gingembre',

prix:1500,

image:'assets/images/gingembre.jpg'

})">


Ajouter au panier

</button>


</div>


</div>


</div>







<!-- PRODUIT 3 -->


<div class="col-lg-4 col-md-6">


<div class="product-card">


<img src="assets/images/bouy.jpg"
class="product-img"
alt="Jus bouy">


<div class="product-content">


<h3>
Jus de Bouy
</h3>


<p>
La fraîcheur du fruit du baobab.
</p>



<div class="price">

2000 FCFA

</div>



<button 
class="btn add-cart"

onclick="ajouterAuPanier({

id:3,

nom:'Jus de Bouy',

prix:2000,

image:'assets/images/bouy.jpg'

})">


Ajouter au panier

</button>


</div>


</div>


</div>




</div>

</div>


</section>






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