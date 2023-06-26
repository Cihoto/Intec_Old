<?php

$isDetails = true;
//Variables que manipulan condiciones if en Form proyecto
$detalle = true;
?>

<!DOCTYPE html>
<html lang="en">
<?php

require_once('./includes/head.php');
$active = 'proximosEventos';

?>

<body>
    <script src="./assets/js/initTheme.js"></script>
    <?php  include_once('./includes/Constantes/empresaId.php')?>
    <div id="app">

        <?php require_once('./includes/sidebar.php') ?>
        <?php require_once('./includes/Constantes/empresaId.php') ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-header">
                <h3>Proyectos </h3>
            </div>

            <div class="page-content">
                <!-- aca va la info de la pagina -->


                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="created-tab" data-bs-toggle="tab" href="#created" role="tab" aria-controls="created" aria-selected="true">
                                Creados
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="confirmed-tab" data-bs-toggle="tab" href="#confirmed" role="tab" aria-controls="confirmed" aria-selected="false">
                                Confirmados
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="finished-tab" data-bs-toggle="tab" href="#finished" role="tab" aria-controls="finished" aria-selected="false">
                                Finalizados
                            </a>
                        </li>
                    </ul>
                    

                    <div class="tab-content" id="myTabContent">

                        <div style="display:none" class="lds-facebook"><div></div><div></div><div></div></div>
                        <div class="tab-pane fade" id="created" role="tabpanel" aria-labelledby="created-tab">
                            <table class="resume-table" id="createdProjects" >
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Id</th>
                                        <th style="text-align: center;">Nombre Proyecto</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Dirección</th>
                                        <th style="text-align: center;">Fecha Inicio</th>
                                        <th style="text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                            <table class="resume-table" id="confirmedProjects" >
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Id</th>
                                        <th style="text-align: center;">Nombre Proyecto</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Dirección</th>
                                        <th style="text-align: center;">Fecha Inicio</th>
                                        <th style="text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="finished" role="tabpanel" aria-labelledby="finished-tab">
                            <div class="tab-pane fade" id="finished" role="tabpanel" aria-labelledby="finished-tab">
                                <table class="resume-table" id="finishedProjects" >
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Id</th>
                                            <th style="text-align: center;">Nombre Proyecto</th>
                                            <th style="text-align: center;">Nombre Cliente</th>
                                            <th style="text-align: center;">Dirección</th>
                                            <th style="text-align: center;">Fecha Inicio</th>
                                            <th style="text-align: center;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php
            require_once('./includes/footer.php');
            require_once('./includes/Modal/detallesProyecto.php');
            require_once('./includes/Modal/cliente.php');
            require_once('./includes/Modal/direccion.php');
            ?>
        </div>
    </div>

    <?php require_once('./includes/footerScriptsJs.php') ?>

<script src="/js/Funciones/UpdateProject.js"></script>
<script src="/js/clientes.js"></script>
<script src="/js/direccion.js"></script>
<script src="/js/personal.js"></script>
<script src="/js/vehiculos.js"></script>
<script src="/js/productos.js"></script>
<script src="/js/project.js"></script>
<script src="/js/Funciones/NewProject.js"></script>
<script src="/js/localeStorage.js"></script>
<script src="/js/valuesValidator/validator.js"></script>
<script src="/js/ClearData/clearFunctions.js"></script>
<script src="/js/ProjectResume/viatico.js"></script>
<script src="/js/ProjectResume/subArriendo.js"></script>
<script src="/js/ProjectResume/projectResume.js"></script>


<script>

const EMPRESA_ID = document.getElementById('empresaId').textContent;
let listed;
let actualizarCliente ="";

//OPEN MODAL DIRECCION
$('#direccionInput').on('click', function() {
    $('#direccionModal').modal('show');
})

//OPEN MODAL CLIENTE
$('#inputNombreCliente').on('click', function() {
    $('#clienteModal').modal('show');
})

//CLOSE ALL TABS IN PROJECT ASSIGNMENTS
function CloseAllTabsOnProjectsAssigments(){

    $('#myTab .projectAssigmentTab').each((key,element)=>{
        if($(element).hasClass('active')){
            element.classList.remove("active")
        }
    })
    $('#myTabContent .tabAssigments').each((key,element)=>{
        if($(element).hasClass('active show')){
            $(element).removeClass('active show');
            $(element).addClass('fade');
        }
    })
}

