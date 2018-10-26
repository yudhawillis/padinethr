
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employment - PadiHR </title>
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
                    <h3 class="page-header">Employment History</h3>
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
                    <a href="<?php echo base_url(); ?>employment/employee/personal_employment/<?php echo $list_employee[0]['id_employee']  ?>">
                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-arrow-left"></span> Back to Profile</button>
                    </a>
                    <br /><br />
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Employee
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <?php foreach ($list_employee as $user) { ?>
                                            <div class="col-md-4">
                                                Foto
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table-profile">
                                                    <tr>
                                                        <td>ID</td><td>:</td><td><?php echo $user['nik_employee']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td><td>:</td><td><?php echo $user['fullname']; ?></td>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Employment History
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table" id="table_driver">
                                            <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Position</td>
                                                <td>Division</td>
                                                <td>City</td>
                                                <td>Start Date</td>
                                                <td>End Date</td>
                                                <td>Level</td>
                                                <td>Description</td>
                                                <td>Status</td>
                                            </tr>
                                            </thead>

                                            <?php
                                            $no=1;
                                            foreach($list_employment as $row) { ?>
                                                <tr>
                                                    <td><?php echo $startnum; ?></td>
                                                    <td><?php echo $row['position']; ?></td>
                                                    <td><?php echo $row['division']; ?></td>
                                                    <td><?php echo $row['name_city']; ?></td>
                                                    <td><?php echo $row['tgl_mulai']; ?></td>
                                                    <td><?php echo $row['tgl_berakhir']; ?></td>
                                                    <td><?php echo $row['level_name']; ?></td>
                                                    <td><?php echo $row['description']; ?></td>
                                                    <td><?php
                                                        if ($row['employment_status'] == "1") echo "Aktif";
                                                        else if ($row['employment_status'] == "0") echo "Non Aktif";
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                    <!--                                                    <td><a href="--><?php //echo base_url(); ?><!--admin/speedtes/edit_data/--><?php //echo $row['id_speedtes']; ?><!--">Edit</a> |-->
                                                    <!--                                                        <a name="--><?php //echo $row['id_speedtes']; ?><!--" class="hapus" href="#">Delete</a>-->
                                                    <!--                                                    </td>-->
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
                    <a href="<?php echo base_url(); ?>employment/employee/personal_employment/<?php echo $list_employee[0]['id_employee']  ?>">
                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-arrow-left"></span> Back to Profile</button>
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
<?php $this->load->view('element/footer_v'); ?>
</body>

</html>
