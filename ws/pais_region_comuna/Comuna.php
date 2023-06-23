<?php

if ($_POST) {
    require_once('../bd/bd.php');

    $action = $_POST['action'];

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'getComunasByRegion':
            // Recibe el parámetro jsonRequest
            $jsonRequest = json_decode($_POST['jsonRequest']);
            
            // Llama a la función getComunasByRegion y devuelve el resultado
            $comunas = getComunasByRegion($jsonRequest);
            echo json_encode($comunas);
            break;
        
        default:
            echo 'Invalid action.';
            break;
    }
}else{
    require_once('./ws/bd/bd.php');

}

    function getComunasByRegion($jsonRequest){
        $conn = new bd();
        $conn->conectar();
    
        $idRegion = $jsonRequest->idRegion;
        $comunas = [];
    
        $query = 'Select c.id, c.comuna from comuna c
                    INNER JOIN region r on r.id = c.region_id
                    WHERE r.id ='.$idRegion;
                    
        if($responseBd = $conn->mysqli->query($query)){
            while($dataComunas = $responseBd->fetch_object()){
                $comunas [] = $dataComunas;
            }
        }

        return $comunas;
    }
?>