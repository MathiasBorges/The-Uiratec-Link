    <?php session_start(); ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/index.css">
        <link rel="icon" href="../images/brainLogo.png">
        <script src="../javaScript/index.js" defer></script>
        <title>The Uiratec</title>
    </head>
    <body>
        
        <div class="modalExiste">
            <h2>E-mail ou senha incorretos</h2>
            <button class="btnClose">Ok</button>
        </div>
        
        <div id="modal-esqueceuSenha">
            <form class="alteraSenha" method="post" action="">
                <div id="sairModal">✖</div>
                <div id="div1" class="etapa">
                        <h3 class="tituloAlteraSenha">Digite seu e-mail institucional para alterar sua senha</h3>
                        <input class="inputModal" type="email" minlength="10" maxlength="100" name="emailCodigo" autocomplete="off">
                        <button class="inputEnvia" type="button" onclick="nextStage()">Prosseguir</button> 
                </div>

                <div id="div2" class="etapa">
                    <h4 class="tituloAlteraSenha">Enviamos um código para seu e-mail institucional, transcreva-o</h4>
                    <input class="inputModal" id="codigo" type="number" minlength="6" maxlength="6" autocomplete="off">
                    <button class="inputEnvia" type="button" onclick="nextStage2()">Prosseguir</button> 
                </div>

                <div id="div3" class="etapa">
                    <h3 class="tituloAlteraSenha">Insira a nova senha</h3>
                    <div class="divPassword inputModal"> 
                        <input style="padding:10% 0%;" class="inputPassword" type="password" placeholder="Senha" required minlength="8" maxlength="40" name="novaSenha">
                            <img class="eye" title="Ver senha" onclick="showPassword(0)" src="../images/eyePassword.png" alt="">
                    </div>
                    <button class="inputEnvia" type="submit">Prosseguir</button> 
                </div>
            </form>
        </div>

        <?php
            include '../classes/Usuario.php';
            $usuario = new Usuario();
            if(isset($_POST['emailCodigo']) && isset($_POST['novaSenha']) ){
                $email = $_POST['emailCodigo'];
                $senhaNova = $_POST['novaSenha'];
                $usuario->alterarSenha($email,$senhaNova);
            }
        ?>

        <div class="containForm">
            <div id="forms-insanos">
                <form action="" class="formEntrar" method="post">
                    <h1>Entrar</h1>
                    <!-- Section entrar, dados para entrar apenas -->
                    <input type="email" placeholder="E-mail institucional" minlength="10" maxlength="100" id="email" name="emailL">
                    <div class="divPassword"> 
                        <input class="inputPassword"  type="password" placeholder="Senha" required minlength="8" maxlength="40" name="senhaL">
                        <img class="eye" title="Ver senha" onclick="showPassword(1)" src="../images/eyePassword.png" alt="">
                    </div>
                    <!--Fim dados entrar-->

                    <a href="" class="esqueceuSenha">Esqueceu a senha?</a>
                    <button class="button">Entrar</button>
                    <p class="semConta" id="text-info">Não tem uma conta? <a href="#" id="transitar" onclick="cadastrar(1)">Cadastrar-se</a></p>
                </form>

                <form method="post" class="formCad">
                    <img src="../images/seta.png" id="seta" onclick="cadastrar(2)">
                    <h1>Cadastrar</h1>
                    <!--Section cadastrar, parte I-->
                    <input type="text" placeholder="Nome" maxlength="30" required id="nome" name="nomeC">
                    <input type="email" placeholder="E-mail institucional" minlength="10" maxlength="100" id="emailCad" name="emailC">
                    <div class="divPasswordCad"> 
                        <input class="inputPassword"  type="password" placeholder="Senha" required minlength="8" maxlength="40" name="senhaC">
                        <img class="eye" title="Ver senha" onclick="showPassword(2)" src="../images/eyePassword.png" alt="">
                    </div>
                    <div id="button" class="button">Continuar</div>
                    <!--Fim cad parte I-->

                    <!--Dados parte II cadastro-->
                    <input type="number" placeholder="RM" pattern="\d{4}" title="Digite exatamente 4 dígitos" id="rm" name="rmC">
                    <div class="box-select">
                        <select id="curso" onchange="getEscolha()">
                            <option class="op" value="" disabled selected>Curso</option>
                            <option class="op" value="nutri">Nutrição</option>
                            <option class="op" value="ds">Desenvolvimento de Sistemas</option>
                            <option class="op" value="adm">Administração</option>
                        </select>
                        <img src="../images/setaBaixo.png" id="setaBaixo">
                    </div>
                    
                    <input type="text" class="cursoEscolha" name="cursoC" style="display: none;">

                    <input type="submit" class="button submit" value="Cadastrar" id="submitCad">
                    <p class="semConta" id="text-info">Já tem uma conta? <a href="#" id="transitar" onclick="entrar()">Entrar</a></p>
                    <!--Fim cad parte II-->
                </form>
            </div>

            <div class="logoForm">
                <img src="../images/brainLogo.png" alt="brainLogo">
            </div>
        </div>

    </body>
    </html>

    <?php
            
    if( isset($_POST['nomeC']) && isset($_POST['emailC']) && isset($_POST['senhaC']) && isset($_POST['rmC']) ){ 
        
        $usuario->setNome(trim($_POST['nomeC']));
        $usuario->setEmail(trim($_POST['emailC']));
        $usuario->setSenha(trim($_POST['senhaC']));
        $usuario->setRm(trim($_POST['rmC']));
        $usuario->setCurso(trim($_POST['cursoC']));

        $verificacao = array();
        
        $regexEmail = '/^[a-z]+\.[a-zA-Z]+(\d)?/';
        $regexNome = '/^[a-zA-Z0-9\s]+$/';

            if (empty( $usuario->getSenha() ) || empty( $usuario->getEmail() ) || empty( $usuario->getNome() )) {
                $verificacao[] = 'inválido';
            }

            if (preg_match($regexEmail, $usuario->getEmail() )) {
                if (!preg_match('/@etec\.sp\.gov\.br$/', $usuario->getEmail() )) {
                    $verificacao[] = 'inválido';
                }
            } else {
                $verificacao[] = 'inválido';
            }

            if (!preg_match($regexNome, $usuario->getNome() )) {
                $verificacao[] = 'inválido';
            }

            if (strlen( $usuario->getSenha() ) < 8) {
                $verificacao[] = 'inválido';
            }

            if( $usuario->verificarNomeEmailRM($usuario->getEmail(), $usuario->getNome(), $usuario->getRM()) ){
                $verificacao[] = 'inválido';
            }

        if( empty($verificacao) ){
            $usuario->adicionarUsuario($usuario->getNome(),$usuario->getEmail(),$usuario->getSenha(),$usuario->getRM(),$usuario->getCurso());
            echo "<script>
            window.location.href='index.php'
            </script>";
        }else{
            echo "<script>
                    setTimeout(function(){
                        window.location.href='index.php';
                    }, 1000)
                </script>";
            echo "<script>alert('Dados inválidos ou já cadastrados')</script>";
        }
    
    }
        //Entrando
    if( isset($_POST['emailL']) && isset($_POST['senhaL']) ){
        $emailL=trim($_POST['emailL']);
        $senhaL=trim($_POST['senhaL']);
    
        if($usuario->conferirUsuarioSenha($emailL,$senhaL)){
            //problema é em algo aqui nessa parte
            $valores = $usuario->retornarUsuario($emailL); 
            $token = rand(458834, 456784355484);

            $_SESSION['token'] = $token;
            $_SESSION['nome'] = $valores['nome_usuario'];
            $_SESSION['user'] = $valores['id_usuario'];
            $_SESSION['curso'] = $valores['curso_usuario'];

            echo "<script>
                    window.location.href='geral.php?tipo=1&titulo=Público'
                </script>";
        }else{
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded',()=>{
                        let modalErro=document.querySelector('.modalExiste');
                        let btnClose=document.querySelector('.btnClose');
                        modalErro.style.display='flex';
                        btnClose.addEventListener('click',()=>{
                            modalErro.style.display='none';
                        })
                    })  
                </script>";
        }
    }

    ?>