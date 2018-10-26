<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee - PadiHR</title>
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
    <?php
    if(isset($status_form)) {
        if ($status_form == "add") {
            ?>
            <form method="post" action="<?php echo base_url(); ?>employment/employee/tambah_data/" enctype="multipart/form-data"/>
            <?php
        } else if ($status_form == "edit") {
            ?>
            <form method="post" action="<?php echo base_url(); ?>employment/employee/detil_data/<?php echo $list_employee[0]['id_employee'] ?>" enctype="multipart/form-data"/>
            <?php
        }
    }
    ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                    <h1 class="page-header">Detil Employee</h1>
                    <?php
                    if ($this->session->flashdata('pesan')!=NULL){
                        ?>
                        <div class="alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> <?php echo $this->session->flashdata('pesan'); ?>
                        </div>

                        <?php
                    } else if ($this->session->flashdata('pesan_gagal')!=NULL){
                        ?>
                        <div class="alert alert-warning fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Gagal!</strong> <?php echo $this->session->flashdata('pesan_gagal'); ?>
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
                                    Details
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($list_employee as $user) { ?>
                                        <div class="col-md-4">
                                            <div class="profile-photo">
                                                <?php
                                                if($current_user[0]['photo'] != "") {
                                                    ?>
                                                    <img style="max-width: 200px;" src="<?php echo assets_url()."uploads/profile/".$list_employee[0]['photo']; ?>" alt="">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <img style="max-width: 200px;" src="<?php echo assets_url()?>images/no-image-available.png" alt="">
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
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    Employee Profile
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
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
                                                                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php if (form_error('fullname') != "") echo set_value('fullname'); else echo $list_employee[0]['fullname'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo set_value('fullname'); ?>">
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
                                                                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?php if (form_error('nickname') != "") echo set_value('nickname'); else echo $list_employee[0]['nickname'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo set_value('nickname'); ?>">
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
                                                                        echo form_dropdown('id_role', $options, set_value('id_role',$list_employee[0]['id_role']), $prop_role);
                                                                    }


                                                                    ?>
                                                                    <?php echo form_error('id_role');?>
                                                                </div>


                                                            </div>
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
                                                                        <input type="text" class="form-control" id="nik_employee" name="nik_employee" value="<?php if (form_error('nik_employee') != "") echo set_value('nik_employee'); else echo $list_employee[0]['nik_employee'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="nik_employee" name="nik_employee" value="<?php echo set_value('nik_employee'); ?>">
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
                                                                    <label for="identity_number">Identity Card Number </label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <?php
                                                                    if ($status_form=="edit") { ?>
                                                                        <input type="text" class="form-control" id="identity_number" name="identity_number" value="<?php if (form_error('identity_number') != "") echo set_value('identity_number'); else echo $list_employee[0]['identity_number'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="identity_number" name="identity_number" value="<?php echo set_value('identity_number'); ?>">
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
                                                                        <input type="text" class="form-control" id="npwp" name="npwp" value="<?php if (form_error('npwp') != "") echo set_value('npwp'); else echo $list_employee[0]['npwp'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="npwp" name="npwp" value="<?php echo set_value('npwp'); ?>">
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
                                                                        <input type="text" class="form-control" id="email" name="email" value="<?php if (form_error('email') != "") echo set_value('email'); else echo $list_employee[0]['email'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>">
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
                                                                        <textarea class="form-control" id="address" name="address"><?php if (form_error('address') != "") echo set_value('address'); else echo $list_employee[0]['address'];?></textarea>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="address" name="address" value="<?php echo set_value('address'); ?>">
                                                                        <?php
                                                                    }
                                                                    echo form_error('address');?>
                                                                </div>
                                                            </div>
															<div class="col-md-12"
																<?php
																if (form_error('address_2') != "") echo "class = 'alert alert-danger'"
																?>
															>
																<div class="col-md-3">
																	<label for="address_2">Alamat 2</label>
																</div>
																<div class="col-md-9">
																	<?php
																	if ($status_form=="edit") { ?>
																		<textarea class="form-control" id="address_2" name="address_2"><?php if (form_error('address_2') != "") echo set_value('address_2'); else echo $list_employee[0]['address_2'];?></textarea>
																		<?php
																	} else {
																		?>
																		<input type="text" class="form-control" id="address_2" name="address_2" value="<?php echo set_value('address_2'); ?>">
																		<?php
																	}
																	echo form_error('address_2');?>
																</div>
															</div>
                                                            <div class="col-md-12"
                                                                <?php
                                                                if (form_error('birthdate') != "") echo "class = 'alert alert-danger'"
                                                                ?>
                                                            >
                                                                <div class="col-md-3">
                                                                    <label for="birthdate">Birthdate</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <?php
                                                                    if ($status_form=="edit") { ?>
                                                                        <input data-provide="datepicker" type="text" class="form-control" id="birthdate" name="birthdate" value="<?php if (form_error('birthdate') != "") echo set_value('birthdate'); else echo $list_employee[0]['birthdate'];?>">

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
                                                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php if (form_error('phone_number') != "") echo set_value('phone_number'); else echo $list_employee[0]['phone_number'];?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="text" class="form-control" id="fullname" name="phone_number" value="<?php echo set_value('phone_number'); ?>">
                                                                        <?php
                                                                    }
                                                                    echo form_error('phone_number');?>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12"
                                                                <?php
                                                                if (form_error('status') != "") echo "class = 'alert alert-danger row'";
                                                                else echo "class = 'row'"
                                                                ?>
                                                            >
                                                                <div class="col-lg-3">
                                                                    <label for="status">Status Aktif</label>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <?php
                                                                    if ($status_form=="edit") { ?>
                                                                        <input type="checkbox" id="status_aktif" name="status" value="1" <?php if (form_error('status') != "") echo set_value('status'); else if(isset($status)) echo $status; else echo $list_employee[0]['status_aktif'];?>> Active
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="checkbox" id="status" name="status" value="1" <?php if(isset($status)) echo $status; ?>> Active
                                                                        <?php
                                                                    }
                                                                    echo form_error('status');?>
                                                                </div>
                                                            </div>
                                                            <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-info btn-md"><span class="glyphicon glyphicon-floppy-saved"></span> Simpan</button>
                                                                </div>
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
            </div>
        </div>
    </div>

    </form>





</div>

<?php $this->load->view('element/footer_v'); ?>


</body>

</html>
