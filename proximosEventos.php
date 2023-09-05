<?php

require_once('./ws/pais_region_comuna/Comuna.php');
require_once('./ws/vehiculo/Vehiculo.php');
require_once('./ws/personal/Personal.php');
require_once('./ws/productos/Producto.php');
require_once('./ws/pais_region_comuna/Region.php');

// $empresaId = 1;
// $isDetails = false;

// // $obj = (object) array('idRegion' => 1);
// // // $comunas = getComunasByRegion($obj);
// // $vehiculos = getVehiculos($empresaId);
// $vehiculos = [];
// $personal =  getPersonal($empresaId);
// // $personal = [];
// // $productos = getProductos($empresaId);
// $productos = [];
// $regiones = getRegiones();



// $regiones = [];


?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once('./includes/head.php');
$active = 'proximosEventos';
?>

<body>

  <?php require_once('./includes/Constantes/empresaId.php') ?>
  <?php require_once('./includes/Constantes/rol.php') ?>

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
        <h3>Nuevo Evento</h3>
      </div>

      <div class="page-content">

        <div class="card box">
          <div class="card-body">
            <div class="form-group">
              <div class="mt-2">
                <form id="projectForm">
                  <div class="row">
                    <div class="col-md-4 col-12">
                      <label for="inputProjectName">Nombre del proyecto</label>
                      <input type="text" class="form-control" name="txtProjectName" id="inputProjectName" placeholder="Nombre">
                    </div>
                    <div class="col-md-3 col-12">
                      <label for="fechaInicio">Fecha del Proyecto</label>
                      <input type="date" class="form-control" name="dpInicio" id="fechaInicio">
                    </div>
                    <div class="col-md-3 col-12">
                      <label for="fechaTermino">Fecha del Proyecto</label>
                      <input type="date" class="form-control" name="dpTermino" id="fechaTermino">
                    </div>
                  </div>
                  <div class="mt-2 row">
                    <div class="col-lg-6 col-md-12">
                      <label for="direccionInput">Direccion del proyecto</label>
                      <input autocomplete="off" type="text" class="form-control" name="txtDir" id="direccionInput" placeholder="Dirección">
                    </div>
                    <div class="col-lg-6 col-md-12">
                      <label for="inputNombreCliente">Nombre Cliente</label>
                      <input autocomplete="off" type="text" class="form-control" name="txtCliente" id="inputNombreCliente" placeholder="Cliente">
                    </div>
                  </div>

                  <div class="form-floating mt-3">
                    <textarea class="form-control" style="min-height: 150px;" placeholder="" id="commentProjectArea" name="txtAreaComments"></textarea>
                    <label for="commentProjectArea">Comentarios</label>
                  </div>

                  <button type="submit" style="display: none;" id="hiddenAddProject" class="btn btn-success ml-1 col-4">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Guardar</span>
                  </button>
                </form>

              </div>
            </div>
          </div>
        </div>


        <?php include_once('./includes/projectAssigments.php') ?>

        <div class="card box">
          <div class="row" style="justify-content: end;">


            <div class="col-3 mt-2 mb-2">
              <button class="btn btn-success" id="submitProject">Crear Proyecto</button>
            </div>
            <?php if ($empresaId === "1") : ?>
              <div class="col-3 mt-2 mb-2">
                <button class="btn btn-success" id="verarray">Ver array</button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
    <?php require_once('./includes/footer.php') ?>
  </div>
  </div>

  <!-- require Modal -->
  <?php require_once('./includes/Modal/direccion.php') ?>
  <?php require_once('./includes/Modal/cliente.php') ?>
  <!-- FIN require Modal -->
  <?php require_once('./includes/footerScriptsJs.php') ?>

  <!-- REQUIRE FORM ARRIENDOS -->
  <?php require_once('./includes/forms/arriendosForm.php') ?>

  <!-- REQUIRE DE FUNCIONES JS -->
  <script src="/js/Funciones/NewProject.js"></script>
  <script src="/js/packages.js"></script>
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
</body>

