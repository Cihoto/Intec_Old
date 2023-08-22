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
            <?php if($empresaId === "1"):?>
              <div class="col-3 mt-2 mb-2">
                <button class="btn btn-success" id="verarray">Ver array</button>
              </div>
            <?php endif;?>
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
  <?php require_once('./includes/forms/arriendosForm.php')?>

  <!-- REQUIRE DE FUNCIONES JS -->
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
</body>

<script>
  //BOTON DE TEST
  $('#verarray').on('click', function() {
    // localStorage.clear();
    // console.log("asdjhasd,jahkdsjhasd");
    $('#arriendosModal').modal('show');



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
  var ROL_ID = <?php echo json_encode($rol_id);?>

  // console.log(EMPRESA_ID);
  $(document).ready(function() {


    // $("#sortable1, #sortable2").sortable({
    //   connectWith: ".connectedSortable"
    // }).disableSelection();

    // $("#sortable1, #sortable2").sortable({
    //   connectWith: ".connectedSortable"
    // }).disableSelection();

    // $("#sortablePersonal1, #sortablePersonal2").sortable({
    //   connectWith: ".connectedSortablePersonal"
    // }).disableSelection();

    $('#tableResume').DataTable({})

    //fillvehiculos
    FillVehiculos(EMPRESA_ID);
    // Fill Clientes
    FillClientes(EMPRESA_ID);
    //FILL DIRECCIONES
    FillDirecciones();
    //FILL PRODUCTOS
    FillProductos(EMPRESA_ID);
    //FILL PERSONAL
    FillPersonal(EMPRESA_ID);
    // CLEAR LOCALSTORGE
    localStorage.clear();
    // FILL REGIONES
    FillRegiones(EMPRESA_ID)


    $(document).on('click', '.logoRemove', function() {
      let productId = $(this).closest('.detailsProduct-box').find('.itemId').text();
      removeProduct(productId);
      $(this).closest('.detailsProduct-box').remove()
      $('#resumeBody').find(`.idProd${productId}`).remove();

    })

    // SHOW BILLING DATA 
    $('#clientHasFacturacion').on('click',function(){
      if($(this).is(':checked')){

        $('#clientFactData').addClass('active');
      }else{

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


        if (localStorage.getItem("direccion") === null){
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
      rules:{
        nombreArriendo:{
          required:true
        },
        valorArriendo:{
          required:true
        },
        txtNombre:{
          required:true
        },
        txtApellidos:{
          required:true
        },
        txtRut:{
          required:true
        },
        txtCorreo:{
          required:true
        },
        txtTelefono:{
          required:true
        },
        txtRut:{
          required:true
        },
        txtRazonSocial:{
          required:true
        },
        txtNombreFantasia:{
          required:true
        },
        txtDireccionDatosFacturacion:{
          required:true
        },
        txtCorreoDatosFacturacion:{
          required:true
        }
      },messages:{
        nombreArriendo:{
          required: "Ingrese un valor"
        },
        valorArriendo:{
          required: "Ingrese un valor"
        },
        txtNombre:{
          required: "Ingrese un valor"
        },
        txtApellidos:{
          required: "Ingrese un valor"
        },
        txtRut:{
          required: "Ingrese un valor"
        },
        txtCorreo:{
          required: "Ingrese un valor"
        },
        txtTelefono:{
          required: "Ingrese un valor"
        },
        txtRut:{
          required: "Ingrese un valor"
        },
        txtRazonSocial:{
          required: "Ingrese un valor"
        },
        txtNombreFantasia:{
          required: "Ingrese un valor"
        },
        txtDireccionDatosFacturacion:{
          required: "Ingrese un valor"
        },
        txtCorreoDatosFacturacion:{
          required: "Ingrese un valor"
        }
      },submitHandler:function(){
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
            empresa_id : EMPRESA_ID
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
          requestCliente.push({"idCliente":idClienteReq})
          
        }

        console.log("----------------------------");
        console.log("----------------------------");
        console.log(requestCliente);
        console.log("----------------------------");
        console.log("----------------------------");

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
        $('.detailsProduct-box').each(function() {
          let idProduct = $(this).find('.itemId').text();
          let productPrice = $(this).find('.getPrice').text();
          let productQuantity = $(this).find('.addProdInput').val();
          arrayProducts.push({
            idProject: idProject,
            idProduct: idProduct,
            price: productPrice,
            quantity: productQuantity
          })
        })

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
        }else{
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
            FillProductosAvailable(EMPRESA_ID, "all", "", "");
          }
        })
      }
      if ($('#fechaInicio').val() !== "" && $('#fechaTermino').val() !== "") {
        FillProductosAvailable(EMPRESA_ID, "available", $('#fechaInicio').val(), $('#fechaTermino').val());
      }
    }

  })

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