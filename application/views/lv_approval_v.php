
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
                    <h1 class="page-header">Request Approval</h1>
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
                                    <div class="table-responsive">
                                        <table class="table" id="table_driver">
                                            <thead>
                                            <tr>
                                                <td>No</td>

                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/approval/search/lv.submission_date/<?php echo $typeorder; ?>">
                                                        Submission date
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/approval/search/e.fullname/<?php echo $typeorder; ?>">
                                                        Fullname
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/approval/search/lv.start_date/<?php echo $typeorder; ?>">
                                                        Start Date
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/approval/search/lv.end_date/<?php echo $typeorder; ?>">
                                                        End Date
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?php echo base_url(); ?>leave/approval/search/leave_description/<?php echo $typeorder; ?>">
                                                        Leave Explanation
                                                    </a>
                                                </td>
                                                <td>
<!--                                                    <a href="--><?php //echo base_url(); ?><!--leave/approval/search/status/--><?php //echo $typeorder; ?><!--">-->
<!--                                                        Status-->
<!--                                                    </a>-->
                                                </td>
                                                <td>Status</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </thead>
                                            <form method="post" action="<?php echo base_url(); ?>leave/approval/search/orby/ortype/" />
                                            <tr>
                                                <td></td>
                                                <td><input type="text" name="search_fullname" class="form-control" placeholder="..."<?php
                                                    if(isset($fullname)){
                                                        echo "value = '".$fullname."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_submissioan_date" class="form-control" placeholder="..."<?php
                                                    if(isset($submissioan_date)){
                                                        echo "value = '".$submissioan_date."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_start_date" class="form-control" placeholder="..."<?php
                                                    if(isset($start_date)){
                                                        echo "value = '".$start_date."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>
                                                <td><input type="text" name="search_end_date" class="form-control" placeholder="..."<?php
                                                    if(isset($end_date)){
                                                        echo "value = '".$end_date."'";
                                                    }
                                                    ?>
                                                    >
                                                </td>

                                                <td></td>
                                                <td></td>
                                                <td></td>
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
                                            foreach($list_leave as $row) { ?>
                                                <tr>
                                                    <td><?php echo $startnum; ?></td>
                                                    <td><?php echo $row['submission_date']; ?></td>
                                                    <td><?php echo $row['fullname']; ?></td>
                                                    <td><?php echo $row['start_date']; ?></td>
                                                    <td><?php echo $row['end_date']; ?></td>
                                                    <td><?php echo $row['leave_description'];
//                                                        if ($row['leave_quota_ext'] > 0) {
//                                                            echo "<br /><i style='font-size: 11px;'>inc Extended Leave Quota ".$row['leave_quota_ext']." day/s</i>";
//                                                        }
//                                                        if ($row['payroll_deduction'] > 0) {
//                                                            echo "<br /><i style='font-size: 11px;'>with Payroll Deduction ".$row['payroll_deduction']." day/s</i>";
//                                                        }
                                                    ?>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <?php
														if ($row['cancel_status'] == 1){
															echo "Request has been cancelled";
														}
														else {
															if (isset($row['app_status_1'])) {
																if ($row['app_status_1'] == 1) {
																	echo "Approved by " . $row['app_name_1'];
																}
																if ($row['app_status_1'] == 0) {
																	echo "Rejected by " . $row['app_name_1'];
																	if($row['app_reason_reject_1'] != "") echo "<br /><i style='font-size: 11px;'>Reason : ".$row['app_reason_reject_1']."</i>";
																}
															}
															if (isset($row['app_status_2'])) {
																if ($row['app_status_2'] == 1) {
																	echo "<br />Approved by ".$row['app_name_2'];
																	if($row['dispensation_quota'] > 0) echo "<br /><i style='font-size: 11px;'>Dispensation : ".$row['dispensation_quota']." day/s</i>";
																}
																if ($row['app_status_2'] == 0) {
																	echo "<br />Rejected by ".$row['app_name_2'];
																	if($row['app_reason_reject_2'] != "") echo "<br /><i style='font-size: 11px;'>Reason : ".$row['app_reason_reject_2']."</i>";

																}
															}
															if ( !isset($row['app_status_1']) && !isset($row['app_status_2']) ) {
																echo "Waiting for Approval";
															}
														}



                                                        ?>
                                                    </td>
                                                    <!--                                                    -->
                                                    <!--                                                    <td>--><?php
                                                    //                                                        if ($row['status_aktif'] == "1") echo "Aktif";
                                                    //                                                        else if ($row['status_aktif'] == "0") echo "Non Aktif";
                                                    //                                                        ?>
                                                    <!--                                                    </td>-->
                                                    <!--                                                    <td><a href="--><?php //echo base_url(); ?><!--admin/speedtes/edit_data/--><?php //echo $row['id_speedtes']; ?><!--">Edit</a> |-->
                                                    <!--                                                        <a name="--><?php //echo $row['id_speedtes']; ?><!--" class="hapus" href="#">Delete</a>-->
                                                    <!--                                                    </td>-->
                                                    </td>
                                                    <td><a href="<?php echo base_url(); ?>leave/approval/detil_data/<?php echo $row['id_leave']; ?>">Detail</a>
                                                    </td>
                                                    <td></td>


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
