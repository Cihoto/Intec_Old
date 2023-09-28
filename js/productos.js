function FillProductos(empresaId) {
  if ($.fn.DataTable.isDataTable('#tableProducts')) {
    $('#tableProducts').DataTable().destroy();
    $('#tableDrop > tr').each((key, element) => {
      $(element).remove();
    })
  }
  $.ajax({
    type: "POST",
    url: "ws/productos/Producto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "getProductos",
      empresaId: empresaId
    }),
    success: function (response) {
      response.forEach(producto => {
        let td = `
              <td class="productId" style="display:none">${producto.id}</td>
              <td class="catProd"> ${producto.categoria}</td>
              <td class="itemProd"> ${producto.item}</td>
              <td style="width:25%" class="productName">${producto.nombre}</td>
              <td class="productPrice"> ${producto.precio_arriendo} </td>
              <td class="productStock" >${producto.cantidad}</td>
              <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${producto.cantidad}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
        $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`)
      });

      $('#tableProducts').DataTable({
        scrollX: true,
        fixedHeader: true
      })
    }
  })
}

function FillProductosAvailable(empresaId, tipo, fecha_inicio, fecha_termino) {

  if ($.fn.DataTable.isDataTable('#tableProducts')) {
    $('#tableProducts').DataTable().destroy();
    $('#tableDrop > tr').each((key, element) => {
      $(element).remove();
    })
  }

  let arrayProductosAssigned;
  if (GetProductsStorage()) {
    arrayProductosAssigned = GetProductsStorage();
  } else {
    arrayProductosAssigned = [];
  }

  console.log(GetProductsStorage());

  // console.log("empresaId", empresaId);
  // console.log("tipo", tipo);
  // console.log("fecha_inicio", fecha_inicio);
  // console.log("fecha_termino", fecha_termino);

  $.ajax({
    type: "POST",
    url: "ws/productos/Producto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "GetAvailableProducts",
      empresaId: empresaId
    }),
    success: function (response) {
      // console.table(response)

      if (tipo === "available") {

        response.forEach(producto => {

          let sumaExistencias = 0;
          if (arrayProductosAssigned.length > 0) {
            arrayProductosAssigned.forEach((arr) => {
              if (arr.productId === producto.id) {
                sumaExistencias = sumaExistencias + parseInt(arr.quantityToAdd);
              }
            })
          }

          let assigned;
          if (producto.assigned === "" || producto.assigned === null || producto.assigned === undefined) {
            assigned = 0;
          } else {
            assigned = producto.assigned;
          }

          let stock;

          // let trSearchable = $("#tableDrop > tr").find(`#${producto.id}`)

          let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

          if (trSearchable.prevObject.length === 1) {
            let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());
            stock = oldStock;
            // console.log(`RESTE ${assigned} A ${oldStock} DENTRO DEL EACH DE JQUERY`);
            stock = oldStock - parseInt(assigned);

            $(trSearchable.prevObject).find('.productStock').text(stock);
          } else {
            stock = parseInt(producto.stock) - parseInt(sumaExistencias) - parseInt(assigned);
          }

          // console.log(`STOCK PREVIO A FUNCIONES ${stock}`);
          // console.log(`ID DE PRODUCTO ${producto.id}`);

          if (producto.fecha_inicio !== null && producto.fecha_termino !== null) {


            if (fecha_inicio > producto.fecha_inicio && fecha_inicio > producto.fecha_termino ||
              fecha_termino < producto.fecha_inicio && fecha_termino < producto.fecha_termino) {
              console.log("DISPONIBLE");

              // console.log("Estoy dentro dE IF DE FECHAS DISPONIBLES Y PRODUCTOS LIBERADOS");
              // console.log(`ESTE STOCK ME ESTA LLEGANDO A LA FECHA LIBERADA ${stock}`);
              // console.log(`LE SUMANDO RESTANDO ${producto.assigned}`);

              let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

              if (trSearchable.prevObject.length === 1) {

                // console.log("ALLLLLLLL ENCONTRE UN ID DENTRO DE LA TABLA");
                let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());

                if (producto.estado === "2") {
                  // console.log(`DISPONIBLE LE SUMO ${assigned}`);
                  stock = oldStock + parseInt(assigned);
                }

                $(trSearchable.prevObject).find('.productStock').text(stock);
              } else {
                // console.log("DISPONIBLE NO ENCONTRE HAGO APPEND");

                if (producto.estado === "2") {
                  // gconsole.lo(`DISPONIBLE LE SUMO ${assigned}`);
                  // stock = stock - parseInt(producto.assigned)
                  stock = stock + parseInt(assigned);
                }

                let td = `<td class="productId" style="display:none">${producto.id}</td>
                          <td class=""> ${producto.categoria}</td>
                          <td class=""> ${producto.item}</td>
                          <td class="productName">${producto.nombre}</td>
                          <td class="productPrice"> ${producto.precio_arriendo} </td>
                          <td class="productStock">${stock}</td>
                          <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${stock}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
                $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);
              }

            } else {

              // console.log(`ESTE STOCK ME ESTA LLEGANDO A LA FECHA NO LIBERADA ${stock}`);
              // console.log(`LE SUMANDIO RESTANDO ${producto.assigned}`);
              // stock = stock - parseInt(producto.assigned);
              // console.log("NOOOOOOO DISPONIBLE");

              let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

              if (trSearchable.prevObject.length === 1) {
                // console.log("FECHA NO LIBERADA ENCONTRE UN ID DENTRO DE LA TABLA");
                let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());
                if (producto.estado !== "2") {
                  // console.log(`NO DISPONIBLE LE SUMO NADA ${producto.assigned}`);
                  // stock = stock - parseInt(producto.assigned)
                  stock = oldStock + parseInt(assigned);
                }
                $(trSearchable.prevObject).find('.productStock').text(stock);

              } else {

                if (producto.estado !== "2") {
                  // console.log(` NO DISPONIBLE LE SUMO NADA ${producto.assigned}`);
                  stock = stock + parseInt(assigned)
                }
                // console.log("NOOOOOOO DISPONIBLE NO ENCONTRE HAGO APPEND");

                let td = `<td class="productId" style="display:none">${producto.id}</td>
                              <td class=""> ${producto.categoria}</td>
                              <td class=""> ${producto.item}</td>
                              <td class="productName">${producto.nombre}</td>
                              <td class="productPrice"> ${producto.precio_arriendo} </td>
                              <td class="productStock">${stock}</td>
                              <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${stock}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
                $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);

              }
              // console.log(`ESTE STOCK ESTA SIENDO APPEND ${stock}`);

            }
          } else {
            let stock = parseInt(producto.stock);
            if (producto.estado === 2) {
              stock = stock
            }
          }

          if (producto.fecha_inicio === null && producto.fecha_termino === null) {

            // console.log("SIN FECHA");

            // console.log("ALL");
            // console.log(`ALL STOCK ${stock}`);
            // console.log(`ALL ASSIGNED ${producto.assigned}`);
            // console.log(`ALL ESTADO ${producto.estado}`);

            let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);
            // console.log("EL TR A BUSCAR", trSearchable.prevObject);
            // console.log("EL LARGOOOOO TR A BUSCAR", trSearchable.prevObject.length);

            if (trSearchable.prevObject.length === 1) {

              // console.log("ALLLLLLLL ENCONTRE UN ID DENTRO DE LA TABLA");
              let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());

              if (producto.estado !== "2") {
                // console.log(`ALL LE SUMO   ${producto.assigned}`);
                stock = stock + parseInt(assigned)
              }

              $(trSearchable.prevObject).find('.productStock').text(stock);
            } else {

              if (producto.estado !== "2") {
                // console.log(`ALL LE SUMO   ${producto.assigned}`);
                // stock = stock - parseInt(producto.assigned)
                stock = stock + parseInt(assigned);
              }

              // console.log("SIN FECHA  NO ENCONTRE HAGO APPEND");


              let td = `<td class="productId" style="display:none">${producto.id}</td>
              <td class=""> ${producto.categoria}</td>
              <td class=""> ${producto.item}</td>
              <td class="productName">${producto.nombre}</td>
              <td class="productPrice"> ${producto.precio_arriendo} </td>
              <td class="productStock">${stock}</td>
              <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${stock}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
              $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);
            }

          }
        });
      }



      if (tipo === "all") {
        response.forEach(producto => {
          let td = `<td class="productId" style="display:none">${producto.id}</td>
              <td class="productName">${producto.nombre}</td>
              <td class=""> ${producto.categoria}</td>
              <td class=""> ${producto.item}</td>
              <td class="productPrice"> ${producto.precio_arriendo} </td>
              <td class="productStock">${producto.stock}</td>
              <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${producto.stock}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
          $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);
        })
      }
      $('#tableProducts').DataTable({
        scrollX: true,
        fixedHeader: true,
        responsive: true
      })
    }, error: function (response) {
      console.log(response);
    }
  })
}


