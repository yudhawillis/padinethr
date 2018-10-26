<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a <?php if ($active == "dashboard") echo "class='active'"; ?> href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo base_url() ."dashboard/summary"; ?>">Summary</a>
                    </li>
<!--                    <li>-->
<!--                        <a href="--><?php //echo base_url() ."dashboard/profile"; ?><!--">Profile</a>-->
<!--                    </li>-->
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a <?php if ($active == "leave") echo "class='active'"; ?> href="#"><i class="fa fa-send-o fa-fw"></i> Leave<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo base_url() ."leave/myschedule"; ?>">Schedule a Leave</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() ."leave/myrequest"; ?>">My Request</a>
                    </li>
                    <?php if ($this->session->userdata('id_role')!=5) {
                        ?>
                        <li>
                            <a href="<?php echo base_url() ."leave/approval"; ?>">Request Approval</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url() ."leave/jointholiday"; ?>">Joint Holiday</a>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
                <!-- /.nav-second-level -->
            </li>
            <?php if ($this->session->userdata('id_role')!=5) {
                ?>
                <li>
                    <a <?php if ($active == "employment") echo "class='active'"; ?> href="#"><i class="fa fa-group fa-fw"></i> Employment<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo base_url() ."employment/employee"; ?>">List Employee</a>
                        </li>
                        <?php if ($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2) {
                          ?>
                            <li>
                                <a href="<?php echo base_url() ."employment/employee/tambah_data"; ?>">Add Employee</a>
                            </li>
                          <?php
                        }
                        ?>

                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            <?php
            }
            ?>


        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>