<?php
require_once('../../backend/funcionalidades/config.php');

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=7">
    <link rel="stylesheet" href="../estilos/postagens.css?v=8">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Logo" class="logo-header">
        </picture>
        <nav id="options-header">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteudo Educativo</a></li>
                <li><a href="postagens.php" class="active">Postagens</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
                        echo '<li><a href="login.php">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="post_ongs.php">Criar Post</a></li>';
                        }
                        echo '<li><a href="logout.php" class="logout">LogOut</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <section id="posts">
            <h2>Postagens das ongs</h2>

            <div class="card-post"> <!-- use a classe correta do CSS flex -->

                <?php
                $consulta = $mysqli->prepare("SELECT * FROM postagens ORDER BY data_postagem DESC");
                $consulta->execute();
                $resultado = $consulta->get_result();
                $meses = [
                    1 => 'Jan',
                    2 => 'Fev',
                    3 => 'Mar',
                    4 => 'Abr',
                    5 => 'Mai',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Ago',
                    9 => 'Set',
                    10 => 'Out',
                    11 => 'Nov',
                    12 => 'Dez'
                ];
                $mostrarIdPost = isset($_SESSION['ong']) && !isset($_SESSION['usuario']);

                while ($post = $resultado->fetch_assoc()) {
                    $timestampPostagem = strtotime($post['data_postagem']);
                    $dataPostagem = date("d", $timestampPostagem) . " " . $meses[(int) date("n", $timestampPostagem)] . " " . date("Y", $timestampPostagem);

                    echo "<div class='post'>";
                    echo "<h2>" . htmlspecialchars($post['titulo']) . "</h2>";
                    echo "<p class='descricao'>" . nl2br(htmlspecialchars($post['descricao'])) . "</p>";
                    echo "<p><a href='" . htmlspecialchars($post['link']) . "' target='_blank' class='ver-mais'>Acessar link</a></p>";
                    if ($mostrarIdPost) {
                        echo "<p class='id-post'>ID:" . htmlspecialchars($post['id']). "</p>";
                    }
                    echo "<p class='data'>Postado em: " . $dataPostagem . "</p>";
                    echo "</div>";
                }
                ?>

            </div>
        </section>

    </main>
    <footer>
        <p>Apoio+ - O guia colaborativo para jovens egressos de abrigos institucionais
        </p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
