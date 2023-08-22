<!DOCTYPE html>
<html lang="en">
<?php
require_once('./includes/head.php');
$active = 'eventos';
?>

<body>
    <?php include_once('./includes/Constantes/empresaId.php') ?>
    <?php include_once('./includes/Constantes/rol.php') ?>
    <script src="./assets/js/initTheme.js"></script>
    <div id="app">
        <?php require_once('./includes/sidebar.php') ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-header" style="margin-bottom: 30px;">
                <div style="display:flex; align-items: center;justify-content: space-between; ">
                    <h3 style="margin-right: 50px">Paquetes de recursos</h3>
                    <!-- <a href="./ExcelFiles/Clientes.xlsx" download="Carga Masiva Clientes">
                        <div class="card">
                            <i style="font-size: 30px; color:#1D6F42; text-align: center;" class="fa-solid fa-download"></i>
                            <p style="font-size: 20px;">Descargar Formato Excel</p>
                        </div>
                    </a> -->
                </div>
            </div>
            <div class="page-content">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Mis Paquetes</a>
                    </li>
                    <li class="nav-item" role="presentation" id="showEventsRecord">
                        <a class="nav-link" id="EventRecord-tab" data-bs-toggle="tab" href="#EventRecord" role="tab" aria-controls="EventRecord" aria-selected="false">Crear Nuevo Paquete</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <p>No tienes paquetes creados, para ser listados, por favor crea uno antes de continuar</p>
                    </div>
                    <div class="tab-pane fade" id="EventRecord" role="tabpanel" aria-labelledby="EventRecord-tab">

                        <br>
                        <label for="packageName">Nombre</label>
                        <input type="text" name="packageName" id="packageName">

                        <br>


                        <p>Asigna todos los elementos que necesites</p>

                        <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-sunday-list" data-bs-toggle="list" href="#list-sunday" role="tab">Productos</a>
                            <a class="list-group-item list-group-item-action" id="list-monday-list" data-bs-toggle="list" href="#list-monday" role="tab">Técnicos</a>
                            <a class="list-group-item list-group-item-action" id="list-tuesday-list" data-bs-toggle="list" href="#list-tuesday" role="tab">Vehículos</a>
                        </div>
                        <div class="tab-content text-justify">
                            <div class="tab-pane fade show active" id="list-sunday" role="tabpanel" aria-labelledby="list-sunday-list">
                                <table id="productsTable">
                                    <thead>
                                        <th>Categoría</th>
                                        <th>Sub Categoría</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <!-- <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="cursor: pointer;" class="addToPackage"><i class="fa-solid fa-plus"></i></td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="list-monday" role="tabpanel" aria-labelledby="list-monday-list">

                            </div>
                            <div class="tab-pane fade" id="list-tuesday" role="tabpanel" aria-labelledby="list-tuesday-list">

                            </div>
                        </div>
                        <section class="row justify-content-between">

                            <div class="card col-lg-4 col-8">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">Equipos</h4>
                                        <p class="card-text">
                                        </p>
                                    </div>
                                    <img class="img-fluid w-100" src="assets/images/samples/banana.jpg" alt="Card image cap">
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <span>Card Footer</span>
                                    <button class="btn btn-light-primary">Read More</button>
                                </div>
                            </div>
                            <div class="card col-lg-4 col-8">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">Técnicos</h4>
                                        <p class="card-text">

                                        </p>
                                    </div>
                                    <img class="img-fluid w-100" src="assets/images/samples/banana.jpg" alt="Card image cap">
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <span>Card Footer</span>
                                    <button class="btn btn-light-primary">Read More</button>
                                </div>
                            </div>
                            <div class="card col-lg-4 col-8">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">Vehículos</h4>
                                        <p class="card-text">
                                            
                                        </p>
                                    </div>
                                    <img class="img-fluid w-100" src="assets/images/samples/banana.jpg" alt="Card image cap">
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <span>Card Footer</span>
                                    <button class="btn btn-light-primary">Read More</button>
                                </div>
                            </div>

                        </section>

                        <footer class="row justify-content-end" >
                            <button class="btn btn-success col-4">Crear Paquete</button>
                        </footer>
                    </div>
                </div>

            </div>
        </div>
        <?php require_once('./includes/footer.php') ?>
    </div>
    </div>

    <?php require_once('./includes/footerScriptsJs.php') ?>
    <!-- Validador intec -->
    <script src="./js/valuesValidator/validator.js"></script>

    <!-- JS FUNCTIONS -->
    <script src="/js/productos.js"></script>

    <script>
        const EMPRESA_ID = $('#empresaId').text();
        $(document).ready(async function() {
            // GET ALL PRODUCTS BY BUSSINESS AND SEND JSON TO FUNCTION, FILL TABLE AND CONVERT IT TO DATATABLE
            // THIS LIST IS MADE FOR CONSTRUCT A NEW EVENT STANDARD PACKAGE
            const products = await GetAllProductsByBussiness(EMPRESA_ID);
            FillPackageProductsTable(products);
            
        })
        /* FILL PRODUCTS BY GIVES JSON ARRAY AND TRANSFORM TRABLE TO DATATABLE TO SORT IT AND ADD SELECTED PRODUCT TO STANDARD
         PROJECT PACKAGE*/
        function FillPackageProductsTable(products){
            // console.log(products);
            console.table(products);
            products.forEach(product =>{
                let td = `<tr product_id="${product.id}">
                    <td>${product.categoria}</td>
                    <td>${product.item}</td>
                    <td>${product.nombre}</td>
                    <td>${product.cantidad}</td>
                    <td style="cursor: pointer;" class="addToPackage"><i class="fa-solid fa-plus addNewProdToPackage"></i></td>
                </tr>`

                $('#productsTable').append(td);
            })

            $('#productsTable').DataTable();
        }


        // JQUERY SELECTOR FROM FONT-AWESOME PLUS ICON TO GET product_id attr from closest tr on PRODUCT TABLE

        $(document).on('click',".addNewProdToPackage",function(){
            console.log("ADDING NEW PRODUCT TO STANDARD EVENT PACKAGE");
            const product_id = $(this).closest('tr').attr('product_id');

            // SEND TR ATTR PRODUCT_ID VALUE TO AJAX FUNCTION AND CREATE ELEMENT ON PACKAGE PRODUCT RESUME
            AddProductToPackageResume(product_id)
        })

        // CALL AJAX FUNCTION ON JS FUNCTIONS (PRODUCTOS.JS) AND RETURN DATA TO CREATE DYNAMIC ELEMENT ONR RESUME
        async function AddProductToPackageResume(product_id){
            const productData = await GetProductDataById(product_id);
            console.log(productData);
        } 
        


    </script>

</body>

</html>