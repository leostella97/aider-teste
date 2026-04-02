<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
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

$page_title = "Lista de Usuários - Sistema";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Lista de Usuários</h4>
                    <div>
                        <a href="cadastrar_usuario.php" class="btn btn-sm btn-success">Novo Usuário</a>
                        <a href="index.php" class="btn btn-sm btn-outline-light">Voltar</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($users) > 0): ?>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo isset($user['nome']) ? htmlspecialchars($user['nome']) : ''; ?></td>
                                        <td><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?></td>
                                        <td><?php echo isset($user['user']) ? htmlspecialchars($user['user']) : ''; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhum usuário encontrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
