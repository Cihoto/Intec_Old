let PACKAGE_LIST = [];
let allMyProducts = [];
let allMyTakenPoducts = [];
let listProductArray = [];
let allAndselectedProductsList = [];
let selectedPackages = [];
let selectedProducts = [];
let resumeSelectedProducts = [];
let allMyCatAndSubCat = [];


async function FillStandardPackages() {
    const myPackages = await GetAllStandardPackages(EMPRESA_ID);
    if (myPackages.success) {
        const packages = myPackages.data;
        packages.forEach((package) => {
            let tr = `<tr package_id="${package.id}">
            <td>${package.nombre}</td>
            <td style="cursor:pointer;" class="addPackageToAssigments"><i  class="fa-solid fa-plus "></i></td>
            </tr>`;
            $('#standardPackagesList').append(tr);
        })
        PACKAGE_LIST = packages;
        console.log("PACKAGE_LIST", PACKAGE_LIST);
    }
}

async function GetAllStandardPackages(empresa_id) {
    return $.ajax({
        type: "POST",
        url: "ws/standard_package/standard_package.php",
        data: JSON.stringify({
            action: "GetAllStandardPackages",
            empresa_id: empresa_id
        }),
        dataType: 'json',
        success: async function (data) {

        },
        error: function (response) { }
    })
}


async function addPackageToProjectAssigments(element){

    const package_id = $(element).closest('tr').attr('package_id');
    const idExists = PACKAGE_LIST.find((package) => {
        return parseInt(package.id) === parseInt(package_id)
    });

    if (!idExists) {
        Swal.fire(
            'Lo sentimos!',
            'Algo ha ido mal, intenta nuevamente',
            'error'
        )
    }

    if(selectedPackages.length > 0 ){
        const packageIsSelected = selectedPackages.find((selected)=>{
            return selected.id === package_id
        })
        if(packageIsSelected){
            Swal.fire(
                'Lo sentimos!',
                'No se puede seleccionar mas de una vez el mismo paquete',
                'warning'
            )
            return
        }
    }

    //GET ALL PACKAGE DETAILS, NAME, ID FROM PACKAGE AND PRODUCTS THAT CONTAINS 
    const detailsPackage = await GetPackageDetails(package_id);
    console.log("detailsPackage",detailsPackage);
    if (!detailsPackage.success) {
        console.log("nada");
        return
    }
    // SET PACKAGE ID TO FIND IT ON GLOBAL VARIABLE PACKAGE_LIST
    // IF RETURN TRUE PUSH RESULT AND APPEND IT TO RESUME
    const detailPackageId = detailsPackage.data[0].id;
    const packageToAdd = PACKAGE_LIST.find((package)=>{
        if(package.id === detailPackageId){
            return package
        }
    })
    
    // PUSH FINDED PACKAGE TO GLOBAL LIST
    selectedPackages.push(packageToAdd);
    // ADD SELECTED PACKAGES TO RESUME
    addPackageToPackageAssigment();
    Toastify({
        text: `Se ha agregado el paquete ${packageToAdd.nombre} `,
        duration: 2000,
        close: true
    }).showToast();
    // FORMAT PRODUCTS TO STANDARD JSON AND APPEND ON RESUME
    // ALSO SET STOCK AND AVAILABILITY ON RESUME PRODUCT TABLE
    const productsToAdd = detailsPackage.products.map((packageProducts)=>{
        return {
            'product_id' : packageProducts.product_id,
            'quantityToAdd' : packageProducts.quantity
        }
    });
    // THIS FUNCTION MODIFY GLOBAL CONST listProductArray 
    substractStockFromProducts(productsToAdd);
    // THIS FUNCTION USE GLOBLA VARIABLE AND APPEND ARRAY ON TABLE PRODUCTS
    fillProductsTableAssigments();
    //FORMAT RESUME PRODUCT ARRAY
    SetSelectedProducts_Substract(productsToAdd);
    // APPEND ALL PRODUCTS TO RESUME AND RESUME PROJECT TABLE
    addProductToResumeAssigment()
}

