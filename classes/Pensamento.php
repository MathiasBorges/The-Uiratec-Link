<?php

class Pensamento extends Usuario{
    private $idPensamento;
    private $texto;
    private $fkIdUsuario;
    private $cor;

    public function setIdPensamento($idPensamento){
        $this->idPensamento=$idPensamento;
    }
    public function getIdPensamento(){
        return $this->idPensamento;
    }

    
    public function setTexto($texto){
        $this->texto=$texto;
    }
    public function getTexto(){
        return $this->texto;
    }


    public function setFkIdUsuario($fkIdUsuario){
        $this->fkIdUsuario=$fkIdUsuario;
    }
    public function getFkIdUsuario(){
        return $this->fkIdUsuario;
    }


    public function setCor($cor){
        $this->cor=$cor;
    }
    public function getCor(){
        return $this->cor;
    }

    public function adicionarPostagem($textoP,$corP,$id,$tipo){
       
        $banco = "INSERT INTO pensamentos SET texto_pensamento=:t,cor=:c,fk_id_usuario=:iu,tipo=:ti";
        $banco = $this->pdo->prepare($banco);
        $banco-> bindValue(":t",$textoP);
        $banco-> bindValue(":c",$corP);
        $banco-> bindValue(":iu",$id);
        $banco-> bindValue(":ti",$tipo);
            
        $banco->execute();
        
	}

    public function apagarPostagem($id){
        $banco = "DELETE FROM pensamentos WHERE id_pensamento =:i AND tipo <> 2";
        $banco = $this->pdo->prepare($banco);
        $banco->bindValue(":i", $id);
        $banco->execute();
    }

    public function favoritar($idUser,$idPensamento){
        $banco = "INSERT INTO pensamentos_favoritos SET fk_id_usuario=:iu, fk_id_pensamento=:ip";
        $banco = $this->pdo->prepare($banco);
        $banco->bindValue(":iu", $idUser);
        $banco->bindValue(":ip", $idPensamento);
        $banco->execute();
	}

    public function desfavoritar($idUser,$idPensamento){
        $banco = "DELETE FROM pensamentos_favoritos WHERE fk_id_usuario =:iu AND fk_id_pensamento =:ip";
        $banco = $this->pdo->prepare($banco);
        $banco->bindValue(":iu", $idUser);
        $banco->bindValue(":ip", $idPensamento);
        $banco->execute();
	}

