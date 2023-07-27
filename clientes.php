<?php

?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('./includes/head.php');
$active = 'clientes';
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
                    <h3 style="margin-right: 50px">Clientes</h3>
                    <a href="./ExcelFiles/Clientes.xlsx" download="Carga Masiva Clientes">
                        <div class="card">
                            <i style="font-size: 30px; color:#1D6F42; text-align: center;" class="fa-solid fa-download"></i>
                            <p style="font-size: 20px;">Descargar Formato Excel</p>
                        </div>
                    </a>
                </div>
                <div class="row">

                    <div class="col-md-5 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Carga unitaria</h5>
                                <button class="btn btn-success" id="newClient">Agregar Nuevo Cliente</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-10">
                        <div class="card">
                            <div class="card-body" style="overflow-x: hidden;">
                                <div class="row">
                                    <h5>Carga masiva de clientes</h5>
                                </div>
                                <input type="file" name="" id="excel_input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-content">

                <table id="clientesTable">
                    <thead>
                        <th>Nombre Empresa</th>
                        <th>Nombre Cliente</th>
                        <th>Rut</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <?php require_once('./includes/footer.php') ?>
    </div>
    </div>
    <?php require_once('./includes/Modal/cliente.php') ?>
    <?php require_once('./includes/footerScriptsJs.php') ?>

    <!-- require once de modal para ingresar clientes Masiva -->
    <?php require_once('./includes/Modal/productosMasiva.php') ?>

    <!-- Validador intec -->
    <script src="./js/valuesValidator/validator.js"></script>

    <!-- Validate.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>

    <!-- XSLX READER HJS CDN And JS CLASS FUNCTIONS-->
    <script src="js/xlsxReader.js"></script>
    <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

    <!-- SCRIPTS FUNCIONES JS -->


    <script src="/js/Funciones/NewProject.js"></script>
    <script src="/js/clientes.js"></script>
    <script src="/js/direccion.js"></script>
    <script src="/js/personal.js"></script>
    <script src="/js/vehiculos.js"></script>
    <script src="/js/productos.js"></script>
    <script src="/js/valuesValidator/validator.js"></script>
    <script src="/js/ClearData/clearFunctions.js"></script>
    <script src="/js/localeStorage.js"></script>
    <script src="/js/ProjectResume/projectResume.js"></script>
    <script src="/js/ProjectResume/viatico.js"></script>
    <script src="/js/ProjectResume/subArriendo.js"></script>
    <script src="/js/Funciones/assigments.js"></script>
    <script src="/js/validateForm/client.js"></script>


</body>

