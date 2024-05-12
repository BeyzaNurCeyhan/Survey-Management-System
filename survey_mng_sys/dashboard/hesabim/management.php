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
        <div class="sl-page-title"><h5>Anket Sistemi Yönetimi</h5></div>
          <!-- <div class="row"> -->
          <div class="card bd-0 pd-5 ">
            <div class="col-md-12" >
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#tab0" data-toggle="tab"> Anket Formları</a></li>
                  <li class="nav-item"><a class="nav-link " href="#tab1" data-toggle="tab"> Form Bölümleri</a></li>
                  <li class="nav-item"><a class="nav-link " href="#tab2" data-toggle="tab"> Sorular</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div id="processMessage"></div>
                <?php $workset = getPreviousWorksetsList('SURV');?>
                <div class="tab-content">

                  <!-- TAB 0 FORM -->
                  <div class="active tab-pane" id="tab0">
                    <form method="post" id="tab0_form">
                      <div class="row"> 
                        <div class="col-lg-12"><h4>Yeni Anket Formu Ekle</h4></div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Anket Formu Adı&nbsp; <strong class="tx-danger">*</strong></span>
                              <input name="tab0_workset_title" id="tab0_workset_title" type="text" class="form-control" required>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Tanım&nbsp; <strong class="tx-danger">*</strong></span>
                              <textarea name="tab0_workset_desc" id="tab0_workset_desc" class="form-control" required></textarea>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Başlangıç Tarihi&nbsp; <strong class="tx-danger">*</strong></span>
                              <input name="tab0_start_date" id="tab0_start_date" type="date" class="form-control" required>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-lg-4">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Bitiş Tarihi&nbsp; <strong class="tx-danger">*</strong></span>
                              <input name="tab0_end_date" id="tab0_end_date" type="date" class="form-control" required>
                            </div>
                          </div>
                        </div>
                        <!-- End Col 6 -->
                        
                        <div class="col-lg-4">
                          <input type="submit" name="workset_submit" id="workset_submit" value="Kaydet" class="btn btn-info"/>
                        </div>
                      </div> 
                    </form>
                    <hr>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered table-primary mg-b-0">
                            <thead>
                              <tr>
                                <th>Başlık</th>
                                <th>Başlangıç Tarihi</th>
                                <th>Bitiş Tarihi</th>
                                <th>İşlem</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php //$workset = getWorksets('SURV');
                                if(isset($workset) && is_array($workset)){ ?>
                                
                                  <?php foreach ($workset as $key => $value) {  ?>
                                    <tr>
                                      <td><?php echo $value['title'] ?></td>
                                      <td><?php echo date('d-m-Y', strtotime($value['start_date'])) ?></td>
                                      <td><?php echo date('d-m-Y', strtotime($value['end_date'])) ?></td>
                                      <td>
                                        <button class="btn-success updSet" id="setId<?php echo $value['id']?>"
                                          data-toggle="modal" data-target="#surv_sys_modal"><i class="fa fa-edit"></i></button>
                                      </td>
                                    </tr>
                                  <?php }
                                }?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- TAB 1 FORM -->
                  <div class="tab-pane" id="tab1">
                    <form method="post" id="tab1_form" >
                      <div class="row">
                        <div class="col-lg-5">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Anket Formu &nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="Choose one" tabindex="-1" aria-hidden="true"
                              id="tab1_work_set" name="tab1_work_set" required>
                                <option disabled value="" selected = "">Seçin</option>
                                <?php
                                if(isset($workset) && is_array($workset)){
                                foreach ($workset as $key => $value) {?>
                                  <option value="<?php echo $value["id"] ?>"><?php echo $value["title"] ?></option>
                                <?php }
                                }?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-7">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Form Bolümü&nbsp; <strong class="tx-danger">*</strong></span>
                              <input name="tab1_section_name" id="tab1_section_name" type="text" class="form-control" required>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Tanım&nbsp; <strong class="tx-danger">*</strong></span>
                              <textarea name="tab1_section_desc" id="tab1_section_desc" class="form-control" required></textarea>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <input type="submit" name="tab1_submit" id="tab1_submit" value="Kaydet" class="btn btn-info"/>
                      </div>
                    </form>
                    <hr>
                  </div>

                  <!-- TAB 2 FORM -->
                  <div class="tab-pane" id="tab2">
                    <form method="post" id="tab2_form">
                      <div class="row">
                        <!-- PORTAL SYSTEM MODULES LIST -->
                        <div class="col-lg-6">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Anket Formlaı&nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="Choose one" tabindex="-1" aria-hidden="true"
                                id="tab2_work_set" name="tab2_work_set" required>
                                <option disabled value="" selected>Seçin</option>
                                <?php 
                                  if(isset($workset) && is_array($workset)){
                                    foreach ($workset as $key => $value) {?>
                                      <option value="<?php echo $value["id"] ?>"><?php echo $value["title"] ?></option>
                                    <?php }
                                   }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Form Bolumleri&nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="" tabindex="-1" aria-hidden="true"
                                name="tab2_sections" id="tab2_sections" required>
                                  <option label=""></option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Cevap Tipi&nbsp; <strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="" tabindex="-1" aria-hidden="true"
                                name="tab2_question_type" id="tab2_question_type" required>
                                  <option value="std">Standart</option>
                                  <option value="radio">Tek Seçmeli</option>
                                  <option value="checkox">Çoktan seçmeli</option>
                                  <option value="text">Yazılı</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- Kullanici Adi -->
                        <div class="col-lg-9">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Soru İçeriği&nbsp; <strong class="tx-danger">*</strong></span>
                              <textarea name="tab2_question" id="tab2_question" class="form-control" required></textarea>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-layout-footer">
                        <input type="submit" name="tab2_submit" id="tab2_submit" value="Kaydet" class="btn btn-info"/>
                      </div>
                    </form>
                    <hr>
                    <h3><strong><span id="tab2_defTitle">Seçilen Anket Formu </span> Soruları</strong></h3>
                    <div class="row" id="tab2_questionsList">
                    </div>
                  </div>

                  <!-- TAB 3 FORM -->
                  <div class="tab-pane" id="tab3">
                    <form method="post" id="tab3_form">
                      <div class="row">
                        <!-- PORTAL SYSTEM MODULES LIST -->
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Çalışma Seti &nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="Choose one" tabindex="-1" aria-hidden="true"
                                id="tab3_work_set" name="tab3_work_set" required>
                                <option disabled value="" selected>Seçin</option>
                                <?php $workset = getAllWorkset();
                                if(isset($workset) && is_array($workset)){
                                foreach ($workset as $key => $value) {?>
                                  <option value="<?php echo $value["id"] ?>"><?php echo $value["title"] ?></option>
                                <?php }
                                }?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Yetkinlik tanımları &nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="" tabindex="-1" aria-hidden="true"
                              name="tab3_competences" id="tab3_competences">
                                <option label=""></option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">Yetkinlik tanımları &nbsp;<strong class="tx-danger">*</strong></span>
                              <select class="form-control select2 " data-placeholder="" tabindex="-1" aria-hidden="true"
                              name="tab3_competence_definition" id="tab3_competence_definition">
                                <option label=""></option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- Kullanici Adi -->
                        <div class="col-sm-9 mb-2">
                          <div class="card ">
                            <div class="card-header card-header info_card_header p-1">Tanım Sorusu &nbsp; <strong class="tx-danger">*</strong></div>
                              <div class="card-body p-1">
                              <div class="form-group">
                                <div class="input-group">
                                  <!-- <span class="input-group-addon">Tanım Sorusu &nbsp; <strong class="tx-danger">*</strong></span> -->
                                  <textarea name="tab3_definition_question" id="tab3_definition_question" class="form-control" required ></textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                          <div class="card ">
                            <div class="card-header card-header info_card_header p-1">Puan Derecesi &nbsp; <strong class="tx-danger">*</strong></div>
                              <div class="card-body p-1">
                                <div class="form-group">
                                  <select class="form-control" id="point_rate" name="point_rate" required>
                                    <option value="" disable selected >SEÇİN</option>  
                                    <option value="1">Çok yetersiz</option>
                                    <option value="2">yetersiz</option>
                                    <option value="3">orta derecede yeterli</option>
                                    <option value="4">iyi derecede yeterli</option>
                                    <option value="5">çok iyi derecede yeterli</option>
                                  </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>

                      <div class="form-layout-footer">
                        <input type="submit" name="tab3_submit" id="tab3_submit" value="Kaydet" class="btn btn-info"/>
                      </div>
                    </form>
                    <hr>
                    <h3><strong><span id="qTitle">Tanım</span> Soruları</strong></h3>
                    <div class="row" >
                      <!-- PORTAL SYSTEM MODULES LIST -->
                      <div class="col-lg-12" id="comp_ded_rsults">
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div><!-- card -->
      </div><!-- sl-pagebody -->

    <!-- LARGE MODAL -->
    <div id="surv_sys_modal" class="modal fade">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-size-sm">
          <div class="modal-header pd-x-20">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold" id="surv_sys_modal_title">Message Preview</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pd-20" id="surv_sys_modal_body">
            <h4 class=" lh-3 mg-b-20"><a href="" class="tx-inverse hover-primary">Why We Use Electoral College, Not Popular Vote</a></h4>
            <p class="mg-b-5">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </p>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->

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

        // ADD_WORKSET
        $("#tab0_form").submit(function(ev){
          ev.preventDefault();
          // if($.trim($('#tab1_competance_name').val()).length == 0){
          //   alert("Yetkiinlik tanımı seçmeniz gerekmektedir.");
          //   $('#tab1_competance_name').focus();
          //   return false;
          // }
          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "ADD_WORKSET");
          submitForm(form_data, 'surv_sys_bg_process.php', "#processMessage");
        });

        // ADD_COMPETENCE
        $("#tab1_form").submit(function(event){
          event.preventDefault();
          if($.trim($('#tab1_work_set').val()).length == 0){
            alert("Yetkiinlik tanımı seçmeniz gerekmektedir.");
            $('#tab1_work_set').focus();
            return false;
          }
          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "ADD_SURVEY_SECTION");
          submitForm(form_data, 'surv_sys_bg_process.php', "#processMessage");
        });

        $(document).on('change', '#tab1_work_set', function(){
          selectedVal = $(this).find(":selected").val();
    
          var pass_data = { '_action': 'LOAD_SURVEY_SECTION',   
            'processPath': 'surv_sys_bg_process.php',
            'target': '#tab1_survey_sections',
            'tab1_work_set':selectedVal
          }; 
          $.ajax({
              type: "POST",
              url:'surv_sys_bg_process.php',
              data: pass_data,
              success: function(response){
                console.log(response)
              if(response != "") $(pass_data.target).html(response);
              else $(pass_data.target).html(response);
              },
              failure: function(err){ console.error(err);}
            });
          // loadListInDiv(pass_data)
        });

        // TAB 2
        $(document).on('change', '#tab2_work_set', function(){
            selectedVal = $(this).find(":selected").val();
            var pass_data = { '_action': 'GET_WORKSET_SECTIONS',   
              'processPath': 'surv_sys_bg_process.php',
              'target': 'tab2_sections',
              'workSetId':selectedVal};
              
            loadSelectOptionList(pass_data);

            var pass_data2 = { '_action': 'LOAD_SURVEY_SECTION_QUESTION',   
              'processPath': 'surv_sys_bg_process.php',
              'target': '#tab2_questionsList',
              'workSetId':selectedVal,};
            console.log(pass_data2)
            loadSelectOptionList(pass_data2);
        });

        // $(document).on('change', '#tab2_competences', function(){
        //     selectedVal = $(this).find(":selected").val();
        //     $('#tab2_defTitle').text($(this).find(":selected").text())
        //     var pass_data = { '_action': 'GET_COMPETENCE_DEFINITION',   
        //       'processPath': 'surv_sys_bg_process.php',
        //       'target': '#comp_defitions',
        //       'competenceId':selectedVal
        //     }; 
            
        //       // console.log(pass_data)
        //     // loadListInDiv(pass_data)
        // });

        // YETKI EKLEME
        $("#tab2_form").submit(function(event){
          event.preventDefault();

          // if($.trim($('#yetki_adi').val()).length == 0){
          //   alert("Yetki adı yazmanız gerekmektedir.");
          //   $('#grup_adi').focus();
          //   return false;
          // }

          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "ADD_SURVEY_QUESTION");
          submitForm(form_data, "surv_sys_bg_process.php", "#processMessage")
        });

      
        // TAB 3
        $(document).on('change', '#tab3_work_set', function(){
            selectedVal = $(this).find(":selected").val();
            var pass_data = { 'action': 'GET_WORKSET_COMPETENCES',   
              'processPath': '../getList.php',
              'target': 'tab3_competences',
              'workSetId':selectedVal,};
            loadSelectOptionList(pass_data);

            // LOAD QUESTIONS
        });

        $(document).on('change', '#tab3_competences', function(){
            selectedVal = $(this).find(":selected").val();
            var pass_data = { 'action': 'GET_COMPETENCE_DEFINITIONS',   
              'processPath': '../getList.php',
              'target': 'tab3_competence_definition',
              'competenceId':selectedVal,}; 
            loadSelectOptionList(pass_data);
        });

        // YETKI EKLEME
        $("#tab3_form").submit(function(event){
          event.preventDefault();

          // if($.trim($('#yetki_adi').val()).length == 0){
          //   alert("Yetki adı yazmanız gerekmektedir.");
          //   $('#grup_adi').focus();
          //   return false;
          // }

          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "ADD_DEFINITION_QUESTION");
          submitForm(form_data, "surv_sys_bg_process.php", "#processMessage")
          getQuestions($("#tab3_competence_definition option:selected").val())
        });

        $(document).on('change', '#tab3_competence_definition', function(){
            selectedVal = $(this).find(":selected").val();
            $('#qTitle').text($(this).find(":selected").text())
            getQuestions($("#tab3_competence_definition option:selected").val())
            // loadSelectOptionList(pass_data);
            // ajaxProcessAll(islemTipi, barProcess, pass_data, viewTarget, title, surv_sys_bg_process.php, errorMsg)
        });

        $(document).on('click', '.updSet', function(){
          setId = this.id;
          console.log(setId)
    
          var pass_data = { '_action': 'GET_WORKSET_DATA',   
            'processPath': 'surv_sys_bg_process.php',
            'target': '#surv_sys_modal_body',
            'set_id': setId.substr(5)
            // 'tab1_work_set':selectedVal
          }; 
          $.ajax({
              type: "POST",
              url:'surv_sys_bg_process.php',
              data: pass_data,
              success: function(response){
                console.log(response)
                if(response != "") $(pass_data.target).html(response);
                else $(pass_data.target).html(response);
              },
              failure: function(err){ console.error(err);}
            });
        });

        // $("#tab0_form").submit(function(ev){
        $(document).on('submit', '#workset_upd_form', function(ev){
          ev.preventDefault();
          var request_method = $(this).attr("method");
          var form_data = new FormData(this);
          form_data.append("_action", "UPDATE_WORKSET");
          console.log(form_data)

          submitForm(form_data, 'surv_sys_bg_process.php', "#processMessage");
        });

        // getCompetences($("#tab2_competences option:selected").val())
        function loadListInDiv(pass_data){
          console.log(pass_data)
          $.ajax({
            type: "POST",
            url:pass_data.process_path,
            data: pass_data,
            success: function(response){
              console.log(response)
            if(response != "") $(pass_data.target).html(response);
            else $(pass_data.target).html(response);
            },
            failure: function(err){ console.error(err);}
          });
        }

        function getQuestions(defId){
          var pass_data = { '_action': 'GET_DEFINITIONS_QUETIONS',   
              'processPath': 'surv_sys_bg_process.php',
              'target': '#comp_ded_rsults',
              'definitionId':defId}; 
              console.log(pass_data)
              $.ajax({
                type: "POST",
                url:'surv_sys_bg_process.php',
                data: pass_data,
                success: function(response){
                  console.log(response)
                if(response != "") $(pass_data.target).html(response);
                else $(pass_data.target).html(response);
                },
                failure: function(err){ console.error(err);}
              });
        }
      });
    </script>