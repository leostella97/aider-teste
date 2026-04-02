<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connection.php';

$db = Database::getInstance()->getPdo();

try {
    $stmt = $db->query('SELECT * FROM pessoas');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar usuários: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuários</title>
</head>
<body>
    <h1>Lista de Usuários</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>User</th>
            <!-- Adicione mais colunas conforme necessário -->
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo isset($user['nome']) ? htmlspecialchars($user['nome']) : ''; ?></td>
            <td><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?></td>
            <td><?php echo isset($user['user']) ? htmlspecialchars($user['user']) : ''; ?></td>
            <!-- Adicione mais colunas conforme necessário -->
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