function GetAllProductsByBussiness(empresaId) {
  return $.ajax({
    type: "POST",
    url: "ws/productos/Producto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "getProductos",
      empresaId: empresaId
    }),
    success: function (response) {
      // console.log(response);
    }, error: function (error) {
      console.log(error);
    }
  })
}

async function GetProductDataById(product_id) {
  return $.ajax({
    type: "POST",
    url: "ws/productos/Producto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "GetProductDataById",
      product_id: product_id
    }),
    success: function (response) {
      // console.log(response);
    }, error: function (error) {
      console.log(error);
    }
  })
}


function SetResumeProductsValue() {

  let personalCost = $('.valorProductoResume')
  let totalPersonal = 0

  Array.from(personalCost).forEach(pCost => {
    totalPersonal = totalPersonal + parseInt(ClpUnformatter($(pCost).text()));
  });

  console.log("total de PRODUCTOS EN UPDATE", totalPersonal);

  $('#totalResumeProductos').text(CLPFormatter(totalPersonal));
  // $('#totalResumeProductos').text("totalPersonal");

}


function removeProduct(idProduct) {
  removeProductoStorage(idProduct)
  RemoveProductFromResume(idProduct);
}

function RemoveProductFromResume(id) {


  if (ROL_ID.includes("1") || ROL_ID.includes("2") || ROL_ID.includes("7")) {

    let tdProductos = $('#projectEquipos tbody').find('.idProductoResume');

    tdProductos.each((index, td) => {
      if ($(td).text() === id) {
        $(td).closest('tr').remove();
      }
    })
    SetResumeProductsValue();

  } else {
    Swal.fire({
      title: 'Lo sentimos',
      text: "No tienes los persisos para poder ejecutar esta acción, si deseas tenerlos debes ponerte en contacto con el administrador de tú organización",
      icon: 'warning',
      showCancelButton: false,
      showConfirmButton: true,
      confirmButtonText: "Entendido"
    })
  }


}

