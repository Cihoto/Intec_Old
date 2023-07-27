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
        $query="SELECT per.email from persona per 
        INNER JOIN personal p on p.persona_id = per.id 
        INNER JOIN usuario u on u.id = p.usuario_id
        where LOWER(per.email) = LOWER('$email')";

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

        // $queryGetLogin = "SELECT * FROM usuario u WHERE LOWER(user) = LOWER('$correo') and LOWER(password)= LOWER('$pass')";
        $queryGetLogin = "SELECT u.empresa_id,u.id  as usuario_id, per.email, u.password FROM usuario u 
        INNER JOIN personal p on p.usuario_id = u.id 
        INNER JOIN persona per on per.id = p.persona_id 
        WHERE LOWER(per.email) = LOWER('$correo') and LOWER(u.password) = LOWER('$pass');";
        // return $queryGetLogin;
        if($responseBd = $conn->mysqli->query($queryGetLogin)){
            if($responseBd->num_rows > 0){

                while($dataReponseUser = $responseBd->fetch_object()){
                    $usuario_id = $dataReponseUser->usuario_id;
                    $empresa_id = $dataReponseUser->empresa_id;
                }
                $queryGetRol = "SELECT r.id as rol_id, r.rol, u.id FROM usuario u  
                INNER JOIN rol_has_usuario rhu on rhu.usuario_id = u.id
                INNER JOIN rol r on r.id = rhu.rol_id 
                where u.id = $usuario_id";

                $responseRol = $conn->mysqli->query($queryGetRol);

                session_start();
                $_SESSION["empresa_id"] = $empresa_id;
                if($responseRol->num_rows > 0){
                 $rol_id = $responseRol->fetch_object()->rol_id;
                 $_SESSION["rol_id"] = $rol_id;
                }else{
                    $_SESSION["rol_id"] = 4;
                }
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