<?php
require 'banco.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$email = $_POST["email"];
$senha = $_POST["senha"];

$pdo = Banco::conectar();

$sql = "SELECT u.nome, u.id, r.pontuacao FROM usuarios u left join ranking r on r.id_usuario = u.id where email='$email' and senha='$senha'";
$q = $pdo->query($sql);
$data = $q->fetchAll(PDO::FETCH_ASSOC);

if ($data){
    $usuario["usuario"]  = $data[0]['nome'];
    $usuario["id"]  = $data[0]['id'];
    $usuario["pontuacao"]  = $data[0]['pontuacao'];
}else{
    $usuario["usuario"]  = "semAcesso"; 
}
echo json_encode($usuario);

Banco::desconectar();

?>