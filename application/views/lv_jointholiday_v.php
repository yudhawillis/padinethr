
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave - PadiHR </title>
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
                <div class="col-lg-12">
                    <h1 class="page-header">Joint Holiday</h1>
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
                                    <h4>List Data</h4>
                                </div>
                                <div class="panel-body">
                                    <div align="center">
                                        <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                            ?>
                                            <a href="<?php echo base_url(); ?>leave/jointholiday/tambah_data"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-duplicate"></span> Tambah Data</button></a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table" id="table_driver">
                                            <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/jointholiday/search/date_holiday/<?php echo $typeorder; ?>">
                                                        Holiday Date
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/jointholiday/search/description/<?php echo $typeorder; ?>">
                                                        Description
                                                    </a>
                                                </td>

                                                <td></td>
                                            </tr>
                                            </thead>
                                            <form method="post" action="<?php echo base_url(); ?>leave/jointholiday/search/orby/ortype/" />
                                            <tr>
                                                <td></td>
                                                <td><input type="text" name="search_date_holiday" class="form-control" placeholder="..."<?php
                                                    if(isset($date_holiday)){
                                                        echo "value = '".$date_holiday."'";
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
                                                <td><button type="submit" class="btn btn-info btn-md"><span class="	glyphicon glyphicon-search"></span></button></td>
                                            </tr>
                                            </form>
                                            <?php
                                            $no=1;
                                            foreach($list_joint_holiday as $row) { ?>
                                                <tr>
                                                    <td><?php echo $startnum; ?></td>
                                                    <td><?php echo $row['date_holiday']; ?></td>
                                                    <td><?php echo $row['description']; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>leave/jointholiday/edit_data/<?php echo $row['id_joint_holiday']; ?>">Edit</a> |
                                                        <a href="<?php echo base_url(); ?>leave/jointholiday/hapus_data/<?php echo $row['id_joint_holiday']; ?>">Delete</a>
                                                    </td>
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
