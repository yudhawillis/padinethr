<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>PadiHR Login Form</title>
    <link rel="stylesheet" href="<?php echo assets_url(); ?>templates/bootstrap-3.3.5-dist/css/bootstrap.min.css">
    <script src="<?php echo assets_url(); ?>templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

    <style>
        body{
            background-color: f7f7f7;
            padding-top:50px;
        }
        .brand-logo{

            padding: 20px;
        }

    </style>
</head>
<body>
<?php
echo form_open(base_url() . 'mem_login/submit_login');
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php
            if ($this->session->flashdata('stop')!=NULL){
                ?>
                <div class="alert alert-warning fade in">
                    <!--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                    <strong>Warning!</strong> <?php echo $this->session->flashdata('stop'); ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ($this->session->flashdata('select')!=NULL){
                ?>
                <div class="alert alert-danger fade in">
                    <!--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                    <strong>Gagal!</strong> <?php echo $this->session->flashdata('select'); ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ($this->session->flashdata('logout')!=NULL){
                ?>
                <div class="alert alert-info fade in">
                    <!--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                    <strong>Info.</strong> <?php echo $this->session->flashdata('logout'); ?>
                </div>
                <?php
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div align="center" class="brand-logo">
                        <img src="<?php echo assets_url(); ?>images/logopadinet.jpg">
                    </div>
                </div>
                <br /><br />
                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Email..." name="email" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password..." name="password" type="password" value="">
                            </div>
                            <!--                            <div class="checkbox">-->
                            <!--                                <label>-->
                            <!--                                    <input name="remember" type="checkbox" value="Remember Me"> Remember Me-->
                            <!--                                </label>-->
                            <!--                            </div>-->
                            <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
                        </fieldset>
                    </form>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>
<?php
echo form_close();
?>
</body>
</html>



