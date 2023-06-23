
function SetTotalCost(){
    $('#totalCostProject').text(CLPFormatter(parseInt(GetTotalCosts())));
}

function CalcUtilidad(){
    $('#totalResumeProductos').text(CLPFormatter(totalPersonal))
    $('#totalResumePersonal').text(CLPFormatter(totalPersonal))

}


function CalcularUtilidad(){

    
if($('#totalIngresos').text() === ""){

}else{
    $('#totalIngresos').text(CLPFormatter(parseInt($('#totalIngresos').text())));
}

let personalCost = $('.valorPersonalResume')
let totalPersonalContratado = 0
let totalPersonalBHE = 0;
let totalPersonal = 0;

Array.from(personalCost).forEach(pCost => {

    let tipoContrato = $(pCost).closest('tr').find('.tipoContratoProjectResume').text();
    console.log($(pCost).text());
    let valor = $(pCost).text()
    valor = ClpUnformatter(valor);
    console.log(`TIPO CONTRATO ${tipoContrato}`);
    if(tipoContrato === "BHE"){

        totalPersonalBHE = totalPersonalBHE + parseInt(valor);

    }else{
        console.log(parseInt(valor));
        totalPersonalContratado = totalPersonalContratado + parseInt(valor);

    }
});

console.log(`TOTAL DE PERSONAL CONTRATADO ${totalPersonalContratado}`);

let totalIngresos = parseInt(ClpUnformatter($('#totalIngresos').text()));
let totalSubarriendos = $('#totalSubResume').text(); 
// let totalPersonal = $('#totalResumePersonal').text();
let totalViaticos = $('#totalViaticoResume').text();
// let totalPersonalBHE = $('#totalPersonalBHEDes').text();


let unftotalSubarriendos;
let unftotalPersonal;
let unftotalViaticos;

if(totalSubarriendos === ""){
    totalSubarriendos = CLPFormatter(0);
    unftotalSubarriendos = 0;
}else{
    unftotalSubarriendos = ClpUnformatter(totalSubarriendos);
}

if(totalPersonal === ""){
    totalPersonal = CLPFormatter(0);
    unftotalPersonal = 0;
}else{
    unftotalPersonal = totalPersonal;
}

if(totalPersonalBHE === ""){
    totalPersonalBHE = CLPFormatter(0);
    unftotalPersonalBHE = 0;
}else{
    unftotalPersonalBHE = totalPersonalBHE;
}

if(totalViaticos === ""){
    totalViaticos = CLPFormatter(0);
    unftotalViaticos = 0;
}else{
    unftotalViaticos = ClpUnformatter(totalViaticos);
}

$('#totalSubarriendosDes').text(totalSubarriendos);

let ingresOP = totalIngresos - parseInt(unftotalSubarriendos);
$('#ingresoOPDes').text(CLPFormatter(ingresOP))

$('#totalPersonalDes').text(CLPFormatter(totalPersonalContratado));
$('#totalPersonalBHEDes').text(CLPFormatter(totalPersonalBHE));

$('#totalViaticosDes').text(totalViaticos);

let f29 = ((ingresOP*19)/100) + (unftotalPersonalBHE*13.5)/100
$('#totalf29Des').text(CLPFormatter(f29));

let gastosOP = parseInt(unftotalPersonal) + parseInt(unftotalViaticos) + parseInt(totalPersonalBHE);
$('#totalGastosOPDes').text(CLPFormatter(gastosOP));

let utilidadOP = totalIngresos - gastosOP - parseInt(unftotalSubarriendos);
$('#totalUtilidadOPDes').text(CLPFormatter(utilidadOP));

let porcentajeGastos = ((gastosOP*100) / (ingresOP)).toFixed(3);
$('#totalporGastosOPDes').text(`${porcentajeGastos}%`)

let totalsueldos =  parseInt(unftotalPersonal) + parseInt(totalPersonalBHE)
let porcentajesinsueldos = `${(((gastosOP - totalsueldos) * 100) / ingresOP).toFixed(3)}%`;
$('#totalGastosOPSNDes').text(porcentajesinsueldos);

let totalOPSN = (utilidadOP + parseInt(unftotalPersonal));
$('#totalUtililidadOPSNDes').text(CLPFormatter(totalOPSN));

}


function ClearTables(){

    $('#fechaProjectResume').text("");
    $('#clienteProjectResume').text("");
    $('#clienteProjectResume').text("");
    $('#lugarProjectResume').text("");
    $('#comentariosProjectResume').text("");
    $('#totalResumePersonal').text("");
    $('#totalResumeProductos').text("");
    $('#totalResumeViatico').text("");
    $('#totalViaticoResume').text("");
    $('#totalSubResume').text("");
    $('#totalCostProject').text("");
    $('#totalIngresos').text("");
    $('#totalSubarriendosDes').text("");
    $('#ingresoOPDes').text("");
    $('#totalPersonalDes').text("");
    $('#totalViaticosDes').text("");
    $('#totalf29Des').text("");
    $('#totalGastosOPDes').text("");
    $('#totalUtilidadOPDes').text("");

    $('#projectPersonal').find('.tbodyHeader').closest('tr').each((key,element)=>{
        $(element).remove();
    })
    $('#projectEquipos').find('.tbodyHeader').closest('tr').each((key,element)=>{
        $(element).remove();
    })
    $('#vehiculosProject').find('.tbodyHeader').closest('tr').each((key,element)=>{
        $(element).remove();
    })
    $('#projectViatico').find('.tbodyHeader').closest('tr').each((key,element)=>{
        $(element).remove();
    })
    $('#projectSubArriendos').find('.tbodyHeader').closest('tr').each((key,element)=>{
        $(element).remove();
    })
}

function UnformatToClp(element){

    let valorUNCLP = ClpUnformatter($(element).text())
    $(element).text(valorUNCLP);
    
}