<!DOCTYPE html>
<html lang="en">
  
<?php 
require_once('./includes/head.php');
$active = 'dashboard';
?>

  <body>
    <script src="./assets/js/initTheme.js"></script>
    <div id="app">

        <?php require_once('./includes/sidebar.php') ?>

      <div id="main">
        <header class="mb-3">
          <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
          </a>
        </header>

        <div class="page-header">
          <h3>Dashboard</h3>
        </div>
        
        <div class="page-content">
          <!-- main section -->
          <!-- <section class="row"> -->
            <!-- info central grid -->
            <div class="col-12">
            <!-- primer  -->
              <div class="row">

              <!-- eventos -->
                <div class="col-12 col-lg-4 col-sm-6">
                  <div class="card">
                    <div class="card-body px-4 py-4-5">
                      <a href="proyectos.php">
                        <div class="row">
                          <!-- icono del cuadro info -->
                          <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center">
                            <!-- color recuadro -->
                            <div class="stats-icon purple mb-2">
                              <!-- logo interno icono -->
                              <i class="iconly-boldCalendar"></i>
                            </div>
                          </div>
                          <!-- fin icono -->
                          <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7  d-flex justify-content-center align-items-center">
                            <h5 class="text-muted font-semibold">
                              Proximos Eventos Pushed
                            </h5>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- fin eventos -->

                <!-- inventario -->
                <div class="col-12 col-lg-4 col-sm-6">
                  <div class="card">
                    <div class="card-body px-4 py-4-5">
                    <a href="inventario.php">
                      <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center">
                          <div class="stats-icon blue mb-2">
                            <i class="fa-solid fa-warehouse"></i>
                          </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 d-flex justify-content-center align-items-center">
                          <h5 class="text-muted font-semibold">Inventario</h5>
                        </div>
                      </div>
                    </a>
                    </div>
                  </div>
                </div>
                <!-- fin inventario -->

                <!-- calendario -->
                <div class="col-12 col-lg-4 col-sm-6">
                  <div class="card">
                    <div class="card-body px-4 py-4-5">
                      <a href="calendario.php">
                        <div class="row">
                          <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center">
                            <div class="stats-icon green mb-2">
                            <i class="fa-solid fa-calendar-day"></i>
                            </div>
                          </div>
                          <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 d-flex justify-content-center align-items-center">
                            <h5 class="text-muted font-semibold">Calendario</h5>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- fin calendario -->

              </div>

              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Urgencias</h4>
                    </div>
                    <div class="card-body">
                      tabla con info de los equipos con reparaciones pendientes
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-12 col-xl-4">
                  <div class="card">
                    <div class="card-header">
                      <h4>Profile Visit</h4>
                    </div>
                    <div class="card-body">
                      Info de los prox eventos
                    </div>
                  </div>
                </div>

                <div class="col-12 col-xl-8">
                  <div class="card">
                    <div class="card-header">
                      <h4>Algo que agregar</h4>
                    </div>
                    <div class="card-body">
                      body
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- info in grid right -->
            <!-- <div class="col-12">
              <div class="card">
                <div class="card-body py-4 px-4">
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl">
                      <img src="assets/images/faces/1.jpg" alt="Face 1" />
                    </div>
                    <div class="ms-3 name">
                      <h5 class="font-bold">John Duck</h5>
                      <h6 class="text-muted mb-0">@johnducky</h6>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h4>Recent Messages</h4>
                </div>
                <div class="card-content pb-4">
                  <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                      <img src="assets/images/faces/4.jpg" />
                    </div>
                    <div class="name ms-4">
                      <h5 class="mb-1">Hank Schrader</h5>
                      <h6 class="text-muted mb-0">@johnducky</h6>
                    </div>
                  </div>
                  <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                      <img src="assets/images/faces/5.jpg" />
                    </div>
                    <div class="name ms-4">
                      <h5 class="mb-1">Dean Winchester</h5>
                      <h6 class="text-muted mb-0">@imdean</h6>
                    </div>
                  </div>
                  <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                      <img src="assets/images/faces/1.jpg" />
                    </div>
                    <div class="name ms-4">
                      <h5 class="mb-1">John Dodol</h5>
                      <h6 class="text-muted mb-0">@dodoljohn</h6>
                    </div>
                  </div>
                  <div class="px-4">
                    <button
                      class="btn btn-block btn-xl btn-outline-primary font-bold mt-3"
                    >
                      Start Conversation
                    </button>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h4>Visitors Profile</h4>
                </div>
                <div class="card-body">
                  <div id="chart-visitors-profile"></div>
                </div>
              </div>
            </div> -->

          <!-- </section> -->
        </div>
        
        <?php require_once('./includes/footer.php') ?>

      </div>
    </div>

    <?php require_once('./includes/footerScriptsJs.php') ?>
    
  </body>
</html>
