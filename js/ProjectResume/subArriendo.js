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
function SetResumeArriendosResumeValue() {

    let arriendoCost = $('.inputSubValue')
    let totalArriendo = 0
  
    Array.from(arriendoCost).forEach(pCost => {
      totalArriendo = totalArriendo + parseInt(ClpUnformatter($(pCost).val()));
    });

    $('#totalSubResume').text(CLPFormatter(totalArriendo));
    $('#totalCostProject').text(CLPFormatter(parseInt(ClpUnformatter(GetTotalCosts())) + totalArriendo));

    
  
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

