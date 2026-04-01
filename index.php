<?php

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $senha = $_POST['senha'];

    $admin_id = 'admin';
    $admin_senha = 'admin';

    if ($id === $admin_id && $senha === $admin_senha) {
        echo "Login como Administrador bem-sucedido!";
        $is_admin = true;
    } else {
        try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT id, senha FROM pessoas WHERE id = :id");
        $stmt->bindParam(':id', $id);
 
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user["senha"])) {
            echo "Login bem-sucedido!";
            // Definir uma sessão aqui, por exemplo: $_SESSION["user_id"] = $user["id"];
            // E então redirecionar para uma página restrita, como o menu admin
        } else {
            echo "ID ou senha incorretos.";
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
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br><br>
        <button type="submit">Entrar</button>
    </form>

    <?php if (isset($is_admin) && $is_admin): ?>
        <h2>Menu Admin</h2>
        <ul>
            <li><a href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>