function AppendProductToResume(tipo) {

  let lStorage = GetProductsStorage();
  let arrayLength = lStorage.length;
  lStorage = lStorage[arrayLength - 1];

  if (tipo === "add") {
    let newTr = `<tr>
                      <td class="idProductoResume" style="display:none">${lStorage.productId}</td>
                      <td class="tbodyHeader">${lStorage.productName}</td>
                      <td class="quantity">${lStorage.quantityToAdd}</td>
                      <td class="perUnit">${lStorage.productPrice}</td>
                      <td class="valorProductoResume">${lStorage.totalPrice}</td>
                    </tr>`;
    for (let i = arrayLength - 1; i === arrayLength - 1; i++) {
      $("#projectEquipos tr:last").before(newTr);
    }

    SetResumeProductsValue();
    // $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));

  }
}
function AddProduct(el) {
  if (ROL_ID.includes("1") || ROL_ID.includes("2") || ROL_ID.includes("7")) {
    let product_id = $(el).closest("tr").find('.productId').text();
    console.log(product_id);
    let quantityToAdd = $(el).closest("td").find('.quantityToAdd').val();

    const productExist = listProductArray.find((producto) => {
      if (producto.id === product_id) {
        return producto
      }
    })
    if (!productExist) {
      Swal.fire(
        'Lo sentimos!',
        'Ha ocurrido un error, intente nuevamente',
        'error'
      )
      return
    }

    const disponibles = productExist.disponibles;


    if (quantityToAdd === "" || quantityToAdd === undefined || quantityToAdd < 0) {
      Swal.fire({
        'title': 'Ups!',
        'text': 'Ingresa una cantidad valida',
        'icon': 'warning',
        'showConfirmButton': false,
        'timer': 2000
      })
      return;
    }

    if ((parseInt(disponibles) - parseInt(quantityToAdd)) < 0) {
      console.log("FALTARAN PRODUCTOS");
    }

    const productsToAdd = [{
      'product_id': product_id,
      'quantityToAdd': quantityToAdd
    }];

    Toastify({
      text: `Se han agregado ${quantityToAdd} ${productExist.nombre}`,
      duration: 2000,
      close: true
    }).showToast();

    // console.log("previo 1");
    substractStockFromProducts(productsToAdd);
    // THIS FUNCTION USE GLOBLA VARIABLE AND APPEND ARRAY ON TABLE PRODUCTS
    // console.log("previo 2");
    fillProductsTableAssigments();
    //FORMAT RESUME PRODUCT ARRAY
    // console.log("previo 3");
    SetSelectedProducts_Substract(productsToAdd);
    // APPEND ALL PRODUCTS TO RESUME AND RESUME PROJECT TABLE
    // console.log("previo 4");
    printAllMySelectedProds();
    printAllMySelectedProdsOnProjectResume();

  } else {

    Swal.fire({
      title: 'Lo sentimos',
      text: "No tienes los permisos para poder ejecutar esta acción, si deseas tenerlos debes ponerte en contacto con el administrador de tú organización",
      icon: 'warning',
      showCancelButton: false,
      showConfirmButton: true,
      confirmButtonText: "Entendido"
    })
  }
}

