<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave - PadiWEB</title>
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
            <form method="post" action="<?php echo base_url(); ?>leave/jointholiday/tambah_data"
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
                                    Add Joint Holiday
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="form-dkm">
                                            <div
                                                <?php
                                                if (form_error('date_holiday') != "") echo "class = 'alert alert-danger row'";
                                                else echo "class = 'row'"
                                                ?>
                                            >
                                                <div
                                                    <?php
                                                    if (form_error('date_holiday') != "") echo "class = 'alert alert-danger row'";
                                                    else echo "class = 'row'"
                                                    ?>
                                                >
                                                    <div class="col-md-3">
                                                        <label for="date_holiday">Holiday Date<span style="color:red">*</span></label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <?php
                                                        if ($status_form=="edit") { ?>
                                                        <input type="text" data-provide="datepicker"class="form-control" id="nik_employee" name="nik_employee" value="<?php if (form_error('date_holiday') != "") echo set_value('date_holiday'); else echo $list_holiday[0]['date_holiday'];?>">
                                                        <?php
                                                        } else {
                                                        ?>
                                                        <input type="text" data-provide="datepicker" class="form-control" id="date_holiday" name="date_holiday" value="<?php echo set_value('date_holiday'); ?>">
                                                        <?php
                                                        }
/*                                                        <input data-provide="datepicker" type="text" class="form-control" id="date_holiday" name="date_holiday" value="<?php echo set_value('date_holiday'); ?>">*/
                                                        echo form_error('date_holiday');?>
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
<!--                                                        <textarea class="form-control" id="description" name="description">--><?php //echo set_value('description'); ?><!--</textarea>-->
                                                        <?php
                                                        if ($status_form=="edit") { ?>
                                                        <textarea class="form-control" id="description" name="description"><?php if (form_error('description') != "") echo set_value('description'); else echo $list_holiday[0]['description'];?></textarea>
                                                        <?php
                                                        } else {
                                                            ?>
                                                        <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>">
                                                        <?php
                                                        }
                                                        echo form_error('description');?>
                                                    </div>
                                                </div>

                                            </div>
                                            <br />
                                            <br /><br />
                                            <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                                ?>
                                                <button type="submit" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Simpan</button>
                                            <?php
                                            }
                                            ?>

                                            <a href="<?php echo base_url(); ?>leave/jointholiday">
                                                <button type="button" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Cancel</button>
                                            </a>
                                        </div>
                                    </div>
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


</body>

</html>