$(document).ready(function() {
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

        $('#lugarProjectResume').text(`${dir} ${numDir} ${depto}, ${comunaInput}, ${regionInput}`);

        SetProjectData($('#inputProjectName').val(), $('#fechaInicio').val(), $('#fechaTermino').val(), $('#inputNombreCliente').val(), $('#commentProjectArea').val() );

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
          allDirs.push({dir,numDir,depto,region,comuna,regionInput,comunaInput,postal_code,idDireccion});
          localStorage.setItem("direccion",JSON.stringify(allDirs));
        }

        console.log(JSON.parse(localStorage.getItem('direccion')))
        
      }
    })

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
        submitHandler: async function () {
            event.preventDefault()
            updateProject();
        }
    })

    // Fill Clientes
    FillClientes(EMPRESA_ID);
    //FILL DIRECCIONES
    FillDirecciones();

    //FILL PRODUCTOS
    // FillProductos(EMPRESA_ID);

    // FILL PERSONAL
    FillPersonal(EMPRESA_ID);

    //FILLPROJECTS
    // FillCreated(EMPRESA_ID);

})

$('#created-tab').on('click',function(){
    FillCreated(EMPRESA_ID,1); 
})
$('#confirmed-tab').on('click',function(){
    FillCreated(EMPRESA_ID,2); 
})
$('#finished-tab').on('click',function(){
    FillCreated(EMPRESA_ID,3); 
})



$('#getAvailableVehicles').on('click',function(){

    $('#DragVehiculos').show();
    $('#fechaInicio').val();
    $('#fechaTermino').val();
    let fechaInicio = $('#fechaInicio').val();
    let fechaTermino = $('#fechaTermino').val();
    DropVehiculos();
    if(fechaInicio === "" || fechaTermino === ""){
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
            }else{
            }
        })
    }
    if(fechaInicio !== "" && fechaTermino !== ""){
        GetAvailableVehicles(EMPRESA_ID,fechaInicio,fechaTermino);    
    }
})

$('#products-tab').on('click',function(){

})


function DropAllSelectedProducts(){
    $('.detailsProduct-box').each((key,element)=>{
        $(element).remove();
    })
}

function DropVehiculos(){
    $('#sortable1 li').each((key,element)=>{
        $(element).remove()
    })
}

function DropDragPersonal(){
    $('#sortablePersonal2 li').each((key,element)=>{
        $(element).remove()
    })
}

$('#tableResumeView').on('click',function(){
    GetResumeProjectList();
})

function GetResumeProjectList(){

    // $('#resumen').show();
    let idProyecto = $('#idProjectModalResume').text();
    let dataProject = GetProjectData();
    let personalProject = GetPersonalStorage();
    let productosProject = GetProductsStorage();
    let vehiculosProject = GetVehicleStorage();
    let arriendosProject = GetArriendosProject();
    let viaticoProject = GetProjectViaticos();
    let totalIngresos = GetTotalProject();
    let direccion = localStorage.getItem('direccion');

    console.log("DATOS DEL PROYECTO",dataProject);

    if(listed !== idProyecto){
        ClearTables();
    }

    if(listed !== idProyecto){
        listed = idProyecto;
        if(dataProject !== false){
            
            $('#projectNameResume').text(`${dataProject.nombre_proyecto}`);
            $('#fechaProjectResume').text(`${dataProject.fecha_inicio} ${dataProject.fecha_termino}`);
            $('#clienteProjectResume').text(`${dataProject.nombre_cliente}`);
            $('#lugarProjectResume').text("");
            $('#comentariosProjectResume').text(dataProject.comentarios);
            
        }

        if(personalProject !== false){
            AppendPersonalTableResumeArray(personalProject);
        }
    
        if(productosProject !== false){
            AppendProductosTableResumeArray(productosProject);
        }
    
        if(vehiculosProject !== false){
            AppendVehiculoTableResumeArray(vehiculosProject);
        }

        if(viaticoProject !== false){
            viaticoProject.forEach(element =>{
                AddViaticoWithValues(element.valor, element.detalle);
            });
            SetResumeViaticoResumeValue();
        }

        if(arriendosProject !== false){
            arriendosProject.forEach(element => {
                AddSubArriendoWithValues(element.detalle,element.valor);
            });
            SetResumeArriendosResumeValue();
        }

        if(totalIngresos !== false){
            totalIngresos.forEach(element => {
                console.log("TOTAL INGRESOS PARA TEXT",element.valor);
                $('#totalIngresos').text(element.valor);
            });
            CalcularUtilidad();
        }

    }
}


$('#clienteSelect').on('change',function(){

    $('#clientDataBtn').text("Guardar");
    const SelectValue = $(this).val();
    if(SelectValue === "" || SelectValue === "new"){
        ResetClienteForm();
        $('#idClienteModalResume').text("");
    }
})


