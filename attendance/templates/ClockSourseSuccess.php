<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript">


</script>

<div class="formpage4col">
    <div class="navigation">

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Employee Attendance") ?></h2></div>
        <?php echo message() ?>


        <div id="tabs" style="height: 200px;width: 500px;">
            <ul>
                <li><a href="#tabs-1"><?php echo __("Text") ?></a></li>
<!--                <li><a href="#tabs-2"><?php echo __("MS Excel") ?></a></li>
                <li><a href="#tabs-3"><?php echo __("MS Access") ?></a></li>
                <li><a href="#tabs-4"><?php echo __("MS SQL Server") ?></a></li>-->



            </ul>
            <div id="tabs-1"><p><?php echo __("Text") ?></p>

                <form enctype="multipart/form-data" action="<?php echo url_for('attendance/Text') ?>" method="POST" id="txtfile" name="txtfile"  >
                    <div class="leftCol"><label class="controlLabel" for="txtLocationCode"><?php echo __("Upload Text file") ?> </label></div>
                    <div class="centerCol"><INPUT TYPE="file" class="formInputText" VALUE="Upload" name="txtletter"></div>
                    <br class="clear"/>
                    <br class="clear"/>
                    <input class="plainbtn" type="submit" value="<?php echo __("Upload") ?>">
                </form>


            </div>

        </div>
        <br class="clear"/>
        <div class="centerCol" align=center>
            <form name="frmSave" id="frmSave" method="post"  action="<?php echo url_for('attendance/dataProcess') ?>">
                <input class="plainbtn" type="submit" value="<?php echo __("process") ?>">

            </form>
        </div>
        <br class="clear"/>
        <br class="clear"/>



    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(function() {
            $("#tabs").tabs();
        });

    });
</script>