// AGREGAR UN ITEM A LA TABLA DE RESUMEN A UN COSTADO DE 
//LA TABLA, CREA RESUMEN DEPENDIENDO DE LAS CANTIDADES, NOMBRE Y PRECIO DE ARRIENDO
function AddProductssssss(el) {
  if (ROL_ID.includes("1") || ROL_ID.includes("2") || ROL_ID.includes("7")) {
    // if (ROL_ID !== 3) {
    let tdProductos = $('#projectEquipos tbody').find('.idProductoResume');
    // tdProductos.each((index, td) => {
    //   if ($(td).text() === id) {
    //     $(td).closest('tr').remove();
    //   }
    // })
    SetResumeProductsValue();

    let quantityToAdd = $(el).closest("td").find('.quantityToAdd').val();
    let productId = $(el).closest("tr").find('.productId').text();
    let productName = $(el).closest("tr").find('.productName').text();
    let productPrice = $(el).closest("tr").find('.productPrice').text();
    let stock = parseInt($(el).closest("tr").find('.productStock').text());
    let finalStock = stock - parseInt(quantityToAdd);

    if (quantityToAdd === 0 || quantityToAdd === undefined || quantityToAdd === null || quantityToAdd === "") {
      Swal.fire({
        icon: 'info',
        title: 'Ups!',
        text: `Debes agregar la cantidad de ${productName} que deseas añadir`,
      })
      return;
    }


    if (finalStock < 0) {
      Swal.fire({
        icon: 'error',
        title: 'Ups!',
        text: `Has seleccionado más ${productName} de los que dispones`,
      });

    } else {

      $(el).closest("tr").find('.productStock').text(finalStock);
      $(el).closest("td").find('.quantityToAdd').val("");
      AddDivProduct(productName, productPrice, productId, quantityToAdd);
      let totalPrice = productPrice * quantityToAdd;
      ProductsStorage(productId, productName, productPrice, quantityToAdd, totalPrice);
      TotalCosts(totalPrice);
      AppendProductToResume("add");
    }
  } else {
    Swal.fire({
      title: 'Lo sentimos',
      text: "No tienes los permisos para poder ejecutar esta acción, si deseas tenerlos debes ponerte en contacto con el administrador de tú organización",
      icon: 'warning',
      showCancelButton: false,
      showConfirmButton: true,
      confirmButtonText: "Entendido"
    })
  }
}

