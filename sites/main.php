<?php
session_start();
setcookie("user_role", "admin", time() + (86400 * 30), "/");

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
    <script src="../scripts/template.js" defer></script>
    <script src="../scripts/script.js" defer></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body>
    <header id="header"></header>
    <main id="main" class="flex">
    </main>
    <footer id="footer"></footer>
</body>

</html>