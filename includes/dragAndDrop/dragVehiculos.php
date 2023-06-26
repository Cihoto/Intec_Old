<div class="row mb-4">
    <div class="card col-12 box" style="max-height: 350px; overflow-y: scroll;overflow-x: hidden;">
        <div class="row">
            <div class="col-8 mt-3">
                <h4>Asignar Vehículo</h4>
            </div>
        </div>
        <div class="card-body" id="DragVehiculos">
             <div class="serachInputDrag">
                <label for="searchInputVehiculo">Búscar Vehiculos: </label>
                <input type="text" name="" oninput="searchVehiculoDrag()" id="searchInputVehiculo">
            </div>
            <div class="row mb-4">
                <div class="col-md-6 col-12 mb-4">
                    <ul id="sortable1" class="connectedSortable" style="min-height: 150px;">
                    </ul>
                </div>
                <div class="col-md-6 col-12">
                    <ul id="sortable2" class="connectedSortable" style="min-height: 150px;">
                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>