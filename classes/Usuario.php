<?php
    class Usuario{
		private $nome;
		private $email;
		private $senha;
        private $rm;
        private $curso; 
		//
		protected $pdo;
		
		//
		
///////////////////////////////////////////////////////////
		public function __construct(){
            
			try {
				$this->pdo=new PDO("mysql:dbname=theuiratec;host=localhost;charset=utf8","root","");
                // echo "<script>console.log('Banco funcionando')</script>";
				
			} 
			catch (PDOException $e) {
				echo "<script>console.log('Erro no banco de dados')</script>";
				echo "<script>console.log('".$e->getMessage()."')</script>";
			}
			catch (Exception $e){
				echo "Erro genérico<br>";
			}

		}
		
///////////////////////////////////////////////////////////	
		public function setNome($nome){
			$this->nome=$nome;
		}
		public function getNome(){
			return $this->nome;
		}
///////////////////////////////////////////////////////////

		public function setEmail($email){
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			$this->email=$email;
		}
		public function getEmail(){
			return $this->email;
		}

///////////////////////////////////////////////////////////

		public function setSenha($senha){
			$this->senha=$senha;
		}
		public function getSenha(){
			return $this->senha;
		}
///////////////////////////////////////////////////////////
    public function setRm($rm){
        $this->rm=$rm;
    }
    public function getRM(){
       return $this->rm;
    }
///////////////////////////////////////////////////////////
    public function setCurso($curso){
       $this->curso=$curso;
    }
    public function getCurso(){
      return $this->curso;
    }
///////////////////////////////////////////////////////////

	public function getId(){
		$id_logado=$this->pdo->lastInsertId();
		return $id_logado;
	}

///////////////////////////////////////////////////////////
		public function adicionarUsuario($nome,$email,$senha,$rm,$curso){
		$banco = "INSERT INTO usuarios SET nome_usuario=:n, email_usuario=:e, senha_usuario=:s, rm_usuario=:r,curso_usuario=:c";
		$banco = $this->pdo->prepare($banco);
		$banco-> bindValue(":n",$nome);
		$banco-> bindValue(":s",$senha);
		$banco-> bindValue(":e",$email);
        $banco-> bindValue(":r",$rm);
        $banco-> bindValue(":c",$curso);
		
		$banco-> execute();
		
	}

	public function conferirUsuarioSenha($emailE,$senhaE){
		$queryDB = 'SELECT * FROM usuarios WHERE email_usuario=:emp and senha_usuario=:sp';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":emp",$emailE);
		$queryDados->bindValue(":sp",$senhaE);
		
		$queryDados->execute();

		$on = $queryDados->fetch(PDO::FETCH_ASSOC);

		return $on;
	}

	public function conferirUsuario($emailU,$nomeU){
		$queryDB = 'SELECT * FROM usuarios WHERE email_usuario=:emp OR nome_usuario = :nmp';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":emp",$emailU);
		$queryDados->bindValue(":nmp",$nomeU);
		$queryDados->execute();

		$on = $queryDados->fetch(PDO::FETCH_ASSOC);
		if ($on !== false && $on !== null) { 

			if ($on['email_usuario'] == $emailU && $on['nome_usuario'] == $nomeU) {
				return ['erro' => 'Usuário já cadastrado'];

			}elseif($on['nome_usuario'] == $nomeU) {
				return ['erro' => 'Nome indisponível'];

			}else {
				return ['erro' => 'E-mail já cadastrado'];
			}
		} else {
			return ['sucesso' => 'Usuário disponível para cadastro'];
		}
	}

	public function verificarRM($rm){
		$queryDB = 'SELECT * FROM usuarios WHERE rm_usuario=:rm';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":rm",$rm);
		$queryDados->execute();
		$on = $queryDados->fetch(PDO::FETCH_ASSOC);
		if ($on !== false && $on !== null) {
			return ['erro' => "RM já cadastrado"];
		}else{
			return ['sucesso' => "."];
		}

	}

	public function retornarUsuario($emailUser){
		$queryDB = 'SELECT id_usuario, nome_usuario, curso_usuario FROM usuarios WHERE email_usuario =:e';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":e",$emailUser);
		$queryDados->execute();
		$on = $queryDados->fetch(PDO::FETCH_ASSOC);
		return $on;
	}

	public function retornarEmail($nome){
		$queryDB = 'SELECT email_usuario FROM usuarios WHERE nome_usuario = :n';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":n",$nome);
		$queryDados->execute();
		$on = $queryDados->fetch(PDO::FETCH_ASSOC);
		return $on;
	}

	public function verificarNomeEmailRM($emailU,$nomeU,$rmU){
		$queryDB = 'SELECT * FROM usuarios WHERE email_usuario=:emp OR nome_usuario = :nmp OR rm_usuario =:rm';
		$queryDados =$this->pdo->prepare($queryDB);
		$queryDados->bindValue(":emp",$emailU);
		$queryDados->bindValue(":nmp",$nomeU);
		$queryDados->bindValue(":rm",$rmU);
		$queryDados->execute();

		$on = $queryDados->fetch(PDO::FETCH_ASSOC);

		if ($on !== false && $on !== null) {
			return ["erro" => "Dados inválidos ou já cadastrados"];
		}
	}

	public function alterarSenha($email,$senha){
		$queryDB = 'UPDATE usuarios SET senha_usuario=:s WHERE email_usuario=:e';
		$queryDados = $this->pdo->prepare($queryDB);
		$queryDados->bindParam(":e", $email);
		$queryDados->bindParam(":s", $senha);
		// COM HASH BUGA ok
		//deleta minha conta
		//por que?
		//naao consigo acessar por conta da nva senha
		//muda a senha uai
		$queryDados->execute();
	}
}
?>