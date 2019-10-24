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
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Download Configuration") ?></h2></div>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>

            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Text File Configuration") ?> </label>
            </div>
            <br class="clear"/>

            <div id="tabel2" style="width: 400px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <td scope="col" style="width: 200px;"><?php echo __("Field Name"); ?></td>
                            <td scope="col" style="width: 100px;"><?php echo __("Start Position"); ?></td>
                            <td scope="col" style="width: 100px;"><?php echo __("End Position"); ?></td>
                        </tr>
                    </thead>
                    <?php foreach ($fieldlist as $row) {
                    ?>
                        <tr><td >
                                <input style="width: 200px;" id="fieldname<?php echo $row['aff_id'] ?>" type="text" name="fieldname<?php echo $row['aff_id'] ?>" readonly="readonly" value="<?php echo __($row['aff_fieldname']); ?>" >
                            </td><td>
                                <input  style="width: 100px;" id="StratPosition<?php echo $row['aff_id'] ?>" type="text" name="StratPosition<?php echo $row['aff_id'] ?>" value="<?php echo $row['aff_fieldstartposition']; ?>" onkeypress='return onkeyUpevent(event)' maxlength="2">
                            </td><td>
                                <input  style="width: 100px;" id="EndPosition<?php echo $row['aff_id'] ?>" type="text" name="EndPosition<?php echo $row['aff_id'] ?>" value="<?php echo $row['aff_fieldendposition']; ?>" onkeypress='return onkeyUpevent(event)' maxlength="2" >
                            </td>

                        <?php if ($row['aff_id'] == "2") {
                        ?>

                        <?php } elseif ($row['aff_id'] == "3") {
 ?>

<?php } ?>

                    </tr>
<?php } ?>
                </table>

            </div>
            <br class="clear">
        </form>



        <div class="formbuttons">
            <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                   value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                   title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                   value="<?php echo __("Reset"); ?>" />
        </div>

    </div>
</div>

<script type="text/javascript">
    function onkeyUpevent(e){


        var keynum;
        var keychar;
        var numcheck;


        if(window.event) // IE
        {
            keynum = e.keyCode;
        }
        else if(e.which) // Netscape/Firefox/Opera
        {
            keynum = e.which;
        }
        keychar = String.fromCharCode(keynum);
        //numcheck = /^[^@\*\!#\$%\^&()-~`\+=]+$/i;
        numcheck = /^[0-9\b^?]+$/;

        if(!numcheck.test(keychar)){
            alert("<?php echo __('No invalid characters are allowed') ?>");
            return false;
        }
    }
                             
    $(document).ready(function() {
        buttonSecurityCommon("null","null","editBtn","null");
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

                location.href="<?php echo url_for('attendance/DownloadConfiguration?id=1&lock=1') ?>";
            }
            else {

                var inval=0;
<?php foreach ($fieldlist as $row) { ?>
                                               if($('#StratPosition<?php echo $row['aff_id'] ?>').val()==""){
                                                   inval=1;
                                               }
                                               if($('#EndPosition<?php echo $row['aff_id'] ?>').val()==""){
                                                   inval=1;
                                               }
<?php } ?>
                                                if(inval==1){
                                                    alert("<?php echo __("Start and End position can not be blank ") ?>");
                                                    return false;
                                                }else{
                                                    $('#frmSave').submit();
                                                }
                                            }


                                        });

                                        //When Click back button
                                        $("#btnBack").click(function() {
                                            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/attendance/DownloadConfiguration')) ?>";
                                        });

                                        //When click reset buton
                                        $("#btnClear").click(function() {
                                            // Set lock = 0 when resetting table lock
                                            location.href="<?php echo url_for('attendance/DownloadConfiguration?id=1&lock=0') ?>";
                                        });
                                    });
</script>
