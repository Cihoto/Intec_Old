async function updateProject() {
  // VALIDAR DATOS Y CREAR PROYECTO
  event.preventDefault()

  let idProject = $('#idProjectModalResume').text();
  let idCliente = $('#clienteSelect').val();

  //DATOS PROYECTO
  let projectName = $('#inputProjectName').val();
  let fechaInicio = $('#fechaInicio').val();
  let fechaTermino = $('#fechaTermino').val();
  let comentarios = $('#commentProjectArea').val()

  let array = {

  }


  $.ajax({
    type: "POST",
    url: 'ws/proyecto/proyecto.php',
    data: JSON.stringify({
      action: "dropAssigmentVehicles",
      "idProject": idProject,
      "projectName": projectName,
      "fechaInicio": fechaInicio,
      "fechaTermino": fechaTermino,
      "comentarios": comentarios
    }),
    dataType: 'json',
    success: function (data) {
      console.log("RESPONSE Updated Project VEHICLES", data);
    },
    error: function (response) {
      console.log(response.responseText);
    }
  })

  // UPDATE DATOS DE PROYECTO


  //CREAR CLIENTE PARA PROYECTO
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

  let requestCliente = {
    empresaId: EMPRESA_ID,
    idProject: idProject,
    idCliente: idCliente,
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

  if ($('#inputNombreCliente').val() !== "") {

    const SelectValue = $("#clienteSelect").val();

    const resultCliente = await Promise.all([addCliente(requestCliente)])
    idCliente = resultCliente[0].idCliente

    // DATOS PARA LA CRECION BASE DE UN PROYECTO
    let direccion = $('#direccionInput').val();
    let nombreCliente = $('#inputNombreCliente').val();
  }





  let arrayVehiclesID = []
  $('#sortable2 > li').each(function () {
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

  if (arrayVehiclesID.length === 0) {
    $.ajax({
      type: "POST",
      url: 'ws/vehiculo/Vehiculo.php',
      data: JSON.stringify({
        idProject: idProject,
        action: "dropAssigmentVehicles"
      }),
      dataType: 'json',
      success: function (data) {
        console.log("RESPONSE DROPPED VEHICLES", data);
      },
      error: function (response) {
        console.log(response.responseText);
      }
    })
  } else {
    const responseAssignVehiculo = await Promise.all([assignvehicleToProject(requestVehicle)])
    console.log(responseAssignVehiculo);
  }

  let arrayPersonal = []
  $('#sortablePersonal2 > li').each(function () {
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

  if (requestPersonal.length === 0) {
    $.ajax({
      type: "POST",
      url: 'ws/personal/Personal.php',
      data: JSON.stringify({
        idProject: idProject,
        action: "dropAssigmentPersonal"
      }),
      dataType: 'json',
      success: function (data) {
        console.log("RESPONSE DROPPED PERSONAL", data);
      },
      error: function (response) {
        console.log(response.responseText);
      }
    })
  } else {
    const responseAssignPersonal = await Promise.all([assignPersonal(requestPersonal)])
    console.log(responseAssignPersonal);
  }


  let arrayProducts = []
  $('.detailsProduct-box').each(function () {
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

  if (arrayProducts.length === 0) {
    $.ajax({
      type: "POST",
      url: 'ws/productos/Producto.php',
      data: JSON.stringify({
        idProject: idProject,
        action: "dropAssigmentProduct"
      }),
      dataType: 'json',
      success: function (data) {
        //   console.log("RESPONSE DROPPED PRODUCTOS", data);
      },
      error: function (response) {
        console.log(response.responseText);
      }
    })
  } else {
    const responseAssignProductos = await Promise.all([assignProduct(arrayProducts)])
    response = responseAssignProductos
  }
  $('#proyectosModal').modal('hide');





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
          action: 'setviatico', request: arrayViaticosRequest
        }),
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
  if (arrayArriendos.length > 0) {
    $('#projectSubArriendos > tbody tr .tbodyHeader').each((key, el) => {
      SetArriendosProject(idProject, $(el).closest('tr').find('.inputSubValue').val(), $(el).closest('tr').find('.inputSubDetalle').val());
    })

    let arriendosRequest = GetArriendosProject();
    console.log("REQUEST DE ARRIENDOS", arriendosRequest);
    if (arriendosRequest !== false) {
      $.ajax({
        type: "POST",
        url: 'ws/personal/Personal.php',
        data: JSON.stringify({
          action: 'setArriendos', request: arriendosRequest
        }),
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

  if (totalIngresos === "" || totalIngresos === undefined || totalIngresos === null || totalIngresos === "$NaN") {
    totalIngresos = 0
  }
  console.log("---------------------------------");
  console.log(`totalProject ${totalIngresos}`);
  console.log("---------------------------------");
  let request = [{
    idProject: idProject,
    valor: totalIngresos
  }];
  $.ajax({
    type: "POST",
    url: 'ws/personal/Personal.php',
    data: JSON.stringify({
      action: 'SetTotalProject', request: request
    }),
    dataType: 'json',
    success: function (data) {

      console.log("LOG", data);

      Swal.fire({
        position: 'bottom-end',
        icon: 'success',
        title: 'El proyecto ha sido creado exitosamente',
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        // window.location = "proyectos.php"
      })

    },
    error: function (response) {
      console.log(response.responseText);
    }
  })


}



function UpdateProjectData(request) {

  console.log(request);
  $.ajax({
    type: "POST",
    url: "ws/proyecto/proyecto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "UpdateProjectData",
      "request": request
    }),
    success: function (response) {
      console.log(response)
    }
  })
}


