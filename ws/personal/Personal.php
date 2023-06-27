<?php
if ($_POST) {
    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'getPersonal':
            // Recibe el parámetro empresaId
            $empresaId = $data->empresaId;
            
            // Llama a la función getPersonal y devuelve el resultado
            $personal = getPersonal($empresaId);
            echo json_encode($personal);
            break;
        
        case 'addPersonalToProject':
            // Recibe el parámetro request
            $request = $data->request;
            
            // Llama a la función addtoProject y devuelve el resultado
            $response = addPersonalToProject($request);
            echo json_encode($response);
            break;
        case 'setviatico':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función addtoProject y devuelve el resultado
            $response = setviatico($request);
            echo json_encode($response);
            break;
        case 'setArriendos':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función addtoProject y devuelve el resultado
            $response = setArriendos($request);
            echo json_encode($response);
            break;
        case 'SetTotalProject':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función SetTotalProject y devuelve el resultado
            $response = SetTotalProject($request);
            echo json_encode($response);
            break;
        case 'AddPersonal':
            // Recibe el parámetro request
            $request = $data->request;
            $empresaId = $data->empresaId;
            // Llama a la función SetTotalProject y devuelve el resultado
            $response = AddPersonal($request,$empresaId);
            echo json_encode($response);
            break;
        case 'getAvailablePersonal':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función getAvailablePersonal y devuelve el resultado
            $response = getAvailablePersonal($request);
            echo json_encode($response);
            break;
        case 'AddEspecialidad':
            // Recibe el parámetro request
            $request = $data->request;
            $empresaId = $data->empresaId;
            // Llama a la función AddEspecialidad y devuelve el resultado
            $response = AddEspecialidad($request,$empresaId);
            echo json_encode($response);
            break;
        case 'AddCargo':
            // Recibe el parámetro request
            $request = $data->request;
            $empresaId = $data->empresaId;
            // Llama a la función AddCargo y devuelve el resultado
            $response = AddCargo($request,$empresaId);
            echo json_encode($response);
            break;
        case 'getEspecialidad':
            // Recibe el parámetro request
            $empresaId = $data->empresaId;
            // Llama a la función getEspecialidad y devuelve el resultado
            $response = getEspecialidad($empresaId);
            echo json_encode($response);
            break;
        case 'getCargo':
            // Recibe el parámetro request
            $empresaId = $data->empresaId;
            // Llama a la función getCargo y devuelve el resultado
            $response = getCargo($empresaId);
            echo json_encode($response);
            break;
        case 'getAllPersonalData':
            // Recibe el parámetro request
            $empresaId = $data->empresaId;
            // Llama a la función getAllPersonalData y devuelve el resultado
            $response = getAllPersonalData($empresaId);
            echo json_encode($response);
            break;
        case 'getAllContratos':
            // Llama a la función getAllContratos y devuelve el resultado
            $response = getAllContratos();
            // $response = "123123123123123123";
            
            echo json_encode($response);
            break;
        case 'dropAssigmentPersonal':
            // Recibe el parámetro request
            $idProject = $data->idProject;
            // Llama a la función dropAssigmentPersonal =>
            // devuelve los ids eliminados de las asignaciones
            $response = dropAssigmentPersonal($idProject);
            echo json_encode($response);
            break;
        case 'addPersonalMasiva':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función dropAssigmentPersonal =>
            // devuelve los ids eliminados de las asignaciones
            $response = addPersonalMasiva($request);
            echo json_encode($response);
            break;
        default:
            echo 'Invalid action.';
            break;
    }
} else {
    require_once('./ws/bd/bd.php');
}

function AddPersonal($request,$empresaId){
    $conn = new bd();
    $conn->conectar();
    $today= date('Y-d-m');

    foreach($request as $req){
        $nombre = $req->nombre;
        $apellido = $req->apellido;
        $rut = $req->rut;
        $correo = $req->correo;
        $telefono = $req->telefono;
        $neto = $req->neto;
        $especialidad = $req->especialidad;
        $idContrato = $req->idContrato;
        $cargo = $req->cargo;
    }

    $queryPersona = "INSERT INTO intec.persona
    (nombre, apellido, rut, telefono, email)
    VALUES('".$nombre." ', '".$apellido."', '".$rut."', '".$telefono."', '".$correo."')";

    $conn->mysqli->query($queryPersona);
    $idPersona = $conn->mysqli->insert_id;

    $queryInsert = "INSERT INTO intec.personal
    (persona_id, cargo_id, especialidad_id, tipo_contrato_id, createAt, IsDelete, empresa_id,neto)
    VALUES(".$idPersona.",".$cargo.",".$especialidad.",".$idContrato.",'".$today."', 0, $empresaId, $neto)";


    if($conn->mysqli->query($queryInsert)){
        return array("success"=>array("message"=>"Se ha ingresado a ".$nombre." ".$apellido." al sistema"));
    }else{
        return array("error"=>array("message"=>"No se ha podido ingresar a ".$nombre." ".$apellido." al sistema"));
    }
}

