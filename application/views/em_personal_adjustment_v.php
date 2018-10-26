
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
                                    <h4>Personal Adjustment</h4>
                                </div>
                                <div class="panel-body">
                                    <div align="center">
                                        <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                            ?>
                                            <a href="#"><button type="button" class="btn btn-info btn-md adjustment"><span class="glyphicon glyphicon-floppy-saved"></span> Insert Adjustment</button></a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table class="table" id="table_driver">
                                            <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/personal_adjustment_search/<?php echo $selected_employee_id; ?>/adjustment_type/<?php echo $typeorder; ?>">
                                                        Adjustment Type
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/personal_adjustment_search/<?php echo $selected_employee_id; ?>/quota/<?php echo $typeorder; ?>">
                                                        Quota (day/s)
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/personal_adjustment_search/<?php echo $selected_employee_id; ?>/description/<?php echo $typeorder; ?>">
                                                        Description
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/personal_adjustment_search/<?php echo $selected_employee_id; ?>/description/<?php echo $typeorder; ?>">
                                                        Date Time
                                                    </a>
                                                </td>

                                                <td></td>
                                            </tr>
                                            </thead>
                                            <form method="post" action="<?php echo base_url(); ?>employment/employee/personal_adjustment_search/<?php echo $selected_employee_id; ?>/orby/ortype/" />
                                            <tr>
                                                <td></td>
                                                <td><input type="text" name="search_adjustment_type" class="form-control" placeholder="..."<?php
                                                    if(isset($adjustment_type)){
                                                        echo "value = '".$adjustment_type."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_quota" class="form-control" placeholder="..."<?php
                                                    if(isset($quota)){
                                                        echo "value = '".$quota."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_description" class="form-control" placeholder="..."<?php
                                                    if(isset($description)){
                                                        echo "value = '".$description."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_description" class="form-control" placeholder="..."<?php
                                                    if(isset($datetime)){
                                                        echo "value = '".$datetime."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>

                                                <!--                                                <td>--><?php
                                                //
                                                //                                                    $options = array(
                                                //                                                        ''  => '...',
                                                //                                                        '0' => 'Non Aktif',
                                                //                                                        '1' => 'Aktif'
                                                //                                                    );
                                                //                                                    $prop_aktif = "id='search_status_aktif' class='form-control'"; //class dari bootstrap
                                                //                                                    if(isset($status_aktif)){
                                                //                                                        echo form_dropdown('search_status_aktif', $options, $status_aktif, $prop_aktif);
                                                //                                                    }
                                                //                                                    else echo form_dropdown('search_status_aktif', $options, '', $prop_aktif);
                                                //                                                    ?>
                                                <!--                                                </td>-->

                                                <td><button type="submit" class="btn btn-info btn-md"><span class="	glyphicon glyphicon-search"></span></button></td>
                                            </tr>
                                            </form>
                                            <?php
                                            $no=1;
                                            foreach($list_personal_adjustment as $row) { ?>
                                                <tr>
                                                    <td><?php echo $startnum; ?></td>
                                                    <td><?php echo $row['adjustment_type']; ?></td>
                                                    <td><?php echo $row['quota']; ?></td>
                                                    <td><?php echo $row['description']; ?></td>
                                                    <td><?php echo $row['datetime']; ?></td>
                                                </tr>
                                                <?php
                                                $startnum++;
                                            } ?>
                                        </table>
                                    </div>

                                    <div class="halaman">
                                        <ul class="pagination">
                                            <?php echo $halaman;?>
                                        </ul>
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
    <div class="modal fade" id="adjustment_form" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form method="post" action="<?php echo base_url(); ?>employment/employee/tambah_data_adjustment/<?php echo $list_employee[0]['id_employee'] ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Adjustment Form</h4>
                </div>
                <div class="modal-body">
                    <div class="form-dkm form-employee-add">
                        <div class="col-md-12"
                            <?php
                            if (form_error('adjustment_type') != "") echo "class = 'alert alert-danger row'";
                            else echo "class = 'row'";
                            ?>
                        >
                            <div class="col-lg-3">
                                <label for="adjustment_type">Adjustment Type <span style="color:red">*</span></label>
                            </div>
                            <div class="col-lg-9">
                                <?php
                                $select_kategori = '';
                                $options = array(''  => '----------------- Choose Adjustment -----------------',
                                    'Adjustment' => 'Adjustment',
                                    'Deduction' => 'Deduction'
                                );

                                $prop_adjustment_type = "id='adjustment_type' class='form-control selectlevel' required"; //class dari bootstrap
                                //                                if ($status_form_adjustment == "add") {
                                echo form_dropdown('adjustment_type', $options, set_value('adjustment_type',$select_adjustment_type), $prop_adjustment_type);
                                //                                } else if ($status_form == "edit") {
                                //                                    echo form_dropdown('adjustment_type', $options, set_value('adjustment_type',$list_level[0]['id_level']), $prop_adjustment_type);
                                //                                }


                                ?>
                                <?php echo form_error('id_adjustment_type');?>
                            </div>
                        </div>
                        <div class="col-md-12"
                            <?php
                            if (form_error('quota') != "") echo "class = 'alert alert-danger'"
                            ?>
                        >
                            <div class="col-md-3">
                                <label for="quota">Quota (day/s) <span style="color:red">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <?php
                                //                                if ($status_form_adjustment=="edit") { ?>
                                <!--                                    <input type="text" class="form-control" id="quota" name="quota" value="--><?php //if (form_error('quota') != "") echo set_value('quota'); else echo $list_employee[0]['quota'];?><!--">-->
                                <!--                                    --><?php
                                //                                } else {
                                ?>
                                <input type="text" required class="form-control" id="quota" name="quota" value="<?php echo set_value('quota'); ?>">
                                <?php
                                //                                }
                                echo form_error('quota');?>
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

        $(".adjustment").click(function(){
            $("#adjustment_form").modal({backdrop: "static"});
        });
    });
</script>
</body>

</html>
