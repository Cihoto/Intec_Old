<div class="container">
    <h1>Resumen de Evento</h1>
    <div class="row resumeProjectTables">
        <table id="projectData" class="verticalTable spaced table-responsive">
            <thead>
                <tr>
                    <th colspan="2" class="projectNameResume" id="projectNameResume"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tbodyHeader">Fecha</td>
                    <td class="fechaProjectResume" id="fechaProjectResume"></td>
                </tr>
                <tr>
                    <td class="tbodyHeader">Cliente</td>
                    <td class="clienteProjectResume" id="clienteProjectResume"></td>
                </tr>
                <tr>
                    <td class="tbodyHeader">Contacto</td>
                    <td class="clienteProjectResume" id="clienteProjectResume"></td>
                </tr>
                <tr>
                    <td class="tbodyHeader">Lugar</td>
                    <td class="lugarProjectResume" id="lugarProjectResume"></td>
                </tr>
                <tr>
                    <td class="tbodyHeader">Comentarios</td>
                    <td class="comentariosProjectResume" id="comentariosProjectResume"></td>
                </tr>
            </tbody>
        </table>
        
        <h4>Técnicos</h4>
        <table id="projectPersonal" class="verticalTable spaced">
            <thead>
                <tr>
                    <th></th>
                    <th>Tipo Contrato</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td id="totalResumePersonal" colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <h4>EQUIPOS</h4>
        <table id="projectEquipos" class="verticalTable spaced">
            <thead>
                <tr>
                    <th></th>
                    <th>Cantidad</th>
                    <th>c/u</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td id="totalResumeProductos" colspan="3"></td>
                </tr>
            </tbody>
        </table>

        <h4>VEHICULOS</h4>
        <table id="vehiculosProject" class="verticalTable spaced">
            <thead>
                <tr>
                    <th></th>
                    <th>Detalle</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td id="totalResumeViatico" colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <h4>VIÁTICOS</h4>
        <table id="projectViatico" class="verticalTable">
            <thead>
                <tr>
                    <th>Detalle</th>
                    <th>Costo</th>
                    <th width="10px"></th>
                </tr>
            </thead>
            <tbody>
            <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td id="totalViaticoResume" colspan="2"></td>
                </tr>
            </tbody>
        </table>
        <?php if($rol_id !== 3):?>
        <button class="btn btn-success spaced"  onclick="AddViatico()" id="addViatico">Agregar</button>
        <?php endif;?>
        <h4>Sub arriendos</h4>
        <table id="projectSubArriendos" class="verticalTable">
            <thead>
                <tr>
                    <th></th>
                    <th>Detalle</th>
                    <th>Costo</th>
                    <th></th>

                </tr>
            </thead>
            <tbody>

                <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td id="totalSubResume" colspan="3"></td>
                </tr>

            </tbody>
        </table>

        <?php if($rol_id !== 3):?>
        <div class="row centered-spaced">
            <button class="btn btn-success spaced col-md-5 col-10 mb-3"  onclick="GetArriendos()" id="addViatico">Agregar Existente</button>
            <button class="btn btn-success spaced col-md-5 col-10"  onclick="OpenArriendoModal()" id="addViatico">Agregar Nuevo</button>
            <div class="arriendosSelect">
                <i class="fa-solid fa-chevron-up" id="closeDiv"></i>
                <ul id="rentsUl" class="rentsUl">
                    <li id="searchRentInput"><input type="text" placeholder="Buscar Arriendo" id="searchRent" onkeyup="searchRent()"></li>
                </ul>
            </div>
        </div>
        <?php  endif;?>


        <section class="section-cost">
            <p>Costos del evento</p>
            <h4 id="totalCostProject"></h4>
        </section>

        <h4>Resumen</h4>
        <table id="projectResumeTableValues" class="verticalTable">
            <thead>
                <tr>
                    <th colspan="2"> Desglose </th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="tbodyHeader">Ingreso Total</td>
                        <td id="totalIngresos" contenteditable onclick="UnformatToClp(this)" onblur="CalcularUtilidad()"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Sub Arriendos</td>        
                        <td id="totalSubarriendosDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Ingreso OP</td>        
                        <td id="ingresoOPDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Técnicos Contratados</td>        
                        <td id="totalPersonalDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Técnicos Freelance</td>        
                        <td id="totalPersonalBHEDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Viáticos</td>        
                        <td id="totalViaticosDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">F29</td>        
                        <td id="totalf29Des"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Total Gastos OP</td>        
                        <td id="totalGastosOPDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Utilidad OP</td>        
                        <td id="totalUtilidadOPDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">%Gastos OP</td>        
                        <td id="totalporGastosOPDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">%Gastos OP / sin sueldos</td>        
                        <td id="totalGastosOPSNDes"></td>
                    </tr>
                    <tr>
                        <td class="tbodyHeader">Utilidad OP / sin sueldos</td>        
                        <td id="totalUtililidadOPSNDes"></td>
                    </tr>
                <tr class="tbodyHeaderTotal">
                    <td>Total</td>
                    <td colspan="3"></td>
                </tr>

            </tbody>
        </table>



    </div>
</div>