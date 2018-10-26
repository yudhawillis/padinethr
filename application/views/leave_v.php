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

    //    if(isset($status_form)) {
    //        if ($status_form == "add") {
    //            ?>
    <!--            <form method="post" action="--><?php //echo base_url(); ?><!--admin/affiliate/tambah_data/" enctype="multipart/form-data"/>-->
    <!--            --><?php
    //        } else if ($status_form == "edit") {
    //            ?>
    <!--            <form method="post" action="--><?php //echo base_url(); ?><!--admin/affiliate/edit_data/--><?php //echo $list_affiliate[0]['id_affiliate'] ?><!--" enctype="multipart/form-data"/>-->
    <!--            --><?php
    //        }
    //    }
    ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Leave</h1>
                    <?php

                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Profile
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($current_user as $user) { ?>
                                            <div class="col-md-6">
                                                <table>
                                                </table>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php

                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Leave
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($current_user as $user) { ?>
                                            <div class="col-md-4">
                                                Foto

                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                if(isset($status_form)) {
                                                    ?>
                                                    <div class="form-dkm">
                                                        <div
                                                            <?php
                                                            if (form_error('tgl_artikel') != "") echo "class = 'alert alert-danger row'";
                                                            else echo "class = 'row'"
                                                            ?>
                                                        >
                                                            <div class="col-lg-3">
                                                                <label for="tgl_artikel">Tanggal Artikel <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input data-provide="datepicker" type="text" class="form-control" id="tgl_artikel" name="tgl_artikel" value="<?php if (form_error('tgl_artikel') != "") echo set_value('tgl_artikel'); else if(isset($tgl_artikel)) echo $tgl_artikel; else echo $list_artikel[0]['tgl_artikel'];?>">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <input data-provide="datepicker" type="text" class="form-control" id="tgl_artikel" name="tgl_artikel" value="<?php echo set_value('tgl_artikel'); ?>">
                                                                    <?php
                                                                }
                                                                echo form_error('tgl_artikel');?>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div
                                                            <?php
                                                            if (form_error('link_affiliate') != "") echo "class = 'alert alert-danger row'";
                                                            else echo "class = 'row'"
                                                            ?>
                                                        >
                                                            <div class="col-lg-3">
                                                                <label for="link_affiliate">Link </label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="link_affiliate" name="link_affiliate" value="<?php if (form_error('link_affiliate') != "") echo set_value('link_affiliate'); else if(isset($link_affiliate)) echo $link_affiliate; else echo $list_affiliate[0]['link_affiliate'];?>">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <input type="text" class="form-control" id="link_affiliate" name="link_affiliate" value="<?php echo set_value('link_affiliate'); ?>">
                                                                    <?php
                                                                }
                                                                echo form_error('link_affiliate');?>
                                                                <i>Type with 'http://'</i>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <label for="cover">Image
                                                                    <a href="#" data-toggle="tooltip" data-placement="auto" title="Ukuran image disarankan sebesar : lebar 1200px, tinggi 563px (atau serasio).">
                                                                        <span class="glyphicon glyphicon-question-sign"></span>
                                                                    </a>
                                                                    <br /><i>(max. 500Kb - jpg,png,gif)</i></label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <input type="file" name="foto" id="foto" class="filestyle" data-buttonBefore="true" accept="image/x-png, image/gif, image/jpeg"/>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div
                                                            <?php
                                                            if (form_error('status_aktif') != "") echo "class = 'alert alert-danger row'";
                                                            else echo "class = 'row'"
                                                            ?>
                                                        >
                                                            <div class="col-lg-3">
                                                                <label for="status_aktif">Status Aktif</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="checkbox" id="status_aktif" name="status_aktif" value="1" <?php if (form_error('status_aktif') != "") echo set_value('status_aktif'); else if(isset($status_aktif)) echo $status_aktif; else echo $list_affiliate[0]['status'];?>> Active
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <input type="checkbox" id="status_aktif" name="status_aktif" value="1" <?php echo set_value('status_aktif'); ?>> Active
                                                                    <?php
                                                                }
                                                                echo form_error('status_aktif');?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <br />
                                                    <br /><br />
                                                    <button type="submit" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Simpan</button>  <a href="<?php echo base_url(); ?>admin/affiliate"><button type="button" class="btn btn-warning btn-md"><span class="glyphicon glyphicon-floppy-remove"></span> Batal</button></a>

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
        </div>
    </div>
    </form>

</div>
<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
