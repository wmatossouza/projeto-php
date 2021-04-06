<?php
require 'banco.php';

$id = $_REQUEST['acao'];
if($id == 'teste'){
    carregaTela();
}

function carregaTela(){
    $pdo = Banco::conectar();

    $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
    $q = $pdo->query($sql);
    $data = $q->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
    
    Banco::desconectar();
}

?>