    public function consultarFavoritos($id){
        $sql = "SELECT fk_id_pensamento
        FROM pensamentos_favoritos 
        WHERE fk_id_usuario =:i
        ORDER BY id_favorito DESC;";
        $stmt = $this->pdo->prepare($sql); 
        $stmt-> bindValue(":i",$id);
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result,"fk_id_pensamento");
    }

    public function carregarPostagens($id,$tipo){
        $sql = "SELECT p.id_pensamento, p.texto_pensamento, p.cor,u.nome_usuario,p.fk_id_usuario
        FROM pensamentos p
        JOIN usuarios u on u.id_usuario = p.fk_id_usuario
        WHERE p.tipo =:t
        ORDER BY p.id_pensamento DESC;";
        $stmt = $this->pdo->prepare($sql); 
        $stmt-> bindValue(":t",$tipo);
        $stmt->execute();

        $favoritos = $this->consultarFavoritos($id);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            foreach($result as $row){
                if ($row['fk_id_usuario'] !== $id){ 
                    if(in_array($row['id_pensamento'], $favoritos)){
                        $imgOn = "<img src='../images/favorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                    }else{
                        $imgOn = "<img src='../images/noFavorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                    }
                    $idUserPost = $row['nome_usuario'];
                    $classPensamento = "";

                }else{ 
                    $imgOn = "";
                    $classPensamento = "pensamento2";
                    $idUserPost = "";
                }
                echo 
                "<div class='publicacao'>
                    <div class='usuario' id='".$idUserPost."'>
                        <img src='../images/perfil.png' title='Ver Perfil'>
                        <p class='userName'>" . $row['nome_usuario'] . "</p>
                    </div> 
                    <div class='pensamento ".$classPensamento."' id='publicacao-id-". $row['id_pensamento']."' style='background:".$row['cor']."'>
                        <p class='texto'>" . $row['texto_pensamento'] . "</p>
                    </div>".$imgOn."
                </div>";
            }
        } else {
            echo "<script>console.log('nenhuma postagem encontrada')</script>";
        }
        return $result;
    }

    public function carregarPostagensPerfil($id){
        $sql = "SELECT texto_pensamento,id_pensamento,cor FROM pensamentos
        WHERE fk_id_usuario =:iu and tipo <> 2 ORDER BY id_pensamento DESC";
        $banco = $this->pdo->prepare($sql); 
        $banco-> bindValue(":iu",$id);
        $banco->execute();

        $result = $banco->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row){
                 echo 
                 "<div class='publicacao' id='publiPerfil'>
                     <div class='pensamento' style='background:".$row['cor']."' id='publicacao-id-".$row['id_pensamento']."'>
                         <p class='texto'>" . $row['texto_pensamento'] . "</p>
                     </div>
                     <img src='../images/excluir.png' class='apaga' id='apagar".$row['id_pensamento']."'>
                 </div>";
            }

        } else {
            echo "<script>console.log('nenhuma postagem encontrada')</script>";
        }
    }

    public function verificarPensamento($idUser, $idPensamento){
        $sql = "SELECT id_pensamento
        FROM pensamentos 
        WHERE id_pensamento =:ip AND fk_id_usuario =:iu AND tipo <> 2";

        $banco = $this->pdo->prepare($sql); 
        $banco-> bindValue(":ip",$idPensamento);
        $banco-> bindValue(":iu",$idUser);
        $banco->execute();

        return $banco->fetch(PDO::FETCH_ASSOC);
    }

    public function cursoNomePensamento($idPensamento){
        $sql = "SELECT u.curso_usuario, u.nome_usuario FROM pensamentos p
        JOIN usuarios u on u.id_usuario = p.fk_id_usuario
        WHERE id_pensamento =:ip AND tipo <> 2";
        $banco = $this->pdo->prepare($sql); 
        $banco-> bindValue(":ip",$idPensamento);
        $banco->execute();
        return $banco->fetch(PDO::FETCH_ASSOC);
    }

    public function carregarPensamentosAnonimos($id){
        $sql = "SELECT texto_pensamento,id_pensamento,cor FROM pensamentos
        WHERE tipo = 2 ORDER BY id_pensamento DESC";
        $banco = $this->pdo->prepare($sql); 
        $banco->execute();

        $favoritos = $this->consultarFavoritos($id);
        $result = $banco->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row){
                if(in_array($row['id_pensamento'], $favoritos)){
                    $imgOn = "<img src='../images/favorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                }else{
                    $imgOn = "<img src='../images/noFavorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                }

                 echo 
                 "<div class='publicacao' id='publiAnonimo'>
                     <div class='pensamento' id='publicacao-id-". $row['id_pensamento']."' style='background:".$row['cor']."'>
                         <p class='texto'>" . $row['texto_pensamento'] . "</p>
                     </div>".$imgOn."
                 </div>";
            }

        }

    }

    public function carregarOutrosPerfis($idPerfil,$idUserAtivo){
        $sql = "SELECT texto_pensamento,id_pensamento,cor FROM pensamentos
        WHERE fk_id_usuario =:iu and tipo <> 2 ORDER BY id_pensamento DESC";
        $banco = $this->pdo->prepare($sql); 
        $banco-> bindValue(":iu",$idPerfil);
        $banco->execute();

        $favoritos = $this->consultarFavoritos($idUserAtivo);
        $result = $banco->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row){

                if(in_array($row['id_pensamento'], $favoritos)){
                    $imgOn = "<img src='../images/favorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                }else{
                    $imgOn = "<img src='../images/noFavorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 
                }

                 echo 
                 "<div class='publicacao' id='publiPerfil'>
                     <div class='pensamento' style='background:".$row['cor']."' id='publicacao-id-" . $row['id_pensamento'] ."'>
                         <p class='texto'>" . $row['texto_pensamento'] . "</p>
                     </div>
                     ".$imgOn."
                 </div>";
            }

        } else {
            echo "<script>console.log('nenhuma postagem encontrada')</script>";
        }

    }

    public function denunciarPensamento($idPensamentoDenunciado, $id_reporter, $motivo){
        $sql = "SELECT fk_id_usuario FROM pensamentos
        WHERE id_pensamento =:i";
        $banco = $this->pdo->prepare($sql); 
        $banco-> bindValue(":i",$idPensamentoDenunciado);
        $banco->execute();
        $result = $banco->fetch(PDO::FETCH_ASSOC);

        $sqlDenuncia  = "INSERT INTO denuncia SET id_usuario_reporter=:ir, fk_id_pensamento = :ip, texto_denuncia =:td, 
        id_usuario_reported =:ird";
        $stmtDenuncia  = $this->pdo->prepare($sqlDenuncia); 
        $stmtDenuncia -> bindValue(":ir",$id_reporter);
        $stmtDenuncia -> bindValue(":ip",$idPensamentoDenunciado);
        $stmtDenuncia -> bindValue(":td",$motivo);
        $stmtDenuncia -> bindValue(":ird",$result['fk_id_usuario']);
        $stmtDenuncia ->execute();
    }

    public function carregarFavoritos(){
        $id = $_SESSION['user'];
        $favoritos = $this->consultarFavoritos($id);

        if (empty($favoritos)) {
            return;
        } else {
            $favoritosString = implode(", ", $favoritos);
        }

        $sql = "SELECT p.id_pensamento, p.texto_pensamento, p.cor,u.nome_usuario,p.tipo
        FROM pensamentos p
        JOIN usuarios u on u.id_usuario = p.fk_id_usuario
        WHERE id_pensamento IN ($favoritosString)
        ORDER BY FIELD(id_pensamento, $favoritosString);";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row){
                if($row['tipo'] == 2){
                    $imgPerfil = "<img src='../images/perfilAnonimo.png'>";
                    $idUserPost = "";
                    $nomeUser = "Anonymus";
                }else{
                    $imgPerfil = "<img src='../images/perfil.png' title='Ver Perfil'>";
                    $idUserPost = $row['nome_usuario'];
                    $nomeUser = $row['nome_usuario'];
                }

                $imgOn = "<img src='../images/favorite.png' class='estrela' id='".$row['id_pensamento']."'>"; 

                echo 
                "<div class='publicacao'>
                    <div class='usuario' id='".$idUserPost."'>
                        ".$imgPerfil."
                        <p class='userName'>". $nomeUser ."</p>
                    </div> 
                    <div class='pensamento' id='publicacao-id-". $row['id_pensamento']."' style='background:".$row['cor']."'>
                        <p class='texto'>" . $row['texto_pensamento'] . "</p>
                    </div>".$imgOn."
                </div>";
            }
        } else {
            echo "<script>console.log('nenhuma postagem encontrada')</script>";
        }
        return $result;

    }
}
?>
