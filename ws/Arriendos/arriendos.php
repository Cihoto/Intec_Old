<?php

if ($_POST) {
    require_once('../bd/bd.php');
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'SetNewRent':
            $request = $data->request;
            $empresa_id = $data->empresa_id;
            $response = SetNewRent($empresa_id,$request);
            echo json_encode($response);
            break;
        case 'GetArriendos':
            $empresa_id = $data->empresa_id;
            $response = GetArriendos($empresa_id);
            echo json_encode($response);
            break;
        case 'GetArriendoById':
            $arriendo_id = $data->arriendo_id;
            $response = GetArriendoById($arriendo_id);
            echo json_encode($response);
            break;
        case 'AssignRentToProject':
            $request = $data->request;
            $response = AssignRentToProject($request);
            echo json_encode($response);
            break;
        default:
            echo 'Invalid action.';
            break;
    }
}else{
    require_once('./ws/bd/bd.php');

}

function SetNewRent($empresa_id,$request){
    $conn = new bd();
    $conn->conectar();
    // return $empresa_id;

    $success = true;

    // return $request;

    $nombreArriendo = $request->nombreArriendo;
    $valorArriendo = $request->valorArriendo;
    $nombre = $request->txtNombre;
    $apellidos = $request->txtApellidos;
    $rut = $request->txtRut;
    $correo = $request->txtCorreo;
    $telefono = $request->txtTelefono;
    $rut = $request->txtRut;
    $razonSocial = $request->txtRazonSocial;
    $nombreFantasia = $request->txtNombreFantasia;
    $direccionDatosFacturacion = $request->txtDireccionDatosFacturacion;
    $correoDatosFacturacion = $request->txtCorreoDatosFacturacion;

    $queryInsertDF = "INSERT INTO datos_facturacion
    (razon_social, nombre_fantasia, rut, direccion, correo)
    VALUES('$razonSocial', '$nombreFantasia', '$rut', '$direccionDatosFacturacion', '$correoDatosFacturacion')";
    if($conn->mysqli->query($queryInsertDF)){
        $datosFacturacionId = $conn->mysqli->insert_id;
    }else{
        $success = false;
        return array("error"=>true,"message"=>"No se ha podido completar, por favor intente nuevamente");
    }

    $queryInsertPersona = "INSERT INTO persona
    (nombre, apellido, rut, email, telefono)
    VALUES('$nombre', '$apellidos', '$rut', '$correo', '$telefono')";
    if($conn->mysqli->query($queryInsertPersona)){
        $personaInsertId = $conn->mysqli->insert_id;
    }else{
        $success = false;
        return array("error"=>true,"message"=>"No se ha podido completar, por favor intente nuevamente");
    }

    $queryInsertProveedor = "INSERT INTO proveedor
    (persona_id_contacto, empresa_id, datos_facturacion_id)
    VALUES($personaInsertId, $empresa_id, $datosFacturacionId)";
    if($conn->mysqli->query($queryInsertProveedor)){
        $proveedorInsertId = $conn->mysqli->insert_id;
    }else{
        $success = false;
        return array("error"=>true,"message"=>"No se ha podido completar, por favor intente nuevamente");
    }

    $queryInsertArriendo = "INSERT INTO arriendos
    (item, proveedor_id, descripcion)
    VALUES('$nombreArriendo', $proveedorInsertId, NULL)";
    if($conn->mysqli->query($queryInsertArriendo)){

    }else{
        $success = false;
        return array("error"=>true,"message"=>"No se ha podido completar, por favor intente nuevamente");
    }


    if($success){
        $conn->desconectar();
        return array("sucess"=>true,"message" => "Se ha ingresado un nuevo subArriendo");
    }else{
        $conn->desconectar();
        return array("error"=>true,"message" => "No se ha podido ingresar un nuevo subArriendo");
    }

}

function GetArriendos($empresa_id){
    $conn = new bd();
    $conn->conectar();
    $arriendos = [];

    $query = "SELECT a.id, a.item, per.nombre, per.apellido, df.rut FROM arriendos a
    INNER JOIN proveedor p on p.id = a.proveedor_id
    INNER JOIN persona per on per.id = p.persona_id_contacto 
    INNER JOIN datos_facturacion df on df.id = p.datos_facturacion_id 
    WHERE p.empresa_id = $empresa_id";

    if($responseBd = $conn->mysqli->query($query)){
        while($dataArriendos = $responseBd->fetch_object()){
            $arriendos [] = $dataArriendos;
        }
        $conn->desconectar();
        return array("success"=>true, "message"=>"Se han encontrado ".count($arriendos)."","data"=>$arriendos);
    }else{
        $conn->desconectar();
        return array("error"=>true,"message"=>"Ha ocurrido un error, por favor intente nuevamente");
    }
}


function GetArriendoById($arriendo_id){
    $conn = new bd();
    $conn->conectar();
    $arriendos = [];

    $query = "SELECT a.id, a.item, per.nombre, per.apellido, df.rut FROM arriendos a
    INNER JOIN proveedor p on p.id = a.proveedor_id
    INNER JOIN persona per on per.id = p.persona_id_contacto 
    INNER JOIN datos_facturacion df on df.id = p.datos_facturacion_id 
    WHERE a.id = $arriendo_id";

    if($responseBd = $conn->mysqli->query($query)){
        while($dataArriendos = $responseBd->fetch_object()){
            $arriendos [] = $dataArriendos;
        }
        $conn->desconectar();
        return array("success"=>true, "message"=>"Se han encontrado ".count($arriendos)."","data"=>$arriendos);
    }else{
        $conn->desconectar();
        return array("error"=>true,"message"=>"Ha ocurrido un error, por favor intente nuevamente");
    }
}


function AssignRentToProject($request){
    $conn = new bd();
    $conn->conectar();
    $counter = 0;
    foreach($request as $req){
        $queryAssingnRent = "INSERT INTO proyecto_has_arriendos
        (proyecto_id, arriendos_id, costo)
        VALUES($req->proyecto_id, $req->arriendo_id, $req->costo)";
        if($conn->mysqli->query($queryAssingnRent)){
            $counter ++;
        } 
    }
    $conn->desconectar();
    if($counter === count($request)){
        return array("success"=>true,"message"=>"Se han agregado todos los subarriendos al proyecto");
    }else{
        return array("error"=>true,"message"=>"Se han agregado ".$counter." de ".count($request)."");
    }
}

?>