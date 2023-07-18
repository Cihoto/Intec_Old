<?php
if ($_POST) {
    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $action = $data->tipo;

    // Realiza la acción correspondiente según el valor de 'action'
    switch($action) {
        case 'addCliente':
            $request = $data->request;
            $result = addCliente($request);
            break;
        case 'getCliente':
            $request = $data->request;
            $result = getClienteById($request);
            break;
        case 'getClientesByEmpresa':
            $request = $data->request;
            $result = json_encode(getClientesByEmpresa($request));
            break;
        case 'getClienteById':
            $request = $data->request;
            $result = getClienteById($request);
            break;
        case 'UpdateCliente':
            $request = $data->request;
            $result = UpdateCliente($request);
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

    function addCliente($request){

        $conn = new bd();
        $conn ->conectar();
        $clienteExist = "";
        // return json_encode($request);
        
        foreach($request as $req){
            if (isset($req->idCliente) && isset($req->idProject)) {
                $idCliente = $req->idCliente;
                $idProject = $req->idProject;
                $responseBdClienteId = $conn->mysqli->query("SELECT id FROM cliente c where c.id = $idCliente");
                $clienteExist = $responseBdClienteId->fetch_object()->id;
                if($clienteExist !== ""){
                    $conn->mysqli->query("UPDATE proyecto
                                            SET cliente_id = $clienteExist
                                            WHERE id = $idProject");
        
                    return json_encode(array("idCliente"=>$clienteExist));
                }
            }if(isset($req->idCliente) && !isset($req->idProject)){
                $idCliente = $req->idCliente;
                $responseBdClienteId = $conn->mysqli->query("SELECT id FROM cliente c where c.id = $idCliente");
                $clienteExist = $responseBdClienteId->fetch_object()->id;
                if($clienteExist !== ""){
                    return json_encode(array("idCliente"=>$clienteExist));
                }
            }   
        }

        foreach($request as $req){

            $empresaId = $req->empresaId; 
            $nombreCliente = $req->nombreCliente;
            $apellidos = $req->apellidos;
            $rutCliente = $req->rutCliente;
            $correoCliente = $req->correoCliente;
            $telefono = $req->telefono;
            $rut = $req->rut;
            $razonSocial = $req->razonSocial;
            $nombreFantasia = $req->nombreFantasia;
            $direccionDatosFacturacion = $req->direccionDatosFacturacion;
            $correoDatosFacturacion = $req->correoDatosFacturacion;

            $queryInsertPersona = "INSERT INTO persona
            (nombre, apellido, rut, email, telefono)
            VALUES('".$nombreCliente."', '".$apellidos."', '".$rutCliente."', '".$correoCliente."', '".$telefono."')";
            
            $conn->mysqli->query($queryInsertPersona);
            $idPer = $conn->mysqli->insert_id;
            
            $queryInsertDatosFacturacion = "INSERT INTO datos_facturacion
            (razon_social, nombre_fantasia, rut, direccion, correo)
            VALUES('".$razonSocial."', '".$nombreFantasia."', '".$rut."', '".$direccionDatosFacturacion."', '".$correoDatosFacturacion."');";
            $conn->mysqli->query($queryInsertDatosFacturacion);
            $idDf = $conn->mysqli->insert_id;

            $queryCliente = "INSERT INTO cliente
            (datos_facturacion_id, persona_id_contacto, id_empresa)
            VALUES($idDf, $idPer, $empresaId);";

            if($conn->mysqli->query($queryCliente)){
                $idCliente = $conn->mysqli->insert_id;
                return json_encode(array("idCliente"=>$idCliente));
            }else{
                return false;
            }
        }
    }
    function getClientesByEmpresa($request){

        $conn = new bd();
        $conn ->conectar();
        $clientes = [];
        $empresaId = $request;


        $query = "SELECT CONCAT(p.nombre ,' ',p.apellido) as nombre_cliente ,c.id from cliente c 
        INNER JOIN persona p on p.id = c.persona_id_contacto 
        INNER JOIN datos_facturacion df on df.id = c.datos_facturacion_id 
        INNER JOIN empresa e on e.id = c.empresa_id 
        where e.id =  $empresaId";

        // return $query;


        if($responseBd = $conn->mysqli->query($query)){
            while($dataResponse =$responseBd->fetch_object()){
                $clientes [] = $dataResponse;
            }
        }else{
            return false;
        }
        return $clientes;
        
    }
    function getClienteById($request){

        $conn = new bd();
        $conn ->conectar();
        $clientes = [];
        $clienteId = $request;


        $query = "SELECT *,df.rut as df_rut  From cliente c 
                INNER JOIN persona p on p.id = c.persona_id_contacto 
                INNER JOIN datos_facturacion df on df.id = c.datos_facturacion_id 
                where c.id = $clienteId ";

        if($responseBd = $conn->mysqli->query($query)){
            while($dataResponse =$responseBd->fetch_object()){
                $clientes [] = $dataResponse;
            }
        }else{
            return false;
        }
        return json_encode(array("cliente"=>$clientes));
        
    }

    function UpdateCliente($request){
        $conn =  new bd();
        $conn->conectar();

        foreach ($request as $key => $req) {
            $idCliente = $req->idCliente;
            $nombreCliente = $req->nombreCliente;
            $apellidos = $req->apellidos;
            $rutCliente = $req->rutCliente;
            $correo = $req->correo;
            $telefono = $req->telefono;
            $rut = $req->rut;
            $razonSocial = $req->razonSocial;
            $nombreFantasia = $req->nombreFantasia;
            $direccionDatosFacturacion = $req->direccionDatosFacturacion;
            $correoDatosFacturacion = $req->correoDatosFacturacion;
        }
        
        $queryCliente = "SELECT * FROM cliente c where c.id = $idCliente";

        $responseBd = $conn->mysqli->query($queryCliente);

        while($dataCliente = $responseBd->fetch_object()){
            $idDatosFacturacion = $dataCliente->datos_facturacion_id;
            $idPersona = $dataCliente->persona_id_contacto;
        }

        $queryUpdateDatosFacturacion = "UPDATE datos_facturacion
                                            SET razon_social='".$razonSocial."', nombre_fantasia='".$nombreFantasia."', rut='".$rut."',
                                            direccion='".$direccionDatosFacturacion."', correo='".$correoDatosFacturacion."'
                                            WHERE id= $idDatosFacturacion";

        $queryUpdatePersona = "UPDATE persona
        SET nombre='".$nombreCliente."', apellido='".$apellidos."', rut='".$rutCliente."', email='".$correo."', telefono='".$telefono."'
        WHERE id= $idPersona";

        $conn->mysqli->query($queryUpdateDatosFacturacion);
        $conn->mysqli->query($queryUpdatePersona);

    }


?>