<script>
  //BOTON DE TEST
  $('#verarray').on('click', function() {

    // localStorage.clear();
    // console.log("asdjhasd,jahkdsjhasd");
    // $('#arriendosModal').modal('show');

    console.log(listProductArray);
  })
  //FIN BOTON TEST

  $('#inputProjectName').on('change', function() {
    $('.projectNameResume').text($(this).val());
  })

  $('#fechaInicio').on('change', function() {
    $('.fechaProjectResume').text($(this).val())
  })

  $('#fechaTermino').on('change', function() {
    $('.fechaProjectResume').text($('.fechaProjectResume').text() + '  /  ' + $(this).val())
  })
  $('#commentProjectArea').on('change', function() {
    $('.comentariosProjectResume').text($(this).val())
  })

  const EMPRESA_ID = document.getElementById('empresaId').textContent;
  let ROL_ID = <?php echo json_encode($rol_id); ?>;


  // ADD PACKAGE TO PROJECT ON PLUS ICON ON PACKAGE LIST
  $(document).on('click', '.addPackageToAssigments', async function() {
    addPackageToProjectAssigments($(this))
  })

  $(document).ready(async function() {

    // const data = {
    //   'fecha_inicio': "2023-08-01",
    //   'fecha_termino': "2023-08-10"
    // }
    FillStandardPackages();
    $('#tableResume').DataTable({})
    //fillvehiculos
    FillVehiculos(EMPRESA_ID);
    // Fill Clientes
    FillClientes(EMPRESA_ID);
    //FILL DIRECCIONES
    FillDirecciones();
    //FILL PRODUCTOS
    // FillProductos(EMPRESA_ID);
    //FILL PERSONAL
    FillPersonal(EMPRESA_ID);
    // CLEAR LOCALSTORGE
    localStorage.clear();
    // FILL REGIONES
    FillRegiones(EMPRESA_ID);
    $(document).on('click', '.logoRemove', function() {
      let productId = $(this).closest('.detailsProduct-box').find('.itemId').text();
      removeProduct(productId);
      $(this).closest('.detailsProduct-box').remove()
      $('#resumeBody').find(`.idProd${productId}`).remove();
    })

    // SHOW BILLING DATA 
    $('#clientHasFacturacion').on('click', function() {
      if ($(this).is(':checked')) {

        $('#clientFactData').addClass('active');
      } else {

        $('#clientFactData').removeClass('active');
      }
    })

    // VALIDAR FORM AGREGAR DIRECCION
    $('#direccionAddForm').validate({
      rules: {
        txtDir: {
          required: true
        },
        txtNumDir: {
          required: true
        },
        regionSelect: {
          required: true
        },
        comunaSelect: {
          required: true
        }
      },
      messages: {
        txtDir: {
          required: "Debe ingresar un valor"
        },
        txtNumDir: {
          required: "Debe ingresar un valor"
        },
        regionSelect: {
          required: "Debe ingresar un valor"
        },
        comunaSelect: {
          required: "Debe ingresar un valor"
        }
      },
      submitHandler: function() {

        event.preventDefault();
        // localStorage.clear();
        //CREAR LOCALE STORAGE TO DIRECCIONES
        $("#direccionModal ").modal('hide');
        //DATOS DE DIRECCION
        let dir = $('#txtDir').val();
        let numDir = $('#txtNumDir').val();
        let depto = $('#txtDepto').val();
        let region = $('#regionSelect').val();
        let comuna = $('#comunaSelect').val();
        let regionInput = $('#regionSelect option:selected').text();
        let comunaInput = $('#comunaSelect option:selected').text();
        let postal_code = $('#txtcodigo_postal').val();
        let idDireccion = $('#idDireccionModal').text();

        $('#direccionInput').val(`${dir} ${numDir} ${depto}, ${comunaInput}, ${regionInput}`);
        $('.lugarProjectResume').text(`${dir} ${numDir} ${depto}, ${comunaInput}, ${regionInput}`);


        if (localStorage.getItem("direccion") === null) {
          localStorage.setItem("direccion", JSON.stringify([{
            dir,
            numDir,
            depto,
            region,
            comuna,
            regionInput,
            comunaInput,
            postal_code,
            idDireccion
          }]))
        } else {
          let allDirs = JSON.parse(localStorage.getItem("direccion"))
          allDirs.push({
            dir,
            numDir,
            depto,
            region,
            comuna,
            regionInput,
            comunaInput,
            postal_code,
            idDireccion
          });
          localStorage.setItem("direccion", JSON.stringify(allDirs));
        }

        // localStorage.setItem("direccion",JSON.stringify())
      }
    })

    $('#arriendoForm').validate({
      rules: {
        nombreArriendo: {
          required: true
        },
        valorArriendo: {
          required: true
        },
        txtNombre: {
          required: true
        },
        txtApellidos: {
          required: true
        },
        txtRut: {
          required: true
        },
        txtCorreo: {
          required: true
        },
        txtTelefono: {
          required: true
        },
        txtRut: {
          required: true
        },
        txtRazonSocial: {
          required: true
        },
        txtNombreFantasia: {
          required: true
        },
        txtDireccionDatosFacturacion: {
          required: true
        },
        txtCorreoDatosFacturacion: {
          required: true
        }
      },
      messages: {
        nombreArriendo: {
          required: "Ingrese un valor"
        },
        valorArriendo: {
          required: "Ingrese un valor"
        },
        txtNombre: {
          required: "Ingrese un valor"
        },
        txtApellidos: {
          required: "Ingrese un valor"
        },
        txtRut: {
          required: "Ingrese un valor"
        },
        txtCorreo: {
          required: "Ingrese un valor"
        },
        txtTelefono: {
          required: "Ingrese un valor"
        },
        txtRut: {
          required: "Ingrese un valor"
        },
        txtRazonSocial: {
          required: "Ingrese un valor"
        },
        txtNombreFantasia: {
          required: "Ingrese un valor"
        },
        txtDireccionDatosFacturacion: {
          required: "Ingrese un valor"
        },
        txtCorreoDatosFacturacion: {
          required: "Ingrese un valor"
        }
      },
      submitHandler: function() {
        event.preventDefault();
        const form = $('#arriendoForm').serializeArray();

        let request = convertFormToJSON(form);
        console.table(request);

        $.ajax({
          type: "POST",
          url: 'ws/Arriendos/arriendos.php',
          data: JSON.stringify({
            action: 'SetNewRent',
            request: request,
            empresa_id: EMPRESA_ID
          }),
          dataType: 'json',
          success: function(data) {

            // console.log("REQUEST ENVIADO", data);

          },
          error: function(response) {
            // console.log(response.responseText);
          }
        })
      }
    })


    // VALIDAR FORM CLIENTE Y DATOS DE FACTURACION
    $('#clienteForm').validate({
      rules: {
        txtNombreCliente: {
          required: true
        },
        txtApellidos: {
          required: true
        },
        txtRut: {
          required: false
        },
        txtCorreo: {
          required: true
        },
        txtTelefono: {
          required: true
        },
        txtRut: {
          required: false
        },
        txtRazonSocial: {
          required: false
        },
        txtNombreFantasia: {
          required: false
        },
        txtDireccionDatosFacturacion: {
          required: false
        },
        txtCorreoDatosFacturacion: {
          required: false
        }
      },
      messages: {
        txtNombreCliente: {
          required: "Ingrese un valor"
        },
        txtApellidos: {
          required: "Ingrese un valor"
        },
        txtRut: {
          required: "Ingrese un valor"
        },
        txtCorreo: {
          required: "Ingrese un valor"
        },
        txtTelefono: {
          required: "Ingrese un valor"
        },
        txtRut: {
          required: "Ingrese un valor"
        },
        txtRazonSocial: {
          required: "Ingrese un valor"
        },
        txtNombreFantasia: {
          required: "Ingrese un valor"
        },
        txtDireccionDatosFacturacion: {
          required: "Ingrese un valor"
        },
        txtCorreoDatosFacturacion: {
          required: "Ingrese un valor"
        }
      },
      submitHandler: function() {
        event.preventDefault();
        //DATOS DE CLIENTE
        let nombreCliente = $('#inputNombreClienteForm').val();
        let apellidos = $('#inputApellidos').val();
        let rutCliente = $('#inputRutCliente').val();
        let correo = $('#inputCorreo').val();
        let telefono = $('#inputTelefono').val();
        let rut = $('#inputRut').val();
        let razonSocial = $('#inputRazonSocial').val();
        let nombreFantasia = $('#inputNombreFantasia').val();
        let direccionDatosFacturacion = $('#inputDireccionDatosFacturacion').val();
        let correoDatosFacturacion = $('#inputCorreoDatosFacturacion').val();
        $('#inputNombreCliente').val(`${nombreCliente} ${apellidos}`);
        $(".clienteProjectResume").text(`${nombreCliente} ${apellidos}`);
        $("#clienteModal ").modal('hide');
      }
    })



    // VALIDAR DATOS Y CREAR PROYECTO
    $('#projectForm').validate({
      rules: {
        txtProjectName: {
          required: true
        },
        dpInicio: {
          required: false
        },
        dpTermino: {
          required: false
        },
        txtDir: {},
        txtCliente: {}
      },
      messages: {
        txtProjectName: {
          required: "Ingrese un valor"
        },
        dpInicio: {
          required: "Ingrese un valor"
        },
        dpTermino: {
          required: "Ingrese un valor"
        },
        txtDir: {
          required: "Ingrese un valor"
        },
        txtCliente: {
          required: "Ingrese un valor"
        }
      },
      submitHandler: async function() {
        event.preventDefault()

        //DATOS PROYECTO
        let projectName = $('#inputProjectName').val();
        let fechaInicio = $('#fechaInicio').val();
        let fechaTermino = $('#fechaTermino').val();
        let comentarios = $('#commentProjectArea').val()

        //CREAR CLIENTE PARA PROYECTO
        let idCliente;
        let nombre = $('#txtNombreCliente').val()


        let nombreCliente = $('#inputNombreClienteForm').val()
        let apellidos = $('#inputApellidos').val()
        let rutCliente = $('#inputRutCliente').val()
        let correoCliente = $('#inputCorreo').val()
        let telefono = $('#inputTelefono').val()
        let rut = $('#inputRut').val()
        let razonSocial = $('#inputRazonSocial').val()
        let nombreFantasia = $('#inputNombreFantasia').val()
        let direccionDatosFacturacion = $('#inputDireccionDatosFacturacion').val()
        let correoDatosFacturacion = $('#inputCorreoDatosFacturacion').val()
        let idClienteReq = $('#clienteSelect').val();




        let requestCliente = {
          empresaId: EMPRESA_ID,
          nombreCliente: nombreCliente,
          apellidos: apellidos,
          rutCliente: rutCliente,
          correoCliente: correoCliente,
          telefono: telefono,
          rut: rut,
          razonSocial: razonSocial,
          nombreFantasia: nombreFantasia,
          direccionDatosFacturacion: direccionDatosFacturacion,
          correoDatosFacturacion: correoDatosFacturacion
        }

        if (idClienteReq === "" || idClienteReq === null || idClienteReq === undefined) {

        }else{
          requestCliente["idCliente"] = idClienteReq
          // requestCliente.push({
          //   "idCliente": idClienteReq
          // })
        }

        // console.log("----------------------------");
        // console.log("----------------------------");
        // console.log(requestCliente);
        // console.log("----------------------------");
        // console.log("----------------------------");

        //DATOS DE DIRECCION
        let dir = $('#txtDir').val()
        let numDir = $('#txtNumDir').val()
        let depto = $('#txtDepto').val()
        let region = $('#regionSelect').val()
        let comuna = $('#comunaSelect').val()
        let regionInput = $('#regionSelect option:selected').text()
        let comunaInput = $('#comunaSelect option:selected').text()
        let postal_code = $('#txtcodigo_postal').val()
        let id_direccion;
        let id_lugar;

        let requestDir = [{
          direccion: dir,
          numero: numDir,
          depto: depto,
          region: region,
          codigo_postal: postal_code,
          comuna: comuna
        }]

        if ($('#direccionInput').val() !== "") {
          const resultDireccion = await Promise.all([addDir(requestDir)]);
          id_direccion = resultDireccion[0].id_direccion;
          let lugarRequest = [{
            lugar: dir,
            direccion_id: id_direccion
          }]
          const responseLugar = await Promise.all([addLugar(lugarRequest)]);

          id_lugar = responseLugar[0].id_lugar;
        }

        if ($('#inputNombreCliente').val() !== "") {
          console.table(requestCliente);
          const resultCliente = await Promise.all([addCliente(requestCliente)]);

          console.log("RESPONSE CLIENTE ADD AJAX PHP");
          console.log("RESPONSE CLIENTE ADD AJAX PHP");
          console.log(resultCliente);
          console.log("RESPONSE CLIENTE ADD AJAX PHP");
          console.log("RESPONSE CLIENTE ADD AJAX PHP");
          idCliente = resultCliente[0].idCliente

          // DATOS PARA LA CRECION BASE DE UN PROYECTO
          let direccion = $('#direccionInput').val();
          let nombreCliente = $('#inputNombreCliente').val();
        }

        //PUT CLIENT ID VALUE ON "" WHEN INPUT IS EMPTY ON PROJECT REQUEST
        if ($('#inputNombreCliente').val() === "") {
          idCliente = "";
        }

        //PUT PLACE ID VALUE ON "" WHEN INPUT IS EMPTY ON PROJECT REQUEST
        if ($('#direccionInput').val() === "") {
          id_direccion = "";
          id_lugar = "";
        }

        console.log("ID CLIENTE");
        console.log("ID CLIENTE");
        console.log(idCliente);
        console.log("ID CLIENTE");
        console.log("ID CLIENTE");

        let requestProject = {
          nombre_proyecto: projectName,
          lugar_id: id_lugar,
          fecha_inicio: fechaInicio,
          fecha_termino: fechaTermino,
          cliente_id: idCliente,
          comentarios: comentarios,
          empresa_id: EMPRESA_ID
        }

        console.log("REQUEST PARA PROYECTO");
        console.log("REQUEST PARA PROYECTO");
        console.log(requestProject);
        console.log("REQUEST PARA PROYECTO");
        console.log("REQUEST PARA PROYECTO");

        const responseProject = await Promise.all([createProject(requestProject)])
        idProject = responseProject[0].id_project;
        let arrayVehiclesID = []
        $('#sortable2 > li').each(function() {

          let vClass = $(this).attr('class')
          // console.log(vClass)
          arrayVehiclesID.push({
            idVehiculo: vClass
          })
        })

        const requestVehicle = arrayVehiclesID.map(vId => {
          return {
            idProject: idProject,
            idVehicle: vId.idVehiculo
          };
        })

        let arrayPersonal = []
        $('#sortablePersonal2 > li').each(function() {
          let vClass = $(this).attr('class')
          let valor = $(this).find('.personalPrice').val()
          arrayPersonal.push({
            idPersonal: vClass,
            cost: valor

          })
        })
        const requestPersonal = arrayPersonal.map(vId => {
          return {
            idProject: idProject,
            idPersonal: vId.idPersonal,
            cost: vId.cost
          };
        })

        let arrayProducts = []

        // 'id': product.id,
        // 'nombre': product.nombre,
        // 'precio_arriendo': product.precio_arriendo,
        // 'quantityToAdd': productExists.quantityToAdd,
        // 'faltantes' : product.faltantes

        selectedProducts.forEach((product)=>{
          arrayProducts.push({
            idProject: idProject,
            idProduct: product.id,
            price: product.precio_arriendo,
            quantity: product.quantityToAdd
          })
        })

        // $('.detailsProduct-box').each(function() {
        //   let idProduct = $(this).find('.itemId').text();
        //   let productPrice = $(this).find('.getPrice').text();
        //   let productQuantity = $(this).find('.addProdInput').val();

        // })

        // console.log("requestPersonal", requestPersonal);

        const responseAssignPersonal = await Promise.all([assignvehicleToProject(requestVehicle), assignPersonal(requestPersonal), assignProduct(arrayProducts)])
        response = responseAssignPersonal

        let arrayViaticos = $('#projectViatico > tbody tr .tbodyHeader');
        if (arrayViaticos.length > 0) {
          $('#projectViatico > tbody tr .tbodyHeader').each((key, el) => {
            SetViatico(idProject, $(el).closest('tr').find('.totalViaticoInput').val(), $(el).closest('tr').find('.inputViaticoName').val());
          })

          let arrayViaticosRequest = GetProjectViaticos();
          console.table("arrayViaticosRequest", arrayViaticosRequest);
          if (arrayViaticosRequest !== false) {
            $.ajax({
              type: "POST",
              url: 'ws/personal/Personal.php',
              data: JSON.stringify({
                action: 'setviatico',
                request: arrayViaticosRequest
              }),
              dataType: 'json',
              success: function(data) {

                // console.log("RESPONSE AGIGNACION VIATICOS", data);

              },
              error: function(response) {
                // console.log(response.responseText);
              }
            })
          }
        }

        let arrayArriendos = $('#projectSubArriendos > tbody tr .tbodyHeader');
        let arrayRequestRent = [];
        if (arrayArriendos.length > 0) {
          $('#projectSubArriendos > tbody tr .tbodyHeader').each((key, el) => {
            arrayRequestRent.push({
              proyecto_id: idProject,
              arriendo_id: $(el).closest('tr').attr('id'),
              costo: $(el).closest('tr').find('.inputSubValue').val()
            })
          })
          console.table()
          let responseRents = await AssignRents(arrayRequestRent);
        } else {
          console.log("NO RENT TO ASSIGN");
        }

        let totalIngresos = parseInt(ClpUnformatter($('#totalIngresos').text()));

        if (totalIngresos === "" || totalIngresos === undefined || totalIngresos === null || totalIngresos === "$NaN") {
          totalIngresos = 0
        }

        let request = [{
          idProject: idProject,
          valor: totalIngresos
        }];

        $.ajax({
          type: "POST",
          url: 'ws/personal/Personal.php',
          data: JSON.stringify({
            action: 'SetTotalProject',
            request: request
          }),
          dataType: 'json',
          success: function(data) {
            Swal.fire({
              position: 'bottom-end',
              icon: 'success',
              title: 'El proyecto ha sido creado exitosamente',
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              window.location = "proyectos.php"
            })

          },
          error: function(response) {
            // console.log(response.responseText);
          }
        })
      }
    })
  })

  //OPEN MODAL DIRECCION
  $('#direccionInput').on('click', function() {
    $('#direccionModal').modal('show');
  })
  //OPEN MODAL CLIENTE
  $('#inputNombreCliente').on('click', function() {
    $('#clienteModal').modal('show');
  })


  // GUARDAR CLIENTE EN INPUT CLIENTE
  $('#addCliente').on('click', function() {

  })

  //GATILLAR EVENTO CLICK EN BOTON SUBMIT DE FORM PARA CREACION DEL PROYECTO
  $('#submitProject').on('click', function() {
    $('#hiddenAddProject').trigger('click')
  })


  $('#tableResumeView').on('click', function() {
    let navItem = $(this).find('.projectAssigmentTab')
    if ($(navItem).hasClass('active')) {
      $(navItem).removeClass('active')
      $('#resumen').removeClass('active show').addClass('fade');
    } else {
      CloseAllTabsOnProjectsAssigments();
      $(navItem).addClass('active')
      $('#resumen').removeClass('fade').addClass('active show');
    }
  })

  $('#getAvailableVehicles').on('click', function() {
    let navItem = $(this).find('.projectAssigmentTab')
    if ($(navItem).hasClass('active')) {
      $(navItem).removeClass('active')
      $('#vehicle').removeClass('active show').addClass('fade');
    } else {

      CloseAllTabsOnProjectsAssigments();
      $(navItem).addClass('active');
      $('#vehicle').removeClass('fade').addClass('active show');
      $('#DragVehiculos').show();
      $('#fechaInicio').val();
      $('#fechaTermino').val();
      let fechaInicio = $('#fechaInicio').val();
      let fechaTermino = $('#fechaTermino').val();
      if (fechaInicio === "" || fechaTermino === "") {
        Swal.fire({
          title: '',
          text: "Debes seleccionar el rango de fechas en las que se realizará este proyecto para poder ver los vehículos disponibles, ¿Deseas continuar y ver todos tus vehículos?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ver todos los vehículos',
          cancelButtonText: 'Seleccionaré un rango de fechas'
        }).then((result) => {
          if (result.isConfirmed) {
            FillVehiculos(EMPRESA_ID);
          } else {}
        })
      }
      if (fechaInicio !== "" && fechaTermino !== "") {
        GetAvailableVehicles(EMPRESA_ID, fechaInicio, fechaTermino);
      }
    }
  })

  $('#getAvailableProducts').on('click', function() {
    let navItem = $(this).find('.projectAssigmentTab')
    if ($(navItem).hasClass('active')) {
      $(navItem).removeClass('active')
      $('#products').removeClass('active show').addClass('fade');
    } else {

      CloseAllTabsOnProjectsAssigments();
      $(navItem).addClass('active')
      $('#products').removeClass('fade').addClass('active show');

      if ($('#fechaInicio').val() === "" || $('#fechaTermino').val() === "") {

        Swal.fire({
          title: '',
          text: "Debes seleccionar el rango de fechas en las que se realizara este proyecto para poder ver los productos disponibles,Deseas continuar y ver todos tus productos sin asignar?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ver todos los productos',
          cancelButtonText: 'Seleccionaré un rango de fechas'
        }).then((result) => {
          if (result.isConfirmed) {
            // FillProductosAvailable(EMPRESA_ID, "all", "", "");
            FillAllProducts()
          }
        })
      }
      if ($('#fechaInicio').val() !== "" && $('#fechaTermino').val() !== "") {
        // FillProductosAvailable(EMPRESA_ID, "available", $('#fechaInicio').val(), $('#fechaTermino').val());

        const dates = {
          'fecha_inicio': $('#fechaInicio').val(),
          'fecha_termino': $('#fechaTermino').val()
        }
        FillAllAvailableProducts(dates);

      }
    }

  })

  function GetAllProductsByBussiness(empresa_id) {
    return $.ajax({
      type: "POST",
      url: "ws/productos/Producto.php",
      dataType: 'json',
      data: JSON.stringify({
        "action": "GetAllProductsByBussiness",
        empresa_id: empresa_id
      }),
      success: function(response) {

      }
    })
  }

  function GetUnavailableProductsByDate(data, empresa_id) {
    return $.ajax({
      type: "POST",
      url: "ws/productos/Producto.php",
      dataType: 'json',
      data: JSON.stringify({
        "action": "GetUnavailableProductsByDate",
        'empresa_id': empresa_id,
        'request': {
          'data': data
        }
      }),
      success: function(response) {

      },
      error: function(error) {
        console.log(error);
      }
    })
  }

  // function to call and fill table products without dates restrictions
  async function FillAllProducts() {

    allMyProducts = []
    listProductArray = [];

    const responseAllProducts = await GetAllProductsByBussiness(EMPRESA_ID);

    if (responseAllProducts.success) {

      allMyProducts = responseAllProducts.data;

      // SET listProductArray (GLOBAL VARIABLE), CONFIG JSON OBJECT BY MAP FUNCTION WITH DB AJAX DATA
      // THIS ARRAY WILL BE USED ON EVERY MOVE ON PRODUCTS ASSIGMENT
      listProductArray = allMyProducts.map(function(producto) {
        let disponibles = producto.cantidad
        return {
          'id': producto.id,
          'categoria': producto.categoria,
          'item': producto.item,
          'nombre': producto.nombre,
          'precio_arriendo': producto.precio_arriendo,
          'cantidad': producto.cantidad,
          'disponibles': disponibles,
          'faltantes': 0
        }
      })
      fillProductsTableAssigments();
    }
  }

  async function FillAllAvailableProducts(dates) {

    allMyProducts = [];
    allMyTakenPoducts = [];
    listProductArray = [];

    const fecha_inicio = dates.fecha_inicio;
    const fecha_termino = dates.fecha_termino;
    const data = {
      'fecha_inicio': fecha_inicio,
      'fecha_termino': fecha_termino
    }
    const responseUnavailableProducts = await GetUnavailableProductsByDate(data, EMPRESA_ID);
    const responseAllProducts = await GetAllProductsByBussiness(EMPRESA_ID);

    if (responseUnavailableProducts.success && responseAllProducts.success) {
      allMyProducts = responseAllProducts.data;
      allMyTakenPoducts = responseUnavailableProducts.data;
      // console.log(allMyProducts);
      if (allMyTakenPoducts.length === 0) {

        // listProductArray = allMyProducts

        listProductArray = allMyProducts.map(function(producto) {
          let disponibles = producto.cantidad
          return {
            'id': producto.id,
            'categoria': producto.categoria,
            'item': producto.item,
            'nombre': producto.nombre,
            'precio_arriendo': producto.precio_arriendo,
            'cantidad': producto.cantidad,
            'disponibles': disponibles,
            'faltantes': 0
          }
        })
      } else {
        listProductArray = allMyProducts.map((producto, index) => {
          let disponibles = producto.cantidad;
          const takenProduct = allMyTakenPoducts.find((taken) => {
            if (taken.producto_id === producto.id) {
              return taken
            }
          });
          if (takenProduct) {
            disponibles = parseInt(producto.cantidad) - parseInt(takenProduct.cantidad)
          }
          return {
            'id': producto.id,
            'categoria': producto.categoria,
            'item': producto.item,
            'nombre': producto.nombre,
            'precio_arriendo': producto.precio_arriendo,
            'cantidad': producto.cantidad,
            'disponibles': disponibles,
            'faltantes': 0
          }
        })
      }

      // FILL TABLE WITH listProductArray
      // this array contains a json object returned by map all data given by ajax db call
      // map gives format to this json, after we can manage this array to disocunt available stock or whatever we need
      fillProductsTableAssigments();
      // allAndselectedProductsList = listProductArray;
    }
  }

  function fillProductsTableAssigments() {
    if ($.fn.DataTable.isDataTable('#tableProducts')) {
      $('#tableProducts').DataTable().destroy();
      $('#tableDrop > tr').each((key, element) => {
        $(element).remove();
      })
    }
    listProductArray.forEach(producto => {
      let td = `
            <td class="productId" style="display:none">${producto.id}</td>
            <td class="catProd"> ${producto.categoria}</td>
            <td class="itemProd"> ${producto.item}</td>
            <td style="width:25%" class="productName">${producto.nombre}</td>
            <td class="productPrice"> ${producto.precio_arriendo} </td>
            <td class="productStock" >${producto.cantidad}</td>
            <td class="productAvailable" >${(producto.disponibles < 0)? 0: producto.disponibles}</td>
            <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${producto.cantidad}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
      $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);
    });

    $('#tableProducts').dataTable();
  }

  $(document).on('click', '.removePackageFromAssigment', async function() {
    const package_id = $(this).closest('.packageNameContainer').attr('package_id');
    console.log(package_id);

    const packageExists = selectedPackages.find((selectedPackage) => {
      return selectedPackage.id === package_id
    })

    if (!packageExists) {
      Swal.fire(
        'Ups!',
        'Ha ocurrido un error, por favor intenta nuevamente',
        'error'
      );
      return
    }
    //GET ALL PACKAGE DETAILS, NAME, ID FROM PACKAGE AND PRODUCTS THAT CONTAINS 
    const detailsPackage = await GetPackageDetails(package_id);
    console.log("detailsPackage", detailsPackage);
    if (!detailsPackage.success) {
      console.log("nada");
    }

    // SET PACKAGE ID TO FIND IT ON GLOBAL VARIABLE PACKAGE_LIST
    // IF RETURN TRUE PUSH RESULT AND APPEND IT TO RESUME
    const detailPackageId = detailsPackage.data[0].id;
    const packageToAdd = PACKAGE_LIST.find((package) => {
      if (package.id === detailPackageId) {
        return package
      }
    })
    // PUSH FINDED PACKAGE TO GLOBAL LIST
    selectedPackages.find((existingPackage, index, array) => {
      if (existingPackage.id === existingPackage.id) {
        array.splice(index, 1)
      }
    })
    console.log(selectedPackages);
    // ADD SELECTED PACKAGES TO RESUME
    addPackageToPackageAssigment();
    // FORMAT PRODUCTS TO STANDARD JSON AND APPEND ON RESUME
    // ALSO SET STOCK AND AVAILABILITY ON RESUME PRODUCT TABLE
    const productsToAdd = detailsPackage.products.map((packageProducts) => {
      return {
        'product_id': packageProducts.product_id,
        'quantityToAdd': packageProducts.quantity
      }
    });
    // THIS FUNCTION MODIFY GLOBAL CONST listProductArray 
    AddStockFromProducts(productsToAdd);
    // THIS FUNCTION USE GLOBAL VARIABLE AND APPEND ARRAY ON TABLE PRODUCTS
    fillProductsTableAssigments();

    //FORMAT RESUME PRODUCT ARRAY
    SetSelectedProducts_Add(productsToAdd);

    // APPEND ALL PRODUCTS TO RESUME AND RESUME PROJECT TABLE
    addProductToResumeAssigment()
  })

  let lastValue = 0
  $(document).on('click', '.addProdInputResume', async function() {
    lastValue = $(this).val();
  })

  $(document).on('blur', '.addProdInputResume', async function() {
    let currentValue = $(this).val();

    if(!isNumeric(currentValue)){
      Swal.fire(
          'Ups!',
          'Debes ingresar un número',
          'error'
        );
      return
    }
    let product_id = $(this).closest('.detailsProduct-box').find('.itemId').text();
    let minProducts = [];
    let minvalue =0;

    if (selectedPackages.length > 0) {

      const prodExists = selectedProducts.find((product) => {
        return product.id === product_id
      })
      if (!prodExists) {
        Swal.fire(
          'Ups!',
          'Ha ocurrido un error, por favor intenta nuevamente',
          'error'
        );
        $(this).val(lastValue);
        return
      }
      const detailsPackage = await Promise.all(
        selectedPackages.map(async (package)=>{
        return await GetPackageDetails(package.id);
      }))

      minProducts =  detailsPackage.map((packageProds,index)=>{
        return packageProds.products[index,0]
      })

      minProducts.forEach((prod) => {
        if (prod.product_id === product_id){
          minvalue += parseInt(prod.quantity)
        }
      })
      if (parseInt(currentValue) < minvalue){
        Swal.fire(
          'Ups!',
          `No puedes seleccionar menos de ${minvalue} de este equipo ya que pertenecen a un paquete estandard que ya seleccionaste`,
          'error'
        ).then(()=>{
          console.log($(this));
          $(this).val(lastValue);
        });


        return
      } else {
        const quantityAddStock = parseInt(lastValue) - parseInt(currentValue);
        const productsToAdd =  [{
          'product_id': product_id,
          'quantityToAdd': quantityAddStock
        }];
        // THIS FUNCTION MODIFY GLOBAL CONST listProductArray 
        AddStockFromProducts(productsToAdd);
        // THIS FUNCTION USE GLOBAL VARIABLE AND APPEND ARRAY ON TABLE PRODUCTS
        fillProductsTableAssigments();

        //FORMAT RESUME PRODUCT ARRAY
        SetSelectedProducts_Add(productsToAdd);

        // APPEND ALL PRODUCTS TO RESUME AND RESUME PROJECT TABLE
        addProductToResumeAssigment()
      }
    }else{

      const prodExists = selectedProducts.find((product) => {
        return product.id === product_id
      })
      if (!prodExists) {
        Swal.fire(
          'Ups!',
          'Ha ocurrido un error, por favor intenta nuevamente',
          'error'
        );
        $(this).val(lastValue);
        return
      }

      if(currentValue > 0){

        const productsToAdd =  [{
            'product_id': product_id,
            'quantityToAdd': currentValue
          }];
          // THIS FUNCTION MODIFY GLOBAL CONST listProductArray 
          AddStockFromProducts(productsToAdd);
          // THIS FUNCTION USE GLOBAL VARIABLE AND APPEND ARRAY ON TABLE PRODUCTS
          fillProductsTableAssigments();
  
          //FORMAT RESUME PRODUCT ARRAY
          SetSelectedProducts_Add(productsToAdd);
  
          // APPEND ALL PRODUCTS TO RESUME AND RESUME PROJECT TABLE
          addProductToResumeAssigment()
      }else{
        $(this).val(lastValue)
      }
    }
  })

  function AddStockFromProductListArray_Package_Management(packageProducts) {
    // MODIFY ARRAY listProductArray AND ADD AVAILABLES FROM EACH ROW
    // THIS FUNCTION ONLY MODIFY GLOBAL VARIABLE listProductArray TO USE IT ON NEW APPEND IN PRODUCTS TABLE

    listProductArray = listProductArray.map((product) => {
      let faltantes = product.faltantes;
      let disponibles = product.disponibles;

      const productExists = packageProducts.find((packProd) => {
        if (packProd.product_id === product.id) {
          return packProd;
        }
      })

      if (productExists) {
        console.table(product)
        disponibles = parseInt(product.disponibles) + parseInt(productExists.quantity);
      }
      if (disponibles > 0) {
        faltantes = 0;
      }

      return {
        'id': product.id,
        'categoria': product.categoria,
        'item': product.item,
        'nombre': product.nombre,
        'precio_arriendo': product.precio_arriendo,
        'cantidad': product.cantidad,
        'disponibles': disponibles,
        'faltantes': faltantes
      }
    })
  }

  $('#getAvailablePersonal').on('click', function() {
    let navItem = $(this).find('.projectAssigmentTab')
    if ($(navItem).hasClass('active')) {
      $(navItem).removeClass('active')
      $('#personal').removeClass('active show').addClass('fade');
    } else {

      CloseAllTabsOnProjectsAssigments();
      $(navItem).addClass('active')
      $('#personal').removeClass('fade').addClass('active show');

      if ($('#fechaInicio').val() === "" || $('#fechaTermino').val() === "") {

        Swal.fire({
          title: '',
          text: "Debes seleccionar el rango de fechas en las que se realizara este proyecto para poder ver los técnicos disponibles,Deseas continuar y ver todos tus productos sin asignar?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ver todos los técnicos',
          cancelButtonText: 'Seleccionaré un rango de fechas'
        }).then((result) => {
          if (result.isConfirmed) {
            FillPersonal(EMPRESA_ID);
          }
        })
      }
      if ($('#fechaInicio').val() !== "" || $('#fechaTermino').val() !== "") {
        FillAvailablepersonal(EMPRESA_ID, $('#fechaInicio').val(), $('#fechaTermino').val());
      }
    }
  })
</script>

</html>