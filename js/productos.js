function FillProductos(empresaId) {
  if ($.fn.DataTable.isDataTable('#tableProducts')){
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
        scrollX:true,
        fixedHeader: true
      })
    }
  })
}

function FillProductosAvailable(empresaId, tipo, fecha_inicio, fecha_termino) {

  if ($.fn.DataTable.isDataTable('#tableProducts')){
    $('#tableProducts').DataTable().destroy();
    $('#tableDrop > tr').each((key, element) => {
      $(element).remove();
    })
  }
  let arrayProductosAssigned;
  if (GetProductsStorage()){
    arrayProductosAssigned = GetProductsStorage();
  }else{
    arrayProductosAssigned = [];
  }

  console.log(GetProductsStorage());

  console.log("empresaId", empresaId);
  console.log("tipo", tipo);
  console.log("fecha_inicio", fecha_inicio);
  console.log("fecha_termino", fecha_termino);

  $.ajax({
    type: "POST",
    url: "ws/productos/Producto.php",
    dataType: 'json',
    data: JSON.stringify({
      "action": "GetAvailableProducts",
      empresaId: empresaId
    }),
    success: function (response) {
      console.table(response)

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

          let stock ;

          // let trSearchable = $("#tableDrop > tr").find(`#${producto.id}`)
          
          let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

          if (trSearchable.prevObject.length === 1) {
            let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());
            stock = oldStock;
            // console.log(`RESTE ${assigned} A ${oldStock} DENTRO DEL EACH DE JQUERY`);
            stock = oldStock - parseInt(assigned);
            
            $(trSearchable.prevObject).find('.productStock').text(stock);
          }else{
            stock = parseInt(producto.stock) - parseInt(sumaExistencias) - parseInt(assigned);
          }

          // console.log(`STOCK PREVIO A FUNCIONES ${stock}`);
          console.log(`ID DE PRODUCTO ${producto.id}`);

          if (producto.fecha_inicio !== null && producto.fecha_termino !== null) {
            

            if (fecha_inicio > producto.fecha_inicio && fecha_inicio > producto.fecha_termino ||
              fecha_termino < producto.fecha_inicio && fecha_termino < producto.fecha_termino){
                console.log("DISPONIBLE");

              console.log("Estoy dentro dE IF DE FECHAS DISPONIBLES Y PRODUCTOS LIBERADOS");
              console.log(`ESTE STOCK ME ESTA LLEGANDO A LA FECHA LIBERADA ${stock}`);
              console.log(`LE SUMANDO RESTANDO ${producto.assigned}`);

              let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

              if (trSearchable.prevObject.length === 1) {
    
                console.log("ALLLLLLLL ENCONTRE UN ID DENTRO DE LA TABLA");
                let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());
  
                if(producto.estado === "2"){
                  console.log(`DISPONIBLE LE SUMO ${assigned}`);
                  stock = oldStock + parseInt(assigned);
                }

                $(trSearchable.prevObject).find('.productStock').text(stock);
              }else{
                // console.log("DISPONIBLE NO ENCONTRE HAGO APPEND");

                if(producto.estado === "2"){
                  console.log(`DISPONIBLE LE SUMO ${assigned}`);
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

            }else{
              
              // console.log(`ESTE STOCK ME ESTA LLEGANDO A LA FECHA NO LIBERADA ${stock}`);
              // console.log(`LE SUMANDIO RESTANDO ${producto.assigned}`);
                // stock = stock - parseInt(producto.assigned);
                // console.log("NOOOOOOO DISPONIBLE");

                let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);

                if (trSearchable.prevObject.length === 1){
                  console.log("FECHA NO LIBERADA ENCONTRE UN ID DENTRO DE LA TABLA");
                  let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());
                  if(producto.estado !== "2"){
                    console.log(`NO DISPONIBLE LE SUMO NADA ${producto.assigned}`);
                    // stock = stock - parseInt(producto.assigned)
                    stock = oldStock + parseInt(assigned);
                  }
                  $(trSearchable.prevObject).find('.productStock').text(stock);

                }else{

                  if(producto.estado !== "2"){
                    console.log(` NO DISPONIBLE LE SUMO NADA ${producto.assigned}`);
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
          }else{
              let stock = parseInt(producto.stock);
              if(producto.estado === 2){
                stock = stock 
              }
          }

          if (producto.fecha_inicio === null && producto.fecha_termino === null) {

            console.log("SIN FECHA");

            // console.log("ALL");
            // console.log(`ALL STOCK ${stock}`);
            // console.log(`ALL ASSIGNED ${producto.assigned}`);
            // console.log(`ALL ESTADO ${producto.estado}`);

            let trSearchable = $("#tableDrop tbody tr").find(`#${producto.id}`);
            console.log("EL TR A BUSCAR",trSearchable.prevObject);
            console.log("EL LARGOOOOO TR A BUSCAR",trSearchable.prevObject.length);

            if (trSearchable.prevObject.length === 1) {
  
              // console.log("ALLLLLLLL ENCONTRE UN ID DENTRO DE LA TABLA");
              let oldStock = parseInt($(trSearchable.prevObject).find('.productStock').text());

              if(producto.estado !== "2"){
                // console.log(`ALL LE SUMO   ${producto.assigned}`);
                stock = stock + parseInt(assigned)
              }
             
              $(trSearchable.prevObject).find('.productStock').text(stock);
            }else{

              if(producto.estado !== "2"){
                // console.log(`ALL LE SUMO   ${producto.assigned}`);
                // stock = stock - parseInt(producto.assigned)
                stock = stock + parseInt(assigned);
              }
             
              console.log("SIN FECHA  NO ENCONTRE HAGO APPEND");


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
        scrollX:true,
        fixedHeader: true,
        responsive: true
      })
    }, error: function (response) {
      console.log(response);
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

  let tdProductos = $('#projectEquipos tbody').find('.idProductoResume');

  tdProductos.each((index, td) => {
    if ($(td).text() === id) {
      $(td).closest('tr').remove();
    }
  })
  SetResumeProductsValue();
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

// AGREGAR UN ITEM A LA TABLA DE RESUMEN A UN COSTADO DE 
//LA TABLA, CREA RESUMEN DEPENDIENDO DE LAS CANTIDADES, NOMBRE Y PRECIO DE ARRIENDO
function AddProduct(el) {

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
    })
  } else {

    $(el).closest("tr").find('.productStock').text(finalStock);
    $(el).closest("td").find('.quantityToAdd').val("");
    AddDivProduct(productName, productPrice, productId, quantityToAdd);
    let totalPrice = productPrice * quantityToAdd;
    ProductsStorage(productId, productName, productPrice, quantityToAdd, totalPrice);
    TotalCosts(totalPrice);
    AppendProductToResume("add");
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


