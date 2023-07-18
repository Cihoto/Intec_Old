function AddSubArriendo(){

    let newTr = `<tr>
                    <td class="tbodyHeader" contenteditable> <input type="text" class="inputSubName" placeholder=""/></td>
                    <td><input type="text" class="inputSubDetalle" placeholder=""/></td>
                    <td><input onblur="SetResumeSubValue(this)" type="number" class="inputSubValue" placeholder=""/></td>
                    <td onclick="deleteResumedata(this)" class="deleteRow"><i class="fa-solid fa-trash trashDelete"></i></td>
                </tr>`;

    $("#projectSubArriendos tr:last").before(newTr);
}
function AddSubArriendoWithValues(detalle,valor){

    let newTr = `<tr>
        <td class="tbodyHeader" contenteditable> <input type="text" class="inputSubName" placeholder=""/></td>
        <td><input type="text" class="inputSubDetalle" placeholder="" value="${detalle}"/></td>
        <td><input onblur="SetResumeSubValue(this)" type="number" class="inputSubValue" placeholder="" value="${valor}"/></td>
        <td onclick="deleteResumedata(this)" class="deleteRow"><i class="fa-solid fa-trash trashDelete"></i></td>
    </tr>`;
    $("#projectSubArriendos tr:last").before(newTr);
    
}

function addSubRentToResumeTable(nombre,detalle,valor,id){

    let newTr = `<tr id="${id}">
        <td class="tbodyHeader"><input type="text" class="inputSubName" placeholder="" value="${nombre}"/></td>
        <td><input type="text" class="inputSubDetalle" placeholder="" value="${detalle}"/></td>
        <td><input onblur="SetResumeSubValue(this)" type="number" class="inputSubValue" placeholder="" value="${valor}"/></td>
        <td onclick="deleteResumedata(this)" class="deleteRow"><i class="fa-solid fa-trash trashDelete"></i></td>
    </tr>`;
    $("#projectSubArriendos tr:last").before(newTr);
    SetResumeArriendosResumeValue(valor);
}

function SetResumeArriendosResumeValue(valuetoAdd){
    let arriendoCost = $('.inputSubValue')
    let totalArriendo = 0;
    Array.from(arriendoCost).forEach(pCost => {
      totalArriendo = totalArriendo + parseInt(ClpUnformatter($(pCost).val()));
    });
    $('#totalSubResume').text(CLPFormatter(totalArriendo));
    AddTotal(valuetoAdd);
    CalcularUtilidad();
}

function SetResumeSubValue(el){

    let valor = $(el).val();
    if(valor === ""){
        valor = 0;
        $(el).val(0);
    }

    let previusValue;
    if($('#totalSubResume').text() === ""){
        previusValue = 0
    }else{
        previusValue = ClpUnformatter($('#totalSubResume').text());
    }

    if(isNumeric(valor)){
        let personalCost = $('.inputSubValue')
        let totalSub = 0
        Array.from(personalCost).forEach(pCost => {
    
            totalSub = totalSub + parseInt(ClpUnformatter($(pCost).val())) 
        });
        $('#totalSubResume').text(CLPFormatter(totalSub))
        $('#totalSubarriendosDes').text(CLPFormatter(totalSub));

        // console.log(totalSub-previusValue);
        AddTotal(totalSub-previusValue);

    }else{
        // Swal.fire({
        //     icon: 'error',
        //     title: 'Ups!',
        //     text: 'Debes ingresar un numero'
        // })
    }
}
function SetResumeSubValueDirectValue(){

    let previusValue;
    if($('#totalSubResume').text() === ""){
        previusValue = 0
    }else{
        previusValue = ClpUnformatter($('#totalSubResume').text());
    }

    
    let personalCost = $('.inputSubValue')
    let totalSub = 0
    Array.from(personalCost).forEach(pCost =>{
        totalSub = totalSub + parseInt(ClpUnformatter($(pCost).val())) 
    });
    $('#totalSubResume').text(CLPFormatter(totalSub))
    $('#totalSubarriendosDes').text(CLPFormatter(totalSub));
    // console.log(totalSub-previusValue);
    AddTotal(totalSub-previusValue);

  
}

async function GetAlArriendosByEmpresa(empresa_id){
    return $.ajax({
        type: "POST",
        url: 'ws/Arriendos/arriendos.php',
        data: JSON.stringify({
            action: 'GetArriendos',
            empresa_id : empresa_id
        }),
        dataType: 'json',
        success: function(data) {
        },
        error: function(response) {
            console.log(response.responseText);
        }
    })
}

async function GetRentById(arriendo_id){
    return $.ajax({
        type: "POST",
        url: 'ws/Arriendos/arriendos.php',
        data: JSON.stringify({
            'action': 'GetArriendoById',
            'arriendo_id' : arriendo_id
        }),
        dataType: 'json',
        success: function(data) {
        },
        error: function(response) {
            console.log(response.responseText);
        }
    })
}
async function FillRent(){

    let ul = $('#rentsUl');
    console.log(ul);
    const rentArray = await GetAlArriendosByEmpresa(EMPRESA_ID);
    console.log("ARRAY RENTAS",rentArray.data);
    rentArray.data.forEach((arr)=>{
        console.log(arr.id);
        let li = `<li class="rentObj" onclick="AddArriendoToTable(this)" id="${arr.id}"><p>${arr.item} | ${arr.nombre} ${arr.apellido} ${arr.rut}</p></li>`;
        $(ul).append(li);
    });
}


function GetArriendos(){

    $('.arriendosSelect').addClass('active');
    $('#rentsUl').addClass('active');
    let liRent = $('#rentsUl').find('.rentObj');
    if(liRent.length > 0){
        $(liRent).each((key,element)=>{
            element.remove();
        })
        FillRent();
    }else{
        FillRent();
    }
}


$('#closeDiv').on('click',function(){
    $('.arriendosSelect').removeClass('active')
    $('#rentsUl').removeClass('active')
});

function OpenArriendoModal(){
    $('#arriendosModal').modal('show');
}

async function AddArriendoToTable(element){

    const id = $(element).attr('id');
    const { value: valorTotal } = await Swal.fire({
        title: 'Ingrese el total bruto de este arriendo',
        input: 'text',
        inputLabel: 'Valor Bruto',
        inputPlaceholder: ''
    })
    if (valorTotal) {
        const responseArriendo = await GetRentById(id);
        const arrayArriendo = responseArriendo.data;
        console.table(arrayArriendo[0].nombre)
        addSubRentToResumeTable(arrayArriendo[0].nombre,`${arrayArriendo[0].nombre} ${arrayArriendo[0].apellido} - ${arrayArriendo[0].rut}`,valorTotal,id)
        Swal.fire(
        {
         'icon':'success',
         'text':'Agregado exitosamente',
         'timer': 800
        });
        return valorTotal;
    }else{
        return
    }
}


