<?php
include('../../../path.php');
include(ROOT_PATH."/portal_app/db_functions.php");
include(ROOT_PATH."/portal_app/helpers/middleWare.php");
// checkSessionStatus();

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
        <div class="sl-page-title"><h5>Genel Raporlar</h5></div>
          <!-- <div class="row"> -->
          <div class="card bd-0 pd-5 ">
            <div class="col-md-12" >
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Kullanıcı Bazlı Sonuçları</a></li>
                  <!-- <li class="nav-item"><a class="nav-link " href="#tab2" data-toggle="tab">Souçlar</a></li> -->
                </ul>
              </div>
              <!-- /.card-header -->

              <div class="card-body">
                <div id="processMessage"></div>
                <div class="tab-content">
                  <!-- ANA GROUP FORM TAB -->
                  <div class="active tab-pane" id="tab1">
                    <div class="row">
                      <div class="col-lg-6">
                        <!-- <div class="form-group"> -->
                          Toplam Katılım Sayısı : 
                          <?php $total_part = count(pdoGetAllDataRow("SELECT DISTINCT ssa.user_id from survey_sys_answers ssa 
                                                                JOIN perf_sys_work_sets psws ON ssa.survey_id = psws.id WHERE ssa.survey_id = 2"));
                            echo $total_part;
                            // displ($total_part);
                          ?>
                        <!-- </div> -->
                      </div>
                      
                      <!-- SORULAR -->
                      <div class="col-lg-12">
                        <div class="table-responsive"> 
                          <?php 
                          $surveySections = getSurveyQuestions(2); 
                          ?>
                          <table id="report_table" class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <!-- <th>No</th> -->
                                <th>Soru</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                              </tr>
                            </thead>
                            <?php 
                            foreach($surveySections as $seckey => $secRow){
                              echo '<tr><td colspan="5">'.$secRow['section_name'].'</td></tr>';
                              
                              $secQuestions = getSectionQuestions($secRow['id']);;
                              foreach ($secQuestions as $qkey => $quest) {?>
                                  <tr>
                                    <td><?php echo $quest['question_title'] ?></td>
                                    <td><?php ?></td>
                                    <td><?php  ?></td>
                                    <td><?php  ?></td>
                                    <td><?php  ?></td>
                                    <td><?php  ?></td>
                                  </tr>
                              <?php }
                            }?>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      
                      </div> 
                    </div> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- card -->
      </div><!-- sl-pagebody -->

      <?php 
        function getUserRawAnswerTot($uId){
          return pdoGetData('select sum(answer_value) as utotal_pnt from perf_sys_answers 
          where competence_definition_id in (
            select id from perf_sys_competence_definitions pscd 
              where competence_id IN (
                select id from perf_sys_competences 
                where work_set_id = (SELECT id FROM perf_sys_work_sets)
              )
          ) and user_id = :uid and participant_id = :pid ', ['uid'=> $uId, 'pid'=> $uId]);
        }
        function getContrRawAnswerTot($uId){
          return pdoGetData('select sum(answer_value) as utotal_pnt from perf_sys_answers 
          where competence_definition_id in (
            select id from perf_sys_competence_definitions pscd 
              where competence_id IN (
                select id from perf_sys_competences 
                where work_set_id = (SELECT id FROM perf_sys_work_sets)
              )
          ) and user_id = :uid and participant_id != :pid ', ['uid'=> $uId, 'pid'=> $uId]);
        }

        function getTotalUnderControl($pkno){
          return pdoGetData('select count(user_pkno) as under_ctl from controled_by_users cbu 
          where user_pkno = :pkno', ['pkno'=> $pkno]);
        }

        function getTotalWrokSetQuestion($setId){
          $request =  pdoGetData('select count(definition_name) as qtotal from perf_sys_competence_definitions pscd 
          join perf_sys_competences psc on psc.id  = pscd.competence_id 
          join perf_sys_work_sets psws on psws.id  = psc.work_set_id
          WHERE psws.id = :id', ['id'=> $setId]);
          return $request['qtotal'];
        }

        
        ?>

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

      
      <!-- COMMON FONCTION SCRIPT  -->
      <script src="<?php echo BASE_URL.'dashboard_assets/dasboard_script_functions.js'?>"></script>

    <script type="text/javascript">
      $(document).ready(function () {

        $(document).on('change', '#tab1_work_set', function(){
          workSetId = $(this).find(":selected").val();
          dataTosend = {
            workSetId: workSetId,
            _action:'GET_WORK_QUESTION_FORM'
          }
          ajaxProcessAll("", false, dataTosend, "#worksEtQuestionForm",
          "", "perf_sys_bg_process.php", "Form bulunamadı veya Arka planda işlem hatası olmuştur.")
        });

        // ADD_COMPETENCE
        $("#tab1_form").submit(function(event){
          event.preventDefault();
          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "SAVE_OTHER_COMPETENCE_PERFORMANCE");
          submitForm(form_data, 'perf_sys_bg_process.php', "#processMessage");
        });

        
        $(document).on('change', '#tab1_depart', function(){
          selectedVal = $(this).find(":selected").val();
          var pass_data = { 
            '_action': 'GET_REPORT',   
            'depart': selectedVal,
            'processPath': 'perf_sys_bg_process.php',
            'target': '#report_table',
            }; 
          getReport(pass_data)
        });

        function getReport(pass_data){
          console.log(pass_data)
          $.ajax({
            type: "POST",
            url:'perf_sys_bg_process.php',
            data: pass_data,
            success: function(response){
              console.log(response)
            if(response != "") 
              $(pass_data.target).append(response);
            else ;
            },
            failure: function(err){ console.error(err);}
          });
        }
 
      });
    </script>