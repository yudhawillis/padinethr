<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - PadiHR</title>
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
                    <h1 class="page-header">Summary</h1>
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
                                                <div class="profile-photo">
                                                    <?php
                                                    if($current_user[0]['photo'] != "") {
                                                        ?>
                                                        <img style="max-width: 200px;" src="<?php echo assets_url()."uploads/profile/".$current_user[0]['photo']; ?>" alt="">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img style="max-width: 200px;" src="<?php echo assets_url()?>images/no-image-available.png" alt="">
                                                        <?php
                                                    }?>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>ID Employee</td><td>:</td><td><?php echo $user['nik_employee']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td><td>:</td><td><?php echo $user['fullname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td><td>:</td><td><?php echo $user['email']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td><td>:</td><td><?php echo $user['address']; ?></td>
                                                    </tr>
													<tr>
														<td>Address Domicile</td><td>:</td><td><?php echo $user['address_2']; ?></td>
													</tr>
                                                    <tr>
                                                        <td>Birthdate</td><td>:</td><td><?php echo $user['birthdate']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>NPWP</td><td>:</td><td><?php echo $user['npwp']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Identity Card Number</td><td>:</td><td><?php echo $user['identity_number']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td><td>:</td><td><?php echo $user['phone_number']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Employee Leave Type</td><td>:</td>
                                                        <td>
                                                            <?php
                                                            if($user['employee_leave_type'] == 'non_extend'){
                                                                echo "Non Extend Leave";
                                                            } else if ($user['employee_leave_type'] == 'extend'){
                                                                echo "Extend Leave";
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>User Role</td><td>:</td><td><?php echo $user['name_role']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td><td>:</td>
                                                        <td>
                                                            <?php
                                                            if ($user['status']==1) echo "Active";
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
                </div>
            </div>
        </div>
    </div>
    </form>

</div>
<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
