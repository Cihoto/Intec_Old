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


function ViewResume(element) {

  // $('#resumen').show();
  ResetClienteForm();
  localStorage.clear();

  // LIMPIAR TODOS LOS li PERSONAL ASSIGNED
  DropDragPersonal();

  // CLOSE ALL NAV-LINKS IN PORJECT ASSIGMENTS
  CloseAllTabsOnProjectsAssigments()

  // CLEAR PRODUCTS ASSIGMENTS
  DropAllSelectedProducts();

  // console.log("LOCAL STORAGE POST CLEAN FUNCTION",GetPersonalStorage());

  $('#idProjectModalResume').text("");
  $('#idClienteModalResume').text("");
  $('#idLugarModalResume').text("");

  let projectId = element.closest('tr').find('.idProject').text();

  $('#idProjectModalResume').text(projectId);

  $('#proyectosModal').modal('show');

  let projectRequest = {
    idProject: projectId,
    asignados: true
  }

  $.ajax({
    type: "POST",
    url: 'ws/proyecto/Proyecto.php',
    data: JSON.stringify({
      request: {
        projectRequest
      },
      action: "getProjectResume"
    }),
    dataType: 'json',
    success: function (response) {
      // console.table(response.asignados.vehiculos);
      // console.table(response.asignados.personal);
      // console.table(response.asignados.cliente);
      // console.table(response.asignados.productos);
      // console.table(response.asignados.viaticos);
      // console.table(response.asignados.arriendos);
      // console.table(response.asignados.totalIngresos);
      console.table(response.dataProject);

      response.dataProject.forEach(data => {

        let nombre_cliente;

        // console.table("response.asignados.cliente",response.asignados.cliente);
        if (response.asignados.cliente.length > 0) {

          response.asignados.cliente.forEach(cliente => {
            $('#inputTelefono').val(cliente.telefono);
            $('#inputNombreCliente').val(`${cliente.nombre} ${cliente.apellido} | ${cliente.razon_social} | ${cliente.rut_df}`);
            nombre_cliente = `${cliente.nombre} ${cliente.apellido} | ${cliente.razon_social} | ${cliente.rut_df}`;
          });
        }
        if (data.nombre_proyecto === "" || data.nombre_proyecto === undefined || data.nombre_proyecto === null) {
          data.nombre_proyecto = "";
        }
        if (data.fecha_inicio === "" || data.fecha_inicio === undefined || data.fecha_inicio === null) {
          data.fecha_inicio = "";
        }
        if (data.fecha_termino === "" || data.fecha_termino === undefined || data.fecha_termino === null) {
          data.fecha_termino = "";
        }
        // console.log("NOMBRE CLIENTE",nombre_cliente);
        if (nombre_cliente === "" || nombre_cliente === undefined || nombre_cliente === null) {
          nombre_cliente = "";
        }
        if (data.comentarios === "" || data.comentarios === undefined || data.comentarios === null) {
          comentarios = "";
        }

        console.log("NOMBRE PROYECTO", data.nombre_proyecto);
        $('#inputProjectName').val(data.nombre_proyecto);
        $('#fechaInicio').val(data.fecha_inicio)
        $('#fechaTermino').val(data.fecha_termino)
        $('#direccionInput').val('')

        if (data.lugarId === null) {

          $('#direccionInput').val("")

        } else {

          $('#direccionInput').val(data.direccion + ' ' + data.numero + ' ' + data.dpto + ', ' + data.comuna + ', ' + data.region)
        }

        $('#inputNombreCliente').val(data.nombre_cliente)
        $('#commentProjectArea').val(data.comentarios);
        $('#estadoProyecto').text(data.estado);
        if (data.estado === "1") {
          $('#changeStatusButton').text("Confirmar Evento")
        }
        if (data.estado === "2") {
          $('#changeStatusButton').text("Finalizar Evento")
        }
        if (data.estado === "3") {
          $('#changeStatusButton').css('display', 'none');
        }
        SetProjectData(data.nombre_proyecto, data.fecha_inicio, data.fecha_termino, nombre_cliente, data.comentarios);
      });

      if (response.asignados.vehiculos.length > 0) {
        // console.log('ARRAY DE VEHICLOS',response.asignados.vehiculos)
        response.asignados.vehiculos.forEach(asignado => {
          $('#sortable2').append(`<li style="display:flex; justify-content:space-between;" class="${asignado.id}">
                                              ${asignado.patente}
                                              <div class="personalPricing" style="display:flex;align-content: center;">
                                                  <input type="number" name="price" value="" class="vehiclePrice" placeholder="Costo"/>
                                                  <i onclick="AddVehiculo(this)"class="fa-solid fa-plus addPersonal"></i>
                                              </div>
                                          </li>`)
          VehicleStorage(asignado.id, asignado.patente, asignado.costo);
          // console.log("SI SE ASIGNA EL LOCALSTORAGE DE VEHICULOS");
        });

      } else {
        // VehicleStorage("","","");
        document.getElementById('car').style.display = "block"
      }

      if (response.asignados.productos.length > 0) {
        response.asignados.productos.forEach(asignado => {
          AddDivProduct(asignado.nombre, asignado.precio_arriendo, asignado.id, asignado.cantidad);
          let totalPrice = parseInt(asignado.precio_arriendo) * parseInt(asignado.cantidad)
          ProductsStorage(asignado.id, asignado.nombre, asignado.precio_arriendo, asignado.cantidad, totalPrice);
        });

      } else {
        // ProductsStorage({id:"", nombre:"", precio_arriendo:"", cantidad:"",totalPrice:""});
      }

      if (response.asignados.cliente.length > 0) {
        response.asignados.cliente.forEach(cliente => {
          $('#clienteSelect').val(cliente.id);
          $('#idClienteModalResume').text(cliente.id);
          $('#inputNombreClienteForm').val(cliente.nombre);
          $('#inputApellidos').val(cliente.apellido);
          $('#inputRutCliente').val(cliente.rut);
          $('#inputCorreo').val(cliente.correo);
          $('#inputTelefono').val(cliente.telefono);
          $('#inputRut').val(cliente.rut_df);
          $('#inputRazonSocial').val(cliente.razon_social);
          $('#inputNombreFantasia').val(cliente.nombre_fantasia);
          $('#inputDireccionDatosFacturacion').val(cliente.direccion);
          $('#inputCorreoDatosFacturacion').val(cliente.correo);
          $('#inputNombreCliente').val(`${cliente.nombre} ${cliente.apellido} | ${cliente.razon_social} | ${cliente.rut_df}`);
        });
      } else {
        document.getElementById('person').style.display = "none";
      }

      if (response.asignados.personal.length > 0) {
        response.asignados.personal.forEach(asignado => {
          $('#sortablePersonal2').append(`<li style="display:flex; justify-content:space-between;" class="${asignado.id}">
                                            ${asignado.nombre} | ${asignado.cargo} ${asignado.especialidad} 
                                            <div class="personalPricing" style="display:flex;align-content: center;">
                                                <input type="number" name="price" value="${asignado.costo}" class="personalPrice" placeholder="Costo"/>
                                                <i onclick="AddPersonal(this)" style="display:none" class="fa-solid fa-plus addPersonal"></i>
                                                <i onclick="removePersonal(this)" class="fa-solid fa-minus" style="color: #b92413;"></i>
                                            </div>
                                        </li>`);

          PersonalLocalStorage(asignado.id, `${asignado.nombre} | ${asignado.cargo} ${asignado.especialidad}`, asignado.costo, asignado.contrato);
        });

      } else {
      }


      if (response.asignados.arriendos.length > 0) {
        response.asignados.arriendos.forEach(asignado => {

          SetArriendosProject(asignado.id_proyecto, parseInt(asignado.valor), asignado.detalle_arriendo)
        });

      } else {

      }
      if (response.asignados.viaticos.length > 0) {
        response.asignados.viaticos.forEach(asignado => {
          SetViatico(asignado.proyecto_id, asignado.valor, asignado.detalle);
        });
      } else {
      }
      if (response.asignados.totalIngresos.length > 0) {
        response.asignados.totalIngresos.forEach(asignado => {
          console.log("TOTAL INGRESOS", asignado);

          SetTotalProject(asignado.id_proyecto, asignado.total, "");

        });
      } else {
        SetTotalProject("", 0, "");

      }
      // GetResumeProjectList();
    },
    error: function (err) { }
  })
}