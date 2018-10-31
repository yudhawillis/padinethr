<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('member_m','member_m');
        $this->load->model('user_m', 'user_m');
        $this->load->model('leave_m', 'leave_m');
        $this->load->model('employment_m', 'employment_m');
        $this->load->model('level_m', 'level_m');
        $this->load->model('city_m', 'city_m');
        $this->load->model('role_m', 'role_m');
        $this->load->model('approval_m', 'approval_m');
        $this->load->model('adjustment_m', 'adjustment_m');

        if($this->logged_in()){
        }
        else redirect(base_url().'dashboard');
    }

    public function index(){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        if($this->session->userdata('nik_employee')) $this->session->unset_userdata('nik_employee');
        if($this->session->userdata('fullname')) $this->session->unset_userdata('fullname');
        if($this->session->userdata('level_name')) $this->session->unset_userdata('level_name');
        if($this->session->userdata('position')) $this->session->unset_userdata('position');
        if($this->session->userdata('name_role')) $this->session->unset_userdata('name_role');
        if($this->session->userdata('status')) $this->session->unset_userdata('status');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/index/";
        $config["total_rows"] = $this->member_m->jum_employee();
        $config["per_page"] = 10;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        if($this->uri->segment(4)) $page = ($this->uri->segment(4));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_member'] = $this->member_m->select_allpaging($config["per_page"], $page);
        $i=0;
        foreach($data['list_member'] as $member){
        	$cek_reset = $this->leave_m->select_leave_history_employee($member['id_employee']);
        	if(!empty($cek_reset)) $data['list_member'][$i]['button_reset'] = FALSE;
        	else $data['list_member'][$i]['button_reset'] = TRUE;

        	$i++;
		}

        $this->load->view('em_employee_v', $data);

    }

    public function search($sortby, $sorttype) {
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');

        if($this->uri->segment(6)) $page = ($this->uri->segment(6));
        else $page = 0;
        $per_page = 10;
        if ($sortby == "orby") $orderby = "";
        else $orderby = "ORDER BY ".$sortby;

        if ($sorttype == "ortype"){
            $data['typeorder'] = "0";
            $ordertype="";
        }
        else if ($sorttype == 0){
            $data['typeorder'] = 1;
            $ordertype="desc";
        }
        else if ($sorttype == 1) {
            $data['typeorder'] = 0;
            $ordertype="asc";
        }
        if($this->input->post()){
            $data['nik_employee'] = $this->input->post('search_nik_employee');
            $data['fullname'] = $this->input->post('search_fullname');
            $data['level_name'] = $this->input->post('search_level_name');
            $data['name_role'] = $this->input->post('search_name_role');
            $data['status'] = $this->input->post('search_status');
			$data['position'] = $this->input->post('search_position');

            $datasrc = array(
                'nik_employee'  => $data['nik_employee'],
                'fullname'  => $data['fullname'],
                'level_name'  => $data['level_name'],
                'position'  => $data['position'],
                'name_role'  => $data['name_role'],
                'status'  => $data['status']
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['nik_employee'] != "") $kondisi[] = "nik_employee LIKE '%".$data['nik_employee']."%'";
            if($data['fullname'] != "") $kondisi[] = "fullname LIKE '%".$data['fullname']."%'";
            if($data['level_name'] != "") $kondisi[] = "l.level_name LIKE '%".$data['level_name']."%'";
            if($data['position'] != "") $kondisi[] = "position LIKE '%".$data['position']."%'";
            if($data['name_role'] != "") $kondisi[] = "name_role LIKE '%".$data['name_role']."%'";
            if($data['status'] != "") $kondisi[] = "e.status LIKE '%".$data['status']."%'";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee');
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_member'] = $this->member_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

            }
        }
        else {
            $new_kondisi = $this->session->userdata('new_kondisi');
            $data['nik_employee'] = $this->session->userdata('nik_employee');
            $data['fullname'] = $this->session->userdata('fullname');
            $data['level_name'] = $this->session->userdata('level_name');
            $data['position'] = $this->session->userdata('position');
            $data['name_role'] = $this->session->userdata('name_role');
            $data['status'] = $this->session->userdata('status');
            $data['list_member'] = $this->member_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "employment/employee/search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->member_m->jum_employee_search($new_kondisi);
        $config["per_page"] = $per_page;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['tipe_print'] = 'search';//untuk button print
        $data['sortby'] = $sortby;//untuk button print
        $data['sorttype'] = $sorttype;//untuk button print

		$i=0;
		foreach($data['list_member'] as $member){
			$cek_reset = $this->leave_m->select_leave_history_employee($member['id_employee']);
			if(!empty($cek_reset)) $data['list_member'][$i]['button_reset'] = FALSE;
			else $data['list_member'][$i]['button_reset'] = TRUE;

			$i++;
		}

        $this->load->view('em_employee_v', $data);

    }

    public function tambah_data(){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "add";
        $data['list_role'] = $this->role_m->select_all();
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('fullname', 'fullname', 'required');
        $this->form_validation->set_rules('nickname', 'nickname', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('nik_employee', 'nik_employee', 'required');
        $this->form_validation->set_rules('id_role', 'role', 'required');
        if ($this->form_validation->run()==FALSE){
//            var_dump($data);
            $get_status = $this->input->post('status');
            if ($get_status==1) $data['status'] = "checked";

            $data['select_id_role'] = $this->input->post('select_id_role');
            $this->load->view('em_addemployee_v', $data);
        }
        else {
            $data_formprof['fullname'] = $this->input->post('fullname');
            $data_formprof['nickname'] = $this->input->post('nickname');
            $data_formprof['email'] = $this->input->post('email');
            $data_formprof['birthdate'] = $this->input->post('birthdate');
            $data_formprof['address'] = $this->input->post('address');
			$data_formprof['address_2'] = $this->input->post('address_2');
            $data_formprof['nik_employee'] = $this->input->post('nik_employee');
            $data_formprof['npwp'] = $this->input->post('npwp');
            $data_formprof['identity_number'] = $this->input->post('identity_number');
            $data_formprof['phone_number'] = $this->input->post('phone_number');
            $data_formprof['id_role'] = $this->input->post('id_role');

            $get_status = $this->input->post('status');
            if ($get_status==1) $data_formprof['status'] = 1;
            else $data_formprof['status'] = 0;

            $this->up_imgprofile();
            $upload_data=$this->upload->data();
            $data_formprof['photo'] = $upload_data['file_name'];

            $get_password = $this->generateRandomString();
            $data_formprof['password'] = $this->generatePassword($get_password);

            $data_formprof['password_asli'] = $get_password;

            $this->sendpassmail($data_formprof['email'], $data_formprof['nickname'], $data_formprof['password']);


            $this->member_m->insert_employee($data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil menambah data.');
            redirect(base_url().'employment/employee');
        }
    }

    public function detil_data($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');
//        $bulanke = 4;
//        $jatahcuti = 14;
//        $z = (9/12) * 15;
//        echo $z;
//        echo date('n');
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        if ($data['list_employee'][0]['status'] == 1) $data['list_employee'][0]['status_aktif'] = "checked";
        else $data['list_employee'][0]['status_aktif'] = "";

        $data['list_employment'] = $this->employment_m->select_active_employment($id_employee);
        $data['list_level'] = $this->level_m->select_all();
        $data['list_city'] = $this->city_m->select_all();
        $data['list_all_employee'] = $this->member_m->select_all_active();
        $data['list_role'] = $this->role_m->select_all();
        $data['list_personal_leave'] = $this->list_personal_leave($id_employee);
        $data['list_adjustment'] = $this->adjustment_m->select_adjustment_employee($id_employee);

        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "employee_profile";


        //cek jumlah history, jika masih nol maka muncul hanya januari
        //cek jika lebih dari satu maka apakah ada yg sama dengan tahun ini, jika tidak maka muncul, jika ada maka tidak muncul
        $data['button_reset'] = FALSE;
        $this_year = date("Y");
        $this_month = date("m");
        $get_year_history = $this->leave_m->select_detil_leave_history($id_employee, $this_year);

        if($get_year_history == 0 && $this_month == 01){ //muncul hanya bulan januari
            $data['button_reset'] = TRUE;
        }


        $data['startnum'] = 1;
        $data['status_form'] = "edit";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('fullname', 'fullname', 'required');
        $this->form_validation->set_rules('nickname', 'nickname', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('nik_employee', 'nik_employee', 'required');
        $this->form_validation->set_rules('id_role', 'role', 'required');
        if ($this->form_validation->run()==FALSE){
            $get_status = $this->input->post('status');
            if ($get_status==1) $data['status'] = "checked";
            $data['select_id_role'] = $this->input->post('select_id_role');
            $data['select_adjustment_type'] = $this->input->post('select_adjustment_type');

            $this->load->view('em_editemployee_v', $data);
        }
        else {
            $data_formprof['fullname'] = $this->input->post('fullname');
            $data_formprof['nickname'] = $this->input->post('nickname');
            $data_formprof['email'] = $this->input->post('email');
            $data_formprof['birthdate'] = $this->input->post('birthdate');
            $data_formprof['address'] = $this->input->post('address');
			$data_formprof['address_2'] = $this->input->post('address_2');
            $data_formprof['nik_employee'] = $this->input->post('nik_employee');
            $data_formprof['npwp'] = $this->input->post('npwp');
            $data_formprof['identity_number'] = $this->input->post('identity_number');
            $data_formprof['phone_number'] = $this->input->post('phone_number');
            $data_formprof['id_role'] = $this->input->post('id_role');

            $get_status = $this->input->post('status');
            if ($get_status==1) $data_formprof['status'] = 1;
            else $data_formprof['status'] = 0;

            $this->up_imgprofile();
            $upload_data=$this->upload->data();
            $data_formprof['photo'] = $upload_data['file_name'];

            $this->member_m->update_employee($id_employee, $data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data.');
            redirect(base_url().'employment/employee');
        }
    }

    public function personal_leave_search($id_employee, $sortby, $sorttype) {
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_leave";

        if($this->uri->segment(8)) $page = ($this->uri->segment(8));
        else $page = 0;
        $per_page = 10;
        if ($sortby == "orby") $orderby = "";
        else $orderby = "ORDER BY ".$sortby;

        if ($sorttype == "ortype"){
            $data['typeorder'] = "0";
            $ordertype="";
        }
        else if ($sorttype == 0){
            $data['typeorder'] = 1;
            $ordertype="desc";
        }
        else if ($sorttype == 1) {
            $data['typeorder'] = 0;
            $ordertype="asc";
        }
        if($this->input->post()){
            $data['submission_date'] = $this->input->post('search_submission_date');
            $data['start_date'] = $this->input->post('search_start_date');
            $data['end_date'] = $this->input->post('search_end_date');
            $data['days'] = $this->input->post('search_days');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'submission_date'  => $data['submission_date'],
                'start_date'  => $data['start_date'],
                'end_date'  => $data['end_date'],
                'days'  => $data['days'],
                'description'  => $data['description'],
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['submission_date'] != "") $kondisi[] = "submission_date LIKE '%".$data['submission_date']."%'";
            if($data['start_date'] != "") $kondisi[] = "start_date LIKE '%".$data['start_date']."%'";
            if($data['end_date'] != "") $kondisi[] = "end_date LIKE '%".$data['end_date']."%'";
            if($data['days'] != "") $kondisi[] = "days LIKE '%".$data['days']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";
            $kondisi[] = "id_employee = ".$id_employee."";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee/personal_leave/'.$id_employee);
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);


            }
        }
        else {
            //$new_kondisi = $this->session->userdata('new_kondisi');

            $data['submission_date'] = $this->session->userdata('submission_date');
            $data['start_date'] = $this->session->userdata('start_date');
            $data['end_date'] = $this->session->userdata('end_date');
            $data['days'] = $this->session->userdata('days');
            $data['description'] = $this->session->userdata('description');

            $kondisi = "id_employee = ".$id_employee."";
            $new_kondisi = "WHERE ". $kondisi;

            $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "employment/employee/personal_leave_search/".$id_employee."/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->leave_m->jum_leave_personal_search($new_kondisi);
        $config["per_page"] = $per_page;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['tipe_print'] = 'search';//untuk button print
        $data['sortby'] = $sortby;//untuk button print
        $data['sorttype'] = $sorttype;//untuk button print

        $this->load->view('em_personal_leave_v', $data);
    }

    public function personal_leave($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_leave";

        if($this->session->userdata('submission_date')) $this->session->unset_userdata('submission_date');
        if($this->session->userdata('start_date')) $this->session->unset_userdata('start_date');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('end_date');
        if($this->session->userdata('days')) $this->session->unset_userdata('days');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/personal_leave/".$id_employee."/";
        $config["total_rows"] = $this->leave_m->jum_leave_personal($id_employee);
        $config["per_page"] = 10;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        if($this->uri->segment(6)) $page = ($this->uri->segment(6));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_leave'] = $this->list_personal_leave($id_employee);
        $this->load->view('em_personal_leave_v', $data);
    }

    public function personal_adjustment_search($id_employee, $sortby, $sorttype) {
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_adjustment";

        if($this->uri->segment(8)) $page = ($this->uri->segment(8));
        else $page = 0;
        $per_page = 10;
        if ($sortby == "orby") $orderby = "";
        else $orderby = "ORDER BY ".$sortby;

        if ($sorttype == "ortype"){
            $data['typeorder'] = "0";
            $ordertype="";
        }
        else if ($sorttype == 0){
            $data['typeorder'] = 1;
            $ordertype="desc";
        }
        else if ($sorttype == 1) {
            $data['typeorder'] = 0;
            $ordertype="asc";
        }
        if($this->input->post()){
            $data['adjustment_type'] = $this->input->post('search_adjustment_type');
            $data['quota'] = $this->input->post('search_quota');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'adjustment_type'  => $data['adjustment_type'],
                'quota'  => $data['quota'],
                'description'  => $data['description'],
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['adjustment_type'] != "") $kondisi[] = "adjustment_type LIKE '%".$data['adjustment_type']."%'";
            if($data['quota'] != "") $kondisi[] = "quota LIKE '%".$data['quota']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";
            if($data['datetime'] != "") $kondisi[] = "datetime LIKE '%".$data['datetime']."%'";
            $kondisi[] = "id_employee = ".$id_employee."";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee/personal_adjustment/'.$id_employee);
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_personal_adjustment'] = $this->adjustment_m->select_allpaging_adjustment_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);


            }
        }
        else {
            //$new_kondisi = $this->session->userdata('new_kondisi');

            $data['adjustment_type'] = $this->session->userdata('adjustment_type');
            $data['quota'] = $this->session->userdata('quota');
            $data['description'] = $this->session->userdata('description');
            $data['datetime'] = $this->session->userdata('datetime');

            $kondisi = "id_employee = ".$id_employee."";
            $new_kondisi = "WHERE ". $kondisi;

            $data['list_personal_adjustment'] = $this->adjustment_m->select_allpaging_adjustment_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "employment/employee/personal_adjustment_search/".$id_employee."/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->adjustment_m->jum_adjustment_personal_search($new_kondisi);
        $config["per_page"] = $per_page;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['tipe_print'] = 'search';//untuk button print
        $data['sortby'] = $sortby;//untuk button print
        $data['sorttype'] = $sorttype;//untuk button print

        $this->load->view('em_personal_adjustment_v', $data);
    }

    public function personal_adjustment($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_adjustment";

        if($this->session->userdata('adjustment_type')) $this->session->unset_userdata('adjustment_type');
        if($this->session->userdata('quota')) $this->session->unset_userdata('quota');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;
        $data['select_adjustment_type'] = $this->input->post('select_adjustment_type');
        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        if ($data['list_employee'][0]['status'] == 1) $data['list_employee'][0]['status_aktif'] = "checked";
        else $data['list_employee'][0]['status_aktif'] = "";

        $config["base_url"] = base_url() . "employment/employee/personal_adjustment/".$id_employee."/";
        $config["total_rows"] = $this->adjustment_m->jum_adjustment_personal($id_employee);
        $config["per_page"] = 10;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        if($this->uri->segment(5)) $page = ($this->uri->segment(5));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_adjustment'] = $this->adjustment_m->select_adjustment_employee($id_employee);
        $this->load->view('em_personal_adjustment_v', $data);
    }

    public function personal_employment($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');
		$data['list_employment_all'] = $this->employment_m->select_all_employee($id_employee);
		$data['startnum'] = 1;

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        $data['selected_employee_id'] = $id_employee;
        $data['list_level'] = $this->level_m->select_all();
        $data['list_city'] = $this->city_m->select_all();
        $data['list_all_employee'] = $this->member_m->select_all_active();
        $data['list_role'] = $this->role_m->select_all();
        $data['active'] = "personal_employment";
		$data['list_employment'] = $this->employment_m->select_active_employment($id_employee);
		if(!empty($data['list_employment'])){
			$data['status_form'] = "edit";
		}
        else $data['status_form'] = "add";
		$data['select_id_level'] = $this->input->post('select_id_level');
		$data['select_id_city'] = $this->input->post('select_id_city');
		$data['select_id_employee'] = $this->input->post('select_id_employee');

        //cek jumlah history, jika masih nol maka muncul hanya januari
        //cek jika lebih dari satu maka apakah ada yg sama dengan tahun ini, jika tidak maka muncul, jika ada maka tidak muncul
        $data['button_reset'] = FALSE;
        $this_year = date("Y");
        $this_month = date("m");
        $get_year_history = $this->leave_m->select_detil_leave_history($id_employee, $this_year);

        if($get_year_history == 0 && $this_month == 01){ //muncul hanya bulan januari
            $data['button_reset'] = TRUE;
        }

        if($this->session->userdata('nik_employee')) $this->session->unset_userdata('nik_employee');
        if($this->session->userdata('fullname')) $this->session->unset_userdata('fullname');
        if($this->session->userdata('level_name')) $this->session->unset_userdata('level_name');
        if($this->session->userdata('position')) $this->session->unset_userdata('position');
        if($this->session->userdata('name_role')) $this->session->unset_userdata('name_role');
        if($this->session->userdata('status')) $this->session->unset_userdata('status');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/";
        $config["total_rows"] = $this->member_m->jum_employee();
        $config["per_page"] = 10;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        if($this->uri->segment(5)) $page = ($this->uri->segment(5));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_employment'] = $this->employment_m->select_active_employment($id_employee);
        $this->load->view('em_personal_employment_v', $data);
    }

    public function employment_history($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        $data['list_employment'] = $this->employment_m->select_all_employee($id_employee);
        $data['startnum'] = 1;
        $this->load->view('em_employment_v', $data);
    }

    private function list_personal_leave($id_member){
        $list_personal_leave = $this->leave_m->select_personal_leave($id_member);

        $i = 0;
        foreach ($list_personal_leave as $leave){
            $id_leave = $list_personal_leave[$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $list_personal_leave[$i]{'app_status_'.$j} = $app["ap_status"];
                    $list_personal_leave[$i]{'app_name_'.$j} = $app["name_role"];
					$list_personal_leave[$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
            $i++;
        }
        return $list_personal_leave;
    }



    public function tambah_data_employment($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "add";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('id_level', 'level', 'required');
        $this->form_validation->set_rules('id_city', 'city', 'required');
        $this->form_validation->set_rules('position', 'position', 'required');
        $this->form_validation->set_rules('tgl_mulai', 'tgl_mulai', 'required');

        if ($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('pesan_gagal', 'Anda gagal mengubah data employment.');
            redirect(base_url().'employment/employee/detil_data/'.$id_employee);
        }
        else {
			$current_employment = $this->employment_m->select_detil_employment_active($id_employee);
			if(empty($current_employment)){
				$leave_quota_ext_set = 3;
			} else {
				$leave_quota_ext_set = $current_employment[0]['leave_quota_ext'];
			}

            $data_formprof['id_level'] = $this->input->post('id_level');
            $data_formprof['id_city'] = $this->input->post('id_city');
            $data_formprof['supervisor'] = $this->input->post('id_employee');
            $data_formprof['position'] = $this->input->post('position');
            $data_formprof['tgl_mulai'] = $this->input->post('tgl_mulai');
            $data_formprof['description'] = $this->input->post('description');
            $data_formprof['status'] = 1;

            $data_formprof['leave_quota'] = $this->count_level_quota($id_employee, $this->input->post('id_level'));
            $data_formprof['leave_quota_ext'] = $leave_quota_ext_set;
            $data_formprof['id_employee'] = $id_employee;
            $data_formprof['division'] = $current_employment[0]['division'];

			$data_formup['status'] = 0;
			$data_formup['tgl_berakhir'] = $data_formprof['tgl_mulai'];
          	$this->employment_m->update_employment_active($id_employee, $data_formup);

            $this->employment_m->insert_employment($data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data employment.');
            redirect(base_url().'employment/employee/personal_employment/'.$id_employee);

        }
    }

    public function tambah_data_adjustment($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);

        $data['status_form'] = "add";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('adjustment_type', 'adjustment_type', 'required');
        $this->form_validation->set_rules('quota', 'quota', 'required');

        if ($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('pesan_gagal', 'Anda gagal mengubah data adjustment.');
            redirect(base_url().'employment/employee/detil_data/'.$id_employee);
        }
        else {
            $data_formprof['id_employee'] = $id_employee;
            $data_formprof['adjustment_type'] = $this->input->post('adjustment_type');
            $data_formprof['quota'] = $this->input->post('quota');
            $data_formprof['description'] = $this->input->post('description');
            $data_formprof['datetime'] = $this->today_datetime();

            $this->count_new_quota($id_employee, $data_formprof['adjustment_type'], $data_formprof['quota']);//update employment

            $this->adjustment_m->insert_adjustment($data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil menanmbahkan data leave adjustment.');
            redirect(base_url().'employment/employee/personal_adjustment/'.$id_employee);
        }
    }

    private function count_new_quota($id_employee, $type_adjustment, $quota_adjustment){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $id_employment = $current_employment[0]['id_employment'];

        if($type_adjustment == 'Adjustment'){
            $data_ad['leave_quota'] = $current_employment[0]['leave_quota'] + $quota_adjustment;
        }
        else if($type_adjustment == 'Deduction'){
            $data_ad['leave_quota'] = $current_employment[0]['leave_quota'] - $quota_adjustment;
        }
        $this->employment_m->update_employment($id_employment, $data_ad);
    }

    private function count_level_quota($id_employee, $id_level){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $jum_employment = $this->employment_m->jum_employment_staff($id_employee);

        if($jum_employment == 0 ) { //jika input employment pertama kali
            $get_level = $this->level_m->select_detil_level($id_level);
            $leave_quota = $get_level[0]['level_quota'];
        } else {
            if ( $current_employment[0]['id_level'] == $id_level){ // jika level sama dengan input, maka tidak akan merubah jumlah quota sebelumnya
                $leave_quota = $current_employment[0]['leave_quota'];
            }
            else {
                //jika level berubah maka menghitung total bulan cuti level sebelumnya dan bulan cuti yg baru
                //algoritma :
                // get1 = (bulan kuota / kuota level saat ini) * kuota level saat ini
                // get2 = (bulan kuota / kuota level baru) * kuota level baru
                // kuota terbaru = get1 + get2 - kuota yg sudah dipakai

                $current_quota = $current_employment[0]['leave_quota']; //jumlah skrg
                $current_id_level = $current_employment[0]['id_level'];

                $get_cur_quota = $this->level_m->select_detil_level($current_id_level);
                $cur_level_quota = $get_cur_quota[0]['level_quota']; //level lama/skrg

                $get_post_quota = $this->level_m->select_detil_level($id_level);
                $post_level_quota = $get_post_quota[0]['level_quota']; //level terbaru

                $get_month_cur = date('m');
                $number_date = date('j', strtotime($data_formprof['tgl_mulai']));
                if ($number_date > 14) $month_cur = $get_month_cur;
                else $month_cur = $get_month_cur - 1;
                $month_remaining = 12 - $month_cur;

                $used_quota = $cur_level_quota - $current_quota;

                $count_current_month = round(($month_cur / 12),2);
                $count_remaining_month = round(($month_remaining / 12),2);
                $result_current = $count_current_month * $cur_level_quota;
                $result_post = $count_remaining_month * $post_level_quota;

                $leave_quota = round(($result_current + $result_post),0) - $used_quota;
            }
        }
        return $leave_quota;
    }

    public function reset_leave($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $id_employment = $current_employment[0]['id_employment'];
        $id_employee = $current_employment[0]['id_employee'];
        $current_level = $current_employment[0]['id_level'];
        $debt_leave_quota = 3 - $current_employment[0]['leave_quota_ext'];

        $detil_level = $this->level_m->select_detil_level($current_level);
        $level_quota = $detil_level[0]['level_quota'];

        $data_formem['leave_quota'] = $level_quota - $debt_leave_quota;
        $this->employment_m->update_employment($id_employment, $data_formem);

        $data_formhs['year_leave_history'] = date("Y");
        $data_formhs['id_employee'] = $id_employee;
        $this->leave_m->insert_leave_history($data_formhs);//belum dibuat di halaman view, reset tidak muncul jika reset tahun ini sudah masuk history

        $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan reset <i>leave quota</i> pada karyawan ini.');
        redirect(base_url().'employment/employee/detil_data/'.$id_employee);
    }

	public function multiple_reset_leave(){
		if($this->log_super_hr()){
		}
		else redirect(base_url().'dashboard');

		if ($this->input->post('chkbox_reset')){
			$data_id = $this->input->post('chkbox_reset');
			//var_dump($data_id);
			foreach ($data_id as $id_employee){
				$current_employment = $this->employment_m->select_detil_employment_active($id_employee);
				$id_employment = $current_employment[0]['id_employment'];
				$id_employee = $current_employment[0]['id_employee'];
				$current_level = $current_employment[0]['id_level'];
				$debt_leave_quota = 3 - $current_employment[0]['leave_quota_ext'];

				$detil_level = $this->level_m->select_detil_level($current_level);
				$level_quota = $detil_level[0]['level_quota'];

				$data_formem['leave_quota'] = $level_quota - $debt_leave_quota;
				$this->employment_m->update_employment($id_employment, $data_formem);

				$data_formhs['year_leave_history'] = date("Y");
				$data_formhs['id_employee'] = $id_employee;
				$this->leave_m->insert_leave_history($data_formhs);
			}

			$this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan reset <i>leave quota</i> pada beberapa karyawan.');
			redirect(base_url().'employment/employee/');
		} else redirect(base_url().'employment/employee/');


	}


    public function up_imgprofile()
    {
        $config['upload_path'] = './assets/uploads/profile/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['maintain_ratio'] = TRUE;
        $config['create_thumb'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $field_name = "profilepic"; //nameinput form

        if (!$this->upload->do_upload($field_name)) {
            $error = array('error' => $this->upload->display_errors());

        }
    }

    private function count_days($start_date, $end_date, $weekendtype) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');
        $interval = $end->diff($start);
        // total days
        $days = $interval->days;
        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        //$holidays = array('2018-07-18'); // harilibur nasional
        $holidays = $this->joint_holiday();

        foreach($period as $dt) {
            $curr = $dt->format('D');

            // substract if Saturday or Sunday
            if ($weekendtype == "satsun") {
                if ($curr == 'Sat' || $curr == 'Sun') {
                    $days--;
                }
                // (optional) for the updated question
                elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            } else if ($weekendtype == "sun") {
                if ($curr == 'Sun') {
                    $days--;
                }
                elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            }
        }
        return $days;
    }

    private function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generatePassword($randomString){
        $pengacak = "1S4I0V2I1A9A9Z7IZAH";
        $resPassword = password_hash($randomString.$pengacak, PASSWORD_BCRYPT);

        return $resPassword;
    }

    private function sendpassmail($to, $nickname, $password){
        $from_email = "yudha.will.stay@gmail.com";
        $name = "HRD Padinet";
        $subject = "Akses Aplikasi Cuti PadiNET";
        $message = "Dear ".$nickname.", berikut passwordmu :". $password;

        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);

        $this->email->from($from_email);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
    }

    private function today_datetime(){
        $datestring = '%Y-%m-%d %h:%i:%s';
        $time = time();
        $tanggal = mdate($datestring, $time);

        return $tanggal;
    }

    private function logged_in(){
        if($this->session->userdata('logged_in')){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }

    private function log_super_hr(){
        if($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }

    private function log_super_hr_staff(){
        if($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2 || $this->session->userdata('id_role')==3 || $this->session->userdata('id_role')==4){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }
}
