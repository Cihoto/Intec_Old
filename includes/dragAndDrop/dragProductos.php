<div class="card-body dragableItems">

    <div class="row">
        <div class="notSelectedProd moveProd" id="selectableProducts">
            <div class="row" style="min-height: 150px; max-height: 150px; overflow: scroll;width: 100%; margin: 20px 0px;overflow-x: hidden;">
                <table id="standardPackagesList">
                    <thead>
                        <th>Nombre</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
 
            </div>
            <div id="itemList">
                <table id="tableProducts">
                    <thead>
                        <th style="display:none;">Id</th>
                        <th>Cateogria</th>
                        <th>Item</th>
                        <th class="itemProd">Nombre</th>
                        <th class="productPrice" >Precio arriendo</th>
                        <th>Stock</th>
                        <th>Disponibles</th>
                        <th>Agregar</th>
                    </thead>
                    <tbody id="tableDrop">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="selectedProd moveProd" id="tbodyReceive">
            <div class="row justify-content-around" id="package-names-resume" style="max-height: 120px;min-height: 120px; overflow: scroll; overflow-x: hidden;">
                <h4>Paquetes Seleccionados</h2>
            </div>
            <div id="receiveProducto" class="">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h3>Productos Asignados</h3>
                    </div>
                    <div class="col-md-6 col-12">
                        <select name="" id="filterSelectedProducts">
                        </select>
                    </div>
                    
                </div>
                <div id="productResume-tables" style="margin-top: 30px;">
                </div>
            </div>
        </div>
    </div>
</div>