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
    $email = $_POST['email'];
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("INSERT INTO pessoas (nome, email, user, senha) VALUES (:nome, :email, :user, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user', $user);
        $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindParam(':senha', $senha_hashed);
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Cadastre um novo usuário</h4>
                    <a href="index.php" class="btn btn-sm btn-outline-light">Voltar</a>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo:</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Username:</label>
                            <input type="text" id="user" name="user" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