function setviatico($request){
    $conn = new bd();
    $conn->conectar();
    $arrayResponse = [];

    foreach ($request as $req) {
        $idProject = $req->idProject;
    }

    $queryIfAssigned = "SELECT * from personal_has_proyecto php where php.proyecto_id = $idProject";

    if($conn->mysqli->query($queryIfAssigned)->num_rows>0){
        $qdelete = "DELETE FROM proyecto_has_viatico WHERE proyecto_id =$idProject";
        $conn->mysqli->query($qdelete);
    }

    foreach ($request as $req) {
        $idProject = $req->idProject;
        $valor = $req->valor;
        $detalle = $req->detalle;
        
        $query = "INSERT INTO intec.proyecto_has_viatico
                    (proyecto_id, valor, detalle)
                    VALUES($idProject,'".$valor."', '".$detalle."')";

        if ($conn->mysqli->query($query)) {

            array_push($arrayResponse, array("Asignado" => array("id" => $valor)));
        } else {

            array_push($arrayResponse, array("NoAsignado" => array("id" => $valor)));
        }
    }
    $conn->desconectar();
    return $arrayResponse;

}
function setArriendos($request){
    $conn = new bd();
    $conn->conectar();
    $arrayResponse = [];

    foreach ($request as $req) {
        $idProject = $req->idProject;
    }

    $queryIfAssigned = "SELECT * from intec.arriendos_proyecto php where php.id_proyecto = $idProject";

    if($conn->mysqli->query($queryIfAssigned)->num_rows>0){
        $qdelete = "DELETE FROM arriendos_proyecto WHERE id_proyecto =$idProject";
        $conn->mysqli->query($qdelete);
    }

    foreach ($request as $req) {
        $idProject = $req->idProject;
        $valor = $req->valor;
        $detalle = $req->detalle;

        $query = "INSERT INTO intec.arriendos_proyecto
                  (id_proyecto, detalle_arriendo, valor)
                    VALUES($idProject, '".$detalle."', ".intval($valor) .");";

        if ($conn->mysqli->query($query)) {

            array_push($arrayResponse, array("Asignado" => array("id" => $valor)));
        } else {

            array_push($arrayResponse, array("NoAsignado" => array("id" => $valor)));
        }
    }
    $conn->desconectar();
    return $arrayResponse;

}

function SetTotalProject($request){
    $conn = new bd();
    $conn->conectar();

    // return json_encode($request);

    foreach ($request as $req) {
        $idProject = $req->idProject;
    }

    $queryIfTotal = "SELECT * from intec.arriendos_proyecto php where php.id_proyecto = $idProject";

    if($conn->mysqli->query($queryIfTotal)->num_rows>0){
        $qdelete = "DELETE FROM proyecto_has_ingresos WHERE id_proyecto =$idProject";
        $conn->mysqli->query($qdelete);
    }

    foreach($request as $req){
        $queryInsertTotal = "INSERT INTO intec.proyecto_has_ingresos
                            (id_proyecto, total)
                            VALUES($req->idProject, ".intval($req->valor).");";
        $conn->mysqli->query($queryInsertTotal);

    }
}

function getAvailablePersonal($request)
{
    $conn = new bd();
    $conn->conectar();
    $personal = [];

    $empresaId = $request->empresaId;
    $fechaInicio = $request->fechaInicio;
    $fechaTermino = $request->fechaTermino;

    $queryPersonal = "SELECT  p.id, p.cargo_id, CONCAT(per.nombre ,' ',per.apellido) as nombre,
                            c.cargo, e.especialidad, p.neto, tc.contrato
                            FROM personal p
                        INNER JOIN persona per on per.id = p.persona_id 
                        INNER JOIN cargo c on c.id  = p.cargo_id 
                        INNER JOIN especialidad e on e.id  = p.especialidad_id 
                        INNER JOIN empresa emp on emp.id = p.empresa_id 
                        INNER JOIN tipo_contrato tc on tc.id = p.tipo_contrato_id 
                        LEFT JOIN  personal_has_proyecto php  on php.personal_id  = per.id
                        LEFT JOIN proyecto pro on p.id = php.proyecto_id
                        LEFT JOIN proyecto_has_estado phe on phe.proyecto_id = pro.id
                        where pro.id IS NULL or phe.estado_id != 2
                        or '".$fechaInicio."' < pro.fecha_inicio and '".$fechaTermino."' < pro.fecha_inicio 
                        or '".$fechaInicio."' > pro.fecha_termino and '".$fechaTermino."' > pro.fecha_termino
                        and p.empresa_id = $empresaId";



    if ($responseBdVehiculos = $conn->mysqli->query($queryPersonal)) {
        while ($dataVehiculos = $responseBdVehiculos->fetch_object()) {
            $personal[] = $dataVehiculos;
        }
    }
    $conn->desconectar();
    return $personal;
}


