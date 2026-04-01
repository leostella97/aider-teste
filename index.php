<?php

require_once 'db_connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user']; // Alterado de id para user
    $senha = $_POST['senha'];

    $admin_user = 'admin';
    $admin_senha = 'admin';

    if ($user === $admin_user && $senha === $admin_senha) {
        echo "Login como Administrador bem-sucedido!";
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit();
    } else {
        try {
            $database = Database::getInstance();
            $pdo = $database->getPdo();

            $stmt = $pdo->prepare("SELECT user, senha FROM pessoas WHERE user = :user");
            $stmt->bindParam(':user', $user); // Alterado de id para user

            $stmt->execute();

            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data && password_verify($senha, $user_data["senha"])) {
                echo "Login bem-sucedido!";
                // Definir uma sessão aqui, por exemplo: $_SESSION["user_id"] = $user_data["id"];
                // E então redirecionar para uma página restrita, como o menu admin
            } else {
                echo "User ou senha incorretos.";
            }
        } catch (PDOException $e) {
            echo "Erro ao processar login: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Faça seu login</h1>
    <form method="post" action="">
        <label for="user">User:</label> <!-- Alterado de ID para User -->
        <input type="text" id="user" name="user"><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br><br>
        <button type="submit">Entrar</button>
    </form>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
        <h2>Menu Admin</h2>
        <ul>
            <li><a href="listar_usuarios.php">Listar Usuários</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>
