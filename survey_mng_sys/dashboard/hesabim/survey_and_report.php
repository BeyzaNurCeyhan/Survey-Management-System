<?php
include('../../../path.php');
include(ROOT_PATH."/portal_app/db_functions.php");
include(ROOT_PATH."/portal_app/helpers/middleWare.php");
checkSessionStatus();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Turkuaz Portal Sistemi</title>

    <!-- head links -->
    <?php include ROOT_PATH."/dashboard_assets/d_resources/head_links.php"?>
    <style>
    canvas {
      max-width: 100%;
      height: auto;
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
        <!-- <div class="sl-page-title"><h5>Genel Raporlar</h5></div> -->
          <!-- <div class="row"> -->
          <div class="card pd-20 pd-sm-40 mg-t-30">
          <h6 class="card-body-title">Tüm Anketler</h6>
          <!-- <p class="mg-b-20 mg-sm-b-30">Cards with some options in the right corner of header of card.</p> -->

          <div class="row">

            <?php $workset = $workset = getPreviousWorksetsList('SURV'); ?>
            <?php
              if(isset($workset) && is_array($workset)){
              foreach ($workset as $key => $value) { ?>
                <div class="col-md-6 mg-t-30 mg-md-t-0">
                  <div class="card bd-0">
                    <div class="card-header card-header-default bg-info justify-content-between">
                      <h6 class="mg-b-0 tx-14 tx-white tx-normal"><?php echo $value["title"] ?></h6>
                      <div class="card-option tx-24">
                        <!-- <a href="" class="tx-white-8 hover-white mg-l-10"><i class="icon ion-ios-refresh-empty lh-0"></i></a> -->
                        <!-- <a href="" class="tx-white-8 hover-white mg-l-10"><i class="icon ion-ios-arrow-down lh-0"></i></a> -->
                        <a href="" class="tx-white-8 hover-white mg-l-10"><i class="icon ion-android-more-vertical lh-0"></i></a>
                      </div>
                    </div>

                    <div class="card-body bg-gray-200">
                      <strong>Başlık: </strong> <span><?php echo $value["title"] ?></span> <br>
                      <strong>Tanı: </strong> <span><?php echo $value["title"] ?></span> <br>
                      <strong>Geçerlilik Tar: </strong> <span><?php echo date("d-m-Y", strtotime($value["start_date"])) ."-". date("d-m-Y", strtotime($value["end_date"])) ?></span> <br>
                    </div><!-- card-body -->
                    <div class="card-footer bg-gray-300 d-flex justify-content-between">
                      <!-- <a href="" class="btn btn-secondary">Cancel</a> -->
                      <a href="genel_rapor.php?survey_id=<?php echo $value["id"]?>" class="btn btn-info">Raporu görüntüle</a>
                    </div>

                  </div><!-- card -->
                </div><!-- col-6 -->
              <?php }
            }?>
            
  
        </div>



        </div><!-- card -->
      </div><!-- sl-pagebody -->

      </div>
       <!-- FOOTER -->
       <?php include ROOT_PATH."/dashboard_assets/common_layouts/footer.php"?>
    </div>
   
    <!-- script links -->
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/jquery/jquery.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/popper.js/popper.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/bootstrap/bootstrap.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/jquery-ui/jquery-ui.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js'?>"></script>

    <!-- DATA TABLE -->
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/highlightjs/highlight.pack.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/lib/select2/js/select2.min.js'?>"></script>
    <script src="<?php echo BASE_URL.'dashboard_assets/js/starlight.js'?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      

      
    <!-- COMMON FONCTION SCRIPT  -->
    <script src="<?php echo BASE_URL.'dashboard_assets/dasboard_script_functions.js'?>"></script>

    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
        // Get all the rows in the table body
        let rows = document.querySelectorAll('#report_table tbody tr');

        // Define colors for each label
        let colors = [
          'rgba(75, 192, 192, 0.2)',
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ];

        // let labelColors = {
        //   'Genel Olumlu': 'rgba(75, 192, 192, 0.2)',
        //   'Genel Olumsuz': 'rgba(255, 99, 132, 0.2)',
        //   'Kayseri Olumlu': 'rgba(255, 205, 86, 0.2)',
        //   'Kayseri Olumsuz': 'rgba(54, 162, 235, 0.2)',
        //   'Incesu Olumlu': 'rgba(153, 102, 255, 0.2)',
        //   'Incesu Olumsuz': 'rgba(255, 159, 64, 0.2)'
        // };
        let labelColors = {
          'Genel Olumlu': 'green',
          'Genel Olumsuz': 'red',
          'Kayseri Olumlu': 'rgba(255, 205, 86, 0.5)',
          'Kayseri Olumsuz': 'rgba(54, 162, 235, 0.2)',
          'Incesu Olumlu': 'rgba(153, 102, 255, 0.7)',
          'Incesu Olumsuz': 'rgba(255, 159, 64, 0.2)'
        };

        // Loop through each row in the table body
        rows.forEach(function(row, index) {
          // Get data from the row
          let question = row.cells[0].textContent;
          let data = Array.from(row.cells).slice(1).map(cell => parseFloat(cell.textContent));

          // Create a canvas for the chart
          let canvas = document.createElement('canvas');
          canvas.width = 400; // Set the desired width
          canvas.height = 200; // Set the desired height
          row.cells[0].appendChild(canvas); // Append the canvas to the first cell

          // Create a bar chart with datalabels
          new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
              labels: ['Genel Olumlu', 'Genel Olumsuz', 'Kayseri Olumlu', 'Kayseri Olumsuz', 'Incesu Olumlu', 'Incesu Olumsuz'],
              datasets: [{
                label: question,
                data: data,
                // backgroundColor: colors[index], // Assign color based on index
                backgroundColor: data.map(label => labelColors[label]), // Map labels to colors
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              },
              plugins: {
                datalabels: {
                  anchor: 'end',
                  align: 'end',
                  formatter: function(value, context) {
                    return value.toFixed(2); // Display values with two decimal places
                  }
                }
              }
            }
          });
        });
      });


      // document.addEventListener('DOMContentLoaded', function() {
      //   // Loop through each row in the table body
      //   document.querySelectorAll('#report_table tbody tr').forEach(function(row) {
      //     // Get data from the row
      //     let question = row.cells[0].textContent;
      //     let data = Array.from(row.cells).slice(1).map(cell => parseFloat(cell.textContent));

      //     // Create a canvas for the chart
      //     let canvas = document.createElement('canvas');
      //     canvas.width = 400; // Set the desired width
      //     canvas.height = 200; // Set the desired height
      //     row.cells[0].appendChild(canvas); // Append the canvas to the first cell

      //     // Create a bar chart
      //     new Chart(canvas.getContext('2d'), {
      //       type: 'bar',
      //       data: {
      //         labels: ['Genel Olumlu', 'Genel Olumsuz', 'Kayseri Olumlu', 'Kayseri Olumsuz', 'Incesu Olumlu', 'Incesu Olumsuz'],
      //         datasets: [{
      //           label: question,
      //           data: data,
      //           // backgroundColor: 'rgba(75, 192, 192, 0.2)', // Set the background color
      //           // borderColor: 'rgba(75, 192, 192, 1)', // Set the border color
      //           borderColor: "#8e5ea2",
      //           fill: true,
      //           backgroundColor:'#8e5ea2',
      //           borderWidth: 1 // Set the border width
      //         }]
      //       },
      //       options: {
      //         scales: {
      //           y: {
      //             beginAtZero: true
      //           }
      //         },
      //         plugins: {
      //           datalabels: {
      //             anchor: 'end',
      //             align: 'end',
      //             formatter: function(value, context) {
      //               return value.toFixed(2); // Display values with two decimal places
      //             }
      //           }
      //         }
      //       }
      //     });
      //   });
      // });


      $(document).ready(function() {
    // Initialize DataTable
    $('#report_table').DataTable();

    // Loop through each row in the table body
    $('#report_table tbody tr').each(function(index) {
      // Get data from the row
      let question = $(this).find('td:first-child').text();
      let data = $(this).find('td:not(:first-child)').map(function() {
        return parseFloat($(this).text());
      }).get();

      // Create a canvas for the chart
      let canvas = document.createElement('canvas');
      canvas.width = 400; // Set the desired width
      canvas.height = 200; // Set the desired height
      $(this).find('td:first-child').append(canvas); // Append the canvas to the first cell

      // Create a bar chart with datalabels
      new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Genel Olumlu', 'Genel Olumsuz', 'Kayseri Olumlu', 'Kayseri Olumsuz', 'Incesu Olumlu', 'Incesu Olumsuz'],
          datasets: [{
            label: question,
            data: data,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            datalabels: {
              anchor: 'end',
              align: 'end',
              formatter: function(value, context) {
                return value.toFixed(2); // Display values with two decimal places
              }
            }
          }
        }
      });
    });

    // Add button for downloading PDF
    $('#report_table_wrapper').append('<button id="download_pdf">Download PDF</button>');

    // Handle click event for downloading PDF
    $('#download_pdf').on('click', function() {
      // Create a new jsPDF instance
      let pdf = new jsPDF();

      // Add the table to the PDF
      pdf.autoTable({ html: '#report_table' });

      // Save the PDF
      // Save the PDF
      pdf.save('report.pdf');
    });
  });

     


      
    </script>