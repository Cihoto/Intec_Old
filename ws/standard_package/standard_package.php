<?php
if ($_POST) {

    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'CreatePackage':
            $request = $data->request;
            $empresaId = $data->empresaId;
            $nombre = $data->packageName;
            $result = CreatePackage($request,$empresaId,$nombre);
            break;
        case 'GetAllStandardPackages':
            $empresa_id = $data->empresa_id;
            $result = GetAllStandardPackages($empresa_id);
            break;
        case 'GetPackageDetails':
            $package_id = $data->package_id;
            $result = GetPackageDetails($package_id);
            break;
        default:
            $result = false;
            break;
    }
    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    require_once('./ws/bd/bd.php');
}



    function CreatePackage($request,$empresa_id,$nombre){

        $conn = new bd();
        $conn->conectar();

        $query = "INSERT INTO standard_package
        (empresa_id, nombre)
        VALUES($empresa_id, '$nombre');";

        if($conn->mysqli->query($query)){
            $insert_id = $conn->mysqli->insert_id;


            foreach($request->arrayPackages as $key=>$element){
                $queryInsert="INSERT INTO u136839350_intec.packages_has_products
                (product_id, package_id, quantity)
                VALUES($element->id, $insert_id,$element->cantidad)";
                $conn->mysqli->query($queryInsert);
            }
            return true;
        }else{
            return false;
        }

        // if($conn->mysqli->query($query)){
        //     $conn->desconectar();
        //     return array("success" => true,"message"=>"Usuario eliminado exitosamente");
        // }else{
        //     $conn->desconectar();
        //     return array("error" => true,"message"=>"Ha ocurrido un error por favor intente nuevamente");
        // }

    }

    function GetAllStandardPackages($empresa_id){
        $conn = new bd();
        $conn->conectar();
        $packages = [];

        $query = "SELECT * FROM standard_package sp 
        WHERE sp.empresa_id = $empresa_id and is_deleted is null;";


        if($responseDb = $conn->mysqli->query($query)){
            while($dataPackages = $responseDb->fetch_object()){
                $packages [] =  $dataPackages;
            }
            return array("success"=>true,"data"=>$packages);
        }else{
            return array("false"=>true,"data"=>$packages);
        }

    }


    function GetPackageDetails($package_id){
        $conn = new bd();
        $conn->conectar();
        $products = [];
        $data = [];

        $queryName = "SELECT * FROM standard_package where id = $package_id";
        $queryProducts = "SELECT * FROM packages_has_products php where 
        php.package_id = $package_id";

        if($responseDb = $conn->mysqli->query($queryProducts)){
            while($databd = $responseDb->fetch_object()){
                $products [] = $databd;
            }
        }

        if($responseDbdata = $conn->mysqli->query($queryName)){
            while($databdData = $responseDbdata->fetch_object()){
                $data [] = $databdData;
            }
        }

        return array("success"=>true, "data"=> $data, "products"=>$products);

    }
?>