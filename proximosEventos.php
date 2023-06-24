<?php

require_once('./ws/pais_region_comuna/Comuna.php');
require_once('./ws/vehiculo/Vehiculo.php');
require_once('./ws/personal/Personal.php');
require_once('./ws/productos/Producto.php');
require_once('./ws/pais_region_comuna/Region.php');

$empresaId = 1;
$isDetails = false;

// $obj = (object) array('idRegion' => 1);
// // $comunas = getComunasByRegion($obj);
// $vehiculos = getVehiculos($empresaId);
$vehiculos = [];
$personal =  getPersonal($empresaId);
// $personal = [];
// $productos = getProductos($empresaId);
$productos = [];
$regiones = getRegiones();



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
        <h3>Proximos Eventos</h3>
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


        <?php include_once('./includes/projectAssigments.php')?>

        <div class="card box">
          <div class="row" style="justify-content: end;">
            <div class="col-3 mt-2 mb-2">
              <button class="btn btn-success" id="submitProject">Crear Proyecto</button>
            </div>
            <!-- <div class="col-3 mt-2 mb-2">
              <button class="btn btn-success" id="verarray">Ver array</button>
            </div> -->
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
</body>

<script>
  //BOTON DE TEST
  $('#verarray').on('click', function() {
      localStorage.clear();
      console.log("asdjhasd,jahkdsjhasd");

    })
  //FIN BOTON TEST

  $('#inputProjectName').on('change',function(){
      $('.projectNameResume').text($(this).val());
  })

  $('#fechaInicio').on('change',function(){
    $('.fechaProjectResume').text($(this).val())
  })

  $('#fechaTermino').on('change',function(){
    $('.fechaProjectResume').text($('.fechaProjectResume').text() + '  /  ' + $(this).val())
  })
  $('#commentProjectArea').on('change',function(){
    $('.comentariosProjectResume').text($(this).val())
  })


  $(document).ready(function() {
    const EMPRESA_ID = document.getElementById('empresaId').textContent


    $("#sortable1, #sortable2").sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();

    $("#sortable1, #sortable2").sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();

    $("#sortablePersonal1, #sortablePersonal2").sortable({
      connectWith: ".connectedSortablePersonal"
    }).disableSelection();

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


    $(document).on('click', '.logoRemove', function() {
      let productId = $(this).closest('.detailsProduct-box').find('.itemId').text();
      removeProduct(productId);
      $(this).closest('.detailsProduct-box').remove()
      $('#resumeBody').find(`.idProd${productId}`).remove();
      console.log($('#resumeBody > tr').find(`.idProd${productId}`));

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
        console.log("ENVIO DE INFORMACION DE PRODUCTO NUEVO UNITARIO");

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
        

        if(localStorage.getItem("direccion") === null){

          localStorage.setItem("direccion", JSON.stringify([{dir,
                                                            numDir,
                                                            depto,
                                                            region,
                                                            comuna,
                                                            regionInput,
                                                            comunaInput,
                                                            postal_code,
                                                            idDireccion}]))
        }else{

          let allDirs = JSON.parse(localStorage.getItem("direccion"))
          console.log("PRE PUSH",allDirs);
          allDirs.push({dir,numDir,depto,region,comuna,regionInput,comunaInput,postal_code,idDireccion});
          console.log("POST PUSH",allDirs);
          localStorage.setItem("direccion",JSON.stringify(allDirs));

        }


        console.log(JSON.parse(localStorage.getItem('direccion')))
        

        // localStorage.setItem("direccion",JSON.stringify())
        
      }
    })


    // VALIDAR FORM CLIENTE Y DATOS DE FACTURACION
    $('#clienteForm').validate({
      rules: {
        txtNombreCliente:{
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
      },
      messages: {
        txtNombreCliente:{
          required : "Ingrese un valor"
        },
        txtApellidos:{
          required : "Ingrese un valor"
        },
        txtRut:{
          required : "Ingrese un valor"
        },
        txtCorreo:{
          required : "Ingrese un valor"
        },
        txtTelefono:{
          required : "Ingrese un valor"
        },
        txtRut:{
          required : "Ingrese un valor"
        },
        txtRazonSocial:{
          required : "Ingrese un valor"
        },
        txtNombreFantasia:{
          required : "Ingrese un valor"
        },
        txtDireccionDatosFacturacion:{
          required : "Ingrese un valor"
        },
        txtCorreoDatosFacturacion:{
          required : "Ingrese un valor"
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
        $('#inputNombreCliente').val(`${nombreFantasia} | ${rut}`);
        $(".clienteProjectResume").text(`${nombreFantasia} | ${rut}`);
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
        console.log("idCliente", idClienteReq);

        if(idClienteReq === "" || idClienteReq === null || idClienteReq === undefined){
          idClienteReq = "" 
        }


        let requestCliente ={
          empresaId : EMPRESA_ID,
          nombreCliente :nombreCliente,
          apellidos :apellidos,
          rutCliente :rutCliente,
          correoCliente :correoCliente,
          telefono :telefono,
          rut :rut,
          razonSocial :razonSocial,
          nombreFantasia :nombreFantasia,
          direccionDatosFacturacion :direccionDatosFacturacion,
          correoDatosFacturacion :correoDatosFacturacion,
          idCliente : idClienteReq === "" ?  "" : parseInt(idClienteReq)
        }

        console.table(requestCliente);

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
          const resultCliente = await Promise.all([addCliente(requestCliente)])
          idCliente = resultCliente[0].idCliente

          // DATOS PARA LA CRECION BASE DE UN PROYECTO

          let direccion = $('#direccionInput').val();
          let nombreCliente = $('#inputNombreCliente').val();
        }
        
        //REQUEST LUGAR
        if ($('#inputNombreCliente').val() === "") {
          idCliente = "";
        }

        if ($('#direccionInput').val() === "") {
          id_direccion = "";
          id_lugar = "";
        }

        let requestProject = {
          nombre_proyecto: projectName,
          lugar_id: id_lugar,
          fecha_inicio: fechaInicio,
          fecha_termino: fechaTermino,
          cliente_id: idCliente,
          comentarios: comentarios
          // empresa_id:1
        }
        console.log(requestProject);

        const responseProject = await Promise.all([createProject(requestProject)])
        idProject = responseProject[0].id_project;


        let arrayVehiclesID = []
        $('#sortable2 > li').each(function() {

          let vClass = $(this).attr('class')
          console.log(vClass)
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
            cost :valor
            
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

        console.log("requestPersonal",requestPersonal);

        const responseAssignPersonal = await Promise.all([assignvehicleToProject(requestVehicle), assignPersonal(requestPersonal), assignProduct(arrayProducts)])
        response = responseAssignPersonal
        
        let arrayViaticos = $('#projectViatico > tbody tr .tbodyHeader');
        if(arrayViaticos.length > 0){
          $('#projectViatico > tbody tr .tbodyHeader').each((key,el)=>{
              SetViatico(idProject,$(el).closest('tr').find('.totalViaticoInput').val() ,$(el).closest('tr').find('.inputViaticoName').val());
          })
          
          let arrayViaticosRequest = GetProjectViaticos();
          console.table("arrayViaticosRequest",arrayViaticosRequest);
          if(arrayViaticosRequest !== false){
               $.ajax({
                type: "POST",
                url: 'ws/personal/Personal.php',
                data: JSON.stringify({
                  action: 'setviatico', request: arrayViaticosRequest}),
                dataType: 'json',
                success: function (data) {

                  console.log("RESPONSE AGIGNACION VIATICOS", data);

                },
                error: function (response) {
                  console.log(response.responseText);
                }
              })
          }
        }
        

        let arrayArriendos = $('#projectSubArriendos > tbody tr .tbodyHeader');
        if(arrayArriendos.length > 0){
          $('#projectSubArriendos > tbody tr .tbodyHeader').each((key,el)=>{
            SetArriendosProject(idProject,$(el).closest('tr').find('.inputSubValue').val(),$(el).closest('tr').find('.inputSubDetalle').val() );
          })

          let arriendosRequest = GetArriendosProject();
          console.log("REQUEST DE ARRIENDOS",arriendosRequest);
          if(arriendosRequest !== false){
               $.ajax({
                type: "POST",
                url: 'ws/personal/Personal.php',
                data: JSON.stringify({
                  action: 'setArriendos', request: arriendosRequest}),
                dataType: 'json',
                success: function (data) {

                  console.log("ARRIENDOS", data);

                },
                error: function (response) {
                  console.log(response.responseText);
                }
              })
          }
        }



        let totalIngresos = parseInt(ClpUnformatter($('#totalIngresos').text()));

        if(totalIngresos === "" || totalIngresos === undefined || totalIngresos === null || totalIngresos === "$NaN"){
          totalIngresos = 0
        }
        console.log("---------------------------------");
        console.log(`totalProject ${totalIngresos}`);
        console.log("---------------------------------");
        let request = [{
          idProject:idProject,
          valor:totalIngresos
        }];
        $.ajax({
          type: "POST",
          url: 'ws/personal/Personal.php',
          data: JSON.stringify({
            action: 'SetTotalProject', request: request}),
          dataType: 'json',
          success: function (data) {

            console.log("LOG", data);

            Swal.fire({
              position: 'bottom-end',
              icon: 'success',
              title: 'El proyecto ha sido creado exitosamente',
              showConfirmButton: false,
              timer: 1500
            }).then(()=>{
              // window.location = "proyectos.php"
            })

          },
          error: function (response) {
            console.log(response.responseText);
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

  $('#addCliente').on('click',function(){
        
  })
  
  //GATILLAR EVENTO CLICK EN BOTON SUBMIT DE FORM PARA CREACION DEL PROYECTO
  $('#submitProject').on('click', function() {
    $('#hiddenAddProject').trigger('click')
  })
</script>

</html>