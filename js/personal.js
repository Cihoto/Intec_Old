function searchPersonalDrag() {
    let dragPersonal = document.getElementById('sortablePersonal1').getElementsByTagName('li')
    let inputValue = document.getElementById('searchInputPersonal').value.toUpperCase();
    for (let item of dragPersonal) {
        let liValue = item.innerText.toUpperCase()
        if (!liValue.includes(inputValue)) {
            item.style.display = 'none';
        } else {
            item.style.display = '';
        }
    }
}

function FillPersonal(empresaId) {
    $.ajax({
        type: "POST",
        url: "ws/personal/Personal.php",
        dataType: 'json',
        data: JSON.stringify({
            "action": "getPersonal",
            empresaId: empresaId
        }),
        success: function (response) {
            console.table(response);
            response.forEach(personal => {

                if(personal.neto ==="" || personal.neto === null || personal.neto === undefined){
                    personal.neto = 0;
                }
                let li = `<li style="display:flex; justify-content:space-between;" class="${personal.id}">
                        ${personal.nombre} | ${personal.cargo} ${personal.especialidad}                             
                        <p class="tipoContrato" style="display:none">${personal.contrato}</p>
                        <div class="personalPricing" style="display:flex;align-content: center;">
                            <input type="number" name="price" class="personalPrice" value="${personal.neto}" placeholder="Costo"/>
                            <i onclick="AddPersonal(this)"class="fa-solid fa-plus addPersonal"></i>
                            <i onclick="removePersonal(this)" class="fa-solid fa-minus removePersonal" style="display:none; color: #b92413;"></i>
                        </div>
                    </li>`;
                $('#sortablePersonal1').append(li)
            });
        }
    })
}
function FillAvailablepersonal(empresaId,fechaInicio,fechaTermino){
    $.ajax({
        type: "POST",
        url: "ws/personal/Personal.php",
        dataType: 'json',
        data: JSON.stringify({
            "action": "getAvailablePersonal",
            request:{
                "empresaId": empresaId,
                "fechaInicio":fechaInicio,
                "fechaTermino":fechaTermino
            }
            
        }),
        success: function (response) {
            console.table(response);
            // response.forEach(personal => {

            //     if(personal.neto ==="" || personal.neto === null || personal.neto === undefined){
            //         personal.neto = 0;
            //     }
            //     let li = `<li style="display:flex; justify-content:space-between;" class="${personal.id}">
            //             ${personal.nombre} | ${personal.cargo} ${personal.especialidad}                             
            //             <p class="tipoContrato" style="display:none">${personal.contrato}</p>
            //             <div class="personalPricing" style="display:flex;align-content: center;">
            //                 <input type="number" name="price" class="personalPrice" value="${personal.neto}" placeholder="Costo"/>
            //                 <i onclick="AddPersonal(this)"class="fa-solid fa-plus addPersonal"></i>
            //                 <i onclick="removePersonal(this)" class="fa-solid fa-minus removePersonal" style="display:none; color: #b92413;"></i>
            //             </div>
            //         </li>`;
            //     $('#sortablePersonal1').append(li)
            // });
        }
    })
}

function AddPersonal(el){

    let idProd = el.closest('li').className;
    let li = el.closest('li');
    let valor = CLPFormatter(el.previousElementSibling.value);
    let notFormattedValue = el.previousElementSibling.value;
    let tipoContrato = $(el).closest('li').find('.tipoContrato').text();

    let nombrePersonal = el.closest('li').innerText;
    let idPersonal = el.closest('li').className;

    let tbodyPersonal = $('#projectPersonal tbody > tr');

    if (notFormattedValue === undefined || notFormattedValue === "" || notFormattedValue === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Ups!',
            text: 'Ingresa el costo de este trabajador antes de asignarlo a este evento'
        })
    } else {

        $(el).hide();
        $(el).closest(li).find('.removePersonal').show();
        li.remove()

        $('#sortablePersonal2').append(li)
        PersonalLocalStorage(idPersonal, nombrePersonal, notFormattedValue,tipoContrato);
        TotalCosts(notFormattedValue);
        changePersonalTableResume("add");
    }
}

function removePersonal(element) {

    let li = $(element).closest('li');
    let idProduct = li.attr('class');

    $(element).closest(li).find('.addPersonal').show();
    $(element).hide();
    li.remove();
    $('#sortablePersonal1').append(li)

    removeProductStorage(idProduct)
    console.log(GetPersonalStorage())
    removePersonalFromResume(idProduct);

}

function removePersonalFromResume(id) {

    let tdPersonal = $('#projectPersonal tbody').find('.idPersonal')
    tdPersonal.each((index, td) => {
        if ($(td).text() === id) {
            $(td).closest('tr').remove();
        }
    })
    SetResumePersonalValue();
}


function SetResumePersonalValue() {
    let personalCost = $('.valorPersonalResume')
    let totalPersonalContratado = 0
    let totalPersonalBHE = 0;
    let totalPersonal = 0;
    Array.from(personalCost).forEach(pCost => {
        let tipoContrato = $(pCost).closest('tr').find('.tipoContratoProjectResume').text();

        if(tipoContrato === "BHE"){
            totalPersonalBHE = totalPersonalBHE + parseInt(ClpUnformatter($(pCost).text()));
        }else{
            totalPersonalContratado = totalPersonalContratado + parseInt(ClpUnformatter($(pCost).text()));
        }
    });

    total = totalPersonalContratado + totalPersonalBHE;
    $('#totalResumePersonal').text(CLPFormatter(total))
    console.log("totalPersonalContratado",totalPersonalContratado);
    $('#totalPersonalDes').text(CLPFormatter(totalPersonalContratado));
    $('#totalPersonalBHEDes').text(CLPFormatter(totalPersonalBHE));
}


function changePersonalTableResume(tipo) {

    let lStorage = GetPersonalStorage();
    let arrayLength = lStorage.length;
    lStorage = lStorage[arrayLength - 1];
    console.log(lStorage);

    if (tipo === "add") {
        let newTr = `<tr>
                        <td class="idPersonal" style="display:none">${lStorage.idPersonal}</td>
                        <td class="tbodyHeader">${lStorage.nombrePersonal}</td>
                        <td class="tipoContratoProjectResume">${lStorage.tipoContrato}</td>
                        <td class="valorPersonalResume">${CLPFormatter(lStorage.valor)}</td>
                    </tr>`;
        for (let i = arrayLength - 1; i === arrayLength - 1; i++) {
            $("#projectPersonal tr:last").before(newTr);
        }
        SetResumePersonalValue();
        $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));
    }

    if (tipo === "delete") {

    }

}

function AppendPersonalTableResumeArray(arrayPersonal) {

    for (let i = 0; i < arrayPersonal.length; i++) {
        let newTr = `<tr>
                        <td class="idPersonal" style="display:none">${arrayPersonal[i].idPersonal}</td>
                        <td class="tbodyHeader">${arrayPersonal[i].nombrePersonal}</td>
                        <td class="tipoContratoProjectResume">${arrayPersonal[i].tipoContrato}</td>
                        <td class="valorPersonalResume">${CLPFormatter(arrayPersonal[i].valor)}</td>
                    </tr>`;

        $("#projectPersonal tr:last").before(newTr);
        TotalCosts(arrayPersonal[i].valor);
    }

    SetResumePersonalValue();
    $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));

}


