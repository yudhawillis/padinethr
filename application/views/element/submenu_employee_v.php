<a href="<?php echo base_url(); ?>employment/employee">
    <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-arrow-left"></span> List Employee</button>
</a>
<br /><br />
<ul class="nav nav-tabs">
    <li <?php if ($active == "employee_profile") echo "class='active'"; ?>><a href="<?php echo base_url() ?>employment/employee/detil_data/<?php echo $selected_employee_id ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
    <li <?php if ($active == "personal_employment") echo "class='active'"; ?>><a href="<?php echo base_url() ?>employment/employee/personal_employment/<?php echo $selected_employee_id ?>"><span class="glyphicon glyphicon-briefcase"></span> Employment</a></li>
    <li <?php if ($active == "personal_leave") echo "class='active'"; ?>><a href="<?php echo base_url() ?>employment/employee/personal_leave/<?php echo $selected_employee_id ?>"><span class="glyphicon glyphicon-plane"></span> Personal Leave</a></li>
    <li <?php if ($active == "personal_adjustment") echo "class='active'"; ?>><a href="<?php echo base_url() ?>employment/employee/personal_adjustment/<?php echo $selected_employee_id ?>"><span class="glyphicon glyphicon-pencil"></span> Leave Adjustment</a></li>
</ul>
<br />