function SetSelectedProducts_Substract(productsToAdd){

    listProductArray.map((product)=>{
        const productExists = productsToAdd.find((addProd)=>{
            if(addProd.product_id === product.id){
                return addProd
            }
        })

        if(productExists){
           
            // console.log("productExists",productExists);
            const exists = selectedProducts.find((selected)=>{
                if(selected.id === productExists.product_id){
                    selected.quantityToAdd = parseInt(selected.quantityToAdd) + parseInt(productExists.quantityToAdd)
                    selected.faltantes = product.faltantes
                    // console.log("NUEVA VANTIDAD A A;ADIR");
                    // console.log(selected.quantityToAdd);
                    return selected
                }
            })
            if(!exists){
                selectedProducts.push({
                    'id': product.id,
                    'nombre': product.nombre,
                    'precio_arriendo': product.precio_arriendo,
                    'quantityToAdd': productExists.quantityToAdd,
                    'categoria': product.categoria,
                    'item': product.item,
                    'faltantes' : product.faltantes
                })  
            }
        }
    })

    // AFTER SET NEW AVAILABILITY STOCK ON "listProductArray" GLOBAL VARIABLE
    // CALL FUNTION TO LOOP ALL CATEGORIES AND SUB CATEGORIES
    // SAVE THE RESULT ARRAY ON NEW GLOBAL VARIABLE "allMyCatAndSubCat"
    setMyCatsAndSubCats();
}

function setMyCatsAndSubCats(){
    let arrayCategorias= [];
    const allCats = selectedProducts.map((prodProperties)=>{
        // return {"categoria":prodProperties.categoria,"subcat":prodProperties.item} 
        
        let categoria 
        // 
        
        prodProperties.filter()



        return {arrayCategoriasprodProperties}
        return {
            "audio":[{"accesorios":[1,5,7]},{"cables":[2,3]}],
            "energia":[{"accesorios":[1,5,7]},{"cables":[2,3]}]
        }
    })

    /*
    {
        selectedProducts=[{
            'id': 1,
            'nombre': product_1,
            'categoria': product.categoria,
            'item': product.item}];

        arrayCategorias = [{
            "audio":[{"accesorios":{1,5,7}},{"cables":{2,3}}],
            "energia":[{"accesorios":{1,5,7}},{"cables":{2,3}}]
        }]
    }
    */ 

    const ps = [];
    console.log(allCats);
}


function SetSelectedProducts_Add(productsToAdd){

    listProductArray.map((product)=>{
        const productExists = productsToAdd.find((addProd)=>{
            if(addProd.product_id === product.id){
                return addProd
            }
        })
        if(productExists){
            const exists = selectedProducts.find((selected)=>{
                if(selected.id === productExists.product_id){
                    selected.quantityToAdd = parseInt(selected.quantityToAdd) - parseInt(productExists.quantityToAdd)
                    selected.faltantes = product.faltantes
                    return selected
                }
            })

            if(!exists){
                selectedProducts.push({
                    'id': product.id,
                    'nombre': product.nombre,
                    'precio_arriendo': product.precio_arriendo,
                    'quantityToAdd': productExists.quantityToAdd,
                    'faltantes' : product.faltantes
                })  
            }
        }
    })
    console.log(selectedProducts);
}

function addPackageToPackageAssigment() {
    $('.packageNameContainer').remove();
    selectedPackages.forEach((selPackage) => {
        let toAppendPackage = `
        <div package_id=${selPackage.id} class="card col-lg-3 col-5 packageNameContainer ">
            <div class="d-flex justify-content-between" style="align-content:center">
                <p style="font-size:20px;">${selPackage.nombre}</p>
                <i style="color:red ;margin-top:10px; cursor:pointer" class="fa-solid fa-minus removePackageFromAssigment"></i>
            </div>
        </div>`;
        $('#package-names-resume').append(toAppendPackage);
    })
}

