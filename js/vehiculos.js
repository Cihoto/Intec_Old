let allVehicles = [];
let selectedVehicles = [];

function searchVehiculoDrag(){
    let dragVehiculos = document.getElementById('sortable1').getElementsByTagName('li')
    let inputValue = document.getElementById('searchInputVehiculo').value.toUpperCase();
    for (let item of dragVehiculos) {
        let liValue = item.innerText.toUpperCase()
        if (!liValue.includes(inputValue)) {
            item.style.display = 'none';
        } else {
            item.style.display = '';
        }
    }
}

function FillVehiculos(empresaId) {
   $('#sortable1 li').each((key,element)=>{
    element.remove();
   })

    $.ajax({
        type: "POST",
        url: "ws/vehiculo/Vehiculo.php",
        dataType: 'json',
        data: JSON.stringify({
            "action": "getVehiculos",
            empresaId: empresaId
        }),
        success: function (response) {
            console.log("vehiculos", response);
            allVehicles = response
            response.forEach(vehiculo => {

                let li = `<li style="display:flex; justify-content:space-between;" class="${vehiculo.id}">
                        ${vehiculo.patente}
                        <div class="personalPricing" style="display:flex;align-content: center;">
                            <input type="number" name="price" class="vehiclePrice" placeholder="Costo"/>
                            <i onclick="AddVehiculo(this)"class="fa-solid fa-plus addPersonal"></i>
                            <i onclick="removeVehicle(this)" class="fa-solid fa-minus addVehicle" style="display:none; color: #b92413;"></i>
                        </div>
                    </li>`;
                // let li = `<li class="${vehiculo.id}">${vehiculo.patente}</li>`
                $('#sortable1').append(li)
            });
        }
    })
}

function GetAvailableVehicles(empresaId, fechaInicio, fechaTermino) {
    let arrayRequest = [{ "empresaId": empresaId, "fechaInicio": fechaInicio, "fechaTermino": fechaTermino }];
    $.ajax({
        type: "POST",
        url: "ws/vehiculo/Vehiculo.php",
        dataType: 'json',
        data: JSON.stringify({
            "action": "getAvailableVehiculos",
            request: { arrayRequest }
        }),
        success: function (response) {
            console.log("vehiculos", response);
            allVehicles = response
            $('#DragVehiculos').show();
            response.forEach(vehiculo => {

                let li = `<li style="display:flex; justify-content:space-between;" class="${vehiculo.id}">
                        ${vehiculo.patente}
                        <div class="personalPricing" style="display:flex;align-content: center;">
                            <input type="number" name="price" class="vehiclePrice" placeholder="Costo"/>
                            <i onclick="AddVehiculo(this)"class="fa-solid fa-plus addPersonal addVehicle"></i>
                            <i onclick="removeVehicle(this)" class="fa-solid fa-minus addVehicle" style="display:none; color: #b92413;"></i>
                        </div>
                    </li>`;
                // let li = `<li class="${vehiculo.id}">${vehiculo.patente}</li>`
                $('#sortable1').append(li)
            });
        }
    })
}



