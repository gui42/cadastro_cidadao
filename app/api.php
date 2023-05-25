<?php
require 'models/Pessoa.php';
require 'db/dba.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$method = $_SERVER['REQUEST_METHOD'];

$dba = new Dba('db.json');

if($method === 'GET'){
    $todas_pessoas = $dba->getAll();
    echo json_encode([ 'success'=>true, 'data'=>$todas_pessoas ]);

}elseif($method === 'PUT'){
    $data = json_decode(file_get_contents("php://input"), true);
    $NIS = rand(10000000000,99999999999);
    try{
        $pessoa = new Pessoa($data['nome'], $NIS );
        $success = $pessoa->save($dba);
    }catch(ErrorException $e){
        echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
    }
    echo json_encode(['success'=>true, 'data'=>$pessoa->toArray()]);

}elseif($method === 'POST'){
    $data = json_decode(file_get_contents("php://input"), true);
    $valor_busca = $data['valor_busca'];
    $criterio_busca = $data['criterio_busca'];
    if($criterio_busca == '1')
        $pessoa = Pessoa::getPessoaByName($valor_busca, $dba);
    else{
        if(ctype_alnum($valor_busca)){
            $valor_busca = (int) $valor_busca;
            $pessoa = Pessoa::getPessoaByNIS($valor_busca, $dba);
        }else
            echo json_encode(['success'=>false, 'error'=>'NIS deve ser numérico']);
    }
    echo json_encode(["success"=>true, "data"=>$pessoa]);

}

?>
