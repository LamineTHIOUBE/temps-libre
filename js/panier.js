// =====================================
// TEMPS LIBRE - PANIER
// =====================================


// =====================================
// RÉCUPÉRATION DU PANIER
// =====================================

let panier = JSON.parse(localStorage.getItem("panier")) || [];


// =====================================
// LIVRAISON
// =====================================

const LIVRAISON = 1000;


// =====================================
// AFFICHER LE PANIER
// =====================================

function afficherPanier() {

    const container = document.getElementById("panier-container");
    const panierVide = document.getElementById("panier-vide");

    if (!container) return;


    // Vider le conteneur
    container.innerHTML = "";


    // =================================
    // PANIER VIDE
    // =================================

    if (panier.length === 0) {

        if (panierVide) {
            panierVide.style.display = "block";
        }

        mettreAJourCompteur();

        return;
    }


    // Cacher le message panier vide
    if (panierVide) {
        panierVide.style.display = "none";
    }


    // =================================
    // CALCUL DU SOUS-TOTAL
    // =================================

    let sousTotal = 0;


    panier.forEach((produit, index) => {

        const prix = parseFloat(produit.prix) || 0;
        const quantite = parseInt(produit.quantite) || 1;

        const totalProduit = prix * quantite;

        sousTotal += totalProduit;


        // =================================
        // IMAGE
        // =================================

        let imageProduit = "";

        if (produit.image) {

            imageProduit =
                `images/${encodeURIComponent(produit.image)}`;

        } else {

            imageProduit =
                "images/produit-placeholder.png";

        }


        // =================================
        // CARTE PRODUIT
        // =================================

        container.innerHTML += `

            <div class="panier-produit">

                <div class="panier-produit-image">

                    <img
                        src="${imageProduit}"
                        alt="${produit.nom}"
                    >

                </div>


                <div class="panier-produit-info">

                    <h3>
                        ${produit.nom}
                    </h3>

                    <p class="panier-produit-prix">
                        ${prix.toLocaleString("fr-FR")} FCFA
                    </p>

                </div>


                <div class="panier-quantite">

                    <button
                        type="button"
                        onclick="modifierQuantite(${index}, -1)"
                        aria-label="Diminuer la quantité"
                    >
                        <i class="fa-solid fa-minus"></i>
                    </button>


                    <span>
                        ${quantite}
                    </span>


                    <button
                        type="button"
                        onclick="modifierQuantite(${index}, 1)"
                        aria-label="Augmenter la quantité"
                    >
                        <i class="fa-solid fa-plus"></i>
                    </button>

                </div>


                <div class="panier-produit-total">

                    ${totalProduit.toLocaleString("fr-FR")} FCFA

                </div>


                <button
                    type="button"
                    class="btn-supprimer"
                    onclick="supprimerProduit(${index})"
                    aria-label="Supprimer le produit"
                >

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>

        `;

    });


    // =================================
    // RÉCAPITULATIF
    // =================================

    const total = sousTotal + LIVRAISON;


    container.innerHTML += `

        <div class="panier-recapitulatif">

            <h2>
                Récapitulatif
            </h2>


            <div class="recap-ligne">

                <span>
                    Sous-total
                </span>

                <strong>
                    ${sousTotal.toLocaleString("fr-FR")} FCFA
                </strong>

            </div>


            <div class="recap-ligne">

                <span>
                    Livraison
                </span>

                <strong>
                    ${LIVRAISON.toLocaleString("fr-FR")} FCFA
                </strong>

            </div>


            <div class="recap-separation"></div>


            <div class="recap-total">

                <span>
                    Total
                </span>

                <strong>
                    ${total.toLocaleString("fr-FR")} FCFA
                </strong>

            </div>


           <a href="order.php" class="btn-commander">

                <i class="fa-solid fa-bag-shopping"></i>

                Passer la commande

            </a>


            <a
                href="produits.php"
                class="continuer-achats"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Continuer mes achats

            </a>

        </div>




    // =================================
    // COMPTEUR DU PANIER
    // =================================

    mettreAJourCompteur();

}


// =====================================
// MODIFIER LA QUANTITÉ
// =====================================

function modifierQuantite(index, valeur) {

    if (!panier[index]) return;


    panier[index].quantite =
        parseInt(panier[index].quantite) + valeur;


    // Minimum = 1
    if (panier[index].quantite < 1) {

        panier[index].quantite = 1;

    }


    sauvegarderPanier();

    afficherPanier();

}


// =====================================
// SUPPRIMER UN PRODUIT
// =====================================

function supprimerProduit(index) {

    if (!panier[index]) return;


    panier.splice(index, 1);


    sauvegarderPanier();

    afficherPanier();

}


// =====================================
// SAUVEGARDER LE PANIER
// =====================================

function sauvegarderPanier() {

    localStorage.setItem(
        "panier",
        JSON.stringify(panier)
    );

}


// =====================================
// COMPTEUR PANIER
// =====================================

function mettreAJourCompteur() {

    const compteur =
        document.getElementById("cart-count");


    if (!compteur) return;


    const total = panier.reduce(
        (acc, produit) => {

            return acc + (
                parseInt(produit.quantite) || 0
            );

        },
        0
    );


    compteur.textContent = total;


    // Cacher le compteur lorsqu'il est à zéro
    if (total > 0) {

        compteur.style.display = "flex";

    } else {

        compteur.style.display = "none";

    }

}


// =====================================
// CHARGEMENT AUTOMATIQUE
// =====================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        afficherPanier();

    }
);