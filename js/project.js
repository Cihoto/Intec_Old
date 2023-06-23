function FillCreated(empresaId,status) {
    let table;
    if(status === 1 ){
        table = $('#createdProjects').hide();
    }
    if(status === 2){
        table = $('#confirmedProjects').hide();
    }
    if(status === 3){
        table = $('#finishedProjects').hide();
    }    
    $('.lds-facebook').show();

    $.ajax({
        type: "POST",
        url: "ws/proyecto/proyecto.php",
        dataType: 'json',
        data: JSON.stringify({
            "action": "getMyProjects",
            request: {"empresaId":empresaId,
                      "status": status}
        }),
        success: function (response) {

            let tbody = table.find('tbody');
            tbody.empty();

            response.forEach(p =>{
                let fechaRealizacion = "";
                if(p.fecha_inicio === null){
                    p.fecha_inicio = "";
                }
                if(p.fecha_termino === null){
                    p.fecha_termino = "";
                }
                
                if(p.fecha_inicio === ""){
                    fechaRealizacion = `/ ${p.fecha_termino}`;
                }
                if(p.fecha_termino === ""){
                    fechaRealizacion = `${p.fecha_inicio} /`; 
                }
                if (p.fecha_inicio !== "" && p.fecha_termino !== ""){
                    fechaRealizacion = `${p.fecha_inicio} 
                                         <br>/ ${p.fecha_termino}`
                }
                if (p.fecha_inicio === "" && p.fecha_termino === ""){
                    fechaRealizacion = ""
                }

                if(p.nombreCliente === null){
                    p.nombreCliente = "";
                }

                if(p.direccion === null){
                  p.direccion = ""  
                }

                let tr = `<tr class="getProjectDetails">;
                            <td class="idProject" align=center>${p.id}</td>
                            <td align=center>${p.nombre_proyecto}</td>
                            <td align=center>${p.nombreCliente}</td> 
                            <td align=center>${p.direccion}</td> 
                            <td align=center>${fechaRealizacion}</td>
                            <td data-tooltip="Detalles" align=center><i style="cursor:pointer;" class="fa-solid fa-eye openDetalleModal"></i></td>
                        </tr>`;
                tbody.append(tr);
            });
            $('.lds-facebook').hide();
            if ( !$.fn.DataTable.isDataTable( '#createdProjects')) {
                $('#createdProjects').DataTable({
                    fixedHeader: true
                })
            }
            if ( !$.fn.DataTable.isDataTable( '#confirmedProjects')) {
                $('#confirmedProjects').DataTable({
                    fixedHeader: true
                })
            }
            if ( !$.fn.DataTable.isDataTable( '#finishedProjects')) {
                $('#finishedProjects').DataTable({
                    fixedHeader: true
                })
            }
            table.fadeIn(1000)
        }
    })
}


