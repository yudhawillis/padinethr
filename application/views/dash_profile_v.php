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
        if(isset($status_form)) {
            if ($status_form == "edit") {
                ?>
                <form method="post" action="<?php echo base_url(); ?>dashboard/profile" enctype="multipart/form-data"/>
                <?php
            }
        }
    ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Profile</h1>
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
                                    Profile
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($current_user as $user) { ?>
                                            <div class="col-md-4">
                                                <div class="profile-photo">
                                                <?php if($current_user[0]['photo'] != "") {
                                                    ?>
                                                    <img style="max-width: 200px;" src="<?php echo assets_url()."uploads/profile/".$current_user[0]['photo']; ?>" alt="">
                                                <?php
                                                }?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="file" name="profilepic" id="profilepic" class="filestyle" data-buttonBefore="true" accept="image/x-png, image/gif, image/jpeg"/>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                if(isset($status_form)) {
                                                    ?>
                                                    <div class="form-dkm form-employee-add">
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('fullname') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="fullname">Fullname <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php if (form_error('fullname') != "") echo set_value('fullname'); else echo $current_user[0]['fullname'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('fullname');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('nickname') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="nickname">Nickname <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="nickname" name="nickname" value="<?php if (form_error('nickname') != "") echo set_value('nickname'); else echo $current_user[0]['nickname'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('nickname');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('id_role') != "") echo "class = 'alert alert-danger row'";
                                                            else echo "class = 'row'";
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="id_role">User Role <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                $select_role = '';
                                                                $options = array(''  => '----------------- Select User Role -----------------' );
                                                                foreach($list_role as $row){
                                                                    $options[$row['id_role']]= $row['name_role'];
                                                                }
                                                                $prop_role = "id='id_role' class='form-control selectrole'"; //class dari bootstrap
                                                                if ($status_form == "add") {
                                                                    echo form_dropdown('id_role', $options, set_value('id_role',$select_id_role), $prop_role);
                                                                } else if ($status_form == "edit") {
                                                                    echo form_dropdown('id_role', $options, set_value('id_role',$current_user[0]['id_role']), $prop_role);
                                                                }


                                                                ?>
                                                                <?php echo form_error('id_role');?>
                                                            </div>


                                                        </div>
<!--                                                        <div-->
<!--                                                            --><?php
//                                                            if (form_error('firstname') != "") echo "class = 'alert alert-danger'"
//                                                            ?>
<!--                                                        >-->
<!--                                                            <label for="firstname">Firstname </label>-->
<!--                                                            --><?php
//                                                            if ($status_form=="edit") { ?>
<!--                                                                <input type="text" class="form-control" id="firstname" name="firstname" value="--><?php //if (form_error('firstname') != "") echo set_value('firstname'); else echo $current_user[0]['firstname'];?><!--">-->
<!--                                                                --><?php
//                                                            }
//                                                            echo form_error('firstname');?>
<!--                                                        </div>-->
<!--                                                        <div-->
<!--                                                            --><?php
//                                                            if (form_error('lastname') != "") echo "class = 'alert alert-danger'"
//                                                            ?>
<!--                                                        >-->
<!--                                                            <label for="lastname">Lastname </label>-->
<!--                                                            --><?php
//                                                            if ($status_form=="edit") { ?>
<!--                                                                <input type="text" class="form-control" id="lastname" name="lastname" value="--><?php //if (form_error('lastname') != "") echo set_value('lastname'); else echo $current_user[0]['lastname'];?><!--">-->
<!--                                                                --><?php
//                                                            }
//                                                            echo form_error('lastname');?>
<!--                                                        </div>-->
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('nik_employee') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="nik_employee">NIK <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="nik_employee" name="nik_employee" value="<?php if (form_error('nik_employee') != "") echo set_value('nik_employee'); else echo $current_user[0]['nik_employee'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('nik_employee');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('identity_number') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="identity_number">Indetity Card Number </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="identity_number" name="identity_number" value="<?php if (form_error('identity_number') != "") echo set_value('identity_number'); else echo $current_user[0]['identity_number'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('identity_number');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('npwp') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="npwp">NPWP </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="<?php if (form_error('npwp') != "") echo set_value('npwp'); else echo $current_user[0]['npwp'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('npwp');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('email') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="email">Email <span style="color:red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="email" name="email" value="<?php if (form_error('email') != "") echo set_value('email'); else echo $current_user[0]['email'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('email');?>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('address') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="address">Alamat </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <textarea class="form-control" id="address" name="address"><?php if (form_error('address') != "") echo set_value('address'); else echo $current_user[0]['address'];?></textarea>
                                                                    <?php
                                                                }
                                                                echo form_error('address');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('birthdate') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="birthdate">Birthdate </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input data-provide="datepicker" type="text" class="form-control" id="birthdate" name="birthdate" value="<?php if (form_error('birthdate') != "") echo set_value('birthdate'); else echo $current_user[0]['birthdate'];?>">

                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <input data-provide="datepicker" type="text" class="form-control" id="birthdate" name="birthdate" value="<?php echo set_value('birthdate'); ?>">
                                                                    <?php
                                                                }
                                                                echo form_error('birthdate');?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"
                                                            <?php
                                                            if (form_error('phone_number') != "") echo "class = 'alert alert-danger'"
                                                            ?>
                                                        >
                                                            <div class="col-md-3">
                                                                <label for="phone_number">Phone </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <?php
                                                                if ($status_form=="edit") { ?>
                                                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php if (form_error('phone_number') != "") echo set_value('phone_number'); else echo $current_user[0]['phone_number'];?>">
                                                                    <?php
                                                                }
                                                                echo form_error('phone_number');?>
                                                            </div>
                                                        </div>

                                                    <br />
                                                        <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                                            ?>
                                                            <button type="submit" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Simpan</button>
                                                            <?php
                                                        }
                                                        ?>

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
    </div>

    </form>
</div>

<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
