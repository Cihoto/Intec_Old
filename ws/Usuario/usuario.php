<?php
if ($_POST) {

    require_once('../bd/bd.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $action = $data->action;

    // Realiza la acción correspondiente según el valor de 'action'
    switch ($action) {
        case 'GetUsuario':
            $email = $data->email;
            $result = GetUsuario($email);
            break;
        case 'LogUser':
            $request = $data->request;
            $result = LogUser($request);
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

    function GetUsuario($email){
        $conn =  new bd();
        $conn->conectar();
        $query="SELECT * FROM usuario u where LOWER(user) = LOWER('$email')";

        if($conn->mysqli->query($query)->num_rows > 0){
            $conn->desconectar();
            return true;
        }else{
            $conn->desconectar();
            return false;
        }
    }

    function LogUser($request){
        $conn =  new bd();
        $conn->conectar();
        
        $correo = $request->email;
        $pass = $request->pass;

        $queryGetLogin = "SELECT * FROM usuario u WHERE LOWER(user) = LOWER('$correo') and LOWER(password)= LOWER('$pass')";
        if($responseBd = $conn->mysqli->query($queryGetLogin)){
            if($responseBd->num_rows > 0){
                $empresa_id = $responseBd->fetch_object()->empresa_id;
                session_start();
                $_SESSION["empresa_id"] = $empresa_id;

                $conn->desconectar();
                return array("success" => true,"message"=>"Excelente","ref"=>true);
            }else{
                $conn->desconectar();
                return array("success" => true,"message"=>"Credenciales Erroneas","ref"=>false);
            }
        }else{
            $conn->desconectar();
            return array("error" => true,"message"=>"Ha ocurrido un error, intente nuevamente");
        }
    }
?>