<?php

require_once 'db_connection.php';

session_start();

// Definir o tempo limite da sessão (10 minutos)
$inactive = 600; // 10 minutos em segundos
$message = "";

if (isset($_SESSION['start']) && $_SESSION['start'] + $inactive < time()) {
    session_unset();
    $message = "Sessão expirada. Por favor, faça login novamente.";
}

$_SESSION['start'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    $admin_user = 'admin';
    $admin_senha = 'admin';

    if ($user === $admin_user && $senha === $admin_senha) {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit();
    } else {
        try {
            $database = Database::getInstance();
            $pdo = $database->getPdo();

            $stmt = $pdo->prepare("SELECT user, senha FROM pessoas WHERE user = :user");
            $stmt->bindParam(':user', $user);

            $stmt->execute();

            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data && password_verify($senha, $user_data["senha"])) {
                $_SESSION["user"] = $user_data["user"];
                $message = "Login bem-sucedido!";
            } else {
                $message = "User ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $message = "Erro ao processar login: " . $e->getMessage();
        }
    }
}

$page_title = "Login - Sistema";
include 'header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($message): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true): ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Faça seu login</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="user" class="form-label">Usuário:</label>
                                <input type="text" id="user" name="user" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Menu Admin</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="cadastrar_usuario.php" class="list-group-item list-group-item-action">Cadastrar Usuário</a>
                            <a href="listar_usuarios.php" class="list-group-item list-group-item-action">Listar Usuários</a>
                            <a href="logout.php" class="list-group-item list-group-item-action list-group-item-danger">Sair</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