$('#clienteForm input').on('change',function(){

    let actualizarCliente = $('#clienteSelect').val();
    if(actualizarCliente !== "new" || actualizarCliente !== ""){
        SetUpdateCliente(true);
    }else{
        SetUpdateCliente(false);
    }

    if(GetUpdateCliente() === true && $('#clientDataBtn').text() !== "Actualizar datos del cliente"){
        SetUpdateCliente(false);
        Swal.fire({
                title: 'Crear nuevo cliente?',
                text: "Estás cambiando los datos del cliente,deseas crear un nuevo cliente o actualizar los datos del actual? ",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Quiero crear un nuevo cliente',
                cancelButtonText: 'Quiero actualizar este cliente!'
            }).then((result) => {
            if (result.isConfirmed){
                $('#idClienteModalResume').text("");
            } else {
                $('#clientDataBtn').text("Actualizar datos del cliente");
            }
        })
    }
});

$('#clientDataBtn').on('click',function(){
    if($("#clienteForm").valid()){
        let idCliente = $('#clienteSelect').val();
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

        if($('#clientDataBtn').text()==="Actualizar datos del cliente"){
            let updateClienteRequest = {
                idCliente:idCliente,
                nombreCliente:nombreCliente,
                apellidos:apellidos,
                rutCliente:rutCliente,
                correo:correo,
                telefono:telefono,
                rut:rut,
                razonSocial:razonSocial,
                nombreFantasia:nombreFantasia,
                direccionDatosFacturacion:direccionDatosFacturacion,
                correoDatosFacturacion:correoDatosFacturacion
            };
            UpdateCliente(updateClienteRequest);

        }else{
        }
    }
})

//VALIDAR DATOS DE CLIENTE
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
        $('#clienteProjectResume').text(`${nombreFantasia} | ${rut}`);

        SetProjectData($('#inputProjectName').val(), $('#fechaInicio').val(), $('#fechaTermino').val(), $('#inputNombreCliente').val(), $('#commentProjectArea').val());

        $("#clienteModal ").modal('hide');
      }
    })

$('#updateProject').on('click', function() {
    $('#hiddenAddProject').trigger('click');
})
$(document).on('click', '.logoRemove', function() {
      let productId = $(this).closest('.detailsProduct-box').find('.itemId').text()
    
      $(this).closest('.detailsProduct-box').remove()
      $('#resumeBody').find(`.idProd${productId}`).remove();
    })

$('#verarray').on('click',function(){
    
})

$('#getAvailableProducts').on('click',function(){
    if($('#fechaInicio').val() === "" || $('#fechaTermino').val() === ""){

        Swal.fire({
            title: '',
            text: "Debes seleccionar el rango de fechas en las que se realizara este proyecto para poder ver los productos disponibles,Deseas continuar y ver todos tus productos sin asignar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ver todos los productos',
            cancelButtonText: 'Seleccionaré un rango de fechas'
            }).then((result) => {
            if (result.isConfirmed) {
                FillProductosAvailable(EMPRESA_ID,"all","","");
            }
        })
    }

    if($('#fechaInicio').val() !== "" && $('#fechaTermino').val() !== ""){
        FillProductosAvailable(EMPRESA_ID,"available",$('#fechaInicio').val(),$('#fechaTermino').val());
    }

})


$('#changeStatusButton').on('click',function(){
    let idProject = $('#idProjectModalResume').text();
    $.ajax({
    type: "POST",
    url: 'ws/proyecto/proyecto.php',
    data: JSON.stringify({
      idProject: idProject,
      action: "UpdateProjectDataStatus"
    }),
    dataType: 'json',
    success: function (data) {

    },
    error: function (response) {

    }
  })
  updateProject();

})






$('#inputProjectName').on('change',function(){
    $('#projectNameResume').text($('#inputProjectName').val)

})
$('#fechaInicio').on('change',function(){
    $('#fechaProjectResume').text( `${$('#fechaInicio').val()}  / ${$('#fechaTermino').val() }`);
})

$('#fechaTermino').on('change',function(){
    $('#fechaProjectResume').text( `${$('#fechaInicio').val()}  / ${$('#fechaTermino').val() }`);
})

$('#direccionInput').on('change',function(){
    $('#lugarProjectResume').text();
})

$('#inputNombreCliente').on('change',function(){
    $('#inputNombreCliente').text($('#inputNombreCliente').val())
})

$('#commentProjectArea').on('change',function(){

    $('#comentariosProjectResume').text($('#commentProjectArea').val());
})


$(document).on('click', '.getProjectDetails', function(){
    // console.log("THIS DE VIEW RESUME",$(this));
     ViewResume($(this))
});


// $('.getProjectDetails').on('click',function(){
//     console.log("THIS DE VIEW RESUME",this);
//     // ViewResume($(this));
// })

</script>
</body>

</html>