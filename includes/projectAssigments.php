<div class="card-body">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" id="getAvailableProducts" role="presentation">
        <!-- role="tab" aria-controls="products" aria-selected="true" data-bs-toggle="tab" -->
            <a class="nav-link projectAssigmentTab"  id="products-tab"  href="#" >
                Asignar productos
            </a>
        </li>
        <li class="nav-item" id="getAvailableVehicles" role="presentation">
        <!-- data-bs-toggle="tab" href="#vehicle"  aria-controls="vehicle" aria-selected="false" -->
            <a class="nav-link projectAssigmentTab" id="vehicle-tab" href="#" role="tab" >
                Asignar vehiculos
            </a>
        </li>
        <li class="nav-item" id="getAvailablePersonal" role="presentation">
        <!-- data-bs-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="false" -->
            <a class="nav-link projectAssigmentTab" id="personal-tab" href="#">
                Asignar personal
            </a>
        </li>
        <li class="nav-item" role="presentation" id="tableResumeView">
        <!-- data-bs-toggle="tab" href="#resumen" role="tab" aria-controls="resumen" aria-selected="false" -->
            <a class="nav-link projectAssigmentTab" id="resumen-tab" href="#">
                Resumen del Evento
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tabAssigments tabAssigments tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
            <?php include_once('./includes/dragAndDrop/dragProductos.php'); ?>
        </div>

        <div class="tabAssigments tab-pane fade" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
            <?php require_once('./includes/dragAndDrop/dragVehiculos.php'); ?>
        </div>

        <div class="tabAssigments tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
            <?php require_once('./includes/dragAndDrop/dragPersonal.php'); ?>
        </div>
        <div class="tabAssigments tab-pane fade" id="resumen" class role="tabpanel" aria-labelledby="resumen-tab">
            <?php  include_once('./includes/resumeProjectTable.php')?>
        </div>

    </div>
</div>
<!-- </div>
</div> -->