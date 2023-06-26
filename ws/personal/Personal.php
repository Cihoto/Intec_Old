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
        case 'getAvailablePersonal':
            // Recibe el parámetro request
            $request = $data->request;
            // Llama a la función getAvailablePersonal y devuelve el resultado
            $response = getAvailablePersonal($request);
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

        default:
            echo 'Invalid action.';
            break;
    }
} else {
    require_once('./ws/bd/bd.php');
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

    return $conn->mysqli->affected_rows;
}