function substractStockFromProducts(productsToSubstract){

    listProductArray = listProductArray.map((product)=>{
        let faltantes = product.faltantes;
        let disponibles = product.disponibles;
        const productExists = productsToSubstract.find((addProd)=>{
            if(addProd.product_id === product.id){
                return addProd
            }
        })
        if (productExists) {
          disponibles = parseInt(product.disponibles) - parseInt(productExists.quantityToAdd);
        }
        if (disponibles < 0) {
          faltantes = Math.abs(disponibles)
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
    });
}

function AddStockFromProducts(productsToAdd){
    listProductArray = listProductArray.map((product)=>{
        let faltantes = product.faltantes;
        let disponibles = product.disponibles;
        const productExists = productsToAdd.find((addProd)=>{
            if(addProd.product_id === product.id){
                return addProd
            }
        })
        if (productExists) {
          disponibles = parseInt(product.disponibles) + parseInt(productExists.quantityToAdd);
        }
        if (disponibles < 0) {
          faltantes = Math.abs(disponibles)
        }else{
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
    });
}


async function GetPackageDetails(package_id) {
    return $.ajax({
        type: "POST",
        url: "ws/standard_package/standard_package.php",
        data: JSON.stringify({
            action: "GetPackageDetails",
            package_id: package_id
        }),
        dataType: 'json',
        success: async function (data) {
        },
        error: function (response) { }
    })
}



function addProductToResumeAssigment(){

    $('.detailsProduct-box').remove()
    $('#projectEquipos .productResumeItem').remove()

    selectedProducts.forEach((product)=>{
        $('#tbodyReceive').append(`
        <div class="detailsProduct-box">
                <div class="checkitem">
                    <input type="checkbox">
                    <span class="verticalLine"></span>
                </div>
                <div class="itemProperties">
                    <p class="itemId" style="display:none">${product.id}</p>
                <div class="itemName"> 
                    <p>${product.nombre}</p>
                    <hr/>
                </div>
                <div class="itemName"> 
                    <p>Faltantes</p>
                    <p>${product.faltantes}</p>
                    <hr/>
                </div>
                <div class="itemPrice">
                    <p class="getPrice" style="display:none">${product.id}</p>
                    <p  style="font-size: 15px; font-weight: 700;">Precio arriendo: ${product.precio_arriendo}</p>
                    <hr/>
                </div>
                <div class="itemDetails">
                    <div class="detailQuantity">
                        <p>Cantidad</p>
                        <input type="number" class="addProdInputResume" min="1" max="" value="${product.quantityToAdd}"/>
                    </div>
                    <div class="containerRemoveLogo">
                        <p style="visibility: hidden;">CANT</p>
                        <i class="fa-solid fa-trash logoRemove" style="color:red;font-size: 30px;"></i>
                    </div>
                </div>
            </div>
        </div>`);
        let newTr = `
        <tr class="productResumeItem">
            <td class="idProductoResume" style="display:none">${product.id}</td>
            <td class="tbodyHeader">${product.nombre}</td>
            <td class="quantity">${product.quantityToAdd}</td>
            <td class="perUnit">${product.precio_arriendo}</td>
            <td class="valorProductoResume">${parseInt(product.precio_arriendo) * parseInt(product.quantityToAdd)}</td>
        </tr>`;
        $("#projectEquipos tr:last").before(newTr);
    })

    // console.table(allAndselectedProductsList)

    // packageProducts.forEach((packageProduct) => {
    //     // console.table(packageProduct);
    //     const prodExists = listProductArray.find((allProds) => {
    //         // console.log("allProds.package_id",allProds.id) ;
    //         // console.log("producto.id",packageProduct.product_id);
    //         if (packageProduct.product_id === allProds.id) {
    //             return allProds;
    //         }
    //     })
    //     console.log(prodExists);
    //     if (prodExists) {
            



        // }
    // })
}

// function discountStockFromProductListArray_Package_Management(packageProducts) {
//     // MODIFY ARRAY listProductArray AND DISCOUND AVAILABLES FROM EACH ROW
//     // THIS FUNCTION ONLY MODIFY GLOBAL VARIABLE listProductArray TO USE IT ON NEW APPEND IN PRODUCTS TABLE
//     // console.table(listProductArray[0])
//     // console.table(listProductArray[1])
//     listProductArray = listProductArray.map((product) => {
//         let faltantes = 0;
//         let disponibles = product.disponibles;

//         const productExists = packageProducts.find((packProd) => {
//             if (packProd.product_id === product.id) {
//                 return packProd;
//             }
//         })

//         if (productExists) {
//             disponibles = parseInt(product.disponibles) - parseInt(productExists.quantity);
//         }
//         if (disponibles < 0) {
//             faltantes = Math.abs(disponibles);
//         }

//         return {
//             'id': product.id,
//             'categoria': product.categoria,
//             'item': product.item,
//             'nombre': product.nombre,
//             'precio_arriendo': product.precio_arriendo,
//             'cantidad': product.cantidad,
//             'disponibles': disponibles,
//             'faltantes': faltantes
//         }
//     })
// }
