<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db_connection.php';
session_start();

// Proteção da página: só admins podem cadastrar
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

$message = "";
$message_type = "";

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $user = trim($_POST['user']);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Por favor, insira um e-mail válido.";
    } else {
        try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("INSERT INTO dados (nome, email, user, pass) VALUES (:nome, :email, :user, :pass)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user', $user);
        $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindParam(':pass', $senha_hashed);
        $stmt->execute();

            $success_message = "Usuário cadastrado com sucesso!";
        } catch (PDOException $e) {
            $error_message = "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}

$pageTitle = "Cadastrar Usuário";
include 'header_template.php';
?>

<div class="box box-narrow">
    <h1>Novo Usuário</h1>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: João Silva">
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required placeholder="Ex: joao@exemplo.com">
        </div>
        <div class="form-group">
            <label for="user">Usuário</label>
            <input type="text" id="user" name="user" required placeholder="Ex: joaosilva">
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required placeholder="Crie uma senha segura">
        </div>
        <button type="submit">Cadastrar</button>
    </form>

    <div class="text-center mt-2">
        <a href="index.php" class="btn-secondary">Voltar ao Menu</a>
    </div>
</div>

<?php include 'footer_template.php'; ?>
