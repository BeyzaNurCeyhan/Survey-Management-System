<?php
include('../../../path.php');
include(ROOT_PATH."/portal_app/db_functions.php");

include ROOT_PATH.'/oneri_sistemi/dashboard/hesabim/hesabim_layouts/oneri_template_functions.php';

$errors = array();
$success = array();
$infos = array();

$output ='';
if(isset($_POST["_action"])){
    
    if ($_POST["_action"] == "ADD_WORKSET") {
        // displ($_POST);
        $exist = pdoGetData("SELECT * from perf_sys_work_sets WHERE title = :_title", 
        ["_title"=>$_POST["tab0_workset_title"]]);
        if(is_array($exist) && count($exist)>0){
            array_push($errors, 'Anket formu Kayıtlıdır!');
            displayMessages($success, $errors, $infos);
        }
        else{
            $date = date('Y-m-d');
            $reslt = pdoInsertExec("INSERT INTO perf_sys_work_sets (ref, title, description, start_date, end_date, user_id, status, created_at)
                                VALUES(:ref, :title, :desc, :start_date, :end_date, :user_id, :status, :_create_at)",
                                ['ref'=> 'SURV', 'title'=> $_POST['tab0_workset_title'],
                                'desc'=> $_POST["tab0_workset_desc"], 'start_date'=> $_POST['tab0_start_date'], 
                                'end_date'=> $_POST['tab0_end_date'], 'user_id'=> $_SESSION['user_id'], 'status'=> 1, 
                                '_create_at'=> $date]);
            if($reslt !== false){
                array_push($success, 'Kayıt başarılı.');
                displayMessages($success, $errors, $infos);
            } // SORGU YANLIS ISE
            else{
                array_push($infos, 'Kayıt başarısız.');
                displayMessages($success, $errors, $infos);
            }
        }
    }

    if ($_POST["_action"] == "ADD_SURVEY_SECTION") {
        // `id`, `section_name`, `description`, `survey_id`
        // displ($_POST);
        $exist = pdoGetData("SELECT * from survey_sys_sections WHERE section_name = :_sec_name AND survey_id =:_wid", 
        ["_sec_name"=>$_POST["tab1_section_name"], '_wid'=>$_POST['tab1_work_set']]);
        if(is_array($exist) && count($exist)>0){
            array_push($errors, 'Anket formu için bolüm Kayıtlıdır!');
            displayMessages($success, $errors, $infos);
        }
        else{
            $date = date('Y-m-d');
            $reslt = pdoInsertExec("INSERT INTO survey_sys_sections (section_name, description, survey_id, created_at)
                                VALUES(:_sec_name, :desc, :_wid,  :_create_at)",
                                ['_sec_name'=> $_POST["tab1_section_name"], 'desc'=> $_POST['tab1_section_desc'], 
                                '_wid'=> $_POST['tab1_work_set'], '_create_at'=> $date]);
            if($reslt !== false){
                array_push($success, 'Kayıt başarılı.');
                displayMessages($success, $errors, $infos);
            } // SORGU YANLIS ISE
            else{
                array_push($infos, 'Kayıt başarısız.');
                displayMessages($success, $errors, $infos);
            }
        }
    }

    if ($_POST["_action"] == "LOAD_SURVEY_SECTION") {
        // displ($_POST);
        $exist = pdoGetAllDataRow("SELECT * from survey_sys_sections WHERE  survey_id =:_wid", 
        ['_wid'=>$_POST['tab1_work_set']]);
        $output = '';
        if(is_array($exist) && count($exist)>0){
            $output .= '
                <div class="col-md-12"><h3>Bolümler</h3></div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-primary mg-b-0">
                            <thead>
                                <tr>
                                    <th>Bölüm adı</th>
                                    <th>Tanımı</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
            ';
                    foreach ($exist as $key => $value) {
                        $output .= '<tr><td>'. $value['section_name'] .'</td>
                        <td>'. $value['description'] .'</td>
                        <td></td></tr>';
                    }
            $output .= '
                        </tbody>
                    </table>
                </div>
            </div>
            ';
        }
        else{
            $output .= '<div class="alert alert-info">Anket Formu icin bulumler bulunmamaktadir</div>';
        }
        echo $output;
    }

    if($_POST["_action"] === 'GET_WORKSET_SECTIONS'){
        // displ($_POST);
        $workSetCompetences =  getWorkSetQuetions($_POST['workSetId']);  
        $output = "<option value='' selected>Seçin</option>";
        if(isset($workSetCompetences) && is_array($workSetCompetences)){ // displ($users);
            foreach($workSetCompetences as $row){
            $output .= "<option value='".$row['id']."'>".$row['section_name']."</option>";
            }
        }
        echo json_encode($output);
    }

    if ($_POST["_action"] == "ADD_SURVEY_QUESTION") {
        // displ($_POST);
        // `question`, `ans_toption`, `type`, `section_id`, `survey_id` tab2_question_type
        $exist = pdoGetData("SELECT * from survey_sys_questions WHERE question_title = :_qtitle AND section_id =:_secid AND survey_id =:_survid", 
        ["_qtitle"=>$_POST["tab2_question"], '_secid'=>$_POST['tab2_sections'], '_survid'=>$_POST['tab2_work_set']]);
        if(is_array($exist) && count($exist) > 0){
            array_push($errors, 'Anket formun bölümü aynı soruyu Kayıtlıdır ');
            displayMessages($success, $errors, $infos);
        }
        else{
            // displ($_POST);
            $date = date('Y-m-d');  
            if($_POST['tab2_question_type'] === 'std'){
                $reslt = pdoInsertExec("INSERT INTO survey_sys_questions (question_title, ans_option, type, section_id, survey_id, created_at)
                                VALUES(:_qtitle, :_ans_opt, :_type, :_secid, :_survid, :_create_at)",
                                ['_qtitle'=> $_POST["tab2_question"], "_ans_opt"=> '', '_type'=> $_POST['tab2_question_type'],
                                '_secid'=> $_POST['tab2_sections'], '_survid'=> $_POST['tab2_work_set'], '_create_at'=> $date]);
                if($reslt !== false){
                    array_push($success, 'Kayıt başarılı.');
                    displayMessages($success, $errors, $infos);
                } // SORGU YANLIS ISE
                else{
                    array_push($infos, 'Kayıt başarısız.');
                    displayMessages($success, $errors, $infos);
                }
            }
            // For other questıon types
            else {
                $reslt = pdoInsertExec("INSERT INTO survey_sys_questions (question_title, ans_option, type, section_id, survey_id, created_at)
                                VALUES(:_qtitle, :_ans_opt, :_type, :_secid, :_survid, :_create_at)",
                                ['_qtitle'=> $_POST["tab2_question"], "_ans_opt"=> '', '_type'=> $_POST['tab2_question_type'],
                                '_secid'=> $_POST['tab2_sections'], '_survid'=> $_POST['tab2_work_set'], '_create_at'=> $date]);
                if($reslt !== false){
                    array_push($success, 'Kayıt başarılı.');
                    displayMessages($success, $errors, $infos);
                } // SORGU YANLIS ISE
                else{
                    array_push($infos, 'Kayıt başarısız.');
                    displayMessages($success, $errors, $infos);
                }
            }
        }
    }

    if ($_POST["_action"] == "LOAD_SURVEY_SECTION_QUESTION") {
        // displ($_POST);
        $exist = pdoGetAllDataRow("SELECT * from survey_sys_sections WHERE  survey_id =:_wid", 
        ['_wid'=>$_POST['workSetId']]);
        $output = '';
        if(is_array($exist) && count($exist)>0){
            $output .= '
                <div class="col-md-12"><h3>Bolümler</h3></div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-primary mg-b-0">
                            <tbody>
            ';
                    foreach ($exist as $key => $value) {
                        $output .= '<tr><td colspan="2">'. $value['section_name'] .'</td></tr>';

                        $questions = pdoGetAllDataRow("SELECT * from survey_sys_questions WHERE section_id = :_secid AND survey_id =:_survid ", 
                            ['_secid'=>$value['id'], '_survid'=>$_POST['workSetId']]);
                        
                        foreach ($questions as $key => $value) {
                            $output .= '<tr><td>'. $value['question_title'] .'</td>
                                        <td></td></tr>';
                        }
                    }
            $output .= '
                        </tbody>
                    </table>
                </div>
            </div>
            ';
            
        }
        else{
            $output .= '<div class="alert alert-info">Anket Formu icin bulumler bulunmamaktadir</div>';
        }
        echo $output;
    }

    if ($_POST["_action"] == "GET_SURVEY_QUESTION_FORM") {
        // displ($_POST);
        $surveySections = getSurveyQuestions($_POST['workSetId']); 
        // displ($surveySections);
        if(isset($surveySections) && is_array($surveySections)){ // displ($users);
            $output = "";
            foreach($surveySections as $seckey => $secRow){
            $radioName = 'sec'.$secRow['id'];
            
            $output .= '
                <div class="card">
                    <div class="card-header text-center" role="tab" id="heading'.$secRow['id'].'">
                        <h3 class="mg-b-0">
                            <a data-toggle="collapse" title="Soruları Katlamak için tıkla" data-parent="#accordion" href="#collapse'.$secRow['id'].'" aria-expanded="true"
                                aria-controls="collapse'.$secRow['id'].'" class="tx-gray-800 transition ">'.$secRow['section_name'].'
                            </a>
                        </h3>
                    </div>
                    <div id="collapse'.$secRow['id'].'" class="collapse show" role="tabpanel" aria-labelledby="heading'.$secRow['id'].'" style="">
                        <div class="card-body text-20">';

                        // $output .= '<input type="text"  value="'.$radioName.'" name="radioNames[]">';
                        
                        $secQuestions = getSectionQuestions($secRow['id']);
                        // displ($secQuestions);
                        foreach($secQuestions as $keyRow => $quesVal){
                            $questRadioName = 'quest'.$quesVal['id'];
                            $output .= '
                            <div class="card bd-0">
                                <div class="card-header card-header-default bg-default">'.($seckey+1).'.'.($keyRow+1).' - '.$quesVal['question_title'].'</div>
                                <div class="card-body bd bd-t-0">';
                                
                                $output .= '<input type="hidden"  value="'.$questRadioName.'" name="questRadioName[]">';
                                
                                $ck = '';
                                if($quesVal['type'] === 'std'){
                                    
                                    $standartAnsers = getStandartAnsers();
                                    foreach ($standartAnsers as $key => $stdVal) {
                                        $check = existUserAnser($_POST['user_id'], $quesVal['id'],$_POST['workSetId'], $stdVal['value']);

                                        $output .= '
                                            <div class="row">
                                                <div class="col-sm-12 mb-2">
                                                    <div class="form-group">
                                                        <label class="rdiobox">
                                                            <input type="radio" '.(($check)? 'checked' :'').' name="'.$questRadioName.'" value="'.$stdVal['value'].'" >
                                                            <span>'.$stdVal['name'].'</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                                elseif($quesVal['type'] === 'text'){
                                    $ansData = pdoGetData("SELECT * FROM survey_sys_answers WHERE user_id = :u_id AND survey_id = :surv_id AND question_id = :qu_id", 
                                                        ['u_id'=> $_POST['user_id'], 'surv_id'=> $_POST['workSetId'], 'qu_id'=>$quesVal['id']]);
                                    $text_ans = $ansData['answer'] ?? 'null';
                                    $output .= '
                                        <div id="textfield_s_clone">
                                            <div class="callout callout-info">
                                                <textarea name="'.$questRadioName.'" id="" class="form-control" placeholder="Buraya bir şey yazınız...">'.$text_ans.'</textarea>
                                            </div>
                                        </div>
                                    ';
                                }
                                $output .= '
                                    </div>
                                </div>
                            ';
                        }
                $output .= '
                        <div class="card-footer"> 
                        </div>
                        </div>
                        
                    </div>
                </div>
                ';
            }
            $output .= '
                <div class="col-lg-12 mt-3">
                    <input type="submit" name="grup_adi_submit" id="grup_adi_submit" value="Hepsini Kaydet" class="btn btn-info"/>
                </div>
            ';
            echo $output;
        }
    }

    if ($_POST["_action"] == "GET_USER_TAKEN_NO") {
        $exist = pdoGetData("SELECT DISTINCT taken_no FROM survey_sys_answers ssa 
                            JOIN perf_sys_work_sets psws ON ssa.survey_id = psws.id
                            WHERE ssa.survey_id = :surv_id AND ssa.user_id = :u_id AND 
                            CURDATE() BETWEEN start_date AND end_date", ['surv_id'=>$_POST['workSetId'] , 'u_id'=>$_SESSION['user_id']]);
        if(isset($exist) && is_array($exist) && count($exist) > 0)
            echo $exist['taken_no'];
        else echo getTakenNo(); 
    }

    if ($_POST["_action"] == "GET_USER_PROGRESS") {
        // displ($_POST);
        $totSave = getTotalUserAnswers($_POST['workSetId'], $_POST['user_id']); 
        $total_questions = getTotalQuestion($_POST['workSetId']) ;
        $pers = round(($totSave / $total_questions)*100, 0) ;
        // echo " qto $total_questions ----  ans tot $totSave ";
        echo '<div class="progress pd-2">
            <h5><b>Cevalama oranınız : </b></h5>
            <div class="progress-bar bg-success progress-bar-lg wd-'.round($pers,0).'p text-center pd-8" style="height: 30px; font-size: 18px;  text-align: center;" role="progressbar" aria-valuenow="'.round($pers,0).'" aria-valuemin="0" aria-valuemax="100">'.round($pers,0).'%</div>
        </div>' ;
    }

    if ($_POST["_action"] == "SAVE_SURVEY_ANSWER") {
        // displ($_POST);
        $date = date('Y-m-d'); // CEVAP TARIHI
        if($_POST["user_id"] != ''){
            $totSave = 0; // KLULLANICI İÇİN TOPLAM CEVAPLADIĞI VEYA GÜNCELLEDİĞİ SAYISIDIR 
            //IF COMPETENCE ARRAY IS ET
            if(isset($_POST["questRadioName"]) && !empty($_POST["questRadioName"]) ){
                foreach($_POST["questRadioName"] as $val ){ // GO TTHROUGH EACH QUETION
                    // SORU SORU İÇİN HERHANGİ CEVAP SEÇİLDİYSE
                    if(isset($_POST[$val]) && !empty($_POST[$val])){
                        $questId = substr($val,5); // SORU İDsi ÖIKARMA
                        $exist = pdoGetData("SELECT * from survey_sys_answers ssa 
                                            JOIN perf_sys_work_sets psws ON ssa.survey_id = psws.id
                                            WHERE ssa.user_id = :u_id AND question_id =:qu_id AND ssa.survey_id = :surv_id
                                            AND CURDATE() BETWEEN psws.start_date AND psws.end_date", 
                        ["u_id"=>$_POST["user_id"], 'qu_id'=>$questId, 'surv_id'=>$_POST['tab1_work_set']]);
                        // existUserAnser FUNKSİYONU İLE SORU İÇİN CAVAP KAYITLI OLUP OLMADIĞINI KONTROL EDİYOR VARSA TRUE DÖNÜYOR AKSİNE FALSE
                        if(isset($exist) && is_array($exist) ){
                            $reslt = pdoUpdateExec("UPDATE survey_sys_answers set answer =:asw_value, taken_no =:taken_no, sirket =:sirket 
                                            WHERE user_id = :u_id AND question_id =:qu_id AND survey_id = :surv_id ",
                                            ['asw_value'=>$_POST[$val], 'taken_no'=>$_POST['taken_no'],  'sirket'=>$_POST['tab1_sirket'],  
                                            'u_id'=> $_POST["user_id"], 'qu_id'=> $questId, 'surv_id'=> $_POST["tab1_work_set"] ]);
                            ++$totSave; 
                        }
                        else {
                            $reslt = pdoInsertExec("INSERT INTO survey_sys_answers (user_id, taken_no, survey_id, question_id, answer, sirket, created_at)
                                            VALUES(:u_id, :taken_no, :surv_id, :q_id, :asw_value, :sirket, :_date)",
                                            ['u_id'=> $_POST["user_id"],'taken_no'=> $_POST["taken_no"], 'surv_id'=>$_POST['tab1_work_set'], 
                                            'q_id'=> $questId, 'asw_value'=> $_POST[$val], 'sirket'=>$_POST['tab1_sirket'], '_date'=> $date]);
                            echo " $questId Inserted ... result $reslt ";
                            if($reslt !== false){
                                // echo " $questId saved+++ ";
                                ++$totSave;
                            }
                        }
                    }
                }

                // $query = 'SELECT * FROM survey_sys_answers 
                //             WHERE user_id = :u_id  
                //                 AND question_id IN 
                //                     (SELECT id from survey_sys_questions 
                //                         WHERE survey_id = (SELECT id FROM perf_sys_work_sets WHERE id = :workSet))';
                // $userExistAnsers = pdoGetAllDataRow($query, ['u_id'=> $_POST["user_id"], 'workSet'=>$_POST['tab1_work_set']]);
                
                 
                $total_questions = getTotalQuestion($_POST['tab1_work_set']); // SISTEMDE TOPLAM SORULAR
                $total_ans = $totSave; // TOPLAM KAYDEILEN SORULAR

                $pers = ($totSave/$total_questions) * 100; // Yüzde ortalaması
                if($pers == 100 )
                    array_push($success, '<h4>Toplam '.$totSave.'/'.$total_questions.' yani %'.round($pers,1).' Soruyu cevapladınız. Katıldğınız iiçin teşekkür ederiz.</h4>');
                else 
                    array_push($infos, '<h4> Toplam '.$totSave.'/'.$total_questions.' yani %'.round($pers,1).' Soruyu cavapladınız. sonra tamamlamanızı rica olunur.</h4>');
                
                displayMessages($success, $errors, $infos);
            }
        }
        else {
            array_push($infos, 'Lütfen önce giriş yapmalısınız.');
            displayMessages($success, $errors, $infos);
        }
        
    }
    

    if ($_POST["_action"] == "GET_FORM") {
        $workSetCompetences = getWorkSetCompetences($_POST["workSetId"]);  
        if(isset($workSetCompetences) && is_array($workSetCompetences)){
            $output = "";
                foreach($workSetCompetences as $key => $row){
                    $output .= '
                    <div class="card">
                        <div class="card-header text-center" role="tab" id="heading'.$row['id'].'" >
                            <h3 class="mg-b-0">
                                <a data-toggle="collapse" title="Soruları Katlamak için tıkla" data-parent="#accordion" 
                                    href="#collapse'.$row['id'].'" aria-expanded="true" 
                                    aria-controls="collapse'.$row['id'].'" class="tx-gray-800 transition ">'.($key+1).'- '.$row['competence_name'].'
                                </a>
                            </h3>
                        </div>
                        <div id="collapse'.$row['id'].'" class="collapse show" role="tabpanel" aria-labelledby="heading'.$row['id'].'" style="">
                        <div class="card-body text-20">';
                            $compDefs = getCompetenceDefiction($row['id']);
                            foreach($compDefs as $keyRow => $compDVal){
                            $radioName = 'comDef'.$compDVal['id'];
                            $output .= '
                                <div class="card bd-0">
                                <div class="card-header card-header-default bg-default">'.($key+1).'.'.($keyRow+1).'- '.$compDVal['definition_name'].'</div>
                                <div class="card-body bd bd-t-0">
                                    <input type="hidden"  value="'.$radioName.'" name="radioNames[]">
                                    ';
                                    $defQuestions = getDefictionQuestion($compDVal['id']);
                                    foreach($defQuestions as $quesVal){
                                    $check = chechIfAnswerExist($_POST["selctedUser"], $_SESSION["user_id"], $compDVal['id'], $quesVal['point_rate']);
                                    $output .= '
                                        <div class="row">
                                        <div class="col-sm-12 mb-2">
                                            <div class="form-group">
                                            <label class="rdiobox">
                                                <input type="radio" '.(($check)? 'checked' :'').' name="'.$radioName.'" value="'.$quesVal['point_rate'].'" required>
                                                <span>'.$quesVal['point_rate'].' - '.$quesVal['question_title'].'</span>
                                            </label>
                                            </div>
                                            <div class="card ">
                                            </div>
                                        </div>
                                        </div>
                                    ';
                                    }
                                    $output .= '
                                </div>
                                </div>
                            ';
                            }
                    $output .= '
                            <div class="card-footer"> 
                            <input type="button" name="saveAandCont" id="saveAandCont" value="Kaydet Sonra Devam Et" class="saveAandCont btn btn-primary"/>
                            </div>
                        </div>
                        
                        </div>
                    </div>';
                }
                $output .='
                    <div class="col-lg-12">
                    <input type="submit" name="save" id="save" value="Hepsi Kaydet" class="btn btn-info"/>
                    </div>';
                echo $output;
        }
    }

    if ($_POST["_action"] == "GET_REPORT") {
        // $users = pdoGetAllDataRow("SELECT * FROM kullanicilar WHERE DEPART = :depart", ['depart'=>$_POST['depart']]); 
        $users = getBolumUsers($_POST['depart']);
        $workSetCompetences = getWorkSetCompetences(1);
        $output = "";
        foreach($users as $key => $userData){
            if(isset($workSetCompetences) && is_array($workSetCompetences)){
                $output .= '
                    <tr>
                        <td>'.($key+1).'</td>
                        <td>'.$userData["AD_SOYAD"].'</td>
                        <td>'.getDepartName($userData["DEPART"]).'</td>
                        <td>'.getGorevName($userData["GOREV"]).'</td>';
                $reportArray = array();
                foreach($workSetCompetences as $row){
                    $compDefs = getCompetenceDefiction($row['id']);
                    $count = $sum = 0; 
                    $data = '';
                    foreach($compDefs as $compDVal){
                        $data = getQuestionAnswerValue($userData["id"], $userData["id"], $compDVal['id']);
                        $sum = (int)$sum + (int)$data;
                        ++$count;
                    }
                    $average = $sum / $count;
                    array_push($reportArray, $average);
                }
                foreach ($reportArray as $value) {
                    $output .= '<td>'.$value.'</td>';
                    $output .= '<td>'.$value.'</td>';
                }
                $output .= '</tr>';

                // OTHER USER
                $output .= '
                    <tr>
                        <td>'.($key+1).'</td>
                            <td>'.$userData["AD_SOYAD"].'</td>
                            <td>'.getDepartName($userData["DEPART"]).'</td>
                            <td>'.getGorevName($userData["GOREV"]).'</td>';
                $reportArray = array();
                foreach($workSetCompetences as $row){
                    $compDefs = getCompetenceDefiction($row['id']);
                    $count = $sum = 0; 
                    $data = '';
                    foreach($compDefs as $compDVal){
                        $data = getQuestionOtherAnswerValue($userData["id"], $userData["id"], $compDVal['id']);
                        $sum = (int)$sum + (int)$data;
                        ++$count;
                    }
                    $average = $sum / $count;
                    array_push($reportArray, $average);
                }
                foreach ($reportArray as $value) {
                    $output .= '<td>'.$value.'</td>';
                    $output .= '<td>'.$value.'</td>';
                }
                $output .= '</tr>';
            }
         
            $output .= '';

            
            // $data = getMyTotalAnswerValue($userData["id"]); $_30 = $data["mytotal"].'/'.(5*$data["qtotal"]);
            // $_30_str = $data["mytotal"].'/ '.(5*$data["qtotal"]);
            // $_30_val = 0;
            // if($data["qtotal"] != 0)
            //   $_30_val = (int) $data["mytotal"] * 0.3 ;

            // $data2 = getOtherTotalAnswerValue($userData["id"]); $_70 = $data2["mytotal"].'/'.(5*$data2["qtotal"]);
            // $_70_str = $data2["mytotal"].'/'.(5*$data2["qtotal"]);
            // $_70_val = 0;
            // if($data2["qtotal"] != 0)
            //   $_70_val = ((int) $data2["mytotal"] * 0.7 ) ;


        //   $output .= '<td>'.$_30_str.'</td>
        //   <td>'.$_30_val.'</td>
        //   <td>'.$_70_str.'</td>
        //   <td>'.$_70_val.'</td>
        // </tr>';
        // if($key == 50) break;
        }

        echo $output;
    }
    if ($_POST["_action"] == "GET_WORKSET_DATA") {
        // displ($_POST['set_id']);
        $work_set = getWorkSet($_POST['set_id']);
        // displ($work_set);
        echo '
            <form method="post" id="workset_upd_form">
                <div class="row"> 
                    <div class="col-lg-12"><h4>Ankteti Güncelle</h4></div>

                    <input type="hidden" name="upd_workset_id" value="'.$work_set["id"].'">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">Anket Formu Adı&nbsp; <strong class="tx-danger">*</strong></span>
                                <input name="upd_workset_title" type="text" class="form-control" 
                                    required="" value="'.$work_set["title"].'">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Tanım&nbsp; <strong class="tx-danger">*</strong></span>
                            <textarea name="upd_workset_desc"  
                                class="form-control" required="">'.$work_set["description"].'</textarea>
                        </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Başlangıç Tarihi&nbsp; <strong class="tx-danger">*</strong></span>
                            <input name="upd_workset_start_date" type="date" class="form-control" 
                                required="" value="'. date('Y-m-d', strtotime($work_set['start_date'])).'">
                        </div>
                        </div>
                    </div>
                
                <div class="col-lg-5">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Bitiş Tarihi&nbsp; <strong class="tx-danger">*</strong></span>
                            <input name="upd_workset_end_date" type="date" class="form-control" 
                                required="" value="'. date('Y-m-d', strtotime($work_set['end_date'])) .'">
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <input type="submit" name="workset_submit" id="workset_submit" value="Kaydet" class="btn btn-info">
                </div>
                </div> 
            </form>
        ';
    }


    
    if ($_POST["_action"] == "UPDATE_WORKSET") {
        // displ($_POST);
        // $exist = pdoGetData("SELECT * from perf_sys_work_sets WHERE title = :_title", 
        // ["_title"=>$_POST["upd_workset_title"]]);

        // if(is_array($exist) && count($exist)>0){
        //     array_push($errors, 'Girilen adlı anket formu Kayıtlıdır!');
        //     displayMessages($success, $errors, $infos);
        // }
        // else{
            $date = date('Y-m-d');
            $reslt = pdoUpdateExec("UPDATE perf_sys_work_sets 
                        SET  title = :title, description = :desc,
                    start_date = :start_date, end_date = :end_date, status = :status , update_at = :update_at WHERE id = :_id",
                    ['title'=> $_POST['upd_workset_title'], 'desc'=> $_POST["upd_workset_desc"], 
                    'start_date'=> $_POST['upd_workset_start_date'], 'end_date'=> $_POST['upd_workset_end_date'], 
                    'status'=> 1, 'update_at'=> $date, '_id' => $_POST['upd_workset_id']]);
            if($reslt !== false){
                array_push($success, 'Kayıt güncelleme başarılı.');
                displayMessages($success, $errors, $infos);
            } // SORGU YANLIS ISE
            else{
                array_push($errors, 'Kayıt güncelleme başarısız.');
                displayMessages($success, $errors, $infos);
            }
        // }
    }

    
}


function getTotalQuestion($survey_id){
    $sql = 'SELECT * FROM survey_sys_questions WHERE survey_id = :surv_id';
    $allQuestions = pdoGetAllDataRow($sql, ['surv_id'=>$survey_id]); 
    return count($allQuestions);
}

function getTotalUserAnswers($survey_id, $user_id){
    $sql = 'SELECT * FROM survey_sys_answers WHERE survey_id = :surv_id and user_id = :u_id';
    $allAnswers = pdoGetAllDataRow($sql, ['surv_id'=>$survey_id, 'u_id'=>$user_id]); 
    if(isset($allAnswers) && is_array($allAnswers))
        return count($allAnswers);
    else return 0;
}
                
function loadStandartAnswers($val){
    $standartAnsers = getStandartAnsers();
    $ck = '';
    foreach ($standartAnsers as $key => $stdVal) {
        if($val == $stdVal['value'])
        $output .= '
        <div class="row">
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label class="rdiobox">
                        <input type="radio" '.$ck.' name="'.$questRadioName.'" value="'.$stdVal['value'].'" >
                        <span>'.$stdVal['name'].'</span>
                    </label>
                </div>
            </div>
        </div>
        ';
    }   
}

function existUserAnser($usr_id, $ques_id, $surv_id, $answer){
    // echo "$usr_id, $ques_id, $surv_id, $answer ****** ";
    $exist = pdoGetData("SELECT * from survey_sys_answers ssa 
                        JOIN perf_sys_work_sets psws ON ssa.survey_id = psws.id
                        WHERE ssa.user_id = :u_id AND question_id =:qu_id AND ssa.survey_id = :surv_id
                        AND answer = :ans AND CURDATE() BETWEEN psws.start_date AND psws.end_date", 
                    ["u_id"=>$usr_id, 'qu_id'=>$ques_id, 'surv_id'=>$surv_id, 'ans'=>$answer]);
    if(is_array($exist) && count($exist)>0)
        return true;
    else return false; 
}
function getAnswerValue($usr_id, $ques_id, $surv_id){
    $exist = pdoGetData("SELECT * from survey_sys_answers ssa 
                        JOIN perf_sys_work_sets psws ON ssa.survey_id = psws.id
                        WHERE ssa.user_id = :u_id AND question_id =:qu_id AND ssa.survey_id = :surv_id
                        AND psws.created_at BETWEEN psws.start_date AND psws.end_date", 
                    ["u_id"=>$_POST["user_id"], 'qu_id'=>$questId, 'surv_id'=>$_POST['tab1_work_set']]);
    if(is_array($exist) && count($exist)>0)
        return $exist['answer'];
    else return null; 
}