// =====================================
// TEMPS LIBRE - PAGE COMMANDE
// =====================================

let panier = JSON.parse(localStorage.getItem("panier")) || [];

const LIVRAISON = 1000;


// =====================================
// AFFICHER LE PANIER
// =====================================

function afficherCommande() {

    const container = document.getElementById("commande-produits");
    const panierVide = document.getElementById("commande-vide");
    const totaux = document.getElementById("commande-totaux");

    const affichageSousTotal = document.getElementById("affichage-sous-total");
    const affichageTotal = document.getElementById("affichage-total");

    if (!container) return;

    container.innerHTML = "";

    // -------------------------------------
    // PANIER VIDE
    // -------------------------------------

    if (panier.length === 0) {

        if (panierVide) {
            panierVide.style.display = "block";
        }

        if (totaux) {
            totaux.style.display = "none";
        }

        return;
    }

    if (panierVide) {
        panierVide.style.display = "none";
    }

    if (totaux) {
        totaux.style.display = "block";
    }


    // -------------------------------------
    // CALCUL DU SOUS-TOTAL
    // -------------------------------------

    let sousTotal = 0;


    // -------------------------------------
    // AFFICHAGE DES PRODUITS
    // -------------------------------------

    panier.forEach(function(produit) {

        const prix = parseFloat(produit.prix) || 0;

        const quantite = parseInt(produit.quantite) || 1;

        const totalProduit = prix * quantite;

        sousTotal += totalProduit;


        let imageProduit = "";

        if (produit.image) {

            imageProduit =
                `images/${encodeURIComponent(produit.image)}`;

        } else {

            imageProduit =
                "images/produit-placeholder.png";

        }


        container.innerHTML += `

            <div class="commande-produit">

                <div class="commande-produit-image">

                    <img
                        src="${imageProduit}"
                        alt="${produit.nom}"
                    >

                </div>


                <div class="commande-produit-info">

                    <h3>
                        ${produit.nom}
                    </h3>

                    <p>
                        ${quantite} ×
                        ${prix.toLocaleString("fr-FR")} FCFA
                    </p>

                </div>


                <div class="commande-produit-prix">

                    ${totalProduit.toLocaleString("fr-FR")} FCFA

                </div>

            </div>

        `;

    });


    // -------------------------------------
    // CALCUL DU TOTAL
    // -------------------------------------

    const total = sousTotal + LIVRAISON;


    // -------------------------------------
    // AFFICHAGE DES TOTAUX
    // -------------------------------------

    if (affichageSousTotal) {

        affichageSousTotal.textContent =
            sousTotal.toLocaleString("fr-FR") + " FCFA";

    }


    if (affichageTotal) {

        affichageTotal.textContent =
            total.toLocaleString("fr-FR") + " FCFA";

    }


    // -------------------------------------
    // CHAMPS CACHÉS
    // -------------------------------------

    const panierJson =
        document.getElementById("panier_json");

    const champSousTotal =
        document.getElementById("sous_total");

    const champTotal =
        document.getElementById("total");


    if (panierJson) {

        panierJson.value =
            JSON.stringify(panier);

    }


    if (champSousTotal) {

        champSousTotal.value =
            sousTotal;

    }


    if (champTotal) {

        champTotal.value =
            total;

    }

}


// =====================================
// COMPTEUR DU PANIER
// =====================================

function mettreAJourCompteur() {

    const compteur =
        document.getElementById("cart-count");

    if (!compteur) return;


    const totalArticles = panier.reduce(

        function(acc, produit) {

            return acc +
                (parseInt(produit.quantite) || 0);

        },

        0

    );


    compteur.textContent = totalArticles;


    if (totalArticles > 0) {

        compteur.style.display = "flex";

    } else {

        compteur.style.display = "none";

    }

}


// =====================================
// INITIALISATION
// =====================================

document.addEventListener(
    "DOMContentLoaded",
    function() {

        afficherCommande();

        mettreAJourCompteur();

    }
);


// =====================================
// COMMANDE VALIDÉE
// =====================================

const commandeSucces =
    new URLSearchParams(window.location.search)
        .get("success");

if (commandeSucces === "1") {

    localStorage.removeItem("panier");

    panier = [];

    mettreAJourCompteur();

}