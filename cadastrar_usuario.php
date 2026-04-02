<?php

require_once 'db_connection.php';
session_start();

// Proteção da página: só admins podem cadastrar
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("INSERT INTO dados (nome, user, pass) VALUES (:nome, :user, :pass)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':user', $user);
        $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindParam(':pass', $senha_hashed);
        $stmt->execute();

        $message = "Usuário cadastrado com sucesso!";
        $message_type = "success";
    } catch (PDOException $e) {
        $message = "Erro ao cadastrar usuário: " . $e->getMessage();
        $message_type = "danger";
    }
}

$page_title = "Cadastrar Usuário - Sistema";
include 'header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h1>Cadastre um novo usuário</h1>
    <form method="post" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"><br><br>
        <label for="user">User:</label>
        <input type="text" id="user" name="user"><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="index.php">Voltar ao Menu</a>
</body>
</html>
