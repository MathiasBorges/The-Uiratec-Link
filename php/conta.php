<?php
    session_start(); 
    if($_SESSION['token'] == ""){
        echo "<script>
        window.location.href='index.php'
        </script>";
    }

    include '../classes/Usuario.php';
    include '../classes/Pensamento.php';
    $usuario = new Usuario();

    $idUserAtivo = $_SESSION['user'];
    $nomeUserAtivo = $_SESSION['nome'];

    if( isset($_GET['username']) ){
        $nome = $_GET['username'];
        $emailUserPerfil = ( $usuario->retornarEmail($nome) )['email_usuario'];
        $dados = $usuario->retornarUsuario($emailUserPerfil);
        $cursoUserPerfil = $dados['curso_usuario'];
        $idUserPerfil = $dados['id_usuario'];

        if($idUserAtivo == $idUserPerfil){
            echo "<script>window.location.href = '../php/perfil.php'</script>";
        }
    }else{
        $nome = "NoAcess";
        $cursoUserPerfil = "NoAcess";
        $idUserPerfil = "";
    }


    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/conta.css">
    <link rel="icon" href="../images/brainLogo.png">
    <script src="../javaScript/default.js" defer></script>
    <script src="../javaScript/conta.js" defer></script>
    <title><?php echo $nome; ?></title>
    <link rel="stylesheet" href="../css/default.css">
</head>
<body>

    <header>
        <img src="../images/brainLogo.png" id="logo">
        <div id="title-header">
            <h1>The Uiratec</h1>
        </div>
        <div id="hamburguer" onclick="ativar()">
            <div class="linhas" id="linha1"></div>
            <div class="linhas" id="linha2"></div>
            <div class="linhas" id="linha3"></div>
        </div>
    </header>
    <div id="sidebar">
        <a href="perfil.php" style="color:white;text-decoration:none;">
            <div id="perfil">
                <img src="../images/perfil.png">
                <p class="nomeUsuario"> <?php echo $nomeUserAtivo ?> </p>
            </div>
        </a>
        <div>
            <a href="geral.php?tipo=1&titulo=Público" style="color:white;text-decoration:none"><div class="categorias" id="geral"> <img src="../images/brain.png" id="brain"> <p>Público</p></div></a>
            <a href="geral.php?tipo=5&titulo=Nutrição" style="color:white;text-decoration:none"><div class="categorias" id="nutri"> <img src="../images/cobra.png"> <p>Nutrição</p> </div></a>
            <a href="geral.php?tipo=3&titulo=Administração" style="color:white;text-decoration:none"><div class="categorias" id="adm"> <img src="../images/excel.png"> <p>Administração</p> </div></a>
            <a href="geral.php?tipo=4&titulo=Desenvolvimento%20de%20Sistemas" style="color:white;text-decoration:none"><div class="categorias" id="ds"> <img src="../images/computador.png"> <p>Desenvolvimento de Sistemas</p> </div></a>
            <a href="anonimos.php" style="color:white;text-decoration:none"><div class="categorias" id="anonim"> <img src="../images/anonimo.png"> <p>Anônimo</p> </div></a>
        </div>
    </div>


    <div id="conteudo">
        <div id="perfil-section">
            <img src="../images/perfil.png">
            <div id="infos">
                <p class="nome"><?php echo $nome; ?></p>
                <p id="cursoUser"> <?php echo $cursoUserPerfil; ?> </p>
            </div>
        </div>

        <div id="opcoes">Posts</div>

        <div id="sections">
            <div id="posts-section">
                <?php
                if($idUserPerfil == ""){
                    echo "NoAcess";
                }else{
                    $pensamento=new Pensamento();
                    $pensamento->carregarOutrosPerfis($idUserPerfil,$idUserAtivo);
                }
                ?>
            </div>
        </div>
    </div>

    <div id="selecionado"></div>
    
</body>
</html>
