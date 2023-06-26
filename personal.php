<?php
require_once('./ws/bd/bd.php');
$conn = new bd();
$conn->conectar();
$arregloPersonal = [];


$queryPersonal = 'SELECT p.id , per.nombre , per.apellido ,e.especialidad ,c.cargo, tc.contrato,per.rut FROM personal p 
                INNER JOIN cargo c on c.id  = p.cargo_id 
                INNER JOIN especialidad e on e.id  = p.especialidad_id 
                INNER JOIN persona per on per.id = p.persona_id 
                LEFT JOIN usuario u on u.id  = p.usuario_id 
                INNER JOIN tipo_contrato tc on tc.id  = p.tipo_contrato_id 
                INNER JOIN empresa emp on emp.id = p.empresa_id 
                where emp.id = 1
                AND p.IsDelete = 0';

$queryCargos = 'select cargo from cargo c';
$queryEsepcialidad = 'SELECT especialidad from especialidad e';
$queryContrato = 'select contrato FROM tipo_contrato tc';

//BUILD DATA PERSONAL
$responseDbPersonal = $conn->mysqli->query($queryPersonal);

while ($dataPersonal = $responseDbPersonal->fetch_object()) {
    $arregloPersonal[] = $dataPersonal;
}

//BUILD DATA CARGOS
$responseDbCargos = $conn->mysqli->query($queryCargos);

while ($dataCargos = $responseDbCargos->fetch_object()) {
    $cargos[] = $dataCargos;
}

//BUILD DATA ESPECIALIDAD
$responseDbEspecialidad = $conn->mysqli->query($queryEsepcialidad);

while ($dataEspecialidad = $responseDbEspecialidad->fetch_object()) {
    $especialidades[] = $dataEspecialidad;
}

//BUILD TIPO CONTRATO DATA
$responseDbTipoContrato = $conn->mysqli->query($queryContrato);