function AddEspecialidad($request,$empresaId){

    $conn =  new bd();
    $conn->conectar();
    $arrayIdsInserted = [];
    $today = date('Y-m-d');

    // return count($request->arrayCategorias);
    for($i = 0 ; $i < count($request->arrayCargos); $i++){

        $queryInsertCargo = "INSERT INTO intec.especialidad
        (especialidad, createAt, IsDelete, empresa_id)
        VALUES('".trim($request->arrayCargos[$i])."', '".$today."', 0, $empresaId);";

        if($conn->mysqli->query($queryInsertCargo)){
            array_push($arrayIdsInserted,$conn->mysqli->insert_id);
        }
    }

    // return $queryInsertCargo;
    return $arrayIdsInserted;
}
function AddCargo($request,$empresaId){

    $conn =  new bd();
    $conn->conectar();
    $arrayIdsInserted = [];
    $today = date('Y-m-d');

    // return count($request->arrayCategorias);
    for($i = 0 ; $i < count($request->arrayCargos); $i++){
        
        $queryInsertCargo = "INSERT INTO intec.cargo (cargo,empresa_id)
        VALUES('".trim($request->arrayCargos[$i])."', $empresaId)";

        if($conn->mysqli->query($queryInsertCargo)){
            array_push($arrayIdsInserted,$conn->mysqli->insert_id);
        }
    }

    // return $queryInsertCargo;
    return $arrayIdsInserted;
}

function getEspecialidad($empresaId){

    $conn = new bd();
    $conn->conectar();
    $especialidades = [];
    $queryGetEspecialidad = "SELECT id, especialidad FROM especialidad e WHERE empresa_id = $empresaId";
    $responseBd = $conn->mysqli->query($queryGetEspecialidad);

    while($dataEspecialidad = $responseBd->fetch_object()){
        $especialidades[] = $dataEspecialidad;
    }

    return array("especialidades"=>$especialidades);
}
function getCargo($empresaId){

    $conn = new bd();
    $conn->conectar();
    $cargos = [];
    $queryGetCargo = "SELECT id, cargo FROM cargo  WHERE empresa_id = $empresaId";
    $responseBd = $conn->mysqli->query($queryGetCargo);

    while($datosCargo = $responseBd->fetch_object()){
        $cargos[] = $datosCargo;
    }

    return array("cargos"=>$cargos);
}

function getPersonal($empresaId)
{
    $conn = new bd();
    $conn->conectar();
    $personal =  [];
    $queryPersonal = "SELECT  p.id, p.cargo_id, CONCAT(per.nombre ,' ',per.apellido) as nombre,
                            c.cargo, e.especialidad, p.neto, tc.contrato
                        FROM personal p
                        INNER JOIN persona per on per.id = p.persona_id 
                        INNER JOIN cargo c on c.id  = p.cargo_id 
                        INNER JOIN especialidad e on e.id  = p.especialidad_id 
                        INNER JOIN empresa emp on emp.id = p.empresa_id 
                        INNER JOIN tipo_contrato tc on tc.id = p.tipo_contrato_id 
                        where emp.id = $empresaId";

    if ($responseBd = $conn->mysqli->query($queryPersonal)) {
        while ($dataPersonal = $responseBd->fetch_object()) {
            $personal[] = $dataPersonal;
        }
    }
    $conn->desconectar();
    return $personal;
}
function getAllPersonalData($empresaId)
{
    $conn = new bd();
    $conn->conectar();
    $personal =  [];
    $queryPersonal = "SELECT  p.id, p.cargo_id, per.nombre, per.apellido, per.rut,per.email,c.cargo ,e.especialidad,
                        c.cargo, e.especialidad, p.neto, tc.contrato, per.telefono
                    FROM personal p
                    INNER JOIN persona per on per.id = p.persona_id 
                    INNER JOIN cargo c on c.id  = p.cargo_id 
                    INNER JOIN especialidad e on e.id  = p.especialidad_id 
                    INNER JOIN empresa emp on emp.id = p.empresa_id 
                    INNER JOIN tipo_contrato tc on tc.id = p.tipo_contrato_id 
                    where emp.id = $empresaId";

    if ($responseBd = $conn->mysqli->query($queryPersonal)) {
        while ($dataPersonal = $responseBd->fetch_object()) {
            $personal[] = $dataPersonal;
        }
    }
    $conn->desconectar();
    return $personal;
}

