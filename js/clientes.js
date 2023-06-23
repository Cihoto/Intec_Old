function FillClientes(empresaId){

    $.ajax({
        type: "POST",
        url: "ws/cliente/cliente.php",
        dataType: 'json',
        data: JSON.stringify({
          "tipo": "getClientesByEmpresa",
          request: empresaId
        }),
        success: function(response) {
  
          console.log("response", response);
        //   $('#clienteSelect').append(new Option("", ""));
          response.forEach(response => {
            let opt  = $('#clienteSelect').append(new Option(response.nombre_cliente, response.id))
            opt.addClass()
          })
        }
      })

}

$('#clienteSelect').on('change',function(){


let idCliente = this.value;

console.log(idCliente);


$.ajax({
    type: "POST",
    url: "ws/cliente/cliente.php",
    dataType: 'json',
    data: JSON.stringify({
      "tipo": "getClienteById",
      request: idCliente
    }),
    success: function(response) {

      console.log(response.cliente);

      response.cliente.forEach(cli => {
        $('#idClienteModalResume').text(cli.id);
        document.getElementById('inputNombreClienteForm').value = cli.nombre;
        document.getElementById('inputApellidos').value = cli.apellido;
        document.getElementById('inputRutCliente').value = cli.rut;
        document.getElementById('inputCorreo').value = cli.email;
        document.getElementById('inputTelefono').value = cli.telefono;
        document.getElementById('inputRut').value = cli.df_rut;
        document.getElementById('inputRazonSocial').value = cli.razon_social;
        document.getElementById('inputNombreFantasia').value = cli.nombre_fantasia;
        document.getElementById('inputDireccionDatosFacturacion').value = cli.direccion;
        document.getElementById('inputCorreoDatosFacturacion').value = cli.correo;
      })
    }
  })
})


function CleanCliente(){
    document.getElementById("clienteForm").reset();
}


function UpdateCliente(request){
  $.ajax({
    type: "POST",
    url: 'ws/cliente/cliente.php',
    data: JSON.stringify({
        "request": {
            request
        },
        "tipo": "UpdateCliente"
    }),
    dataType: 'json',
    success: function(response) {
      $('#clientDataBtn').text("Guardar");
      // console.log(response);
    },error:function(response){
      // console.log(response)
    }})
}


function ResetClienteForm(){
  $("#clienteSelect").val("");
  $("#inputNombreClienteForm").val("");
  $("#inputApellidos").val("");
  $("#inputRutCliente").val("");
  $("#inputCorreo").val("");
  $("#inputTelefono").val("");
  $("#inputRut").val("");
  $("#inputRazonSocial").val("");
  $("#inputNombreFantasia").val("");
  $("#inputDireccionDatosFacturacion").val("");
  $("#inputCorreoDatosFacturacion").val("");
}