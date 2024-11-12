<?php
session_start();

if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    echo "ADMIN";
} else {
    header("location: main.php");
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Web - Admin</title>
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