<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<!--<link href="../../themes/orange/css/style.css" rel="stylesheet" type="text/css"/>-->
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>

<div class="formpage4col" style="width: 850px">
    <div class="navigation">

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Employee Attendance Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSave" id="frmSave" method="post" action="">
            <br class="clear"/>

            <div style="float: left; max-width: 550px;">
                <div class="leftCol">
                    <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?> <span class="required">*</span></label>
                </div>
                <div class="centerCol">
                    <input type="text" name="txtEmployeeName" 
                           id="txtEmployeeName" value="<?php echo $ename; ?>" readonly="readonly" style="color: #222222"/>
                </div>
                <div class="centerCol">
                    <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
                </div>

                <div class="leftCol">&nbsp;</div>
                <div class="centerCol">
                    <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $emp; ?>"/>
                    <input  type="hidden" name="txtempatnno" id="txtempatnno" value=""/>
                </div>
                <br class="clear"/>
                <div class="leftCol">
                    <label for="txtLocationCode"><?php echo __("From Date") ?> <span class="required">*</span></label>
                </div>
                <div class="centerCol">
                    <input id="fdate" type="text" name="txtfdate" value="<?php echo LocaleUtil::getInstance()->formatDate($searchValue); ?>">
                </div>
                <br class="clear"/>
                <div class="leftCol">
                    <label for="txtLocationCode"><?php echo __("To Date") ?> <span class="required">*</span></label>
                </div>
                <div class="centerCol">
                    <input id="tdate" type="text" name="txttdate" value="<?php echo LocaleUtil::getInstance()->formatDate($searchMode); ?>">
                </div>
                <input type="hidden" name="abc" id="abc" value="<?php if ($type == null) {
            echo "0";
        } else {
            echo $type;
        } ?>" >
            </div>
            <div id="legend" style="float: right;width: 150px;">
                <table border="0" >
                    <tr>
                        <td style="background-color: sandybrown; width: 20px"></td>
                        <td><?php echo __("Holiday") ?></td>
                    </tr>
                    <tr>
                        <td style="background-color: wheat;"></td>
                        <td><?php echo __("HalfDay") ?></td>
                    </tr>
                    <tr>
                        <td style="background-color: khaki;"></td>
                        <td><?php echo __("FullDay") ?></td>
                    </tr>
                </table>
            </div>
            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="clearbutton"  id="resetBtn"
                       value="<?php echo __("Reset") ?>" tabindex="9" />
                <input type="button" class="savebutton" id="viewall"
                       value="<?php echo __("View") ?>" tabindex="10" />
                <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?>  </div> </div>
        </form>
        <div class="noresultsbar"></div>

        <input type="hidden" name="mode" id="mode" value=""/>

        <form name="gridsave" id="gridsave" method="post" action="<?php //echo url_for('training/Adminapp')  ?>">
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td scope="col" style="width: 50px;">
                            <?php echo __('Attendance No'); ?>
                        </td>

                        <td scope="col" style="width: 150px;">
                            <?php echo __('Employee Name'); ?>
                        </td>

                        <td scope="col" style="width: 80px;">
<?php echo __('Date') ?>
                        </td>
                        <td scope="col" style="width: 100px;">
<?php echo __('In Time') ?>
                        </td>
                        <td scope="col" style="width: 100px;">
<?php echo __('Out Time') ?>
                        </td>
                        <td scope="col" style="width: 50px;">
<?php echo __('No of Working Hours') ?>
                        </td>
                        <td scope="col" style="width: 50px;">
<?php echo __('Late In') ?>
                        </td>
                        <td scope="col" style="width: 50px;">
<?php echo __('Early Out') ?>
                        </td>
                        <td scope="col"style="width: 100px;">
