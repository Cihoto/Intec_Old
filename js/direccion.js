function FillRegiones(empresaId){

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
            $('#clienteSelect').append(new Option(response.nombre_cliente, response.id))
          })
        }
      })

}
function FillDirecciones(){

  $.ajax({
    type: "POST",
    url: "ws/direccion/Direccion.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "getDireccionesByEmpresa",
      request: ""
    }),
    success: function(response) {

      console.log("DIRECCIONES",response.direcciones);

      $('#dirSelect').empty();
      $('#dirSelect').append(new Option("", ""));
      response.direcciones.forEach(dir => {
        $('#dirSelect').append(new Option(`${dir.direccion} ${dir.numero} ${dir.dpto}`, dir.id))
      })
    }
  })
}

$('#dirSelect').on('change',function(){

    let idDireccion = this.value;
    console.log(idDireccion);

    $.ajax({
      type: "POST",
      url: "ws/direccion/Direccion.php",
      dataType: 'json',
      data: JSON.stringify({
        "action": "getDireccion",
        request: idDireccion
      }),
      success: function(response) {
  
        console.log(response.direcciones);


        response.direcciones.forEach(dir=>{
          document.getElementById('txtDir').value = dir.direccion;
          document.getElementById('txtNumDir').value = dir.numero;
          document.getElementById('txtDepto').value = dir.dpto;
          document.getElementById('txtcodigo_postal').value = dir.postal_code;
          // document.getElementById('regionSelect').value = dir.comuna.id;
          $('#idDireccionModal').text(idDireccion);
          // document.getElementById('comunaSelect').value = dir.;
        })

       

      }
    })
  })


  function CleanCliente(){
    document.getElementById("clienteForm").reset();
  }


function GetComunas(){
  let idRegion = $('#regionSelect').val();
  $.ajax({
    type: "POST",
    url: "ws/pais_region_comuna/Comuna.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "getComunasByRegion",
      idRegion: idRegion
    }),
    success: function(response){

      $('#comunaSelect').empty();
      $('#comunaSelect').append(new Option("", ""));
      response.forEach(response => {
        $('#comunaSelect').append(new Option(response.comuna, response.id))
      })
    }
  })
  
}