<script>
    $('#newClient').on('click', function() {
        $('#clienteModal').modal('show');
    })

    $(document).ready(async function() {
        // FillCientesDisplay()
        FillClientesTable()
    })

    const dataArrayIndex = ['Nombre Cliente', 'Apellido Cliente', 'Rut (opcional)', 'Correo', 'Teléfono', 'Rut Facturación', 'Razón Social', 'Nombre Fantasía', 'Dirección', 'Correo Contacto']
    const dataArray = {
        'xlsxData': [{
                'name': 'Nombre Cliente',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': false
            },

            {
                'name': 'Apellido Cliente',
                'type': 'string',
                'minlength': 3,
                'maxlength': 15,
                'notNull': false
            },

            {
                'name': 'Rut (opcional)',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': true
            },
            {
                'name': 'Correo',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': false
            },

            {
                'name': 'Teléfono',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': false
            },
            {
                'name': 'Rut Facturación',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': true
            },

            {
                'name': 'Razón Social',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': true
            },

            {
                'name': 'Nombre Fantasía',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': true
            },
            {
                'name': 'Dirección',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': false
            },
            {
                'name': 'Correo Contacto',
                'type': 'string',
                'minlength': 3,
                'maxlength': 50,
                'notNull': true
            }
        ]
    }


    //Funcion que verifica la extension del archivo ingresado
    function GetFileExtension() {
        fileName = $('#excel_input').val();
        extension = fileName.split('.').pop();
        return extension;
    }

    $('#excel_input').on('change', async function() {
        const extension = GetFileExtension()
        if (extension === "xlsx") {

            const tableContent = await xlsxReadandWrite(dataArray);

            console.log(tableContent);

            let tableHead = $('#excelTable>thead')
            let tableBody = $('#excelTable>tbody')
            $('#masivaProductoCreation').modal('show')

            //LIMPIAR TABLA
            tableBody.empty()
            tableHead.empty()
            //LLENAR TABLA
            tableHead.append(tableContent[0])
            tableBody.append(tableContent[1])

        } else(
            Swal.fire({
                icon: 'error',
                title: 'Ups',
                text: 'Debes cargar un Excel',
            })
        )
    })

    $('#excelTable>tbody').on('blur', 'td', function() {

        let value = $(this).text()

        //obtencion de las propiedades del TD
        let tdListClass = $(this).attr("class").split(/\s+/);
        let tdClass = tdListClass[0]
        let tdPropertiesIndex = dataArrayIndex.indexOf(tdClass)
        let tdProperties = dataArray.xlsxData[tdPropertiesIndex]

        // SETEO DE PROPIEDADES
        let type = tdProperties.type
        let minlength = tdProperties.minlength
        let maxlength = tdProperties.maxlength
        let notNull = tdProperties.notNull

        //OBTENCION DE PROPIEDADES DE VALOR DE CELDA

        let tdType = isNumeric(value)
        let tdMinlength = minLength(value, minlength)
        let tdMaxlength = maxLength(value, maxlength)

        let tdNull = isNull(value);

        let errorCheck = false
        let tdTitle = ""



        //atributos return a td
        if (!notNull && tdNull) {
            errorCheck = false
            tdTitle = "Ingrese un valor"

        } else {

            if (type === "string" && tdType) {
                errorCheck = true
            } else if (type === "int" && !tdType) {
                errorCheck = false
                tdTitle = "Ingrese un número"
            } else {
                errorCheck = true
            }
            if (!notNull) {
                if (!tdMinlength) {
                    tdTitle = `Debe tener un mínimo de ${minlength} caracteres`
                    errorCheck = false
                }
                if (!tdMaxlength) {
                    tdTitle = `Debe tener un máximo de ${maxlength} caracteres`
                    errorCheck = false
                }
            } else {}
        }
        if (!errorCheck) {
            $(this).prop('title', tdTitle)
            $(this).addClass('err')
        } else {
            $(this).prop('title', "")
            $(this).removeClass('err')
        }
    })



     //GUARDAR REGISTROS MASIVA DENTRO DE MODAL
     $('#saveExcelData').on('click', function() {
        let counterErr = 0;

        $('#excelTable>tbody td').each(function() {

            var cellText = $(this).hasClass('err')
            if (cellText) {
                counterErr++
            }

        });

        if (counterErr == 0) {

            let arrTd = []
            let preRequest = []

            $('#excelTable>tbody tr').each(function() {

                arrTd = []
                let td = $(this).find('td')

                td.each(function() {
                    let tdTextValue = $(this).text()
                    arrTd.push(tdTextValue)
                })
                preRequest.push(arrTd)
            });

            const arrayRequest = preRequest.map(function(value) {
                let requestCliente = {
                    empresaId: EMPRESA_ID,
                    nombreCliente: value[0],
                    apellidos: value[1],
                    rutCliente: value[2],
                    correoCliente: value[3],
                    telefono: value[4],
                    rut: value[5],
                    razonSocial: value[6],
                    nombreFantasia: value[7],
                    direccionDatosFacturacion: value[8],
                    correoDatosFacturacion: value[9]
                }
                return requestCliente
            })
            console.table(arrayRequest);
            console.log(arrayRequest);

            $.ajax({
                type: "POST",
                url: "ws/cliente/cliente.php",
                data: JSON.stringify(
                {
                    tipo:'AddClientMasiva',
                    request: arrayRequest
                }),
                dataType: 'json',
                success: async function(data) {
                    Swal.fire({
                        'icon':"success",
                        'title':'Excelente',
                        'text':`Se han ingresado ${data.inserted} de ${data.total} clientes`,
                        'showConfirmButton':false
                    }).then(()=>{
                        FillClientesTable()
                    })
                },
                error: function(data) {
                    console.log(data);
                }
            })

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Ups',
                text: 'Debe corregir los datos mal ingresado para continuar'
            })
        }
    })
</script>

</html>