<?php echo __('Leave Type') ?>
                        </td>
                        <td scope="col">
                        </td>

                    </tr>
                </thead>
                <tbody>
                    <?php
                            $row = 0;
                            foreach ($view as $Attendance) {

                                //die(print_r($promotion));
                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                $row = $row + 1;
                    ?>              <?php
                                $dd = $Attendance->getAtn_date();
                                $timeStamp = strtotime($dd); //find Day
                                $day = getdate($timeStamp);
                                $cday = $day['weekday'];
                    ?>
<?php
                                foreach ($recordday as $RD) {
                                    if ($RD['adt_day'] == $day['weekday']) {
                                        if ($RD['dt_id'] == "3") {
 ?>
                                        <tr class="" style="background-color: khaki;">
                        <?php } else if ($RD['dt_id'] == "4") {
 ?>
                                        <tr class="" style="background-color: wheat;">
                        <?php } else {
 ?>
                        <?php foreach ($leaveholiday as $HD) {
                                                if ($HD['leave_holiday_date'] == $Attendance->getAtn_date()) {
 ?>
                                                <tr class="" style="background-color: sandybrown" >
                            <?php $var = "1";
                                                }
                                            } ?>
                            <?php if ($var != "1") {
 ?>
                                        <tr class="<?php echo $cssClass ?>" >
                            <?php
                                            }
                                        }
                                    }
                                } ?>
                             
                            <td class="">
                        <?php echo $Attendance->clk_no; ?>
                        </td>
                            <td class="">
                            <?php
                                if ($Culture == 'en') {
                                    $abcd = "getEmp_display_name";
                                } else {
                                    $abcd = "getEmp_display_name_" . $Culture;
                                }
                                $dd = $Attendance->Employee->$abcd();
                                $rest = substr($Attendance->Employee->$abcd(), 0, 100);

                                if ($Attendance->Employee->$abcd() == null) {
                                    $dd = $Attendance->Employee->getEmp_display_name();
                                    $rest = substr($Attendance->Employee->getEmp_display_name(), 0, 100);
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                } else {

                                    if (strlen($dd) > 100) {
                                        echo $rest
                            ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                }
                            ?>
                        </td>
                        <td class="">
<?php echo LocaleUtil::getInstance()->formatDate($Attendance->getAtn_date()); ?>
                        </td>
                        <td class=""><?php $timein = getdate(strtotime($Attendance->getAtn_intime())); ?>
                            <div id="Intime_<?php echo $row ?>" >
                                <div id="EIntimeHH_<?php echo $row ?>"> <select name="IntimeHH_<?php echo $row ?>" id="<?php echo $row ?>" class="formSelect" style="width: 40px; margin-left: 0px;" title="<?php echo __("Please Edit In Time Hours") ?>">
                                        <option value="" ><?php echo __("HH") ?></option>
                                        <?php for ($i = 0; $i < 24; $i++) {
                                            if ($i < 10) { ?>
                                                <option value="<?php echo "0" . $i ?>" <?php if ($timein['hours'] == $i
                                                    )echo "selected=selected"; ?> ><?php echo "0" . $i ?></option>
<?php }else { ?>
                                                <option value=<?php echo $i ?> <?php if ($timein['hours'] == $i
                                                    )echo "selected=selected"; ?>><?php echo $i ?></option>
<?php }
                                        } ?> </select></div>

                                            <div id="EIntimeMM_<?php echo $row ?>">
                                                <select name="IntimeMM_<?php echo $row ?>" id="IntimeMM_<?php echo $row ?>" class="formSelect" style="width: 40px;margin-left: 0px;" title="<?php echo __("Please Edit In Time Minutes") ?>">
                                            <option value='' ><?php echo __("MM") ?></option>
                                    <?php for ($i = 0; $i < 60; $i+=1) {
                                            if ($i < 10) { ?>
                                                    <option value=<?php echo "0" . $i ?> <?php if ($timein['minutes'] == $i
                                                    )echo "selected=selected"; ?>><?php echo "0" . $i ?></option>
<?php }else { ?>
                                                    <option value=<?php echo $i ?> <?php if ($timein['minutes'] == $i
                                                    )echo "selected=selected"; ?>><?php echo $i ?></option>
                                    <?php }
                                        } ?> </select>
                                    </div></div>
                            </td>
                            <td class="">
<?php $timeout = getdate(strtotime($Attendance->getAtn_outtime())); ?>
                                <div id="Outtime_<?php echo $row ?>"> <?php //echo $Attendance->getAtn_outtime();  ?></div>
                                <div id="EOuttimeHH_<?php echo $row ?>">
                                    <select name="OuttimeHH_<?php echo $row ?>" id="OuttimeHH_<?php echo $row ?>" class="formSelect" style="width: 40px;margin-left: 0px;" title="<?php echo __("Please Edit In Time Hours") ?>">
                                        <option value="" ><?php echo __("HH") ?></option>
<?php for ($i = 0; $i < 24; $i++) {
                                            if ($i < 10) { ?>
                                                    <option value="<?php echo "0" . $i ?>" <?php if ($timeout['hours'] == $i
                                                    )echo "selected=selected"; ?> ><?php echo "0" . $i ?></option>
                                <?php }else {
 ?>
                                                    <option value=<?php echo $i ?> <?php if ($timeout['hours'] == $i
                                                    )echo "selected=selected"; ?>><?php echo $i ?></option>
<?php }
                                        } ?> </select>
                                        </div>
                                        <div id="EOuttimeMM_<?php echo $row ?>">
                                            <select name="OuttimeMM_<?php echo $row ?>" id="OuttimeMM_<?php echo $row ?>" class="formSelect" style="width: 40px;margin-left: 0px;" title="<?php echo __("Please Edit In Time Minutes") ?>">
                                                <option value='' ><?php echo __("MM") ?></option>
                            <?php for ($i = 0; $i < 60; $i+=1) {
                                            if ($i < 10) {
 ?>
                                                        <option value=<?php echo "0" . $i ?> <?php if ($timeout['minutes'] == $i
                                                    )echo "selected=selected"; ?>><?php echo "0" . $i ?></option>
                            <?php }else {
 ?>
                                                        <option value=<?php echo $i ?> <?php if ($timeout['minutes'] == $i
                                                    )echo "selected=selected"; ?>><?php echo $i ?></option>
                            <?php }
                                        } ?> </select>
                                        </div>
                                    </td>
                                    <td class="">
                                        <div id="late_<?php echo $row ?>">
                            <?php
                                                    $datetime1 = new DateTime($Attendance->getAtn_intime());
                                                    $datetime2 = new DateTime($Attendance->getAtn_outtime());
                                                    $interval = $datetime1->diff($datetime2);
                                                    
                                                    $hours   = $interval->format('%h');
                                                    $minutes = $interval->format('%i');
                                                    if($hours < 10){
                                                        $hours="0".$hours;
                                                    }
                                                    if($minutes < 10){
                                                        $minutes="0".$minutes;
                                                    }
                                                    
                                                    echo $hours .":".$minutes;
                                                    
                                                    ?>
                                            
                                        </div>
                                    </td>
                                    
                                    <td class="">
                                        <div id="late_<?php echo $row ?>">
                            <?php $datetime1 = new DateTime($Attendance->getAtn_latetime());
                                    echo $datetime1->format("H:i");
                            
                            ?>
                                        </div>
                                    </td>
                                    <td class="">
                                        <div id="early_<?php echo $row ?>">
<?php 
                                $datetime1 = new DateTime($Attendance->getAtn_earlydeptime());
                                    echo $datetime1->format("H:i");
 ?>
                                        </div>
                                    </td>
                                    <td class="">

<?php
                                        $atnda = new attendanceDao();
                                        $leavetype = $atnda->readallleavedetails($Attendance->getAtn_date(), $Attendance->getEmp_number());
                                        if (leavetype != null) {
                                            if ($Culture == 'en') {
                                                echo $leavetype[0]['LeaveType']['leave_type_name'];
                                            } else if ($Culture == 'si') {
                                                if ($leavetype[0]['LeaveType']['leave_type_name_si'] != null) {
                                                    echo $leavetype[0]['LeaveType']['leave_type_name_si'];
                                                } else {
                                                    echo $leavetype[0]['LeaveType']['leave_type_name'];
                                                }
                                            } else {
                                                if ($leavetype[0]['LeaveType']['leave_type_name_si'] != null) {
                                                    echo $leavetype[0]['LeaveType']['leave_type_name_ta'];
                                                } else {
                                                    echo $leavetype[0]['LeaveType']['leave_type_name'];
                                                }
                                            }
                                        }
?>
                                                                </td>

                                                                <td class=""><?php $abcc = $Attendance->getAtn_date();
                                        $cc = str_replace('-', '', $abcc); ?>
                                                                    <input type="button" class="plainbtn editBtn"
                                                                           name="editBtn_<?php echo $row ?>"
                                                                           id="editBtn_<?php echo $row ?>"
                                                                           value="<?php echo __("Edit") ?>"
                                                                           onclick='save1(this.id,this.value,<?php echo $Attendance->getEmp_number(); ?>,"<?php echo $Attendance->getClk_no(); ?>","<?php echo $cc; ?>",<?php echo $row; ?>,$("#IntimeHH_<?php echo $row ?>").val(),$("#IntimeMM_<?php echo $row ?>").val(),$("#OuttimeHH_<?php echo $row ?>").val(),$("#OuttimeMM_<?php echo $row ?>").val())'
                                                                           />
                                                                </td>
                                                            </tr>
<?php } ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
                                        <br class="clear" />
                                    </div>

<?php
                                    require_once '../../lib/common/LocaleUtil.php';
                                    $sysConf = OrangeConfig::getInstance()->getSysConf();
                                    $sysConf = new sysConf();
                                    $inputDate = $sysConf->dateInputHint;
                                    $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());

