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
        $data['active'] = "dashboard";
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
                    <h1 class="page-header">Dashboard</h1>
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
                                            <div class="col-md-4">
                                                Foto
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>ID</td><td>:</td><td><?php echo $user['nik_employee']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td><td>:</td><td><?php echo $user['firstname']." ".$user['lastname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Role</td><td>:</td><td><?php echo $user['name_role']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td><td>:</td><td><?php echo $user['status']; ?></td>
                                                    </tr>
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
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>ID</td><td>:</td><td><?php echo $user['nik_employee']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td><td>:</td><td><?php echo $user['fullname']." ".$user['lastname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Role</td><td>:</td><td><?php echo $user['name_role']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td><td>:</td><td><?php echo $user['status']; ?></td>
                                                    </tr>
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
        </div>
    </div>
    </form>

</div>
<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
