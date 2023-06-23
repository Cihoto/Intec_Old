<?php

if ($_POST){
    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;
    // Realiza la acción correspondiente según el valor de 'action'
    switch($action) {
        case 'addDireccion':
            $request = $data->request;
            $result = addDireccion($request);
            break;
        case 'getDireccion':
            $request = $data->request;
            $result = getDireccion($request);
            break;
        case 'getDireccionesByEmpresa':
            $request = $data->request;
            $result = getDireccionesByEmpresa($request);
            break;
        default:
            $result = false;
            break;
    }
    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo $result;
} else {
    require_once('./ws/bd/bd.php');
}

    function addDireccion($request){
        $conn= new bd();
        $conn ->conectar();
        $direccion = "";
        $numero = "";
        //$extra = "";
        $dpto = "";
        $postal_code = "";
        $comuna_id = "";
        foreach($request as $req){
            $direccion= $req->direccion;
            $numero = $req->numero;
            // $extra = $req->extra;
            $dpto = $req->depto;
            $postal_code = $req->codigo_postal;
            $comuna_id = $req->comuna;
        }
        $query = "INSERT INTO intec.direccion
        (direccion, numero,  dpto, postal_code, comuna_id, empresa_id)
        VALUES('".$direccion."', '".$numero."','".$dpto."', '".$postal_code."', $comuna_id, 1)";

        if($responseBd = $conn->mysqli->query($query)){
            $insert_id = $conn->mysqli->insert_id;
            $conn->desconectar();
            return json_encode(array("id_direccion"=> $insert_id)) ;
        }else{
            $conn->desconectar();
            return false;
        }
    }


    function getDireccion($request){

        $conn= new bd();
        $conn ->conectar();
        $direccionId = $request;

        $query = "SELECT * FROM direccion d where d.id = $direccionId";

        if($responseBd = $conn->mysqli->query($query)){
            
            while($dataResponse = $responseBd->fetch_object()){
                $direcciones[] = $dataResponse; 
            }
        }else{
            $conn->desconectar();
            return false;
        }
        return json_encode(array("direcciones"=>$direcciones));
    }
    function getDireccionesByEmpresa($request){

        $conn= new bd();
        $conn ->conectar();
        $direccionId = "";
        $direcciones = [];

        $query = "SELECT * FROM direccion d";

        if($responseBd = $conn->mysqli->query($query)){
            
            while($dataResponse = $responseBd->fetch_object()){
                $direcciones[] = $dataResponse; 
            }
        }else{
            $conn->desconectar();
            return false;
        }
        return json_encode(array("direcciones"=>$direcciones)); 
    }
?>
