// =====================================
// TEMPS LIBRE - PANIER
// =====================================


// Récupération panier

let panier = JSON.parse(localStorage.getItem("panier")) || [];


// Livraison

const LIVRAISON = 1000;



// Afficher panier

function afficherPanier(){


const tbody = document.getElementById("cart-items");


if(!tbody) return;



tbody.innerHTML="";


let sousTotal = 0;



panier.forEach((produit,index)=>{


let totalProduit = produit.prix * produit.quantite;


sousTotal += totalProduit;



tbody.innerHTML += `


<tr>


<td>

<div class="d-flex align-items-center gap-3">


<img src="${produit.image}" width="70">


<div>

${produit.nom}

</div>


</div>


</td>



<td>

${produit.prix.toLocaleString()} FCFA

</td>




<td>


<button onclick="modifierQuantite(${index},-1)">
-
</button>



${produit.quantite}



<button onclick="modifierQuantite(${index},1)">
+
</button>


</td>




<td>

${totalProduit.toLocaleString()} FCFA

</td>




<td>


<button onclick="supprimerProduit(${index})">

<i class="fa-solid fa-trash"></i>

</button>


</td>



</tr>


`;



});




document.getElementById("subtotal").innerHTML =
sousTotal.toLocaleString()+" FCFA";



document.getElementById("shipping").innerHTML =
LIVRAISON.toLocaleString()+" FCFA";



document.getElementById("total").innerHTML =
(sousTotal+LIVRAISON).toLocaleString()+" FCFA";



mettreAJourCompteur();


}




// Modifier quantité

function modifierQuantite(index,valeur){


panier[index].quantite += valeur;



if(panier[index].quantite <=0){

panier[index].quantite=1;

}



sauvegarderPanier();


afficherPanier();


}




// Supprimer produit

function supprimerProduit(index){


panier.splice(index,1);


sauvegarderPanier();


afficherPanier();


}




// Compteur panier

function mettreAJourCompteur(){


let compteur = document.getElementById("cart-count");


if(!compteur) return;



let total = panier.reduce(
(acc,p)=> acc+p.quantite,
0
);



compteur.innerHTML = total;



}



// Chargement automatique

document.addEventListener(
"DOMContentLoaded",
()=>{


afficherPanier();


}
);