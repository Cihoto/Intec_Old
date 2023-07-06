<?php

if ($_POST) {

    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'addProject':
            $request = $data->request;
            $result = addProject($request);
            break;
        case 'getProjectResume':
            $request = $data->request;
            $result = getProjectResume($request);
            break;
        case 'getMyProjects':
            $request = $data->request;
            $result = json_encode(getMyProjects($request));
            break;
        case 'UpdateProjectData':
            $request = $data->request;
            $result = json_encode(UpdateProjectData($request));
            break;
        case 'UpdateProjectDataStatus':
            $idProject = $data->idProject;
            $result = UpdateProjectDataStatus($idProject);
            break;
        case 'GetAllMyProjects':
            $empresa_id = $data->empresaId;
            $result = json_encode(GetAllMyProjects($empresa_id));
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

function addProject($request)
{
    $conn = new bd();
    $conn->conectar();
    $today = date('Y-m-d');

    foreach ($request as $req) {

        $nombre_proyecto = $req->nombre_proyecto;
        $lugar_id = $req->lugar_id;
        $fecha_inicio = $req->fecha_inicio;
        $fecha_termino = $req->fecha_termino;
        $cliente_id = $req->cliente_id;
        $comentarios = $req->comentarios;
        // $empresa_id = $req->empresa_id;
    }
    if ($lugar_id === "") {
        $lugar_id = "null";
    } else {
        $lugar_id = "'" . $lugar_id . "'";
    }
    if ($cliente_id === "") {
        $cliente_id = "null";
    } else {
        $cliente_id = "'" . $cliente_id . "'";
    }
    if ($fecha_inicio === "") {
        $fecha_inicio = "null";
    } else {
        $fecha_inicio = "'" . $fecha_inicio . "'";
    }
    if ($fecha_termino === "") {
        $fecha_termino = "null";
    } else {
        $fecha_termino = "'" . $fecha_termino . "'";
    }

    $query = "INSERT INTO intec.proyecto
            (nombre_proyecto, lugar_id, fecha_inicio, fecha_termino, createAt, IsDelete , cliente_id, empresa_id,comentarios)
            VALUES('" . $nombre_proyecto . "', $lugar_id,$fecha_inicio, $fecha_termino,'" . $today . "', 0, $cliente_id, 1,'" . $comentarios . "')";

    if ($conn->mysqli->query($query)) {
        $id_project = $conn->mysqli->insert_id;

        // Change project status to Created
        $queryProjectStatus = "SELECT * from estado e where e.estado = 'creado'";
        $response = $conn->mysqli->query($queryProjectStatus)->fetch_assoc();
        $idStatus = $response["id"];

        $queryCreateStatus = "INSERT INTO intec.proyecto_has_estado
                                        (proyecto_id, estado_id, fecha)
                                        VALUES($id_project, $idStatus, '" . $today . "')";

        $conn->mysqli->query($queryCreateStatus);

        $conn->desconectar();
        return json_encode(array("id_project" => $id_project));
    } else {
        $conn->desconectar();
        return false;
    }
}

function getProjectResume($request)
{
    $conn = new bd();
    $conn->conectar();

    $asignadosV = [];
    $clienteAsignado = [];
    $asignadosPer = [];
    $asignadosPro = [];
    $projects = [];
    $viaticoAsignado = [];
    $arriendosasignados = [];
    $totalIngresos = [];
    $viewasignados = false;

    foreach ($request as $key => $value) {
        $idProject = $value->idProject;

        if (isset($value->asignados)) {
            $viewasignados = true;
        }
    }

    if ($viewasignados) {
        $queryVehiclesAsignados = "SELECT v.id, v.patente
                                                from vehiculo v 
                                            INNER JOIN proyecto_has_vehiculo phv ON phv.vehiculo_id = v.id 
                                            INNER JOIN proyecto p on p.id = phv.proyecto_id 
                                            WHERE p.id = $idProject";
        $queryPersonalAsignados = "SELECT p.id ,CONCAT(per.nombre,' ',per.apellido) as nombre,
                                                c.cargo , e.especialidad, php.costo, tc.contrato 
                                            from personal p 
                                            INNER JOIN persona per on per.id = p.persona_id 
                                            INNER JOIN personal_has_proyecto php on php.personal_id = p.id 
                                            INNER JOIN proyecto pro on pro.id  = php.proyecto_id 
                                            INNER JOIN cargo c on c.id = p.cargo_id 
                                            INNER JOIN especialidad e on e.id = p.especialidad_id 
                                            INNER JOIN tipo_contrato tc on tc.id = p.tipo_contrato_id 
                                            where pro.id = $idProject";
        $queryClienteAssigned = "SELECT c.id ,per.nombre, per.apellido, per.rut, per.telefono,per.email,
                                                df.razon_social, df.nombre_fantasia,df.rut as rut_df, df.direccion, df.correo
                                            FROM proyecto p
                                            INNER JOIN cliente c on c.id = p.cliente_id 
                                            INNER JOIN persona per on per.id = c.persona_id_contacto 
                                            INNER JOIN datos_facturacion df on df.id = c.datos_facturacion_id 
                                            INNER JOIN empresa e on e.id = p.empresa_id 
                                            where p.id = $idProject";
        $queryProductsAssigned = "SELECT p.nombre , p.precio_arriendo, p.id,php.cantidad  FROM proyecto_has_producto php 
                                            INNER JOIN producto p on p.id  = php.producto_id 
                                            Where php.proyecto_id = $idProject";
        $queryViaticosAssigned = "SELECT * from proyecto_has_viatico phv WHERE phv.proyecto_id = $idProject";
        $querySubarriendos = "SELECT * FROM arriendos_proyecto ap WHERE id_proyecto = $idProject";
        $queryTotalIngresos = "SELECT * FROM proyecto_has_ingresos WHERE id_proyecto = $idProject";
    }

    $queryProject = "SELECT  p.nombre_proyecto, p.fecha_inicio, p.fecha_termino,p.comentarios,
                        d.id as dirId, l.id as lugarId, d.direccion, d.numero,
                        d.dpto, d.postal_code,c.comuna,r.region, p.id, e.id as estado 
                    FROM proyecto p 
                    INNER JOIN proyecto_has_estado phe on phe.proyecto_id  = p.id 
                    INNER JOIN estado e on e.id = phe.estado_id 
                    LEFT JOIN lugar l on l.id = p.lugar_id 
                    LEFT JOIN direccion d on d.id = l.direccion_id
                    LEFT JOIN comuna c ON c.id = d.comuna_id
                    LEFT JOIN region r ON r.id = c.region_id
                    WHERE p.id = $idProject";

    if ($responseBd = $conn->mysqli->query($queryProject)) {
        while ($dataProject = $responseBd->fetch_object()) {
            $projects[] = $dataProject;
        }
    }
    if ($viewasignados) {
        if ($responseBd = $conn->mysqli->query($queryVehiclesAsignados)) {
            while ($dataAsignadosV = $responseBd->fetch_object()) {
                $asignadosV[] = $dataAsignadosV;
            }
        }
        if ($responseBd = $conn->mysqli->query($queryPersonalAsignados)) {
            while ($dataAsignadosPer = $responseBd->fetch_object()) {
                $asignadosPer[] = $dataAsignadosPer;
            }
        }
        if ($responseBd = $conn->mysqli->query($queryProductsAssigned)) {
            while ($dataAsignadosPro = $responseBd->fetch_object()) {
                $asignadosPro[] = $dataAsignadosPro;
            }
        }
        if ($responseBd = $conn->mysqli->query($queryClienteAssigned)) {
            while ($dataClienteAss = $responseBd->fetch_object()) {
                $clienteAsignado[] = $dataClienteAss;
            }
        }
        if ($responseBd = $conn->mysqli->query($queryViaticosAssigned)) {
            while ($dataClienteAss = $responseBd->fetch_object()) {
                $viaticoAsignado[] = $dataClienteAss;
            }
        }
        if ($responseBd = $conn->mysqli->query($querySubarriendos)) {
            while ($dataArriendos  = $responseBd->fetch_object()) {
                $arriendosasignados[] = $dataArriendos;
            }
        }
        if ($responseBd = $conn->mysqli->query($queryTotalIngresos)) {
            while ($dataIngresos  = $responseBd->fetch_object()) {
                $totalIngresos[] = $dataIngresos;
            }
        }
    }
    $conn->desconectar();
    return json_encode(array(
        "dataProject" => $projects,
        "asignados" => array(
            "vehiculos" => $asignadosV,
            "personal" => $asignadosPer,
            "cliente" => $clienteAsignado,
            "productos" => $asignadosPro,
            "viaticos" => $viaticoAsignado,
            "arriendos" => $arriendosasignados,
            "totalIngresos" => $totalIngresos
        )
    ));
}

function getMyProjects($request)
{
    $conn = new bd();
    $conn->conectar();
    $empresaId = $request->empresaId;
    $status = $request->status;

    $projects = [];
    // $queryProyectos = "SELECT p.id, p.nombre_proyecto,p.empresa_id,c.nombre, 
    //     CONCAT(d.direccion,' ',d.numero,', ',co.comuna,', ',r.region) AS direccion,
    //     p.fecha_inicio,
    //     p.fecha_termino,
    //     p.comentarios 
    //     FROM proyecto p 
    //     INNER JOIN lugar l ON l.id = p.lugar_id 
    //     INNER JOIN cliente c ON c.id = p.cliente_id 
    //     INNER JOIN empresa e ON e.id = p.empresa_id 
    //     INNER JOIN direccion d ON d.id  = l.direccion_id 
    //     INNER JOIN comuna co ON co.id = d.comuna_id  
    //     INNER JOIN region r ON r.id = co.region_id 
    //     Where e.id = $empresa_id";
    // $queryProyectos = "SELECT p.*
    //     FROM proyecto p 
    //     INNER JOIN empresa e ON e.id = p.empresa_id 
    //     Where e.id = $empresa_id";
    $queryProyectos = "SELECT p.id, p.nombre_proyecto, 
                            CONCAT(per.nombre,' ', per.apellido) as nombreCliente, 
                            CONCAT(d.direccion, ' ',d.numero,', ',co.comuna,', ',re.region) as direccion,
                            p.fecha_inicio ,p.fecha_termino
                                    FROM proyecto p
                            INNER JOIN proyecto_has_estado phe ON  phe.proyecto_id  = p.id 
                            LEFT  JOIN lugar l on l.id = p.lugar_id 
                            LEFT JOIN direccion d on d.id = l.direccion_id 
                            LEFT JOIN cliente c on c.id  = p.cliente_id         
                            LEFT JOIN persona per on per.id = c.persona_id_contacto
                            LEFT JOIN comuna co on co.id = d.comuna_id 
                            LEFT JOIN region re on re.id = co.region_id 
                            where phe.estado_id = $status and p.empresa_id = $empresaId";
    if ($responseBd = $conn->mysqli->query($queryProyectos)) {
        while ($dataProject = $responseBd->fetch_object()) {
            $projects[] = $dataProject;
        }
    }
    return $projects;
}

function GetAllMyProjects($empresa_id){

    $conn =  new bd();
    $conn->conectar();
    $projects = [];

    $queryGetAllMyProjects = "SELECT p.id, 
                                p.nombre_proyecto, 
                                p.fecha_inicio, 
                                p.fecha_termino,
                                e.estado 
                            FROM proyecto p 
                            INNER JOIN proyecto_has_estado phe on phe.proyecto_id = p.id 
                            INNER JOIN estado e on e.id = phe.estado_id 
                            where p.empresa_id = $empresa_id";
    
    if($responseBd = $conn->mysqli->query($queryGetAllMyProjects)){
        while($dataProjects = $responseBd->fetch_object()){
            $projects [] = $dataProjects;
        }
        return $projects;
    }else{
        return array("error"=>true, "message"=>"NO DATA RECOVERED");
    }

}

function UpdateProjectData($request)
{

    $conn =  new bd();
    $conn->conectar();

    // return $request;


    $idProject = $request->idProject;

    if(!isset($idProject)){
        return array("error"=>array("message"=>"No has seleccionado eventos, elige uno antes de actualizarlo"));
    }

    $projectName = $request->txtProjectName;
    $fechaInicio = $request->dpInicio;
    $fechaTermino = $request->dpTermino;
    $comentarios = $request->txtAreaComments;
    $today = date("Y-m-d");

    if($idProject === ""){
        $idProject = "null";
    }else{
        $idProject = $idProject;
    }
    if($projectName === ""){
        $projectName = "null";
    }else{
        $projectName = "'".$projectName."'";
    }
    if($fechaInicio === ''){
        $fechaInicio = "null";
    }else{
        $fechaInicio = "'".$fechaInicio."'";
    }
    if($fechaTermino === ""){
        $fechaTermino = "null";
    }else{
        $fechaTermino = "'".$fechaTermino."'";
    }
    if($comentarios === ""){
        $comentarios = "null";
    }else{
        $comentarios = "'".$comentarios."'";
    }


    $queryUpdateProject = "UPDATE intec.proyecto
                SET nombre_proyecto = $projectName, fecha_inicio = $fechaInicio, 
                fecha_termino = $fechaTermino, comentarios = $comentarios,
                modifiedAt = '".$today."'
                WHERE id= $idProject";

    // return $queryUpdateProject;

    if($conn->mysqli->query($queryUpdateProject)){
        return array("success"=>array("message"=>"Evento modificado exitosamente"));
    }else{
        return array("error"=>array("message"=>"No se ha podido actualizar el proyecto, por favor intente nuevamente"));
    }

    // $resultDatabase =  $conn->mysqli->query($queryUpdateProject);

    // while ($dataProject = $resultDatabase->fetch_object()) {
    //     $response[] = $dataProject;
    //     // $nombreProyecto = $dataProject["nombre_proyecto"];
    //     // $lugarId = $dataProject["lugar_id"];
    //     // $fecha_inicio = $dataProject["fecha_inicio"];
    //     // $fecha_termino = $dataProject["fecha_termino"];
    //     // $comentarios = $dataProject["comentarios"];
    //     // $clienteId = $dataProject["cliente_id"];
    // }

    // return json_encode($response);
    // return json_encode($dataProject);



}

function UpdateProjectDataStatus($idProject)
{
    $conn =  new bd();
    $conn->conectar();
    $queryUpdate = "UPDATE proyecto_has_estado set estado_id = estado_id + 1 where proyecto_id = $idProject";
    
    if ($conn->mysqli->query($queryUpdate)) {
        return true;
    } else {
        return false;
    }
}
