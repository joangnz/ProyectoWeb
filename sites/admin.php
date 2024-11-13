<?php
session_start();

if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    echo "ADMIN";
} else {
    header("location: main.php");
}

function readUsers()
{
    $users = json_decode(file_get_contents("../data/users.json"), true) ?? [];
    return $users;
}

function updateUser($id, $newName, $newEmail)
{
    $users = readUsers();

    $userIndex = array_search($id, array_column($users, 'id'));
    if ($userIndex !== false) {
        $users[$userIndex]['name'] = $newName;
        $users[$userIndex]['email'] = $newEmail;

        file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

        return "User updated";
    }

    return "User not found";

}

function deleteUser($id)
{
    $users = readUsers();

    $userIndex = array_search($id, array_column($users, "id"));
    if ($userIndex !== false) {
        unset($users[$userIndex]);
        $users = array_values($users);

        file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

        return "User deleted";
    }

    return "User not found";
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
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $users = readUsers();
                foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']) ?></td>
                        <td><?php echo htmlspecialchars($user['user']) ?></td>
                        <td><?php echo htmlspecialchars($user['role']) ?></td>
                        <td class="actions">
                            <button>Edit</button>
                            <button>Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer id="footer"></footer>
</body>

</html>