//$format=$sysConf->dateFormat;
?>

                                    <script type="text/javascript">
                                        function disableAnchor(obj, disable){
                                            if(disable){
                                                var href = obj.getAttribute("href");
                                                if(href && href != "" && href != null){
                                                    obj.setAttribute('href_bak', href);
                                                }
                                                obj.removeAttribute('href');
                                                obj.style.color="gray";
                                            }
                                            else{
                                                obj.setAttribute('href', obj.attributes
                                                ['href_bak'].nodeValue);
                                                obj.style.color="blue";
                                            }
                                        }
                                        function displaymessage()
                                        {
                                            $("#abc").val(1);
                                        }
                                        var mode;
                                        function SelectEmployee(data){

                                            myArr = data.split('|');
                                            $("#txtEmpId").val(myArr[0]);
                                            $("#txtEmployeeName").val(myArr[1]);
                                            displaymessage();

                                        }

                                        //edit
                                        var mode='edit';

                                        $('#gridsave :input').attr('disabled', true);
                                        $('#gridsave :button').removeAttr('disabled');
                                        function save1(id,value,empno,empaid,atndate,row,intimehh,intimemm,outtimehh,outtimemm){
                                            if(outtimehh <= intimehh){
                                                if(outtimemm <= intimemm){
                                                    alert("<?php echo __("Invalid Date In Out Time") ?>");
                                                    return false;
                                                }
                                            }
                                            $('#gridsave :input').attr('disabled', true);

                                            $('#'+id).removeAttr('disabled');
                                            $('#editBtn_'+row).removeAttr('disabled');


                                            if(mode=='edit'){
                                                $.post(

                                                "<?php echo url_for('attendance/ajaxTableLock') ?>", //Ajax file

                                                { lock : 1 , empno:empno ,empaid : empaid, atndate:atndate },  // create an object will all values

                                                //function that is called when server returns a value.
                                                function(data){


                                                    if(data.lockMode==0){
                                                        alert("<?php echo __("Can not Update Record Lock") ?>");
                                                        $('#gridsave :button').removeAttr('disabled');
                                                        $('#editBtn_'+row).attr('disabled', true);
                                                    }
                                                    else{

                                                        mode='save';

                                                        var comboinHH="";
                                                        var comboinMM="";
                                                        var combooutHH="";
                                                        var combooutMM="";

                                                        $('#'+id).attr('value', '<?php echo __("Save") ?>');
                                                        comboinHH="<select name='IntimeHH_"+row+"' id='IntimeHH_"+row+"' class='formSelect' style='width: 40px;margin-left: 0px;' title='<?php echo __("Please Edit In Time Hours") ?>'>";
                                                        comboinHH+="<option value='' <?php //echo "selected=selected" ?>><?php echo __("HH") ?></option>";
                                                        comboinHH+="<?php for ($i = 0; $i < 24; $i++) {
                                        if ($i < 10) { ?>";
                                                                comboinHH+="<option value=<?php echo "0" . $i ?> ><?php echo "0" . $i ?></option>";
                                                                comboinHH+="<?php } else { ?>";
                                                                comboinHH+="<option value=<?php echo $i ?> ><?php echo $i ?></option>";
                                                                comboinHH+="   <?php }
                                    } ?> </select>";

                                                        comboinMM="<select name='IntimeMM_"+row+"' id='IntimeMM_"+row+"' class='formSelect' style='width: 40px;margin-left: 0px;' title='<?php echo __("Please Edit In Time Minutes") ?>'>";
                                                        comboinMM+="<option value='' <?php //echo "selected=selected" ?>><?php echo __("MM") ?></option>";
                                                        comboinMM+="<?php for ($i = 0; $i < 60; $i+=1) {
                                        if ($i < 10) { ?>";
                                                                comboinMM+=" <option value=<?php echo "0" . $i ?>><?php echo "0" . $i ?></option>";
                                                                comboinMM+="<?php } else { ?>";
                                                                comboinMM+="<option value=<?php echo $i ?>><?php echo $i ?></option>";
                                                                comboinMM+="<?php }
                                    } ?> </select>";

                                                        combooutHH="<select name='OuttimeHH_"+row+"' id='OuttimeHH_"+row+"' class='formSelect' style='width: 40px;margin-left: 0px;' title='<?php echo __("Please Edit Out Time Hours") ?>'>";
                                                        combooutHH+="<option value='' <?php //echo "selected=selected" ?>><?php echo __("HH") ?></option>";
                                                        combooutHH+="<?php for ($i = 0; $i < 24; $i++) {
                                        if ($i < 10) { ?>";
                                                                combooutHH+="<option value=<?php echo "0" . $i ?>><?php echo "0" . $i ?></option>";
                                                                combooutHH+="<?php } else { ?>";
                                                                combooutHH+="<option value=<?php echo $i ?>><?php echo $i ?></option>";
                                                                combooutHH+="   <?php }
                                    } ?> </select>";

                                                        combooutMM="<select name='OuttimeMM_"+row+"' id='OuttimeMM_"+row+"' class='formSelect' style='width: 40px;margin-left: 0px;' title='<?php echo __("Please Edit Out Time Minutes") ?>'>";
                                                        combooutMM+="<option value='' <?php //echo "selected=selected" ?>><?php echo __("MM") ?></option>";
                                                        combooutMM+="<?php for ($i = 0; $i < 60; $i+=1) {
                                        if ($i < 10) { ?>";
                                                                combooutMM+=" <option value=<?php echo "0" . $i ?>><?php echo "0" . $i ?></option>";
                                                                combooutMM+="<?php } else { ?>";
                                                                combooutMM+="<option value=<?php echo $i ?>><?php echo $i ?></option>";
                                                                combooutMM+="<?php }
                                    } ?> </select>";

                                                        $('#early_'+row).html("");
                                                        $('#late_'+row).html("");
                                                        $('#early_'+row).html(data.early);
                                                        $('#late_'+row).html(data.late);
                                                        $('#EIntimeHH_'+row).html(comboinHH);
                                                        $('#EIntimeMM_'+row).html(comboinMM);
                                                        $('#EOuttimeHH_'+row).html(combooutHH);
                                                        $('#EOuttimeMM_'+row).html(combooutMM);
                                                        $("#IntimeHH_"+row+" option[value="+data.intimehrs+"]").attr("selected", "selected");
                                                        $("#IntimeMM_"+row+" option[value="+data.intimemins+"]").attr("selected", "selected");
                                                        $("#OuttimeHH_"+row+" option[value="+data.outtimehrs+"]").attr("selected", "selected");
                                                        $("#OuttimeMM_"+row+" option[value="+data.outtimemins+"]").attr("selected", "selected");
                                                    }

                                                },


                                                "json"

                                            );


                                            }
                                            else{


                                                $.post(
                                                "<?php echo url_for('attendance/Savedata') ?>", //Ajax file

                                                { lock : 0 , empno:empno , empaid:empaid, atndate:atndate ,intimehh:intimehh, intimemm:intimemm, outtimehh:outtimehh, outtimemm:outtimemm  },  // create an object will all values

                                                //function that is called when server returns a value.
                                                function(data){

                                                    if(data.isupdated=="true"){

                                                        mode='edit';
                                                        $('#'+id).attr('value', '<?php echo __("Edit") ?>');
                                                        alert("<?php echo __("Successfully Updated") ?>");
                                                        $('#gridsave :button').removeAttr('disabled');
                                                        $.post(

                                                        "<?php echo url_for('attendance/ajaxTableLock') ?>", //Ajax file

                                                        { lock : 0 , empno:empno , empaid :empaid, atndate:atndate },  // create an object will all values

                                                        //function that is called when server returns a value.
                                                        function(data){


                                                            if(data.lockMode==0){
                                                                mode='edit';
                                                                //location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/attendance/Process')) ?>";

                                                            }

                                                            else{
                                                                mode='edit';
                                                                $('#'+id).attr('value', '<?php echo __("Save") ?>');
                                                            }
                                                        },

                                                        "json"
                                                    );

                                                    }
                                                    else{
                                                        alert("<?php echo __("Error") ?>");
                                                    }

                                                },


                                                "json"

                                            );

                                            }
                                        }


                                        $(document).ready(function() {
                                            buttonSecurityCommonMultiple(null,null,"editBtn",null);
                                            if($("#fdate").val()==""){
                                                $("#fdate").val("<?php echo LocaleUtil::getInstance()->formatDate(date("Y-m-d")) ?>");
                                            }
                                            if($("#tdate").val()==""){
                                                $("#tdate").val("<?php echo LocaleUtil::getInstance()->formatDate(date("Y-m-d")) ?>");
                                            }
                                            jQuery.validator.addMethod("orange_date",
                                            function(value, element, params) {


                                                var format = params[0];

                                                // date is not required
                                                if (value == '') {

                                                    return true;
                                                }
                                                var d = strToDate(value, "<?php echo $format ?>");


                                                return (d != false);

                                            }, ""
                                        );

                                            $('#empRepPopBtn').click(function() {

                                                var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee&reason=atte'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                                                if(!popup.opener) popup.opener=self;
                                                popup.focus();
                                            });

                                            //Validate the form
                                            $("#frmSave").validate({
                                                rules: {
                                                    txtfdate: { required: true,orange_date:true },
                                                    txttdate: { required: true,orange_date:true }

                                                },
                                                messages: {

                                                    txtfdate: { required:"<?php echo __("Please Enter From Date") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"},
                                                    txttdate: { required:"<?php echo __("Please Enter To Date") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"}

                                                }
                                            });

                                            // When click process button
                                            $("#viewall").click(function() {
                                                //alert("df");
                                                var from=$("#fdate").val();
                                                var to=$("#tdate").val();
                                                if(from > to){
                                                    alert("<?php echo __("From Date should be less than to To Date") ?>");
                                                    return false;
                                                }else{
                                                    $('#frmSave').submit();
                                                }
                                            });

                                            //When click reset buton
                                            $("#resetBtn").click(function() {
                                                location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/attendance/Process')) ?>";
                                            });

                                            $("#fdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });
                                            $("#tdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });

    });
</script>