function addPersonalToProject($request)
{
    $conn = new bd();
    $conn->conectar();
    $arrayResponse = [];


    foreach (array_slice($request, 0, 1) as $req){
        if(isset($req->idProject)){

            $idProject = $req->idProject;
            $queryIfAssigned = "SELECT * from personal_has_proyecto php where php.proyecto_id = $idProject";

            if($conn->mysqli->query($queryIfAssigned)->num_rows>0){

                $qdelete = "DELETE FROM personal_has_proyecto WHERE proyecto_id =$idProject";
                $conn->mysqli->query($qdelete);

            }
        }
    }
    foreach ($request as $req) {
        $idProject = $req->idProject;
        $idPersonal = $req->idPersonal;
        $costo = $req->cost;
        $query = "INSERT INTO intec.personal_has_proyecto
                            (personal_id, proyecto_id,costo)
                            VALUES($idPersonal, $idProject,$costo)";

        if ($conn->mysqli->query($query)) {

            array_push($arrayResponse, array("Asignado" => array("id" => $idPersonal)));
        } else {

            array_push($arrayResponse, array("NoAsignado" => array("id" => $idPersonal)));
        }
    }

    $conn->desconectar();
    return $arrayResponse;
}

function dropAssigmentPersonal($idProject){
    $conn = new bd();
    $conn->conectar();

    $queryIfAssigned = "SELECT * from personal_has_proyecto php where php.proyecto_id = $idProject";

    if($conn->mysqli->query($queryIfAssigned)->num_rows>0){
        $qdelete = "DELETE FROM personal_has_proyecto WHERE proyecto_id =$idProject";
        $conn->mysqli->query($qdelete);
    }
    $deleted = $conn->mysqli->affected_rows;
    $conn->desconectar();
    return $deleted;
}

function getAllContratos(){
    $conn = new bd();
    $conn->conectar();
    $contratos = [];
    $queryAllContratos = "SELECT * FROM tipo_contrato tc";
    
    if($responseBd = $conn->mysqli->query($queryAllContratos)){
        while($dataContratos = $responseBd->fetch_object()){
            $contratos [] = $dataContratos;
        }
    }
    $conn->desconectar();
    return $contratos;
}

function addPersonalMasiva($request){
    $conn =  new bd();
    $conn->conectar();
    return 1;
    $today = date('Y-m-d');
    // $arr

    // foreach ($request as  $value){

    //     $nombre = $value ->nombre;
    //     $apellido = $value ->apellido;
    //     $rut = $value ->rut;
    //     $telefono = $value->telefono;
    //     $correo = $value->correo;
    //     $cargo = $value ->cargo;
    //     $especialidad = $value ->especialidad;
    //     $contrato = $value ->contrato;
    //     $neto = 15000;
    //     // $neto = $value->neto;
    //     $idPersona = 0;
        
    //     $queryPersona = "INSERT INTO intec.persona
    //                     (nombre, apellido, rut, email, telefono)
    //                     VALUES('".$nombre." ', '".$apellido."', '".$rut."', '".$correo."', '$telefono')";

    //     $resposenBdPersona = $conn->mysqli->query($queryPersona);
    //     $idPersona = $conn->mysqli->insert_id;

    //     $queryCargo = $conn->mysqli->query('select id from cargo where cargo = "'.$cargo.'"'); 
    //     $value = $queryCargo->fetch_object();
    //     // $idCargo = $value->id;
    //     return 'select id from cargo where cargo = "'.$cargo.'"';

    //     $especialidadq = $conn->mysqli->query('select id from especialidad where especialidad ="' .$especialidad.'"'); 
    //     $value = $especialidadq->fetch_object();
    //     // $idEspecialidad = $value->id;

    //     $contratoq = $conn->mysqli->query('select id from tipo_contrato where contrato = "'.$contrato.'"'); 
    //     $value = $contratoq->fetch_object();
    //     // $idContrato = $value->id;
        
    //     // $query = "INSERT INTO intec.personal
    //     //         (persona_id, cargo_id, especialidad_id, tipo_contrato_id, createAt, IsDelete, empresa_id,neto)
    //     //         VALUES(".$idPersona.",".$idCargo.",".$idEspecialidad.",".$idContrato.",'".$today."', 0, 1, $neto)";
       
    //     // if($conn->mysqli->query($query)){
    //     //     return array("Success"=>"123321");
    //     // }else{

    //     // }

    // }

}






