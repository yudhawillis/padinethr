<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - PadiWEB</title>
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
                    <h1 class="page-header">Pending Request</h1>
                    <?php

                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>leave/approval/">
                                <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-arrow-left"></span> Back to Approval List</button>
                            </a>
                            <br /><br />
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Profile
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($detail_employee as $user) { ?>
                                            <div class="col-md-4">
                                                <div class="profile-photo">
                                                    <?php if($user['photo'] != "") {
                                                        ?>
                                                        <img style="max-width: 200px;" src="<?php echo assets_url()."uploads/profile/".$detail_employee[0]['photo']; ?>" alt="">
                                                        <?php
                                                    }?>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>ID</td><td>:</td><td><?php echo $user['nik_employee']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td><td>:</td><td><?php echo $user['fullname']; ?></td>
                                                    </tr>
<!--                                                    <tr>-->
<!--                                                        <td>Role</td><td>:</td><td>--><?php //echo $user['name_role']; ?><!--</td>-->
<!--                                                    </tr>-->
                                                    <tr>
                                                        <td>Leave Quota</td><td>:</td><td><?php echo $detail_employment[0]['leave_quota']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Position</td><td>:</td><td><?php echo $user['position']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>City</td><td>:</td><td><?php echo $user['name_city']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td><td>:</td>
                                                        <td>
                                                            <?php
                                                            if ($user['status'] == 1) echo "Active";
                                                            else echo "Non Active";
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Detil Leave Request
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table" id="table_driver">

                                                <input type="hidden" name="id_leave" id="id_leave" value="<?php echo $detail_leave[0]['id_leave']; ?>">
                                                <thead>
                                                <tr>
                                                    <td>
                                                        Submission date
                                                    </td>
                                                    <td>
                                                        Start Date
                                                    </td>
                                                    <td>
                                                        End Date
                                                    </td>
                                                    <td>
                                                        Days
                                                    </td>
                                                    <td>
                                                        Leave Explanation
                                                    </td>
                                                    <?php
                                                    if($current_level_appr=="hr"){
                                                        ?>
                                                        <td>
                                                            Dispensation (day/s)
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>

                                                </tr>
                                                </thead>
                                                <?php
                                                $no=1;
                                                foreach($detail_leave as $row) { ?>
                                                    <tr>
                                                        <td><?php echo $row['submission_date']; ?></td>
                                                        <td><?php echo $row['start_date']; ?></td>
                                                        <td><?php echo $row['end_date']; ?></td>
                                                        <td><?php echo $row['day']; ?></td>
                                                        <td><?php echo $row['description'];
//                                                            if ($row['leave_quota_ext'] > 0) {
//                                                                echo "<br /><i style='font-size: 11px;'>inc Extended Leave Quota ".$row['leave_quota_ext']." day/s</i>";
//                                                            }
//                                                            if ($row['payroll_deduction'] > 0) {
//                                                                echo "<br /><i style='font-size: 11px;'>with Payroll Deduction ".$row['payroll_deduction']." day/s</i>";
//                                                            }
                                                            ?>
                                                        </td>
                                                        <?php
                                                        if($current_level_appr=="hr"){
                                                            if($cek_jum_approve==1 && $button_app!=0 ){
                                                                ?>
                                                                <td>
																<table class="table">
																	<?php
																	$i=1;
																	foreach($list_year_leave as $year){
																	    if(isset($list_year_leave[1])){
																		?>
                                                                            <tr>
                                                                                <td> Disp. <?php echo $year; ?> </td>
                                                                                <td> <input type="number" name="dispensation_quota_<?php echo $i; ?>" id="dispensation_quota_<?php echo $i; ?>" class="form-control" value="0"></td>
                                                                            </tr>
                                                                        <?php
                                                                        } else {
																	    ?>
                                                                            <tr>
                                                                                <td> <input type="number" name="dispensation_quota_<?php echo $i; ?>" id="dispensation_quota_<?php echo $i; ?>" class="form-control" value="0"></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
																		$i++;
																	}
																	?>
																</table>

																</td>
                                                                <?php
                                                            } else {
                                                                echo "<td>".$row[' ']."</td>";
                                                            }
                                                        }
                                                        ?>

                                                        <td>
                                                            <?php

                                                            ?>

<!--                                                            jika data approval 2-->
                                                            <?php
															if ($row['cancel_status'] == 1){
																echo "Request has been cancelled";
															}
															else {
																if ($cek_jum_approve==0){
																	if ($button_app==1){
																		?>
																		<a id="link_approv" href="<?php echo base_url(); ?>leave/approval/approve_leave/<?php echo $row['id_leave']; ?>/">Approve</a> |
																		<a name="<?php echo $row['id_leave']; ?>" class="reject_form" href="#">Reject</a>
																		<?php
																	} else echo "Waiting for Supervisor Approval";
																}
																else if ($cek_jum_approve==1){
																	if ($button_app==0){
																		echo "Rejected by " . $name_role;
																	} else {
																		if ($approved_by == 0) {
																			?>
																			<a id="link_approv"
																			   href="<?php echo base_url(); ?>leave/approval/approve_leave/<?php echo $row['id_leave']; ?>/">Approve</a> |
																			<a name="<?php echo $row['id_leave']; ?>"
																			   class="reject_form" href="#">Reject</a>
																			<?php
																		} else {
																			if ($ap_status == 1) {
																				echo "Approved by " . $name_role;
																			} else if ($ap_status == 0) {
																				echo "Rejected by " . $name_role;
																			}
																		}
																	}
																}
																else if ($cek_jum_approve==2){
																	if (isset($row['app_status_1'])) {
																		if ($row['app_status_1'] == 1) {
																			echo "Approved by " . $row['app_name_1'];
																		}
																		if ($row['app_status_1'] == 0) {
																			echo "Rejected by " . $row['app_name_1'];
																		}
																	}
																	if (isset($row['app_status_2'])) {
																		if ($row['app_status_2'] == 1) {
																			echo "<br />Approved by ".$row['app_name_2'];
//                                                                        if($row['dispensation_quota'] > 0) echo "<br /><i style='font-size: 11px;'>Dispensation : ".$row['dispensation_quota']." day/s</i>";
																		}
																		if ($row['app_status_2'] == 0) {
																			echo "<br />Rejected by ".$row['app_name_2'];
																		}
																	}
																	if ( !isset($row['app_status_1']) && !isset($row['app_status_2']) ) {
																		echo "Waiting for Approval";
																	}

																}
															}
                                                            ?>

                                                        </td>



                                                    </tr>
                                                    <?php
                                                    $startnum++;
                                                } ?>
                                            </table>
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
    </form>
    <div class="modal fade" id="reject_reason" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form method="post" action="<?php echo base_url(); ?>leave/approval/reject_leave/<?php echo $detail_leave[0]['id_leave'] ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Rejection Form</h4>
                </div>
                <div class="modal-body">
                    <div class="form-dkm form-employee-add">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label for="address">Rejection Explanation </label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>">
                            </div>
                        </div>
                    </div>
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
        $(".reject_form").click(function(){
            $("#reject_reason").modal({backdrop: "static"});
            //var id = $(this).attr('name');
            //$("#proses_hapus").attr("href", "<?php //echo base_url(); ?>//admin/affiliate/hapus_data/"+id);
        });
        $("#dispensation_quota_1").keyup(function(){
            var id_leave = $("#id_leave").val();
            var dispensation_quota_1 = $("#dispensation_quota_1").val();
            $("#link_approv").attr("href", "<?php echo base_url(); ?>leave/approval/approve_leave/"+id_leave+"/"+dispensation_quota_1+"");
        });

        $("#dispensation_quota_2").keyup(function(){
            var id_leave = $("#id_leave").val();
            var dispensation_quota_1 = $("#dispensation_quota_1").val();
            var dispensation_quota_2 = $("#dispensation_quota_2").val();
            $("#link_approv").attr("href", "<?php echo base_url(); ?>leave/approval/approve_leave/"+id_leave+"/"+dispensation_quota_1+"/"+dispensation_quota_2+"");
        });
    });
</script>

</body>

</html>
