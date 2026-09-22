document.addEventListener("DOMContentLoaded", function () {

    const quantiteInput = document.getElementById("quantite");
    const boutonMoins = document.getElementById("moins");
    const boutonPlus = document.getElementById("plus");
    const boutonAjouter = document.getElementById("ajouterPanier");

    /*
    |--------------------------------------------------------------------------
    | Gestion de la quantité
    |--------------------------------------------------------------------------
    */

    if (quantiteInput && boutonMoins && boutonPlus) {

        const stockMax = parseInt(quantiteInput.max) || 1;

        boutonMoins.addEventListener("click", function () {

            let quantite = parseInt(quantiteInput.value) || 1;

            if (quantite > 1) {
                quantite--;
            }

            quantiteInput.value = quantite;
        });


        boutonPlus.addEventListener("click", function () {

            let quantite = parseInt(quantiteInput.value) || 1;

            if (quantite < stockMax) {
                quantite++;
            }

            quantiteInput.value = quantite;
        });


        quantiteInput.addEventListener("change", function () {

            let quantite = parseInt(this.value) || 1;

            if (quantite < 1) {
                quantite = 1;
            }

            if (quantite > stockMax) {
                quantite = stockMax;
            }

            this.value = quantite;
        });

    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter au panier
    |--------------------------------------------------------------------------
    */

    if (boutonAjouter) {

        boutonAjouter.addEventListener("click", function () {

            const produit = {
                id: parseInt(this.dataset.id),
                nom: this.dataset.nom,
                prix: parseFloat(this.dataset.prix),
                image: this.dataset.image,
                quantite: parseInt(quantiteInput.value) || 1
            };

            ajouterAuPanier(produit);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Fonction ajouter au panier
    |--------------------------------------------------------------------------
    */

    function ajouterAuPanier(produit) {

        let panier = JSON.parse(localStorage.getItem("panier")) || [];

        const produitExistant = panier.find(
            p => parseInt(p.id) === parseInt(produit.id)
        );

        if (produitExistant) {

            produitExistant.quantite += produit.quantite;

        } else {

            panier.push(produit);

        }

        localStorage.setItem(
            "panier",
            JSON.stringify(panier)
        );


        /*
        |--------------------------------------------------------------------------
        | Mise à jour compteur panier
        |--------------------------------------------------------------------------
        */

        mettreAJourCompteurPanier();


        /*
        |--------------------------------------------------------------------------
        | Message utilisateur
        |--------------------------------------------------------------------------
        */

        afficherNotification(
            produit.nom + " a été ajouté au panier."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Compteur panier
    |--------------------------------------------------------------------------
    */

    function mettreAJourCompteurPanier() {

        const panier = JSON.parse(
            localStorage.getItem("panier")
        ) || [];

        const totalProduits = panier.reduce(
            (total, produit) => {
                return total + parseInt(produit.quantite);
            },
            0
        );

        const compteur = document.querySelector(
            ".cart-count"
        );

        if (compteur) {

            compteur.textContent = totalProduits;

            compteur.style.display =
                totalProduits > 0 ? "flex" : "none";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */

    function afficherNotification(message) {

        const ancienneNotification =
            document.querySelector(".notification-panier");

        if (ancienneNotification) {
            ancienneNotification.remove();
        }


        const notification =
            document.createElement("div");

        notification.className =
            "notification-panier";

        notification.innerHTML = `
            <i class="fa-solid fa-circle-check"></i>

            <span>
                ${message}
            </span>
        `;

        document.body.appendChild(notification);


        setTimeout(function () {

            notification.classList.add("visible");

        }, 50);


        setTimeout(function () {

            notification.classList.remove("visible");

            setTimeout(function () {

                notification.remove();

            }, 300);

        }, 3000);

    }


    /*
    |--------------------------------------------------------------------------
    | Initialisation du compteur
    |--------------------------------------------------------------------------
    */

    mettreAJourCompteurPanier();

});