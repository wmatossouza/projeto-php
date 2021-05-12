<?php
require 'banco.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$id = $_POST["id"];
$nova_pontuacao = $_POST["nova_pontuacao"];

$pdo = Banco::conectar();

$sql = "select pontuacao from ranking where id_usuario = $id";
$q = $pdo->query($sql);
$data = $q->fetchAll(PDO::FETCH_ASSOC);
if ($data){
    $sql = "update ranking set pontuacao=? where id_usuario = ? ";
    $q = $pdo->prepare($sql);
    $q->execute(array($nova_pontuacao, $id));
    echo json_encode("OK");
    Banco::desconectar();
}else{
    $sql = "INSERT INTO ranking (id_usuario, pontuacao) VALUES(?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($id,$nova_pontuacao));
    echo json_encode("OK");
    Banco::desconectar();
}
