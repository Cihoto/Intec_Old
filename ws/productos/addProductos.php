<?php
require_once('../bd/bd.php');

$conn = new bd();
$conn->conectar();

$json = file_get_contents('php://input');
$data = json_decode($json);
$productoArr = $data;
$today = date('Y-m-d');
$jsonErrMarca = [];
$jsonErrItemHasClass = [];
$err = false;
$resposePush = [];
set_time_limit(300); 

foreach ($productoArr as $key => $value) {

    
    $err = false;
    $nombre = $value->nombre;
    $marca = $value->marca;
    $modelo = $value->modelo;
    $categoria = $value->categoria;
    $item = $value->item;
    $stock = $value->stock;
    $precioCompra = $value->precioCompra;
    $precioArriendo = $value->precioArriendo;
    $itemId = "";
    $catId = "";


    $queryIdMarca = $conn->mysqli->query("Select id  from marca where LOWER(marca) ='generico'");

   
    if ($queryIdMarca->num_rows === 0) {

        array_push($jsonErrMarca, array(
            "nombre" => $nombre,
            "marca" => $marca,
            "modelo" => $modelo,
            "categoria" => $categoria,
            "item" => $item,
            "stock" => $stock,
            "precioCompra" => $precioCompra,
            "precioArriendo" => $precioArriendo
        ));
        $err = true;
    } else {
        $dataBdResponse = $queryIdMarca->fetch_object();
        $idMarca = $dataBdResponse->id;
    }

    if (!$err) {

        $queryItemHasId = $conn->mysqli->query("SELECT chi.id FROM categoria_has_item chi 
            INNER JOIN categoria c on c.id =chi.categoria_id 
            INNER JOIN item i on i.id = chi.item_id 
            where LOWER(c.nombre)='" . strtolower($categoria) . "' AND LOWER(i.item) ='" . strtolower($item) . "'");

        if ($queryItemHasId->num_rows === 0) {
            $queryCreateItem = "INSERT INTO intec.item(item, createAt, IsDelete)VALUES('" . $item . "','" . $today . "',0)";
            $queryCreateCategoria = "INSERT INTO intec.categoria(nombre, createAt, IsDelete)VALUES('" . $categoria . "','" . $today . "',0)";

            $conn->mysqli->query($queryCreateItem);
            $insertedItem = $conn->mysqli->insert_id;
            $conn->mysqli->query($queryCreateCategoria);
            $insertedCategoria =  $conn->mysqli->insert_id;
            $conn->mysqli->query("INSERT INTO intec.categoria_has_item(categoria_id, item_id)VALUES($insertedCategoria, $insertedItem)");
            array_push($jsonErrItemHasClass, array(
                "nombre" => $nombre,
                "marca" => $marca,
                "modelo" => $modelo,
                "categoria" => $categoria,
                "item" => $item,
                "stock" => $stock,
                "precioCompra" => $precioCompra,
                "precioArriendo" => $precioArriendo
            ));
            $err = true;
        } else {
            $dataBdResponse = $queryItemHasId->fetch_object();
            $cathasitemId = $dataBdResponse->id;
        }

        if (!$err) {

            $queryProducto = "INSERT INTO intec.producto
                (nombre, marca_id, categoria_has_item_id, codigo_barra, precio_compra, precio_arriendo, createAt, IsDelete, empresa_id)
                VALUES('" . $nombre . "'," . $idMarca . "," . $cathasitemId . ", '11011001'," . $precioCompra . "," . $precioArriendo . ", '" . $today . "', 0,1)";

            if ($conn->mysqli->query($queryProducto)) {
                $idProducto = $conn->mysqli->insert_id;

                $queryInventario = "INSERT INTO intec.inventario
                                    (producto_id, cantidad, createAt)
                                    VALUES($idProducto, $stock , $today)";

                if ($conn->mysqli->query($queryInventario)) {
                }
            }
        }
    }
}

echo json_encode(array("total" => count($productoArr), "errMarca" => $jsonErrMarca, "errHasItem" => $jsonErrItemHasClass));
