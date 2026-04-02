<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db_connection.php';

$db = Database::getInstance()->getPdo();

try {
    $stmt = $db->query('SELECT * FROM dados');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}

$pageTitle = "Lista de Usuários";
include 'header_template.php';
?>

<div class="box box-wide">
    <h1>Lista de Usuários</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="4" class="text-center" style="color: var(--text-secondary); padding: 2rem;">Nenhum usuário cadastrado.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-secondary);">#<?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo isset($user['nome']) ? htmlspecialchars($user['nome']) : ''; ?></td>
                        <td><?php echo isset($user['user']) ? htmlspecialchars($user['user']) : ''; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="btn-edit">Editar</a>
                            <a href="excluir_usuario.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-2">
        <a href="index.php" class="btn-secondary">Voltar ao Menu</a>
    </div>
</div>

<?php include 'footer_template.php'; ?>
