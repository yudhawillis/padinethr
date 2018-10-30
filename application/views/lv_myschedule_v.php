<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave - PadiHR</title>
    <?php $this->load->view('element/header_v'); ?>

</head>

<body>

<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php
        $data['active'] = "leave";
        $this->load->view('element/nav_topbar_v');
        $this->load->view('element/nav_sidebar_v',$data);
        ?>
    </nav>
    <?php

        if(isset($status_form)) {
            if ($status_form == "add") {
                ?>
                <form method="post" action="<?php echo base_url(); ?>leave/myschedule/"
                <?php
            }
        }
    ?>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Leave</h1>
                    <?php

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php

                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Schedule a Leave
                                </div>
                                <div class="panel-body">
                                        <?php foreach ($current_user as $user) { ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <i>
                                                        Current Leave Quota :
                                                        <?php
                                                        echo $current_quota." day/s";
                                                        ?>
														<br />Current Leave Quota Extended :
														<?php
														echo $current_quota_ext." day/s (max 3 days)";
														?>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="col-md-12">

                                                <?php
                                                if(isset($status_form)) {
                                                    ?>
                                                    <div class="form-dkm">
                                                        <input type="hidden" class="form-control" id="notif_current_quota" name="notif_current_quota" value="<?php
                                                        if(isset($notif_current_quota)) echo $notif_current_quota;
                                                        ?>">
                                                        <div
                                                            <?php
                                                            if (form_error('start_date') != "") echo "class = 'alert alert-danger row'";
                                                            else echo "class = 'row'"
                                                            ?>
                                                        >
                                                            <div
                                                                <?php
                                                                if (form_error('start_date') != "") echo "class = 'alert alert-danger row'";
                                                                else echo "class = 'row'"
                                                                ?>
                                                            >
                                                                <div class="col-md-3">
                                                                    <label for="start_date">Start Date <span style="color:red">*</span></label>
                                                                </div>
                                                                <div class="col-md-9">

                                                                    <input data-provide="datepicker" type="text" class="form-control" id="start_date" name="start_date" value="<?php echo set_value('start_date'); ?>">
                                                                    <?php
                                                                    echo form_error('start_date');?>
                                                                </div>
                                                            </div>
                                                            <br />
                                                            <div
                                                                <?php
                                                                if (form_error('end_date') != "") echo "class = 'alert alert-danger row'";
                                                                else echo "class = 'row'"
                                                                ?>
                                                            >
                                                                <div class="col-md-3">
                                                                    <label for="end_date">End Date <span style="color:red">*</span></label>
                                                                </div>
                                                                <div class="col-md-9">

                                                                    <input data-provide="datepicker" type="text" class="form-control" id="end_date" name="end_date" value="<?php echo set_value('end_date'); ?>">
                                                                    <?php
                                                                    echo form_error('end_date');?>
                                                                </div>
                                                            </div>
                                                            <br />
                                                            <div
                                                                <?php
                                                                if (form_error('description') != "") echo "class = 'alert alert-danger row'";
                                                                else echo "class = 'row'"
                                                                ?>
                                                            >
                                                                <div class="col-md-3">
                                                                    <label for="description">Leave Description <span style="color:red">*</span></label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <textarea class="form-control" id="description" name="description"><?php echo set_value('description'); ?></textarea>
                                                                    <?php
                                                                    echo form_error('description');?>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <br />
                                                        <br /><br />
                                                        <button type="submit" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Simpan</button>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>

    </div>
    <?php $this->load->view('element/footer_v'); ?>
    <div class="modal fade" id="quota_notif" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class=" 	glyphicon glyphicon-exclamation-sign"></span> Notification</h4>
                </div>
                <div class="modal-body">
                    <p>Kuota cuti anda sudah mencapai batas. Pengajuan cuti selanjutnya akan diberlakukan pemotongan gaji.</p>
                </div>
                <div class="modal-footer">
<!--                    <a href="#" id="proses_email_penerimaan"><button type="submit" class="btn btn-default" id="proses">Email Penerimaan</button></a>-->
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        var notif_current_quota = $("#notif_current_quota").val();
        //alert(current_quota);
        if(notif_current_quota!= ''){
            $("#quota_notif").modal({backdrop: "static"});
        }
    });
</script>
</html>
