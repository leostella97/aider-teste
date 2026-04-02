<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prevent deleting the currently logged-in admin if they were in the table
    // (Assuming session might store user ID or we just allow deletion for now)

    try {
        $db = Database::getInstance()->getPdo();
        $stmt = $db->prepare("DELETE FROM dados WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            header("Location: listar_usuarios.php?success=" . urlencode("Usuário excluído com sucesso!"));
        } else {
            header("Location: listar_usuarios.php?error=" . urlencode("Erro ao excluir usuário."));
        }
    } catch (PDOException $e) {
        header("Location: listar_usuarios.php?error=" . urlencode("Erro no banco de dados: " . $e->getMessage()));
    }
} else {
    header("Location: listar_usuarios.php");
}
exit();
