<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<div class="formpage4col">
    <div class="navigation">

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Define Day Types") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <div class="leftCol">
                <label class="languageBar"><?php echo __("Day") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Day Type") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("In Time") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Out Time") ?></label>
            </div>
            <br class="clear"/>

            <?php $day = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); ?>
            <?php for ($j = 0; $j < 7; $j++) {
 ?>
            <?php $w = $$day[$j]; ?>
<?php $a = $$day[$j]; ?>

                <div class="leftCol">
                    <label class="leftCol"  for="txtLocationCode" ><?php echo __($day[$j]) ?><span class="required"> * </span>
                        <input  type="hidden" name="lblday<?php echo $j ?>" id="txtEmpId<?php echo $j ?>" value="<?php echo $day[$j] ?>"/>
                    </label>
                </div>
                <div class="centerCol">
                    <select name="cmbbtype<?php echo $j ?>" id="cmbbtype<?php echo $j ?>" class="formSelect">

<?php foreach ($daytype as $btype) { ?>
                    <option value="<?php echo $btype->getDt_id(); ?>" <?php if ($a[1] == $btype->getDt_id())
                            echo "selected='selected'" ?>> <?php
                            if ($Culture == 'en') {
                                echo $btype->getDt_name();
                            } elseif ($Culture == 'si') {
                                if (($btype->getDt_name_si()) == null) {
                                    echo $btype->getDt_name();
                                } else {
                                    echo $btype->getDt_name_si();
                                }
                            } elseif ($Culture == 'ta') {
                                if (($btype->getDt_name_ta()) == null) {
                                    echo $btype->getDt_name();
                                } else {
                                    echo $btype->getDt_name_ta();
                                }
                            } ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="centerCol">
                    <select name="IntimeHH<?php echo $j ?>" id="intimeHH<?php echo $j ?>" class="formSelect" style="width: 50px;" tabindex="4">

<?php if ($a[2] == -1) { ?>
                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("HH") ?></option>
<?php } else { ?>
                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("HH") ?></option>
<?php } ?>
<?php for ($i = 0; $i < 24; $i++) {
                            if ($i < 10) {
 ?>
                                <option value="<?php echo "0" . $i ?>" <?php if ($a[2] == "0" . $i)
                                    echo "selected='selected'" ?>><?php echo "0" . $i ?></option>
                    <?php }else {
 ?>
                                    <option value="<?php echo $i ?>" <?php if ($a[2] == $i)
                                        echo "selected='selected'" ?>><?php echo $i ?></option>
                    <?php }
                                } ?>


                            </select>

                            <select name="IntimeMM<?php echo $j ?>" id="intimeMM<?php echo $j ?>" class="formSelect" style="width: 50px;" tabindex="4">


<?php if ($a[2] == -1) { ?>
                                    <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("MM") ?></option>
<?php } else { ?>
                                    <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("MM") ?></option>
                    <?php } ?>
<?php for ($i = 0; $i < 60; $i+=5) {
                                    if ($i < 10) {
 ?>
                                        <option value="<?php echo "0" . $i ?>"<?php if ($a[3] == "0" . $i)
                                            echo "selected='selected'" ?>><?php echo "0" . $i ?></option>
                    <?php }else {
 ?>
                                            <option value="<?php echo $i ?>" <?php if ($a[3] == $i)
                                                echo "selected='selected'" ?>><?php echo $i ?></option>
<?php }
                                        } ?>


                                    </select>
                                </div>
                                <div class="centerCol">
                                    <select name="OuttimeHH<?php echo $j ?>" id="outtimeHH<?php echo $j ?>" class="formSelect" style="width: 50px;" tabindex="4">

<?php if ($a[4] == -1) { ?>
                                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("HH") ?></option>
                    <?php } else {
 ?>
                                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("HH") ?></option>
<?php } ?>
                    <?php for ($i = 0; $i < 24; $i++) {
                                            if ($i < 10) {
 ?>
                                                <option value="<?php echo "0" . $i ?>"<?php if ($a[4] == "0" . $i)
                                                    echo "selected='selected'" ?>><?php echo "0" . $i ?></option>
                    <?php }else {
 ?>
                                                    <option value="<?php echo $i ?>"<?php if ($a[4] == $i)
                                                        echo "selected='selected'" ?>><?php echo $i ?></option>
                    <?php }
                                                } ?>


                                            </select>

                                            <select name="OuttimeMM<?php echo $j ?>" id="outtimeMM<?php echo $j ?>" class="formSelect" style="width: 50px;" tabindex="4">


<?php if ($a[4] == -1) { ?>
                                                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("MM") ?></option>
<?php } else { ?>
                                                            <option value="-1" <?php echo "selected='selected'" ?>><?php echo __("MM") ?></option>
<?php } ?>
<?php for ($i = 0; $i < 60; $i+=5) {
                                                    if ($i < 10) { ?>
                                                                <option value="<?php echo "0" . $i ?>"<?php if ($a[5] == "0" . $i)
                                                            echo "selected='selected'" ?>><?php echo "0" . $i ?></option>
<?php }else { ?>
                                                                    <option value="<?php echo $i ?>"<?php if ($a[5] == $i)
                                                                echo "selected='selected'" ?>><?php echo $i ?></option>
<?php }
                                                        } ?>


                                                            </select>

                                                        </div>
                                                        <br class="clear"/>

<?php } ?>


                                                    <br class="clear"/>

                                                    <div class="formbuttons">
                                                        <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                                                               value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                                                               title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                                                               onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                                                        <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                                                               onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                                                               value="<?php echo __("Reset"); ?>" />
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
                                            <br class="clear" />
                                        </div>

                                        <script type="text/javascript">

                                            $(document).ready(function() {
                                                buttonSecurityCommon("null","null","editBtn","null");

                                                // var mode = 'edit';
<?php if ($editMode == true) { ?>
                                                 $('#frmSave :input').attr('disabled', true);
                                                 $('#editBtn').removeAttr('disabled');
<?php } ?>

                                             // When click edit button
                                             $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                                             $("#editBtn").click(function() {

                                                 var editMode = $("#frmSave").data('edit');
                                                 if (editMode == 1) {
                                                     // Set lock = 1 when requesting a table lock

                                                     location.href="<?php echo url_for('attendance/AttendanceDay?id=' . array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday") . '&lock=1') ?>";
                                                 }
                                                 else {

                                                     var day=new Array("<?php echo __("Monday"); ?>","<?php echo __("Tuesday"); ?>","<?php echo __("Wednesday"); ?>","<?php echo __("Thursday"); ?>","<?php echo __("Friday"); ?>","<?php echo __("Saturday"); ?>","<?php echo __("Sunday"); ?>");
                                                     var a=1;
                                                     for(i=0; i<7; i++){

                                                         if(($("#intimeHH"+i).val()==-1 && $("#intimeMM"+i).val()==-1) && ($("#outtimeHH"+i).val()!=-1 && $("#outtimeMM"+i).val() !=-1)){
                                                             alert(" <?php echo __("Plese enter shift In Time on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#outtimeHH"+i).val()==-1 && $("#outtimeMM"+i).val()==-1) && ($("#intimeHH"+i).val()!=-1 && $("#intimeMM"+i).val() !=-1)){
                                                             alert(" <?php echo __("Plese enter shift Out Time on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#intimeHH"+i).val()==-1 && $("#intimeMM"+i).val()==-1) && ($("#outtimeHH"+i).val()!=-1 || $("#outtimeMM"+i).val() !=-1)){
                                                             alert(" <?php echo __("Plese enter shift In Time on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#outtimeHH"+i).val()==-1 && $("#outtimeMM"+i).val()==-1) && ($("#intimeHH"+i).val()!=-1 || $("#intimeMM"+i).val() !=-1)){
                                                             alert(" <?php echo __("Plese enter shift Out Time on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#outtimeHH"+i).val()!=-1 && $("#outtimeMM"+i).val()==-1) || ($("#intimeHH"+i).val()!=-1 && $("#intimeMM"+i).val() ==-1)){
                                                             alert(" <?php echo __("Plese select shift Time Minutes on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#outtimeHH"+i).val()==-1 && $("#outtimeMM"+i).val()!=-1) || ($("#intimeHH"+i).val()==-1 && $("#intimeMM"+i).val() !=-1)){
                                                             alert(" <?php echo __("Plese select shift Time Hours on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#outtimeHH"+i).val()==-1 || $("#outtimeMM"+i).val()==-1) || ($("#intimeHH"+i).val()==-1 || $("#intimeMM"+i).val() ==-1)){
                                                             alert(" <?php echo __("Please select Time In and Time Out on ") ?>"+day[i]);
                                                             a=0;
                                                         }
                                                         else if(($("#cmbbtype"+i).val()==1)){
                                                             alert(" <?php echo __("Plese select Day type on ") ?>"+day[i]);
                                                             a=0;
                                                         }


                                                     }
                                                     if(a==1){
                                                         $('#frmSave').submit();
                                                     }
                                                     //$('#frmSave').submit();
                                                 }


                                             });

                                             //When click reset buton
                                             $("#resetBtn").click(function() {
                                                 // Set lock = 0 when resetting table lock
                                                 location.href="<?php echo url_for('attendance/AttendanceDay?id=' . array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday") . '&lock=0') ?>";
     });

 });
</script>