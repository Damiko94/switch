<?php
// connexion à la base de données

$host_db = 'mysql:host=localhost;dbname=switch'; //adresse serveur nom de la BDD
$login = 'root'; // identifiant pour la BDD
$password = ''; // le mdp de connexion a la BDD
$options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

$pdo = new PDO($host_db, $login, $password, $options);

// creation d'une variable destinée à afficher des messages utilisateurs
$msg = "";

// ouverture d'une session
session_start();

// déclaration d'une constante
// URL racine du projet
define ('URL', 'http://127.0.0.1/switch/');
// chemin racine du server
define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);
// chemin racine du dossier du site
define('SITE_ROOT', '/switch/');
