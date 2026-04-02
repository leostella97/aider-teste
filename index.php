<?php

require_once 'db_connection.php';

session_start();

// Definir o tempo limite da sessão (10 minutos)
$inactive = 600; // 10 minutos em segundos

// Apenas desloga se a sessão já estiver ativa e expirada
if (isset($_SESSION['start']) && $_SESSION['start'] + $inactive < time()) {
    session_unset();
    echo "Sessão expirada. Por favor, faça login novamente.";
}

// Atualizar o tempo da sessão apenas se o usuário estiver logado
if (isset($_SESSION['admin']) || isset($_SESSION['user'])) {
    $_SESSION['start'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    $admin_user = 'admin';
    $admin_senha = 'admin';

    if ($user === $admin_user && $senha === $admin_senha) {
        echo "Login como Administrador bem-sucedido!";
        $_SESSION['admin'] = true;
        $_SESSION['start'] = time();
        header("Location: index.php");
        exit();
    } else {
        try {
            $database = Database::getInstance();
            $pdo = $database->getPdo();

            $stmt = $pdo->prepare("SELECT user, pass FROM dados WHERE user = :user");
            $stmt->bindParam(':user', $user);

            $stmt->execute();

            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data && password_verify($senha, $user_data["pass"])) {
                echo "Login bem-sucedido!";
                $_SESSION['user'] = $user_data['user'];
                $_SESSION['start'] = time();
                header("Location: index.php");
                exit();
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
    <title>Login / Home</title>
</head>
<body>
    <?php if (!(isset($_SESSION['admin']) || isset($_SESSION['user']))): ?>
        <h1>Faça seu login</h1>
        <form method="post" action="">
            <label for="user">User:</label>
            <input type="text" id="user" name="user"><br><br>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha"><br><br>
            <button type="submit">Entrar</button>
        </form>
    <?php else: ?>
        <h1>Bem-vindo, <?php echo htmlspecialchars(isset($_SESSION['admin']) ? 'Administrador' : $_SESSION['user']); ?>!</h1>
        <a href="logout.php">Sair</a>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <h2>Menu Admin</h2>
            <ul>
                <li><a href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
                <li><a href="listar_usuarios.php">Listar Usuários</a></li>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
