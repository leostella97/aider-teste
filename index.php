<?php

require_once 'db_connection.php';

session_start();

// Definir o tempo limite da sessão (10 minutos)
$inactive = 600; // 10 minutos em segundos
$message = "";

$error_message = "";

// Apenas desloga se a sessão já estiver ativa e expirada
if (isset($_SESSION['start']) && $_SESSION['start'] + $inactive < time()) {
    session_unset();
    $error_message = "Sessão expirada. Por favor, faça login novamente.";
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
                $_SESSION['user'] = $user_data['user'];
                $_SESSION['start'] = time();
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Usuário ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $error_message = "Erro ao processar login: " . $e->getMessage();
        }
    }
}

$pageTitle = "Home / Login";
include 'header_template.php';
?>

<div class="box <?php echo (isset($_SESSION['admin']) || isset($_SESSION['user'])) ? 'box-wide' : 'box-narrow'; ?>">
    <?php if (!(isset($_SESSION['admin']) || isset($_SESSION['user']))): ?>
        <h1>Entrar</h1>

        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="user">Usuário</label>
                <input type="text" id="user" name="user" required placeholder="Digite seu usuário">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>
            <button type="submit">Entrar</button>
        </form>
    <?php else: ?>
        <h1>Bem-vindo, <?php echo htmlspecialchars(isset($_SESSION['admin']) ? 'Administrador' : $_SESSION['user']); ?>!</h1>
        <p class="text-center" style="color: var(--text-secondary); margin-bottom: 2rem;">Você está logado no sistema.</p>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div class="mt-2">
                <h2>Painel Administrativo</h2>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="cadastrar_usuario.php" class="btn-secondary">Cadastrar Usuário</a>
                    <a href="listar_usuarios.php" class="btn-secondary">Listar Usuários</a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer_template.php'; ?>
