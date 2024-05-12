<?php
include('../../../path.php');
include(ROOT_PATH."/portal_app/db_functions.php");
include(ROOT_PATH."/portal_app/helpers/middleWare.php");
checkSessionStatus();

// error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors',1);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Turkuaz Performans Portalı</title>
    <!-- head links -->
    <?php include ROOT_PATH."/dashboard_assets/d_resources/head_links.php"?>

    <style>
    
      .home_row {
        position: relative;
        text-align: center;
        color: white;
      }

      .bottom_top_left {
        position: absolute;
        top: 8px;
        left: 16px;
      }


      .centered {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }

      .bottom_centered {
        position: absolute;
        top: 70%;
        left: 50%; 
      }

    </style>
  </head>

  <body>


    <!-- ########## START: LEFT PANEL ########## -->
    <?php include ROOT_PATH."/dashboard_assets/common_layouts/left_side_top.php"?>
    <?php include ROOT_PATH."/anket_sistemi/dashboard/hesabim/hesabim_layouts/sidebar.php"?>
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <?php include ROOT_PATH."/dashboard_assets/common_layouts/header.php"?>
    <!-- ########## END: HEAD PANEL ########## -->

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="sl-mainpanel">
      <nav class="breadcrumb sl-breadcrumb">
        <a class="breadcrumb-item" href="index.html">Hesabım</a>
        <span class="breadcrumb-item active">Dashboard</span>
      </nav>

      <div class="sl-pagebody">
        <div class="row row-sm " >
          <div class="home_row">
            <img src="<?php echo BASE_URL.'resources/images/Header_portal_002.jpg'?>" alt="Snow"
            style="width:100%; height: 50vh; opacity: 0.6;">
            
            <div class="bottom_top_left">
              <div class="container d-flex flex-column align-items-center " style="">
                <div class="col-md-12">
                  <h2 class="text-white">Turkuaz  </h2>
                  <h2 class="text-white">Anket Yönetim Sistemi</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        
      </div>
      <!-- sl-pagebody -->
      <?php include ROOT_PATH."/dashboard_assets/common_layouts/footer.php"?>
    </div>
    <!-- sl-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->

    <!-- script links -->
    <?php include ROOT_PATH."/dashboard_assets/d_resources/script_links.php"?>
  </body>
</html>