function AddVehiculo(element){

    if (ROL_ID.includes("1")||ROL_ID.includes("2")||ROL_ID.includes("13")){
        console.log(allVehicles)

        const valorVehiculo = $('.addVehicle').closest('.personalPricing').find('.vehiclePrice').val();

        let idVehiculo = $(element).closest('li').attr('class').trim();

        if (valorVehiculo === undefined || valorVehiculo === "" || valorVehiculo === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Ups!',
                text: 'Ingresa el costo de este Vehículo antes de asignarlo a este evento'
            })
            return;
        }

        const vehicleExists = allVehicles.find((vehicle)=>{
            if(vehicle.id === idVehiculo){
                return true 
            }    
        })

        if(vehicleExists){
            selectedVehicles.push({
                'id':vehicleExists.id,
                'patente':vehicleExists.patente,
                'valor':valorVehiculo
            })
        }

        printSelectedVehicles();


    }else{

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

function removeVehicle(element){
    if (ROL_ID.includes("1")||ROL_ID.includes("2")||ROL_ID.includes("13")){

        let vehicle_id = $(element).closest('li').attr('vehicle_id');

        const existsToDelete = selectedVehicles.find((selected,index)=>{
            if(selected.id === vehicle_id){
                selectedVehicles.splice(index,1);
                return true
            }
        })

        console.log("existsToDelete",existsToDelete);
        console.log("selectedVehicles",selectedVehicles);

        printSelectedVehicles()

    }else{
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


function printSelectedVehicles(){
    $('#sortable2 li').remove();
    $('#sortable1 li').remove();
    $("#vehiculosProject .resumeVehicleData").remove();


    allVehicles.forEach((vehicle)=>{
        const vehicleExists = selectedVehicles.find((selected)=>{
            if(selected.id === vehicle.id){
                return true;
            }
        })
        if(!vehicleExists){
            let li = `<li vehicle_id="${vehicle.id}" style="display:flex; justify-content:space-between;" class="${vehicle.id}">
                ${vehicle.patente}
                <div class="personalPricing" style="display:flex;align-content: center;">
                    <input type="number" name="price" class="vehiclePrice" placeholder="Costo"/>
                    <i onclick="AddVehiculo(this)"class="fa-solid fa-plus addPersonal addVehicle"></i>
                </div>
            </li`;

            $('#sortable1').append(li)
        }
    })


    selectedVehicles.forEach((vehicle)=>{
        let li = `<li vehicle_id="${vehicle.id}" style="display:flex; justify-content:space-between;" class="${vehicle.id}">
            ${vehicle.patente}
            <div class="personalPricing" style="display:flex;align-content: center;">
                <input type="number" name="price" class="vehiclePrice" placeholder="Costo"/>
                <i onclick="removeVehicle(this)" class="fa-solid fa-minus" style="color: #b92413;"></i>
            </div>
        </li>`;

        $('#sortable2').append(li)

    })


    selectedVehicles.forEach((selected)=>{
       let tr = `<tr class="resumeVehicleData">
            <td class="idVehicleResume" style="display:none">${selected.id}</td>
            <td class="tbodyHeader">${selected.patente}</td>
            <td></td>
            <td>${CLPFormatter(parseInt(selected.valor)) }</td>
            <td></td>
        </tr>`;

        $("#vehiculosProject tr:last").before(tr);

    })

}

function AddVehiculo_Old(el) {

    if (ROL_ID.includes("1")||ROL_ID.includes("2")||ROL_ID.includes("13")){

        let li = el.closest('li')
        let idVehiculo = $(li).attr('class').trim();
        let patente = $(li).text().trim();
        let valor = el.previousElementSibling.value;

        if (valor === undefined || valor === "" || valor === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Ups!',
                text: 'Ingresa el costo de este Vehículo antes de asignarlo a este evento'
            })
        } else {

            li.remove()
            $(el).hide();
            $(el).closest(li).find('.addVehicle').show();
            $('#sortable2').append(li)
            VehicleStorage(idVehiculo, patente, valor)
            AppendVehiculoToResume("add")

        }
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

function removeVehicle_old(element) {

    if (ROL_ID.includes("1")||ROL_ID.includes("2")||ROL_ID.includes("13")){
        let li = $(element).closest('li');
        let idVehiculoDelete = li.attr('class');
        let patente = li.text();
        element.previousElementSibling.style.display = "block";
        element.style.display = "none";
        li.remove();
        $('#sortable1').append(li)
        removeVehicleStorage(idVehiculoDelete, patente)
        console.log(GetVehicleStorage())

        RemoveVehicleFromResume(idVehiculoDelete);

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

function RemoveVehicleFromResume(id) {
    
    let tdPersonal = $('#vehiculosProject tbody').find('.idVehicleResume')
    tdPersonal.each((index, td) => {
        if ($(td).text() === id) {
            $(td).closest('tr').remove();
        }
    })
}

function AppendVehiculoToResume(tipo) {

    let lStorage = GetVehicleStorage();
    // console.log("ALL STORAGE",lStorage);
    let arrayLength = lStorage.length;
    lStorage = lStorage[arrayLength - 1];
    console.log(lStorage);
    if (tipo === "add") {
        let newTr = `<tr>
                        <td class="idVehicleResume" style="display:none">${lStorage.idVehiculo}</td>
                        <td class="tbodyHeader">${lStorage.patente}</td>
                        <td></td>
                        <td></td>
                    </tr>`;
        for (let i = arrayLength - 1; i === arrayLength - 1; i++) {
            $("#vehiculosProject tr:last").before(newTr);
        }
    }
}


function AppendVehiculoTableResumeArray(arrayVehiculos) {
    console.log("array de vehiculos emn funcion append", arrayVehiculos);

    for (let i = 0; i < arrayVehiculos.length; i++) {

        let newTr = `<tr>
                        <td class="idVehicleResume" style="display:none">${arrayVehiculos[i].idVehiculo}</td>
                        <td class="tbodyHeader">${arrayVehiculos[i].patente}</td>
                        <td></td>
                        <td></td>
                    </tr>`;

        $("#vehiculosProject tr:last").before(newTr);
    }

    $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));

}