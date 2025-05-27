<?php

// Troca idioma via GET
if (isset($_GET['lang']) && in_array($_GET['lang'], ['pt', 'en', 'es'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// Idioma padr���o
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'pt';
}

require "" . $_SESSION['lang'] . ".php";
?>
