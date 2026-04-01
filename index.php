<?php

require_once 'db_connection.php';

$dbuser = 'admin';
$dbsenha = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM pessoas WHERE id = :id AND senha = :senha");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Login bem-sucedido!";
        } else {
            echo "ID ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
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

    <?php if (isset($dbuser) && isset($dbsenha) && $id === $dbuser && $senha === $dbsenha): ?>
        <h2>Menu Admin</h2>
        <ul>
            <li><a href="cadastrar_usuario.php">Cadastrar Usuário</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>
