<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Sistema de Usuários'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">UserSystem</a>
            <ul>
                <?php if (isset($_SESSION['admin']) || isset($_SESSION['user'])): ?>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                        <li><a href="cadastrar_usuario.php">Cadastrar</a></li>
                        <li><a href="listar_usuarios.php">Listar</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="logout">Sair</a></li>
                <?php else: ?>
                    <li><a href="index.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main class="container">
