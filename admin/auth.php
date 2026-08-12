<?php

/* =========================================================
   VÉRIFICATION DE LA SESSION ADMINISTRATEUR
   ========================================================= */

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}


/* =========================================================
   VÉRIFIER SI L'ADMIN EST CONNECTÉ
   ========================================================= */

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");

    exit;

}
