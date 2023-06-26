<div class="row">
    <div class="card col-12 box" style="max-height: 350px; overflow-y: scroll;overflow-x: hidden;">
        <div class="row">
            <div class="col-8 mt-3">
                <h4>Asignar Personal</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="serachInputDrag">
                <label for="searchInputPersonal">Búscar Personal: </label>
                <input type="text" name="" oninput="searchPersonalDrag()" id="searchInputPersonal">
            </div>
            <div class="row">
                <div class="col-md-6 col-12 mb-4 ">
                    <ul id="sortablePersonal1" class="connectedSortablePersonal" style="min-height: 150px;">
                        
                    </ul>
                </div>
                <div class="col-md-6 col-12">
                    <ul id="sortablePersonal2" class="connectedSortablePersonal" style="min-height: 150px;">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>