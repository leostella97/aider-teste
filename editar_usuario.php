<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db_connection.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listar_usuarios.php");
    exit();
}

$db = Database::getInstance()->getPdo();
$success_message = "";
$error_message = "";

// Fetch user data
try {
    $stmt = $db->prepare("SELECT * FROM dados WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: listar_usuarios.php?error=" . urlencode("Usuário não encontrado."));
        exit();
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $username = $_POST['user'];
    $senha = $_POST['senha'];

    try {
        if (!empty($senha)) {
            $stmt = $db->prepare("UPDATE dados SET nome = :nome, user = :user, pass = :pass WHERE id = :id");
            $hashed_pass = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->execute([
                ':nome' => $nome,
                ':user' => $username,
                ':pass' => $hashed_pass,
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE dados SET nome = :nome, user = :user WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':user' => $username,
                ':id' => $id
            ]);
        }

        header("Location: listar_usuarios.php?success=" . urlencode("Usuário atualizado com sucesso!"));
        exit();
    } catch (PDOException $e) {
        $error_message = "Erro ao atualizar usuário: " . $e->getMessage();
    }
}

$pageTitle = "Editar Usuário";
include 'header_template.php';
?>

<div class="box box-narrow">
    <h1>Editar Usuário</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user">Usuário</label>
            <input type="text" id="user" name="user" value="<?php echo htmlspecialchars($user['user']); ?>" required>
        </div>
        <div class="form-group">
            <label for="senha">Nova Senha (deixe em branco para manter a atual)</label>
            <input type="password" id="senha" name="senha">
        </div>
        <button type="submit">Salvar Alterações</button>
    </form>

    <div class="text-center mt-2">
        <a href="listar_usuarios.php" class="btn-secondary">Cancelar</a>
    </div>
</div>

<?php include 'footer_template.php'; ?>
