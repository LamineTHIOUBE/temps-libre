// =====================================
// TEMPS LIBRE - PRODUITS
// Gestion ajout au panier
// =====================================


// Récupération du panier existant
let panier = JSON.parse(localStorage.getItem("panier")) || [];


// Ajouter un produit
function ajouterAuPanier(produit){


    let existe = panier.find(
        p => p.id === produit.id
    );


    if(existe){

        existe.quantite++;

    }else{


        panier.push({

            id: produit.id,
            nom: produit.nom,
            prix: produit.prix,
            image: produit.image,
            quantite: 1

        });

    }


    sauvegarderPanier();

    mettreAJourCompteur();


    alert("Produit ajouté au panier 🛒");


}



// Sauvegarder
function sauvegarderPanier(){

    localStorage.setItem(
        "panier",
        JSON.stringify(panier)
    );

}