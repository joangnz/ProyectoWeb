<?php
session_start();
// session_destroy();
// session_start();
$userFailed = false;
$loggedIn = false;

// if (isset($_SESSION['username'])) {
//     header("location: main.php");
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('../data/users.json');
    $users = json_decode($json_data, true) ?? [];

    $id = uniqid();
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $loggingUser = [
        'id' => uniqid(),
        'username' => $username,
        'password' => $password,
        'role' => 'user'
    ];

    foreach ($users as $user) {
        if (!in_array($loggingUser['username'], $user)) {
            $userFailed = false;

            $loggedIn = true;
            $userFailed = false;
            $_SESSION['username'] = $username;

            $users[] = $loggingUser;

            file_put_contents('../data/users.json', json_encode(
                $users,
                JSON_PRETTY_PRINT
            ));
            // header("Location: main.php");
            header('location: main.php');
        } else {
            $userFailed = true;
        }
    }

    if (!$userFailed) {

    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Web - Register</title>
    <script src="../scripts/jquery.min.js" defer></script>
    <script src="../scripts/template.js" defer></script>
    <script src="../scripts/script.js" defer></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body>
    <p><?php echo $userFailed ? "Existant" : "Non existant" ?></p>
    <header id="header"></header>
    <main id="main" class="flex">
        <div id="registerCell">
            <h1>Registrarse</h1>
            <form action="register.php" method="POST" class="flex col">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Entrar">
            </form>

            <h3>Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></h3>
            <h3 class="<?php echo $userFailed > 0 ? "" : "hide" ?>">Ya hay un usuario con ese nombre</h3>
        </div>
    </main>
    <footer id="footer"></footer>
</body>

</html>