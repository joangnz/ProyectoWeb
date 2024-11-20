<?php
session_start();
// session_destroy();
// session_start();
$userFailed = false;
$loggedIn = false;

if (isset($_SESSION['username'])) {
    header("location: main.php");
}
function readUsers()
{
    $users = json_decode(file_get_contents("../data/users.json"), true) ?? [];
    return $users;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readUsers();

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $loggingUser = [
        'id' => uniqid(),
        'user' => $username,
        'password' => $password,
        'role' => 'user'
    ];

    foreach ($users as $user) {
        if ($user['user'] === $username) {
            // El usuario ya existe.
            $userFailed = true;
            $_SESSION['error'] = 'El nombre de usuario ya está en uso.';
            header('Location: register.php');
        }
    }

    if (!$userFailed) {
        // Si no existe el usuario, procede a registrarlo.
        $loggingUser = [
            'id' => uniqid(),
            'user' => $username,
            'password' => $password,
            'role' => 'user'
        ];

        // Agrega el nuevo usuario al array de usuarios.
        $users[] = $loggingUser;

        // Guarda los usuarios de nuevo en el archivo.
        file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Establece la sesión y redirige al usuario a la página principal.
        $_SESSION['username'] = $username;
        header('Location: main.php');
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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body>
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