<?php

require "config/databases.php";


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

<link rel="stylesheet" href="css/style.css">


</head>


<body>



<?php
include('includes/header.php');
?>






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













<!-- JS -->

<script src="js/produits.js"></script>

<script src="js/script.js"></script>



<?php include 'includes/footer.php'; ?>



</body>

</html>