<?php
session_start();
// session_destroy();
// session_start();
$userFailed = false;
$passwordFailed = false;
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
        'role' => null
    ];

    foreach ($users as $user) {
        if (in_array($loggingUser['username'], $user)) {
            $userFailed = false;

            if ($loggingUser['password'] == $user['password']) {
                $loggedIn = true;
                $passwordFailed = false;
                $userFailed = false;
                $_SESSION['username'] = $username;
                if ($user['role'] == 'admin') {
                    $_SESSION['role'] = 'admin';
                    header('location: admin.php');
                } else {
                    $_SESSION['role'] = 'user';
                    header("location: main.php");
                }

            } else {
                $passwordFailed = true;
            }
        } else {
            $userFailed = true;
        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Web - Login</title>
    <script src="../scripts/jquery.min.js" defer></script>
    <script src="../scripts/template.js" defer></script>
    <script src="../scripts/script.js" defer></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body>
    <p><?php echo $userFailed ? "Failed" : "Not failed";
    echo " | ";
    echo $passwordFailed ? "P Failed" : "P Not failed" ?></p>
    <header id="header"></header>
    <main id="main" class="flex">
        <div id="loginCell">
            <h1>Inicio de Sesión</h1>
            <form action="login.php" method="POST" class="flex col">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Entrar">
            </form>

            <h3>No tienes cuenta? <a href="register.php">Regístrate</a></h3>
            <h3 class="<?php echo $userFailed > 0 ? "" : "hide" ?>">No hay usuario con ese nombre</h3>
        </div>
    </main>
    <footer id="footer"></footer>
</body>

</html>