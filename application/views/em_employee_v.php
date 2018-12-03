
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
        $data['active'] = "employment";
        $this->load->view('element/nav_topbar_v');
        $this->load->view('element/nav_sidebar_v',$data);
        ?>
    </nav>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Employee</h1>
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
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4>Overview</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table" id="table_driver">

                                            <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/nik_employee/<?php echo $typeorder; ?>">
                                                        NIK
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/fullname/<?php echo $typeorder; ?>">
                                                        Fullname
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/l.level_name/<?php echo $typeorder; ?>">
                                                        Level
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/position/<?php echo $typeorder; ?>">
                                                        Position
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/name_role/<?php echo $typeorder; ?>">
                                                        User Role
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>employment/employee/search/status/<?php echo $typeorder; ?>">
                                                        Status
                                                    </a>
                                                </td>

                                                <td></td>
                                            </tr>
                                            </thead>

                                            <form method="post" action="<?php echo base_url(); ?>employment/employee/search/orby/ortype/" />
                                            <tr>
                                                <td></td>
                                                <td><input type="text" name="search_nik_employee" class="form-control" placeholder="..."<?php
                                                    if(isset($nik_employee)){
                                                        echo "value = '".$nik_employee."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_fullname" class="form-control" placeholder="..."<?php
                                                    if(isset($fullname)){
                                                        echo "value = '".$fullname."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_level_name" class="form-control" placeholder="..."<?php
                                                    if(isset($level_name)){
                                                        echo "value = '".$level_name."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_position" class="form-control" placeholder="..."<?php
                                                    if(isset($position)){
                                                        echo "value = '".$position."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_name_role" class="form-control" placeholder="..."<?php
                                                    if(isset($name_role)){
                                                        echo "value = '".$name_role."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><?php

                                                    $options = array(
                                                        ''  => '...',
                                                        '0' => 'Non Aktif',
                                                        '1' => 'Aktif'
                                                    );
                                                    $prop_aktif = "id='search_status_aktif' class='form-control'"; //class dari bootstrap
                                                    if(isset($status)){
                                                        echo form_dropdown('search_status', $options, $status, $prop_aktif);
                                                    }
                                                    else echo form_dropdown('search_status', $options, '', $prop_aktif);
                                                    ?>
                                                </td>

                                                <td><button type="submit" class="btn btn-info btn-md"><span class="	glyphicon glyphicon-search"></span></button></td>
                                            </tr>
                                            </form>
											<form method="post" action="<?php echo base_url(); ?>employment/employee/multiple_reset_leave" />
                                            <?php
                                            $no=1;
                                            foreach($list_member as $row) { ?>
                                                <tr>
                                                    <td><?php echo $startnum; ?></td>

                                                    <td>

														&nbsp;<a href="<?php echo base_url(); ?>employment/employee/detil_data/<?php echo $row['id_employee'];  ?>"><?php echo $row['nik_employee']; ?></a></td>
                                                    <td><?php echo $row['fullname']; ?></td>
                                                    <td><?php echo $row['level_name']; ?></td>
                                                    <td><?php echo $row['position']; ?></td>
                                                    <td><?php echo $row['name_role']; ?></td>
                                                    <td><?php
                                                        if ($row['status'] == "1") echo "Aktif";
                                                        else if ($row['status'] == "0") echo "Non Aktif";
                                                        ?>
                                                    </td>
                                                    <td>


                                                    </td>
                                                </tr>
                                                <?php
                                                $startnum++;
                                            } ?>
<!--											<tr>-->
<!--												<td colspan="3">-->
<!--													<button type="submit" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-retweet"></span> Reset From Checked List</button>-->
<!--												</td>-->
<!--											</tr>-->

											</form>
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
    <div class="modal fade" id="hapus_confirm" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Konfirmasi</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin akan menghapus data link Speedtest tersebut ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" id="proses_hapus"><button type="submit" class="btn btn-default" id="proses">Lanjutkan</button></a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('element/footer_v'); ?>

<script>
    $(document).ready(function(){
        $(".hapus").click(function(){
            $("#hapus_confirm").modal({backdrop: "static"});
            var id = $(this).attr('name');
            $("#proses_hapus").attr("href", "<?php echo base_url(); ?>admin/content/hapus_data/"+id);
        });
    });
</script>
</body>

</html>