while ($dataContratos = $responseDbTipoContrato->fetch_object()) {
    $contratos[] = $dataContratos;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
require_once('./includes/head.php');
$active = 'personal';
?>

<body>
    <script src="./assets/js/initTheme.js"></script>


    <div id="app">

        <?php require_once('./includes/sidebar.php') ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-header">
                <h3>Personal</h3>
                <div class="row">
                    <div class="col-8 col-lg-3 col-sm-4">
                        <div class="card">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#xlarge">
                                Agregar personal
                            </button>
                            <button class="btn mt-2" onclick="ExportToExcel('xlsx')">
                                <h4>Exportar a Excel</h4>
                            </button>
                        </div>
                    </div>
                    <div class="col-8 col-lg-3 col-sm-4">
                        <div class="card">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#xlarge">
                                Agregar personal masivo
                            </button>
                            <input class="form-control form-control-sm" id="excel_input" type="file" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- modal agregar personal -->
            <div class="modal fade text-left w-100" id="xlarge" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" style="align-items: center;">
                                Agregar Personal
                            </h3>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <form id="addPersonal">
                            <div class="modal-body">

                                <div class="row" style="margin-bottom: 8px;">
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <label for="nombres">Nombres:</label>
                                        <div class="form-group">
                                            <input name="nombres" id="nombres" type="text" placeholder="Nombres" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <label for="apellidos">Apellidos:</label>
                                        <div class="form-group">
                                            <input name="apellidos" id="apellidos" type="text" placeholder="Apellidos" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <label for="rut">Rut:</label>
                                        <div class="form-group">
                                            <input name="rut" id="rut" type="text" placeholder="rut" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <label>Telefono</label>
                                        <div class="form-group">
                                            <input name="telefono" id="inputTelefonoPersonal" type="text" placeholder="56 9 1231 2345" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                            <label for="correoPersonalAddUnitario">Correo</label>
                                           <input type="text" name="correoPersonalAddUnitario" class="form-control" id="correoPersonalAddUnitario">
                                    </div>
                                    <hr>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <label>Cargo:</label>
                                        <div class="form-group">
                                            <select name="cargo_select" id="cargo_select" class="form-select">
                                                <option value=""></option>
                                                <?php
                                                foreach ($cargos as $key => $value) :
                                                ?>
                                                    <option value="<?= $value->cargo ?>"><?= $value->cargo ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <label>Especialidad</label>
                                        <div class="form-group">
                                            <select name="especialidad_select" id="especialidad_select" class="form-select">
                                                <option value=""></option>
                                                <?php
                                                foreach ($especialidades as $key => $value) :
                                                ?>
                                                    <option value="<?= $value->especialidad ?>"><?= $value->especialidad ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <label>Tipo de contrato</label>
                                        <div class="form-group">
                                            <select name="contrato_Select" id="contrato_Select" class="form-select">
                                                <option value=""></option>
                                                <?php
                                                foreach ($contratos as $key => $value) :
                                                ?>
                                                    <option value="<?= $value->contrato ?>"><?= $value->contrato ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="neto">Costo Neto</label>
                                            <input type="number" name="neto" class="form-control" id="neto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <input type="submit" value="Agregar" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end modal -->

            <div class="page-content">
                <!-- PAGE CONTENT -->

                <div class="col-12">
                    <!-- primer  -->
                    <div class="row" style="text-align: right;">

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body px-4 py-4">

                                    <table class="table" id="example" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; display:none">id</th>
                                                <th style="text-align: center;">Nombre</th>
                                                <th style="text-align: center;">Apellido</th>
                                                <th style="text-align: center;">Rut</th>
                                                <th style="text-align: center;">Email</th>
                                                <th style="text-align: center;">Telefono</th>
                                                <th style="text-align: center;">Cargo</th>
                                                <th style="text-align: center;">Especialidad</th>
                                                <th style="text-align: center;">Tipo Contrato</th>
                                                <th style="text-align: center;">Disponibilidad</th>
                                                <th style="text-align: center;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php


                                            foreach ($arregloPersonal as $dato) {
                                                echo '<tr>';

                                                echo '<td class="id" align="center" style ="display:none">' . $dato->id . '</td>';
                                                echo '<td class="nombre" align="center">' . $dato->nombre . '</td>';
                                                echo '<td class="apellido" align="center">' . $dato->apellido . '</td>';
                                                echo '<td align="center">' . $dato->rut . '</td>';
                                                echo '<td align="center">Email</td>';
                                                echo '<td align="center">Telefono</td>';
                                                echo '<td align="center">' . $dato->cargo . '</td>';
                                                echo '<td align="center">' . $dato->especialidad . '</td>';
                                                echo '<td align="center">' . $dato->contrato . '</td>';
                                                echo '<td align="center"><input type="radio"></td>';
                                                echo '<td align="center"><i class="fa-solid fa-trash deletePersonal"></i><i style="left-margin:5px" class="fa-solid fa-pencil"></i></td>';
                                                echo '</tr>';
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align: center; display:none">id</th>
                                                <th style="text-align: center;">Nombre</th>
                                                <th style="text-align: center;">Apellido</th>
                                                <th style="text-align: center;">Rut</th>
                                                <th style="text-align: center;">Email</th>
                                                <th style="text-align: center;">Telefono</th>
                                                <th style="text-align: center;">Cargo</th>
                                                <th style="text-align: center;">Especialidad</th>
                                                <th style="text-align: center;">Tipo Contrato</th>
                                                <th style="text-align: center;">Disponibilidad</th>
                                                <th style="text-align: center;">Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal agregar personal masiva -->
            <div class="modal fade" id="masivaPersonalCreation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-full modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Desea ingresar esta información</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <table class="table" id="excelTable">
                                <thead>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="modalClose" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-success" id="saveExcelData">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN modal masiva -->


            <?php require_once('./includes/footer.php') ?>

        </div>
    </div>

    <?php require_once('./includes/footerScriptsJs.php') ?>

    <!-- xlsx Reader -->
    <script src="js/xlsxReader.js"></script>
    <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

    <!-- Validador intec -->
    <script src="./js/valuesValidator/validator.js"></script>

    <!-- Validate.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>


    <script>
        $(document).ready(function() {

            $('#example').DataTable({
                fixedHeader: true,
                scrollX:true
            })

            $('#addPersonal').validate({
                rules: {
                    nombres: {
                        required: true,
                        minlength: 3
                    },
                    apellidos: {
                        required: true,
                        minlength: 3
                    },
                    rut: {

                    },
                    especialidad_select: {
                        required: true
                    },
                    contrato_Select: {
                        required: true
                    },
                    cargo_select: {
                        required: true
                    },
                    telefono:{
                        required:true
                    },
                    correoPersonalAddUnitario:{
                        required:true
                    },
                    neto:{

                    }
                },
                messages: {
                    nombres: {
                        required: "Ingrese un valor",
                        minlength: "El largo mínimo es de 3 caracteres"
                    },
                    apellidos: {
                        required: "Ingrese un valor",
                        minlength: "El largo mínimo es de 3 caracteres"
                    },
                    rut: {
                        required:"Ingrese un valor"
                    },
                    especialidad_select: {
                        required: "Ingrese un valor"
                    },
                    contrato_Select: {
                        required: "Ingrese un valor"
                    },
                    cargo_select: {
                        required: "Ingrese un valor"
                    },
                    telefono:{
                        required:"Ingrese un valor"
                    },
                    correoPersonalAddUnitario:{
                        required:"Ingrese un valor"
                    },
                    neto:{

                    }

                },
                submitHandler: function(form) {
                    event.preventDefault();
                    console.log("AGREGAR PERSONAL UNITARIO");
                    let nombres = $('#nombres').val();
                    let apellidos = $('#apellidos').val();
                    let rut = $('#rut').val();
                    let especialidad = $('#especialidad_select').val();
                    let contrato = $('#contrato_Select').val();
                    let cargo = $('#cargo_select').val();
                    let correoPersonal = $('#inputTelefonoPersonal').val();
                    let telefonoPersonal = $('#correoPersonalAddUnitario').val();
                    let neto = $('#neto').val();

                    let arrayRequest = [{
                        "nombre": nombres,
                        "apellido": apellidos,
                        "rut": rut,
                        "telefono": telefonoPersonal,
                        "correo": correoPersonal,
                        "cargo": cargo,
                        "especialidad": especialidad,
                        "contrato": contrato,
                        "neto": neto
                    }]

                    $.ajax({
                        type: "POST",
                        url: "ws/personal/addpersonal.php",
                        data: JSON.stringify(arrayRequest),
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                        },
                        error: function(data) {
                            console.log(data.responseText);
                        }
                    })
                }
            })
        });



        const dataArrayIndex = ['nombres', 'apellidos', 'rut', 'telefono', 'correo', 'cargo', 'especialidad', 'contrato']
        const dataArray = {
            'xlsxData': [{
                    'name': 'nombres',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': false
                },
                {
                    'name': 'apellidos',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': false
                },

                {
                    'name': 'rut',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': true
                },

                {
                    'name': 'telefono',
                    'type': 'int',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': true
                },
                {
                    'name': 'correo',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': true
                },

                {
                    'name': 'cargo',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': false
                },

                {
                    'name': 'especialidad',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 15,
                    'notNull': false
                },

                {
                    'name': 'contrato',
                    'type': 'string',
                    'minlength': 3,
                    'maxlength': 50,
                    'notNull': false
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
            if (extension == "xlsx") {

                const tableContent = await xlsxReadandWrite(dataArray);
                let tableHead = $('#excelTable>thead')
                let tableBody = $('#excelTable>tbody')
                $('#masivaPersonalCreation').modal('show')

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
            console.log("puede ser nulo ==>", notNull);
            console.log("ES NULO==>", tdNull);
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
                } else {

                }

            }
            if (!errorCheck) {
                $(this).prop('title', tdTitle)
                $(this).addClass('err')
            } else {
                $(this).prop('title', "")
                $(this).removeClass('err')
            }
        })

        //Cerrar Modal
        $('#modalClose').on('click', function() {
            $('#masivaPersonalCreation').modal('hide')
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
                    let returnArray = {
                        "nombre": value[0],
                        "apellido": value[1],
                        "rut": value[2],
                        "telefono": value[3],
                        "correo": value[4],
                        "cargo": value[5],
                        "especialidad": value[6],
                        "contrato": value[7]
                    }
                    return returnArray
                })
                console.log("requestArray", arrayRequest);
                $.ajax({
                    type: "POST",
                    url: "ws/personal/addpersonal.php",
                    data: JSON.stringify(arrayRequest),
                    dataType: 'json',
                    success: async function(data) {
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseText);
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

        //DELETE PERSONAL 
        $(".deletePersonal").on('click', function() {
            let tr = $(this).closest('tr');
            let nombre = $(this).closest('tr').find('.nombre').text()
            let apellido = $(this).closest('tr').find('.apellido').text()
            console.log(apellido);
            let idPersonal = $(this).closest('tr').find('.id').text()

            Swal.fire({
                icon: 'info',
                title: `Desea dar de baja a: ${nombre} ${apellido}`,
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    let arrayRequest = [{
                        id: idPersonal
                    }]

                    $.ajax({
                        type: "POST",
                        url: "ws/personal/deletePersonal.php",
                        data: JSON.stringify(arrayRequest),
                        dataType: 'json',
                        success: async function(data) {

                            tr.remove()
                            Swal.fire({
                                icon: 'success',
                                title: 'Excelente',
                                text: data.message
                            })
                        },
                        error: function(data) {
                            console.log(data.responseText);
                        }
                    })

                } else {
                    console.log("Cancelado");
                }
            })
        })




        
        //   function ExportToExcel(type, fn, dl) {
        //     var elt = document.getElementById('example');
        //     // console.log(elt);
        //     var elt = `<table class="table" id="example" class="display" style="width:100%">
        //             <thead>
        //                 <tr>
        //                     <th style="text-align: center;">Nombre</th>
        //                     <th style="text-align: center;">Apellido</th>
        //                     <th style="text-align: center;">Rut</th>
        //                     <th style="text-align: center;">Email</th>
        //                     <th style="text-align: center;">Telefono</th>
        //                     <th style="text-align: center;">Cargo</th>
        //                     <th style="text-align: center;">Especialidad</th>
        //                     <th style="text-align: center;">Tipo Contrato</th>
        //                     <th style="text-align: center;">Disponibilidad</th>
        //                     <th style="text-align: center;">Acciones</th>
        //                 </tr>
        //             </thead>
        //             <tbody>`
        //     arregloPersonal.map((data) => {
        //         // console.log(data);
        //         elt = elt + '<tr>'
        //         data.forEach(dato => {
        //             elt = elt + `<td align="center">${dato}</td>`
        //         });
        //         elt = elt + '</tr>'
        //     })
        //     elt = elt + `</tbody>
        //                 </table>`;
        //     console.log(elt);
        //     var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        //     return dl ?
        //         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64'}):
        //         XLSX.writeFile(wb, fn || (`example.` + (type || 'xlsx')));
        //}
    </script>

</body>


</html>