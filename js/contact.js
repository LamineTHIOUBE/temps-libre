/* =========================================================
   PAGE CONTACT - TEMPS LIBRE
   VALIDATION JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

/* =====================================================
   MESSAGE PHP - DISPARITION AUTOMATIQUE
===================================================== */

const phpMessage =
    document.querySelector(".php-message");


if (phpMessage) {

    setTimeout(function () {

        phpMessage.style.transition =
            "opacity 0.5s ease, transform 0.5s ease";

        phpMessage.style.opacity = "0";

        phpMessage.style.transform =
            "translateY(-10px)";


        setTimeout(function () {

            phpMessage.remove();

        }, 500);

    }, 6000);

}


    const contactForm = document.getElementById("contactForm");

    if (!contactForm) {
        return;
    }


/* =====================================================
   BOUTON ENVOI
===================================================== */

const btnEnvoyer =
    document.getElementById("btnEnvoyer")



    /* =====================================================
       RÉCUPÉRATION DES CHAMPS
    ===================================================== */

    const nom = document.getElementById("nom");

    const email = document.getElementById("email");

    const sujet = document.getElementById("sujet");

    const message = document.getElementById("message");


    /* =====================================================
       SOUMISSION
    ===================================================== */

    contactForm.addEventListener("submit", function (event) {

        /*
         * On empêche temporairement l'envoi afin
         * de vérifier les champs côté navigateur.
         */

        event.preventDefault();


        /* Supprimer les anciennes erreurs */

        supprimerErreurs();


        let formulaireValide = true;


        /* =================================================
           NOM
        ================================================= */

        if (nom.value.trim() === "") {

            afficherErreur(
                nom,
                "Veuillez saisir votre nom complet."
            );

            formulaireValide = false;

        }


        /* =================================================
           EMAIL
        ================================================= */

        if (email.value.trim() === "") {

            afficherErreur(
                email,
                "Veuillez saisir votre adresse e-mail."
            );

            formulaireValide = false;

        }

        else if (!validerEmail(email.value.trim())) {

            afficherErreur(
                email,
                "Veuillez saisir une adresse e-mail valide."
            );

            formulaireValide = false;

        }


        /* =================================================
           SUJET
        ================================================= */

        if (sujet.value === "") {

            afficherErreur(
                sujet,
                "Veuillez sélectionner un sujet."
            );

            formulaireValide = false;

        }


        /* =================================================
           MESSAGE
        ================================================= */

        if (message.value.trim() === "") {

            afficherErreur(
                message,
                "Veuillez saisir votre message."
            );

            formulaireValide = false;

        }

        else if (message.value.trim().length < 10) {

            afficherErreur(
                message,
                "Votre message doit contenir au moins 10 caractères."
            );

            formulaireValide = false;

        }


        /* =================================================
           ENVOI VERS PHP
        ================================================= */

    
if (formulaireValide) {

    /* Désactiver le bouton */

    if (btnEnvoyer) {

        btnEnvoyer.disabled = true;

        btnEnvoyer.classList.add("sending");


        /* Modifier le texte */

        const texteBouton =
            btnEnvoyer.querySelector("span");


        if (texteBouton) {

            texteBouton.textContent =
                "Envoi en cours...";

        }


        /* Modifier l'icône */

        const iconeBouton =
            btnEnvoyer.querySelector("i");


        if (iconeBouton) {

            iconeBouton.className =
                "fa-solid fa-spinner fa-spin";

        }

    }


    /* Envoyer vers PHP */

    contactForm.submit();

}



    });


    /* =====================================================
       VALIDATION EMAIL
    ===================================================== */

    function validerEmail(email) {

        const regex =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return regex.test(email);

    }


    /* =====================================================
       AFFICHER ERREUR
    ===================================================== */

    function afficherErreur(champ, messageErreur) {

        champ.classList.add("input-error");


        const groupe =
            champ.closest(".form-group");


        if (!groupe) {

            return;

        }


        const erreur =
            document.createElement("small");


        erreur.className =
            "form-error";


        erreur.innerHTML = `
            <i class="fa-solid fa-circle-exclamation"></i>
            ${messageErreur}
        `;


        groupe.appendChild(erreur);

    }


    /* =====================================================
       SUPPRIMER ERREURS
    ===================================================== */

    function supprimerErreurs() {

        const champs =
            contactForm.querySelectorAll(".input-error");


        champs.forEach(function (champ) {

            champ.classList.remove("input-error");

        });


        const erreurs =
            contactForm.querySelectorAll(".form-error");


        erreurs.forEach(function (erreur) {

            erreur.remove();

        });

    }

});
