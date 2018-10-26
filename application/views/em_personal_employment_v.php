
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee - PadiHR </title>
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
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <h1 class="page-header">Detil Employee</h1>
                <div class="col-lg-12">
                    <!--                    <h1 class="page-header">My Request</h1>-->
                    <?php
                    if ($this->session->flashdata('pesan')!=NULL){
                        ?>
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> <?php echo $this->session->flashdata('pesan'); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            $data["active"] = $active;
                            $this->load->view('element/submenu_employee_v', $data);
                            ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Employment
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($list_personal_employment as $emp) { ?>
                                            <div class="col-md-12">
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>Employment Level</td>
                                                        <td> : </td>
                                                        <td><?php echo $emp['level_name'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Leave Quota</td>
                                                        <td> : </td>
                                                        <td>
                                                            <?php echo $emp['leave_quota'] ?> day(s)
                                                            <?php
                                                            if($button_reset){
                                                                ?>
                                                                <a href="<?php echo base_url(); ?>employment/employee/reset_leave/<?php echo $emp['id_employee']; ?>">Reset <span class="glyphicon glyphicon-transfer"></span></a>
                                                                <?php
                                                            }
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Position</td>
                                                        <td> : </td>
                                                        <td><?php echo $emp['position'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>City</td>
                                                        <td> : </td>
                                                        <td><?php echo $emp['name_city'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Supervisor</td>
                                                        <td> : </td>
                                                        <td><?php echo $emp['fullname_sup'] ?></td>
                                                    </tr>
                                                </table>
                                                <br />

                                            </div>
                                        <?php } ?>
                                        <div class="col-md-12">
                                            <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                                ?>
                                                <a href="#"><button type="button" class="btn btn-info btn-md update_employment"><span class="glyphicon glyphicon-floppy-saved"></span> Update Employment</button></a>
                                            <?php
                                            }
                                            ?>
                                            <a href="<?php echo base_url(); ?>employment/employee/employment_history/<?php echo $list_employee[0]['id_employee']  ?>">
                                                <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-book"></span> History</button>
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
    </div>
    <!-- Modal -->
    <div class="modal fade" id="employment_form" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form method="post" action="<?php echo base_url(); ?>employment/employee/tambah_data_employment/<?php echo $list_employee[0]['id_employee'] ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="glyphicon glyphicon-briefcase"></span> Update Employment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-dkm form-employee-add">
                        <div class="col-md-12"
                            <?php
                            if (form_error('id_level') != "") echo "class = 'alert alert-danger row'";
                            else echo "class = 'row'";
                            ?>
                        >
                            <div class="col-lg-3">
                                <label for="id_level">Employment Level <span style="color:red">*</span></label>
                            </div>
                            <div class="col-lg-9">
                                <?php
                                $select_kategori = '';
                                $options = array(''  => '----------------- Choose Level -----------------' );
                                foreach($list_level as $row){
                                    $options[$row['id_level']]= $row['level_name'];
                                }
                                
                                $prop_kategori = "id='id_level' class='form-control selectlevel' required"; //class dari bootstrap
                                if ($status_form == "add") {
                                    echo form_dropdown('id_level', $options, set_value('id_level',$select_id_level), $prop_kategori);
                                } else if ($status_form == "edit") {
                                    echo form_dropdown('id_level', $options, set_value('id_level',$list_employment[0]['id_level']), $prop_kategori);
                                }


                                ?>
                                <?php echo form_error('id_level');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php
                            if (form_error('id_city') != "") echo "class = 'alert alert-danger row'";
                            else echo "class = 'row'";
                            ?>
                        >
                            <div class="col-lg-3">
                                <label for="id_city">City <span style="color:red">*</span></label>
                            </div>
                            <div class="col-lg-9">
                                <?php
                                $select_kategori = '';
                                $options = array(''  => '----------------- Choose City -----------------' );
                                foreach($list_city as $row){
                                    $options[$row['id_city']]= $row['name_city'];
                                }
                                $prop_kategori = "id='id_city' class='form-control selectcity' required"; //class dari bootstrap
                                if ($status_form == "add") {
                                    echo form_dropdown('id_city', $options, set_value('id_city',$select_id_city), $prop_kategori);
                                } else if ($status_form == "edit") {
                                    echo form_dropdown('id_city', $options, set_value('id_city',$list_employment[0]['id_city']), $prop_kategori);
                                }


                                ?>
                                <?php echo form_error('id_city');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php
                            if (form_error('id_employee') != "") echo "class = 'alert alert-danger row'";
                            else echo "class = 'row'";
                            ?>
                        >
                            <div class="col-lg-3">
                                <label for="id_employee">Supervisor </label>
                            </div>
                            <div class="col-lg-9">
                                <?php
                                $select_kategori = '';
                                $options = array(''  => '----------------- Choose Superior -----------------' );
                                foreach($list_all_employee as $row){
                                    $options[$row['id_employee']]= $row['fullname'];
                                }
                                $prop_kategori = "id='id_employee' class='form-control selectemployee'"; //class dari bootstrap
                                if ($status_form == "add") {
                                    echo form_dropdown('id_employee', $options, set_value('id_employee',$select_id_employee), $prop_kategori);
                                } else if ($status_form == "edit") {
                                    echo form_dropdown('id_employee', $options, set_value('id_employee',$list_employment[0]['supervisor']), $prop_kategori);
                                }


                                ?>
                                <?php echo form_error('id_city');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php

                            if (form_error('position') != "") echo "class = 'alert alert-danger'"
                            ?>
                        >
                            <div class="col-md-3">
                                <label for="position">Position <span style="color:red">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <?php
                                if ($status_form=="edit") { ?>
                                    <input type="text" class="form-control" id="position" name="position" value="<?php if (form_error('position') != "") echo set_value('position'); else echo $list_employment[0]['position'];?>" required>
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" id="position" name="position" value="<?php echo set_value('position'); ?>" required>
                                    <?php
                                }
                                echo form_error('position');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php
                            if (form_error('tgl_mulai') != "") echo "class = 'alert alert-danger'"
                            ?>
                        >
                            <div class="col-md-3">
                                <label for="tgl_mulai">Start Date <span style="color:red">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <?php
                                if ($status_form=="edit") { ?>
                                    <input data-provide="tgl_mulai" type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?php if (form_error('tgl_mulai') != "") echo set_value('tgl_mulai'); else echo $list_employment[0]['tgl_mulai'];?>" required>

                                    <?php
                                } else {
                                    ?>
                                    <input data-provide="datepicker" type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?php echo set_value('tgl_mulai'); ?>" requred>
                                    <?php
                                }
                                echo form_error('tgl_mulai');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php
                            if (form_error('description') != "") echo "class = 'alert alert-danger row'";
                            ?>
                        >
                            <div class="col-md-3">
                                <label for="description">Description </label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control" id="description" name="description"><?php echo set_value('description'); ?></textarea>
                                <?php
                                echo form_error('description');?>
                            </div>
                        </div>
                    </div>
                    <!--                        <p>Apakah Anda yakin akan menghapus data affiliate tersebut ?</p>-->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default" id="proses">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('element/footer_v'); ?>

<script>
    $(document).ready(function(){
        $(".update_employment").click(function(){
            $("#employment_form").modal({backdrop: "static"});
            //var id = $(this).attr('name');
            //$("#proses_hapus").attr("href", "<?php //echo base_url(); ?>//admin/affiliate/hapus_data/"+id);
        });
    });
</script>
</body>

</html>
