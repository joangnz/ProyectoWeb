<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
}

setcookie("user_role", "admin", time() + (86400 * 30), "/");

if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    closeSession();  // Llamar a la función PHP
    echo 'logged_out';
    exit;
}

function readCookie()
{
    if (isset($_COOKIE['user_role'])) {
        echo "Rol de usuario: " . $_COOKIE['user_role'];
    } else {
        echo "No se ha configurado un rol de usuario.";
    }
}

function removeCookie()
{
    setcookie("user_role", "", time() - 3600, "/");
}

function readSession()
{
    return "User:" . $_SESSION['user'];
}
function closeSession()
{
    session_unset();
    session_destroy();
}

?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Web</title>
    <script src="../scripts/jquery.min.js" defer></script>
    <script src="../scripts/howler.core.js" defer></script>
    <script src="../scripts/template.js" defer></script>
    <script src="../scripts/script.js" defer></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body id="main-body">
    <header id="header-main" class="flex"></header>
    <main id="main">
        <div id="container" class="flex">
            <section class="box flex col" id="playing">
                <div id="volume-holder">
                    <input type="range" id="volume" value="65" min="0" max="100" step="1">
                </div>
                <div id="banner"></div>
                <div id="button-holder" class="flex">
                    <button id="back"></button>
                    <button id="play"></button>
                    <button id="pause" class="hide"></button>
                    <button id="next"></button>
                </div>
                <div id="timeline-holder" class="flex">
                    <span class="timestamp" id="current-time">0:00</span>
                    <input type="range" id="timeline" value="0" min="0" max="100" step="1">
                    <span class="timestamp" id="end-time">0:00</span>
                </div>
            </section>
            <section class="box" id="queue-holder">
                <ul id="queue" class="flex col"></ul>
            </section>
        </div>

        <section id="mood" class="flex col">
            <button id="toggle-mood-form">SHOW MOOD SELECTION</button>
            <form id="mood-form" class="hide flex col">
                <input type="radio" name="mood" id="happy"><label for="happy">Happy</label>
                <input type="radio" name="mood" id="sad"><label for="sad">Sad</label>
                <input type="radio" name="mood" id="relaxed"><label for="relaxed">Relaxed</label>
                <input type="radio" name="mood" id="angry"><label for="angry">Angry</label>
                <input type="radio" name="mood" id="inspired"><label for="inspired">Inspired</label>
            </form>
        </section>
    </main>
    <footer id="footer">
    </footer>
</body>

</html>