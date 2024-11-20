<?php
session_start();

if ($_SESSION["role"] != "admin") {
    header("location: main.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readUsers();

    try {
        if (isset($_POST['edit'])) {
            echo $_POST['edit'] . $_POST['newName'];
            echo updateUser($_POST['edit'], $_POST['newName']);
        }
    } catch (Exception $e) {
    }

    try {
        if (isset($_POST['delete'])) {
            echo deleteUser($_POST['delete']);
        }
    } catch (Exception $e) {
    }

}


function readUsers()
{
    $users = json_decode(file_get_contents("../data/users.json"), true) ?? [];
    return $users;
}

function updateUser($id, $newName)
{
    $users = readUsers();

    $userIndex = array_search($id, array_column($users, 'id'));
    if ($userIndex !== false) {
        if ($users[$userIndex]['role'] !== "admin") {
            $users[$userIndex]['user'] = $newName;

            file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

            return "User updated";
        }
        return "Cannot edit administrators.";
    }
    return "User not found";
}

function deleteUser($id)
{
    $users = readUsers();

    $userIndex = array_search($id, array_column($users, "id"));
    if ($userIndex !== false) {
        if ($users[$userIndex]["role"] !== "admin") {
            unset($users[$userIndex]);
            $users = array_values($users);

            file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

            return "User deleted";
        }

        return "Cannot delete administrators.";
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
    <script src="../scripts/admin.js" defer></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/util.css">

</head>

<body>
    <header id="header-main"></header>
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
                            <form method="POST">
                                <button class="toggleEdit" type="button" name="edit"
                                    value="<?php echo $user['id'] ?>">Edit</button>
                                <div class="hide" class="editForm">
                                    <label for="newName">New Username</label>
                                    <input type="text" class="newName" name="newName">
                                    <button type="submit" value="<?php echo $user['id'] ?>" name="edit">Edit User</button>
                                </div>
                            </form>
                            <form method="POST">
                                <button type="submit" value="<?php echo $user['id'] ?>" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer id="footer"></footer>
</body>

</html>