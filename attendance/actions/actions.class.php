<?php

/**
 * attendance actions.
 *
 * @package    attendance
 * @subpackage attendance
 * @author     JBL
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
//require_once '../../lib/common/LocaleUtil.php';
ini_set('max_execution_time', 300);
include ('../../lib/common/LocaleUtil.php');

class attendanceActions extends sfActions {

    public function executeDownloadConfiguration(sfWebRequest $request) {
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id'); //die($transPid);
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_atn_fieldformat', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {//dir("hgf");
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_atn_fieldformat', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
//ssssssssssssssssssssssss
        $this->myCulture = $this->getUser()->getCulture();
        $AttendanceDao = new attendanceDao();

        $knwdt = $AttendanceDao->readattendanceconfigure();
        if (!$knwdt) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('attendance/DownloadConfiguration');
        }

        $this->fieldlist = $knwdt;
        if ($request->isMethod('post')) {

            foreach ($knwdt as $row) {

                if (strlen($request->getParameter('fieldname' . $row['aff_id']))) {
                    $row->setAff_fieldname(trim($request->getParameter('fieldname' . $row['aff_id'])));
                } else {
                    $row->setAff_fieldname(null);
                }
                if (strlen($request->getParameter('StratPosition' . $row['aff_id']))) {
                    $row->setAff_fieldstartposition(trim($request->getParameter('StratPosition' . $row['aff_id'])));
                } else {
                    $row->setAff_fieldstartposition(null);
                }
                if (strlen($request->getParameter('EndPosition' . $row['aff_id']))) {
                    $row->setAff_fieldendposition(trim($request->getParameter('EndPosition' . $row['aff_id'])));
                } else {
                    $row->setAff_fieldendposition(null);
                }
                if (strlen($request->getParameter('Format' . $row['aff_id']))) {
                    $row->setAff_fieldformat(trim($request->getParameter('Format' . $row['aff_id'])));
                } else {
                    $row->setAff_fieldformat(null);
                }
                if (strlen($request->getParameter('Type'))) {
                    $row->setAff_fielddatatype(trim($request->getParameter('Type' . $row['aff_id'])));
                } else {
                    $row->setAff_fielddatatype(null);
                }
                try {
                    $AttendanceDao->saveAttnconfiguration($row);
                } catch (Doctrine_Connection_Exception $e) {
                    $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('attendance/DownloadConfiguration?id=1&lock=0');
                } catch (Exception $e) {
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('attendance/DownloadConfiguration?id=1&lock=0');
                }
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('attendance/DownloadConfiguration?id=1&lock=0');
        }
    }

    public function executeAttendanceDay(sfWebRequest $request) {
//Table Lock code is Open
        $this->culture = $this->getUser()->getCulture();
        if ($request->getParameter('con') == 1) {
            $this->con = 1;
            $ss = 1;
        }
        if ($request->getParameter('conf') == 1) {
            $this->conf = 1;
        }

        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_atn_day', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_atn_day', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed


        $this->Culture = $this->getUser()->getCulture();
        $atnday = new attendanceDao();
        $attendace = new AttendanceDay();
        $this->atday = $atnday->readattendanceDay();
        $this->attday = $atnday->readADay();
        $this->daytype = $atnday->getDayTypeload();

        $My_Array = array();

        for ($i = 0; $i < 7; $i++) {
            $day_Array = array();
            $intimeexpand = explode(':', $this->attday[$i]['adt_intime']);
            $this->intimehrs = $intimeexpand[0];
            $this->intimemins = $intimeexpand[1];
            $outtimeexpand = explode(':', $this->attday[$i]['adt_outtime']);
            $this->outtimehrs = $outtimeexpand[0];
            $this->outtimemins = $outtimeexpand[1];

            array_push($day_Array, $this->attday[$i]['adt_day'], $this->attday[$i]['dt_id'], $intimeexpand[0], $intimeexpand[1], $outtimeexpand[0], $outtimeexpand[1]);
            array_push($My_Array, $day_Array);
        }
        $days = array();
        foreach ($My_Array as $key => $value) {
            $days[$value[0]] = $value;
        }
        $this->Monday = $days['Monday'];
        $this->Tuesday = $days['Tuesday'];
        $this->Wednesday = $days['Wednesday'];
        $this->Thursday = $days['Thursday'];
        $this->Friday = $days['Friday'];
        $this->Saturday = $days['Saturday'];
        $this->Sunday = $days['Sunday'];


        if ($request->isMethod('post')) {
            for ($i = 0; $i < 7; $i++) {
                $attendace = new AttendanceDay();
                $this->daytype = $atnday->getDayTypeload();
                $attendace->setAdt_day($request->getParameter('lblday' . $i));
                if ($request->getParameter('cmbbtype' . $i) == 'all') {
                    $attendace->setDt_id("all");
                    $cmbbtpe = "all";
                } else {
                    $attendace->setDt_id($request->getParameter('cmbbtype' . $i));
                    $cmbbtpe = $request->getParameter('cmbbtype' . $i);
                }
                if (($request->getParameter('IntimeHH' . $i) == -1) && ($request->getParameter('IntimeMM' . $i) == -1)) {
                    $attendace->setAdt_intime("-01:01:00");
                    $in = "-01:01:00";
                } else {
                    $attendace->setAdt_intime($request->getParameter('IntimeHH' . $i) . ":" . $request->getParameter('IntimeMM' . $i));
                    $in = $request->getParameter('IntimeHH' . $i) . ":" . $request->getParameter('IntimeMM' . $i);
                }
                if (($request->getParameter('OuttimeHH' . $i) == -1) && ($request->getParameter('OuttimeMM' . $i) == -1)) {
                    $attendace->setAdt_outtime("-01:01:00");
                    $out = "-01:01:00";
                } else {
                    $attendace->setAdt_outtime($request->getParameter('OuttimeHH' . $i) . ":" . $request->getParameter('OuttimeMM' . $i));
                    $out = $request->getParameter('OuttimeHH' . $i) . ":" . $request->getParameter('OuttimeMM' . $i);
                }
                $this->Adtrow = $atnday->ReadAttDay($request->getParameter('lblday' . $i));
                foreach ($this->Adtrow as $row) {
                    if ($row['count'] == 1) {
                        $atnday->updateAttDay($request->getParameter('lblday' . $i), $cmbbtpe, $in, $out);
                    } else {
                        try {
                            $atnday->saveAttDay($attendace);
                        } catch (Exception $e) {
                            $errMsg = new CommonException($e->getMessage(), $e->getCode());
                            $this->setMessage('WARNING', $errMsg->display());
                            $this->redirect('attendance/AttendanceDay?id=' . array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday") . '&lock=0');
                        }
                    }
                }
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('attendance/AttendanceDay?id=' . array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday") . '&lock=0');
        }
    }

    public function setMessage($messageType, $message = array()) {
        $this->getUser()->setFlash('messageType', $messageType);
        $this->getUser()->setFlash('message', $message);
    }

    public function executeClockSourse(sfWebRequest $request) {

    }

    public function executeMSSQL(sfWebRequest $request) {
        die("SQL");
    }

    public function executeMSAccess(sfWebRequest $request) {
        die("MS ACCESS");

        if ($request->isMethod('post')) {

        }
    }

    public function executeMSExcel(sfWebRequest $request) {
        if ($request->isMethod('post')) {

            $fileName = $_FILES['txtletter']['name'];
            $tmpName = $_FILES['txtletter']['tmp_name'];
            $fileSize = $_FILES['txtletter']['size'];
            $fileType = $_FILES['txtletter']['type'];


            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));

            if ($fileType == 'application/vnd.ms-excel') {
                $target_path = sfConfig::get('sf_upload_dir') . "/UExcel/";
                $target_path = $target_path . basename($_FILES['txtletter']['name']);

                if (move_uploaded_file($_FILES['txtletter']['tmp_name'], $target_path)) {

                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Excel file Updated", $args, 'messages')));
                    chmod($target_path, 0755);
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("There was an error uploading the file, please try again! ", $args, 'messages')));
                    $this->redirect('attendance/ClockSourse');
                }

                $exr = new ExcelRead();
                $dd = $exr->displaydata($target_path);
                try {
                    $conn = Doctrine_Manager::getInstance()->connection();
                    $conn->beginTransaction();
                    for ($j = 0; $j < $dd['numRows']; $j++) {
                        if (!strlen($dd['cells'][$j + 1][1]) || !strlen($dd['cells'][$j + 1][2]) || !strlen($dd['cells'][$j + 1][3])) {
                            throw new Exception("Damaged Excel file or data Format Error", 2);
                        }

                        $atnday = new attendanceDao();
                        $txtrecord = new AttendanceClockDetails();
                        $record = $atnday->clckread($dd['cells'][$j + 1][1], $dd['cells'][$j + 1][2], $dd['cells'][$j + 1][3]);

                        if ($record[0]['count'] == 0) {
                            $txtrecord->setClk_no($dd['cells'][$j + 1][1]);
                            $txtrecord->setClk_date($dd['cells'][$j + 1][2]);
                            $txtrecord->setClk_time($dd['cells'][$j + 1][3]);
                            $txtrecord->setClk_status(0);
                            $atnday->clcksave($txtrecord);
                        } else {
                            $atnday->clkupdate($dd['cells'][$j + 1][1], $dd['cells'][$j + 1][2], $dd['cells'][$j + 1][3]);
                        }
                        echo($dd['cells'][$j + 1][1] . "" . $dd['cells'][$j + 1][2] . "" . $dd['cells'][$j + 1][3] . "<br/>" );
                    }
                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollBack();
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('attendance/ClockSourse');
                }
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Excel file Data Updated", $args, 'messages')));
                $this->redirect('attendance/ClockSourse');
            } else {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Please Try with Excel format 2003  ", $args, 'messages')));
                $this->redirect('attendance/ClockSourse');
            }
        }
    }

    public function executeText(sfWebRequest $request) {


        if ($request->isMethod('post')) {
            try {
                $allowedExtensions = array("txt");
                foreach ($_FILES as $file) {
                    if ($file['tmp_name'] > '') {
                        if (!in_array(end(explode(".",
                                                        strtolower($file['name']))),
                                        $allowedExtensions)) {
                            throw new Exception("Damaged Text file or data Format Error", 3);
                        }
                    }
                }
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('attendance/Text');
            }
            $fileName = $_FILES['txtletter']['name'];
            $tmpName = $_FILES['txtletter']['tmp_name'];
            $fileSize = $_FILES['txtletter']['size'];
            $fileType = $_FILES['txtletter']['type'];


            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));
            $content = addslashes($content);
            $delimiter = "\n";
            $splitcontents = explode($delimiter, $content);

//beging transactions
            try {

                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();

                $AttendanceDao = new attendanceDao();
                $knwdt = $AttendanceDao->readattendanceconfigure();
                if ($knwdt == null) {
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Please Edit Download Configuration Page", $args, 'messages')));
                    $this->redirect('attendance/Text');
                }
                foreach ($splitcontents as $row) {

                    //--------------
                    $column = array();
                    $AtnNo = substr($row, $knwdt[0]['aff_fieldstartposition'], ($knwdt[0]['aff_fieldendposition'] + 1) - $knwdt[0]['aff_fieldstartposition']);
                    $Year = substr($row, $knwdt[1]['aff_fieldstartposition'], ($knwdt[1]['aff_fieldendposition'] + 1) - $knwdt[1]['aff_fieldstartposition']);
                    $Month = substr($row, $knwdt[2]['aff_fieldstartposition'], ($knwdt[2]['aff_fieldendposition'] + 1) - $knwdt[2]['aff_fieldstartposition']);
                    $Day = substr($row, $knwdt[3]['aff_fieldstartposition'], ($knwdt[3]['aff_fieldendposition'] + 1) - $knwdt[3]['aff_fieldstartposition']);
                    $HH = substr($row, $knwdt[4]['aff_fieldstartposition'], ($knwdt[4]['aff_fieldendposition'] + 1) - $knwdt[4]['aff_fieldstartposition']);
                    $MM = substr($row, $knwdt[5]['aff_fieldstartposition'], ($knwdt[5]['aff_fieldendposition'] + 1) - $knwdt[5]['aff_fieldstartposition']);
                    if ($AtnNo == null) {
                        throw new Exception("Damaged Text file or data Format Error", 3);
                    }
//             $os = array("~", "`", "!", "@","#", "$", "%", "^","&", "*", "(", ")","_", "+", "{", "}","[", "]", ":", ";", "|",">","<",",",".","?" );
//             $arr1 = str_split($AtnNo);
//             foreach($arr1 as $char){
//                $val=in_array($char,$os );
//            //die(var_dump($val));
//
//             if ($val==true) {
//
//                    }else{
//                       continue;
//                    }
//             }
                    if (!ctype_alnum($AtnNo)) {
//                continue;
//            } else {
                        throw new Exception("Damaged Text file or data Format Error", 3);
                    }
                    $strings = array($Year, $Month, $Day, $HH, $MM);
                    foreach ($strings as $testcase) {
                        if (ctype_digit($testcase)) {
                            continue;
                        } else {
                            throw new Exception("Damaged Text file or data Format Error", 3);
                        }
                    }


                    $column[0] = $AtnNo;
                    $column[1] = $Year . "-" . $Month . "-" . $Day;
                    $column[2] = $HH . ":" . $MM;
                    //--------------

                    for ($i = 0; $i < 3; $i++) {
                        if (strlen($column[$i]) == null) {//die("sdfs");
                            throw new Exception("Damaged Text file or data Format Error", 3);
                        }
                    }
                    //------------
                    require_once '../../lib/common/LocaleUtil.php';
                    $sysConf = OrangeConfig::getInstance()->getSysConf();
                    $sysConf = new sysConf();
                    $inputDate = $sysConf->dateInputHint;
                    $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());



                    $attendanceDao = new attendanceDao();
                    $txtrecord = new AttendanceClockDetails();
                    $Status = $attendanceDao->IsEmployeeAttendanceActive($column[0]);

                    if ($Status[0]['Active'] == 1) {
                        $InOutTime = $attendanceDao->IsInOutTimeAvailable($column[0], $column[1], $column[2]);

                        if ($InOutTime[0]['Status'] == 0) {
                            $txtrecord->setClk_no($column[0]);
                            $txtrecord->setClk_date($column[1]);
                            $txtrecord->setClk_time($column[2]);
                            $txtrecord->setClk_status(0);
                            $attendanceDao->SaveInOutTime($txtrecord);
                        } else {
                            $attendanceDao->UpdateInOutTime($column[0], $column[1], $column[2]);
                        }
                    }
                }$conn->commit();
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('attendance/Text');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Text file Data Updated", $args, 'messages')));
            $this->redirect('attendance/Text');
        }
    }

    public function executeDataProcess(sfWebRequest $request) {
        //if ($request->isMethod('post')) {

            $attendanceDao = new attendanceDao();
            $data = $attendanceDao->filterdata(); //read data statues 0
//die(print_r($data));
            $abc = null;
            foreach ($data as $row) {
                $attendanceDao = new attendanceDao();


                $selectdata = $attendanceDao->getAttendanceminmaxdata($row['clk_date'], $row['clk_no']); //find min & max
                $timeStamp = strtotime($row['clk_date']); //find Day
                $day = getdate($timeStamp);
                $attendanceDao = new attendanceDao();
                $wdt = $attendanceDao->getdaydata($day['weekday']); //find Day in & out time
                $timerin = strtotime($wdt[0][adt_intime]);
                //$minatnd = $selectdata[0]['MIN'];
                if($selectdata[0]['MIN'] == '00:00:00'){
                    $countminzero = $attendanceDao->getminimumtimezeorcount($row['clk_date'], $row['clk_no']);                    
                    //die(print_r($countminzero));
                    if($countminzero[0]['count'] == '1'){
                        $minatnd = $selectdata[0]['MIN'];
                    }
                    else{
                        $minafterval = $attendanceDao->getAttendanceminafterval($row['clk_date'], $row['clk_no']);
                        $minatnd=$minafterval[1]['clk_time'];
                    }
                }else{
                    $minatnd = $selectdata[0]['MIN'];
                }
                //print_r($minatnd);
                $timeuin = strtotime($minatnd);

                $x = $timeuin - $timerin;
                if ($x > 0) {
                    $latetimeHHn = (int) ($x / 3600);
                    $k = ($x % 3600);
                    $latetimeMMn = (int) ($k / 60);
                    $m = $k % 60;
                    $latetimeSSn = (int) ($m / 1);


                    if (!strlen($latetimeHHn)) {
                        $latetimeHHn = '00';
                    } else if (strlen($latetimeHHn) == 1) {
                        $latetimeHHn = '0' . $latetimeHHn;
                    }
                    if (!strlen($latetimeMMn)) {
                        $latetimeMMn = '00';
                    } else if (strlen($latetimeMMn) == 1) {
                        $latetimeMMn = '0' . $latetimeMMn;
                    }
                    if (!strlen($latetimeSSn)) {
                        $latetimeSSn = '00';
                    } else if (strlen($latetimeSSn) == 1) {
                        $latetimeSSn = '0' . $latetimeSSn;
                    }
                    $latetime = $latetimeHHn . ":" . $latetimeMMn . ":" . $latetimeSSn;
                } else {
                    $latetime = "00:00:00";
                }
                $timerout = strtotime($wdt[0][adt_outtime]);
                $timeuout = strtotime($selectdata[0]['MAX']);

                $y = $timerout - $timeuout;
                if ($y > 0) {

                    $earlytimeHHn = (int) ($y / 3600);
                    $l = ($y % 3600);
                    $earlytimeMMn = (int) ($l / 60);
                    $n = $l % 60;
                    $earlytimeSSn = (int) ($n / 1);

                    if (!strlen($earlytimeHHn)) {
                        $earlytimeHHn = '00';
                    } else if (strlen($earlytimeHHn) == 1) {
                        $earlytimeHHn = '0' . $earlytimeHHn;
                    }
                    if (!strlen($earlytimeMMn)) {
                        $earlytimeMMn = '00';
                    } else if (strlen($earlytimeMMn) == 1) {
                        $earlytimeMMn = '0' . $earlytimeMMn;
                    }
                    if (!strlen($earlytimeSSn)) {
                        $earlytimeSSn = '00';
                    } else if (strlen($earlytimeSSn) == 1) {
                        $earlytimeSSn = '0' . $earlytimeSSn;
                    }
                    $earlytime = $earlytimeHHn . ":" . $earlytimeMMn . ":" . $earlytimeSSn;
                } else {
                    $earlytime = "00:00:00";
                }
                //read employee
                $empno = $attendanceDao->reademp($row['clk_no']);
                if (strlen($empno[0]['empNumber'])) {
                    //insert row
                    $dailyatten = new Attendance();
                    $attendanceDao = new attendanceDao();
                    $dailyatten->setClk_no($row['clk_no']);
                    $dailyatten->setEmp_number($empno[0]['empNumber']);
                    $dailyatten->setAtn_date($row['clk_date']);
                    $dailyatten->setAtn_intime($minatnd);
                    $dailyatten->setAtn_outtime($selectdata[0]['MAX']);
                    $dailyatten->setAtn_latetime($latetime);
                    $dailyatten->setAtn_earlydeptime($earlytime);
                    $dailyatten->setDt_id($wdt[0]['dt_id']);

                  try {
//beging transactions
                        $conn = Doctrine_Manager::getInstance()->connection();
                        $conn->beginTransaction();
                        $dd = $attendanceDao->Rreadaattnup($row['clk_no'], $row['clk_date'], $empno[0]['empNumber']);
                        if ($dd[0]['COUNT'] == 0) {
                            $abc = $attendanceDao->saveAttandance($dailyatten);
                        } else {
                            $dailyatten = new Attendance();
                            $dailyatten = $attendanceDao->readaattnup2($row['clk_no'], $row['clk_date'], $empno[0]['empNumber']);
                            $dailyatten->setAtn_intime($minatnd);
                            $dailyatten->setAtn_outtime($selectdata[0]['MAX']);
                            $dailyatten->setAtn_latetime($latetime);
                            $dailyatten->setAtn_earlydeptime($earlytime);
                            $dailyatten->setDt_id($wdt[0]['dt_id']);
                            
                            $datetime1 = new DateTime($selectdata[0]['MAX']);
                            $datetime2 = new DateTime($minatnd);
                            $interval = $datetime1->diff($datetime2);

                            $hours   = $interval->format('%h');
                            $minutes = $interval->format('%i');
                            if($hours < 10){
                                $hours="0".$hours;
                            }
                            if($minutes < 10){
                                $minutes="0".$minutes;
                            }

                            //echo $hours .":".$minutes;
                            
                            
                            $dailyatten->setAtn_work_hours($hours .":".$minutes);
                            $abc = $attendanceDao->saveAttandance($dailyatten);
                        }
                        //update clock statues
                        $clck = new AttendanceClockDetails();
                        $atncDao = new attendanceDao();
                        $clck = 1;

                        $abc = $atncDao->updateclk($row['clk_no'], $row['clk_date'], $clck);
                        $conn->commit();
                    } catch (Exception $e) {
                        $conn->rollBack();
                        $errMsg = new CommonException($e->getMessage(), $e->getCode());
                        $this->setMessage('WARNING', $errMsg->display());
                        $this->redirect('attendance/Text');
                    }
                }
            }
            if (strlen($abc)) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Time Data Uploaded", $args, 'messages')));
                $this->redirect('attendance/Process');
            }
            $this->redirect('attendance/Process');
            
        //}die;
    }

    public function executeProcess(sfWebRequest $request) {//die("ss");
        try {
            $this->Culture = $this->getUser()->getCulture();
            $atnda = new attendanceDao();
            $clkdetails = new AttendanceClockDetails();
            $fdate = new DateTime(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtfdate')));
            $nextdate = new DateTime(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtfdate')));
            $ldate = new DateTime(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txttdate')));

            while ($nextdate <= $ldate) {
                //print_r($nextdate);
                $timeStamp = strtotime($nextdate->format("Y-m-d")); //find Day
                $day = getdate($timeStamp);
                $wdt = $atnda->getdaydata($day['weekday']);

                if ($request->getParameter('abc') == 1) {      // If employee know
                    $empatn = $atnda->readatnd($request->getParameter('txtEmpId'));
                    $read = $atnda->read($request->getParameter('txtEmpId'), $empatn[0]['emp_attendance_no'], $nextdate->format("Y-m-d"));
                    $read2 = $atnda->read2($empatn[0]['emp_attendance_no'], $nextdate->format("Y-m-d"));
                    if ($read[0]['count'] == 0) {
                        $dailyatten = new Attendance();
                        $clockdown = new AttendanceClockDetails();

                        $dailyatten->setClk_no($empatn[0]['emp_attendance_no']);
                        $dailyatten->setEmp_number($request->getParameter('txtEmpId'));
                        $dailyatten->setAtn_date($nextdate->format("Y-m-d"));
                        $dailyatten->setAtn_intime("00:00:00");
                        $dailyatten->setAtn_outtime("00:00:00");
                        $dailyatten->setAtn_latetime("00:00:00");
                        $dailyatten->setAtn_earlydeptime("00:00:00");
                        $dailyatten->setDt_id($wdt[0]['dt_id']);

                        $clockdown->setClk_no($empatn[0]['emp_attendance_no']);
                        $clockdown->setClk_date($nextdate->format("Y-m-d"));
                        $clockdown->setClk_time("00:00:00");
                        $clockdown->setClk_status(1);
                        try {

                            $conn = Doctrine_Manager::getInstance()->connection();
                            $conn->beginTransaction();
                            if ($read2[0]['count'] == 0) {
                                $atnda->SaveInOutTime($clockdown);
                            }
                            $atnda->saveAttandance($dailyatten);
                            $conn->commit();
                        } catch (Exception $e) {
                            $conn->rollBack();
                            $errMsg = new CommonException($e->getMessage(), $e->getCode());
                            $this->setMessage('WARNING', $errMsg->display());
                        }
                    }
                } else if ($request->getParameter('abc') == 0) {
                    //If Select * employees
                    $empall = $atnda->readeveryemp();
                    foreach ($empall as $emp) {
                        $read = $atnda->read($emp['empNumber'], $emp['emp_attendance_no'], $nextdate->format("Y-m-d"));
                        $read2 = $atnda->read2($emp['emp_attendance_no'], $nextdate->format("Y-m-d"));
                        if ($read[0]['count'] == 0) {
                            $dailyatten = new Attendance();
                            $clockdown = new AttendanceClockDetails();

                            $dailyatten->setClk_no($emp['emp_attendance_no']);
                            $dailyatten->setEmp_number($emp['empNumber']);
                            $dailyatten->setAtn_date($nextdate->format("Y-m-d"));
                            $dailyatten->setAtn_intime("00:00:00");
                            $dailyatten->setAtn_outtime("00:00:00");
                            $dailyatten->setAtn_latetime("00:00:00");
                            $dailyatten->setAtn_earlydeptime("00:00:00");
                            $dailyatten->setDt_id($wdt[0]['dt_id']);


                            $clockdown->setClk_no($emp['emp_attendance_no']);
                            $clockdown->setClk_date($nextdate->format("Y-m-d"));
                            $clockdown->setClk_time("00:00:00");
                            $clockdown->setClk_status(1);
                            try {

                                $conn = Doctrine_Manager::getInstance()->connection();
                                $conn->beginTransaction();
                                if ($read2[0]['count'] == 0) {
                                    $atnda->SaveInOutTime($clockdown);
                                }
                                $atnda->saveAttandance($dailyatten);
                                $conn->commit();
                            } catch (Exception $e) {
                                $conn->rollBack();
                                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                                $this->setMessage('WARNING', $errMsg->display());
                            }
                        }
                    }
                }
                $nextdate = $fdate->add(new DateInterval('P1D'));
            }

//            $this->sorter = new ListSorter('Attendance', 'prosess', $this->getUser(), array('a.atn_date', ListSorter::ASCENDING));
//            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            $atnda = new attendanceDao();
            $this->recordday = $atnda->readattendanceDay();
            $LeaveDao = new LeaveDao();
            $this->leaveholiday = $LeaveDao->readLeaveHolyDay();

            $this->sort = ($request->getParameter('sort') == null) ? 'a.atn_date' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == null) ? 'ASC' : $request->getParameter('order');

            $this->searchMode = ($request->getParameter('txttdate') == null) ? $request->getParameter('searchMode') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txttdate']);
            $this->searchValue = ($request->getParameter('txtfdate') == null) ? $request->getParameter('searchValue') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txtfdate']);
            $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
            $this->type = ($request->getParameter('abc') == null) ? $request->getParameter('type') : $_POST['abc'];
            $this->ename = ($request->getParameter('txtEmployeeName') == null) ? $request->getParameter('txtEmployeeName') : $_POST['txtEmployeeName'];

            $res = $atnda->viewall($this->searchValue, $this->searchMode, $request->getParameter('page'), $this->emp, $this->type, $this->sort, $this->order);
            $this->view = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeAjaxTableLock(sfWebRequest $request) {


        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $empId = $request->getParameter('empno');
        $empaid = $request->getParameter('empaid');
        $Cid = $request->getParameter('atndate');
        $yy = substr($Cid, 0, 4);
        $mm = substr($Cid, 4, -2);
        $dd = substr($Cid, 6, 7);
        $atndate = $yy . "-" . $mm . "-" . $dd;
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_atn_dailyattendance', array($empId, $empaid, $atndate), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                    $atnDao = new attendanceDao();
                    $atnday = $atnDao->readatnedit($empId, $empaid, $atndate);

                    $intimeexpand = explode(':', $atnday[0]['atn_intime']);
                    $this->intimehrs = $intimeexpand[0];
                    $this->intimemins = $intimeexpand[1];
                    $outtimeexpand = explode(':', $atnday[0]['atn_outtime']);
                    $this->outtimehrs = $outtimeexpand[0];
                    $this->outtimemins = $outtimeexpand[1];
                    $this->late = $atnday[0]['atn_latetime'];
                    $this->early = $atnday[0]['atn_earlydeptime'];
                } else {
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {

                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_atn_dailyattendance', array($empId, $empaid, $atndate), 1);
                $this->lockMode = 0;
            }
        }
    }

    public function executeSavedata(sfWebRequest $request) {
        $attendanceDao = new attendanceDao();
        $this->trainingDao = $trainingDao;
        try {
            $empId = $request->getParameter('empno');
            $empaid = $request->getParameter('empaid');
            $Cid = $request->getParameter('atndate');
            if ($request->getParameter('intimehh') == null) {
                $intimehh = "00:00:00";
            } else {
                $intimehh = $request->getParameter('intimehh');
            }
            if ($request->getParameter('intimemm') == null) {
                $intimemm = "00:00:00";
            } else {
                $intimemm = $request->getParameter('intimemm');
            }
            if ($request->getParameter('outtimehh') == null) {
                $outtimehh = "00:00:00";
            } else {
                $outtimehh = $request->getParameter('outtimehh');
            }
            if ($request->getParameter('outtimemm') == null) {
                $outtimemm = "00:00:00";
            } else {
                $outtimemm = $request->getParameter('outtimemm');
            }

            $yy = substr($Cid, 0, 4);
            $mm = substr($Cid, 4, -2);
            $dd = substr($Cid, 6, 7);
            $atndate = $yy . "-" . $mm . "-" . $dd; // date format

            $intime = $intimehh . ":" . $intimemm . ":00";     //intime
            $outtime = $outtimehh . ":" . $outtimemm . ":00";  //outtime

            $timeStamp = strtotime($atndate); //find Day
            $day = getdate($timeStamp);
            $atndaytype = new attendanceDao();
            $wdt = $atndaytype->getdaydata($day['weekday']); //find Day in & out time

            $timerin = strtotime($wdt[0][adt_intime]);
            $intime1 = strtotime($intime);
            $x = $intime1 - $timerin;

            if (($x > 0) && ($x < 86400)) {

                $latetimeHHn = (int) ($x / 3600);
                $k = ($x % 3600);
                $latetimeMMn = (int) ($k / 60);
                $m = $k % 60;
                $latetimeSSn = (int) ($m / 1);


                if (!strlen($latetimeHHn)) {
                    $latetimeHHn = '00';
                } else if (strlen($latetimeHHn) == 1) {
                    $latetimeHHn = '0' . $latetimeHHn;
                }
                if (!strlen($latetimeMMn)) {
                    $latetimeMMn = '00';
                } else if (strlen($latetimeMMn) == 1) {
                    $latetimeMMn = '0' . $latetimeMMn;
                }
                if (!strlen($latetimeSSn)) {
                    $latetimeSSn = '00';
                } else if (strlen($latetimeSSn) == 1) {
                    $latetimeSSn = '0' . $latetimeSSn;
                }
                $latetime = $latetimeHHn . ":" . $latetimeMMn . ":" . $latetimeSSn;

                //$latetime=substr($latetimen, 1, 9); //negative into positive
            } else {
                $latetime = "00:00:00";
            }

            $timerout = strtotime($wdt[0][adt_outtime]);
            $outtime1 = strtotime($outtime);
            $y = $timerout - $outtime1;
            if (($y > 0) && ($y < 86400)) {

                $earlytimeHHn = (int) ($y / 3600);
                $l = ($y % 3600);
                $earlytimeMMn = (int) ($l / 60);
                $n = $l % 60;
                $earlytimeSSn = (int) ($n / 1);

                if (!strlen($earlytimeHHn)) {
                    $earlytimeHHn = '00';
                } else if (strlen($earlytimeHHn) == 1) {
                    $earlytimeHHn = '0' . $earlytimeHHn;
                }
                if (!strlen($earlytimeMMn)) {
                    $earlytimeMMn = '00';
                } else if (strlen($earlytimeMMn) == 1) {
                    $earlytimeMMn = '0' . $earlytimeMMn;
                }
                if (!strlen($earlytimeSSn)) {
                    $earlytimeSSn = '00';
                } else if (strlen($earlytimeSSn) == 1) {
                    $earlytimeSSn = '0' . $earlytimeSSn;
                }
                $earlytime = $earlytimeHHn . ":" . $earlytimeMMn . ":" . $earlytimeSSn;
            } else {
                $earlytime = "00:00:00";
            }
            $dtype = $wdt[0]['dt_id'];
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->isupdated = "false";
            $this->redirect('attendance/Process');
        }

        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
            $this->isupdated = $attendanceDao->updatedata($empId, $empaid, $atndate, $intime, $outtime, $latetime, $earlytime, $dtype);
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('attendance/Process');
        }
        $this->isupdated = "true";
    }

    public function executeError(sfWebRequest $request) {

        $this->redirect('default/error');
    }
    
    
    
//<<---------------------------------------------Start-------------------------------------------------->>   

// edit by prabuddha nipun 2014/06/30.........................................

 public function executeSummary(sfWebRequest $request) {
        try {
		 $SempNum = $_SESSION['empNumber'];
       // $this->type = $_SESSION['empNumber'];

        $this->Culture = $this->getUser()->getCulture();
        $atnda = new attendanceDao();
       
            $clkdetails = new AttendanceClockDetails();
            $fdate = new DateTime($request->getParameter('txtfdate'));
            $nextdate = new DateTime($request->getParameter('txtfdate'));
            $ldate = new DateTime($request->getParameter('txttdate'));
            
            $this->fullname = $atnda->LoadEmpData($SempNum);
              
     
      //$this->emp[0]->getEmp_display_name();
    
            while ($nextdate <= $ldate) {
				
                //print_r($nextdate);
                $timeStamp = strtotime($nextdate->format("Y-m-d")); //find Day
                $day = getdate($timeStamp);
                $wdt = $atnda->getdaydata($day['weekday']);

                if ($request->getParameter('abc') == 1) {      // If employee know
                    $empatn = $atnda->readatnd($request->getParameter('empnumber'));
                    $read = $atnda->read($request->getParameter('empnumber'), $empatn[0]['emp_attendance_no'], $nextdate->format("Y-m-d"));
                    $read2 = $atnda->read2($empatn[0]['emp_attendance_no'], $nextdate->format("Y-m-d"));
                    if ($read[0]['count'] == 0) {
                        $dailyatten = new Attendance();
                        $clockdown = new AttendanceClockDetails();

                        $dailyatten->setClk_no($empatn[0]['emp_attendance_no']);
                        $dailyatten->setEmp_number($request->getParameter('empnumber'));
                        $dailyatten->setAtn_date($nextdate->format("Y-m-d"));
                        $dailyatten->setAtn_intime("00:00:00");
                        $dailyatten->setAtn_outtime("00:00:00");
                        $dailyatten->setAtn_latetime("00:00:00");
                        $dailyatten->setAtn_earlydeptime("00:00:00");
                        $dailyatten->setDt_id($wdt[0]['dt_id']);

                        $clockdown->setClk_no($empatn[0]['emp_attendance_no']);
                        $clockdown->setClk_date($nextdate->format("Y-m-d"));
                        $clockdown->setClk_time("00:00:00");
                        $clockdown->setClk_status(1);
                        try {

                            $conn = Doctrine_Manager::getInstance()->connection();
                            $conn->beginTransaction();
                            if ($read2[0]['count'] == 0) {
                                $atnda->SaveInOutTime($clockdown);
                            }
                            $atnda->saveAttandance($dailyatten);
                            $conn->commit();
                        } catch (Exception $e) {
                            $conn->rollBack();
                            $errMsg = new CommonException($e->getMessage(), $e->getCode());
                            $this->setMessage('WARNING', $errMsg->display());
                        }
                    }
                } else if ($request->getParameter('abc') == 0) {
                    //If Select * employees
                    $empall = $atnda->readeveryemp();
                    foreach ($empall as $emp) {
                        $read = $atnda->read($emp['empNumber'], $emp['emp_attendance_no'], $nextdate->format("Y-m-d"));
                        $read2 = $atnda->read2($emp['emp_attendance_no'], $nextdate->format("Y-m-d"));
                        if ($read[0]['count'] == 0) {
                            $dailyatten = new Attendance();
                            $clockdown = new AttendanceClockDetails();
//die(print_r($emp));
                            $dailyatten->setClk_no($emp['emp_attendance_no']);
                            $dailyatten->setEmp_number($emp['empNumber']);
                            $dailyatten->setAtn_date($nextdate->format("Y-m-d"));
                            $dailyatten->setAtn_intime("00:00:00");
                            $dailyatten->setAtn_outtime("00:00:00");
                            $dailyatten->setAtn_latetime("00:00:00");
                            $dailyatten->setAtn_earlydeptime("00:00:00");
                            $dailyatten->setDt_id($wdt[0]['dt_id']);

//die(print_r($nextdate));
                            $clockdown->setClk_no($emp['emp_attendance_no']);
                            $clockdown->setClk_date($nextdate->format("Y-m-d"));
                            $clockdown->setClk_time("00:00:00");
                            $clockdown->setClk_status(1);
                            try {

                                $conn = Doctrine_Manager::getInstance()->connection();
                                $conn->beginTransaction();
                                if ($read2[0]['count'] == 0) {
                                    $atnda->SaveInOutTime($clockdown);
                                }
                                $atnda->saveAttandance($dailyatten);
                                $conn->commit();
                            } catch (Exception $e) {
                                $conn->rollBack();
                                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                                $this->setMessage('WARNING', $errMsg->display());
                            } 
                        }
                    }
                }
                $nextdate = $fdate->add(new DateInterval('P1D'));
            }

           $atnda = new attendanceDao();
            $this->recordday = $atnda->readattendanceDay();
            $LeaveDao = new LeaveDao();
            $this->leaveholiday = $LeaveDao->readLeaveHolyDay();

            $this->sort = ($request->getParameter('sort') == null) ? 'a.atn_date' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == null) ? 'ASC' : $request->getParameter('order');

            $this->searchMode = ($request->getParameter('txttdate') == null) ? $request->getParameter('searchMode') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txttdate']);
            $this->searchValue = ($request->getParameter('txtfdate') == null) ? $request->getParameter('searchValue') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txtfdate']);
            $this->emp = ($request->getParameter('empnumber') == null) ? $_SESSION['empNumber'] : $_POST['empnumber'];
            $this->type = ($request->getParameter('abc') == null) ? $_SESSION['empNumber']: $_POST['abc'];
            $this->ename = ($request->getParameter('txtEmployeeName') == null) ? $request->getParameter('txtEmployeeName') : $_POST['txtEmployeeName'];
            
            
          

            $res = $atnda->viewall($this->searchValue, $this->searchMode, $request->getParameter('page'), $this->emp, $this->type, $this->sort, $this->order);
            $this->view = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }
    
    //----------------END--------------------------//

}
