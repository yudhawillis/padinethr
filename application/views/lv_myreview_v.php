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
        $data['active'] = "dashboard";
        $this->load->view('element/nav_topbar_v');
        $this->load->view('element/nav_sidebar_v',$data);
        ?>
    </nav>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Schedule Review</h1>
                    <?php
                    if ($this->session->flashdata('pesan')!=NULL){
                        ?>
                        <div class="alert alert-warning fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Warning!</strong> <?php echo $this->session->flashdata('pesan'); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Review
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table-profile">
                                                <tr>
                                                    <td>Name</td><td>:</td><td><?php echo $current_user[0]['fullname']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Current Leave Quota </td><td>:</td><td><?php echo $current_quota." day(s)"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Request Leave Date </td><td>:</td><td><?php echo $start_date." until ".$end_date; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Request Leave Quota</td><td>:</td><td><?php echo $req_quota." day(s)"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><h2>Calc</h2></td><td></td><td></td>
                                                </tr>
                                                <tr>
                                                    <td>Get from "Leave Quota"</td><td>:</td><td><?php echo $current_quota." day(s)"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Get from "External Leave Quota" (debt)</td><td>:</td><td><?php echo $leave_quota_ext_updated." day(s)"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Payroll Deduction for Leave</td><td>:</td><td><?php echo $payroll_deduction." day(s)"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Total</b></td><td>:</td><td><?php echo $req_quota." day(s)"; ?></td>
                                                </tr>

                                            </table>
                                            <br />
                                            <a href="<?php echo base_url(); ?>leave/myschedule/review_submit">
                                                <button type="button" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span> Proceed Anyway</button>
                                            </a>
                                            <a href="<?php echo base_url(); ?>leave/myschedule">
                                                <button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
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
    </form>

</div>
<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