function AppendProductosTableResumeArray(arrayProductos) {
  for (let i = 0; i < arrayProductos.length; i++) {
    let newTr = `<tr>
                <td class="idProductoResume" style="display:none">${arrayProductos[i].productId}</td>
                <td class="tbodyHeader">${arrayProductos[i].productName}</td>
                <td class="quantity">${arrayProductos[i].quantityToAdd}</td>
                <td class="perUnit">${arrayProductos[i].productPrice}</td>
                <td class="valorProductoResume">${CLPFormatter(arrayProductos[i].totalPrice)}</td>
              </tr>`;
    $("#projectEquipos tr:last").before(newTr);
    TotalCosts(arrayProductos[i].totalPrice)
  }
  SetResumeProductsValue();
  console.log(GetTotalCosts());
  $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));
}



//ASIGNACION DE PRODUCTOS Y PAQUETES DATOS TOMADOS DESDE EL DOM, FUNCIONES DE MODULO PRODUCTOS
let lastValue = 0
$(document).on('click', '.addProdInputResume', async function () {
  lastValue = $(this).val();
})

$(document).on('blur', '.addProdInputResume', async function () {
  let currentValue = $(this).val();

  if (!isNumeric(currentValue)) {
    Swal.fire(
      'Ups!',
      'Debes ingresar un número',
      'error'
    );
    return
  }

  let product_id = $(this).closest('tr').attr('product_id');
  let minProducts = [];
  let minvalue = 0;

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
      selectedPackages.map(async (package) => {
        return await GetPackageDetails(package.id);
      }))

    minProducts = detailsPackage.map((packageProds, index) => {
      return packageProds.products[index, 0]
    })

    minProducts.forEach((prod) => {
      if (prod.product_id === product_id) {
        minvalue += parseInt(prod.quantity)
      }
    })
    if (parseInt(currentValue) < minvalue) {
      Swal.fire(
        'Ups!',
        `No puedes seleccionar menos de ${minvalue} de este equipo ya que pertenecen a un paquete estandard que ya seleccionaste`,
        'error'
      ).then(() => {
        console.log($(this));
        $(this).val(lastValue);
      });


      return
    } else {
      const quantityAddStock = parseInt(lastValue) - parseInt(currentValue);
      const productsToAdd = [{
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
      // addProductToResumeAssigment()
      printAllMySelectedProds()
    }
  } else {

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

    if (currentValue > 0) {

      const productsToAdd = [{
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
    } else {
      $(this).val(lastValue)
    }
  }
})

$(document).on('click', '.removePackageFromAssigment', async function () {
  const package_id = $(this).closest('.packageNameContainer').attr('package_id');
  // console.log(package_id);

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
      selectedPackages.splice(index, 1)
    }
  })
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
  printAllMySelectedProds();
  printAllMySelectedProdsOnProjectResume();
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
    success: function (response) {
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
    success: function (response) {

    },
    error: function (error) {
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
    listProductArray = allMyProducts.map(function (producto) {
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

      listProductArray = allMyProducts.map(function (producto) {
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
          <td class="productAvailable" >${(producto.disponibles < 0) ? 0 : producto.disponibles}</td>
          <td><input style="margin-right:8px" class="addProdInput quantityToAdd" id="" type="number" min="1" max="${producto.cantidad}"/><i class="fa-solid fa-plus addItem" onclick="AddProduct(this)"></i></td>`
    $('#tableDrop').append(`<tr id="${producto.id}">${td}</tr>`);
  });

  $('#tableProducts').dataTable();
}



$(document).on('change', '#filterSelectedProducts', function () {
  // console.log("estoy haciendo algo en el chagne de las categorias");
  const categorieToSearch = $(this).val()

  if (categorieToSearch === "all") {
    filterProductsResume(selectedProdsAndCategories)
    return
  }
  const catExists = selectedProdsAndCategories.find((categorie) => {
    if (categorie.categoria === categorieToSearch) {
      return true;
    }
  })
  if (catExists) {
    console.log(catExists);
    filterProductsResume([catExists])
  }
})



$('#getAvailableProducts').on('click', function () {
  let navItem = $(this).find('.projectAssigmentTab')

  if ($(navItem).hasClass('active')) {
    $(navItem).removeClass('active')
    $('#products').removeClass('active show').addClass('fade');
  } else {
    CloseAllTabsOnProjectsAssigments();
    $(navItem).addClass('active')
    $('#products').removeClass('fade')
    $('#products').addClass('active show');

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









