<?php
if(session_id() == '') {
    session_start();
}
if(!isset($_SESSION['empresa_id'])){
    header("Location: login.php");
    die();
}else{

    $rol_id = $_SESSION["rol_id"];
}
// $rol_id = 3;
$disabled = "";
if($rol_id === 3){
    $disabled = "readonly ='readonly'";
}
?>
<p style="display: none;font-size: 90px;" id="rol_id"><?=$rol_id?></p>