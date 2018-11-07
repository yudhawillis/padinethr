<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Myrequest extends CI_Controller{
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
        $this->load->model('approval_m', 'approval_m');
        $this->load->model('employment_m', 'employment_m');
		$this->load->model('holiday_m', 'holiday_m');
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'mem_login');
    }

    public function index(){
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
		if ($data['current_user'][0]['id_city'] == 1 || $data['current_user'][0]['id_city'] == 2) $weekendtype = "satsun";
		else $weekendtype = "sun";

        if($this->session->userdata('new_kondisi')) $this->session->unset_userdata('new_kondisi');
        if($this->session->userdata('submission_date')) $this->session->unset_userdata('submission_date');
        if($this->session->userdata('start_date')) $this->session->unset_userdata('start_date');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('end_date');
        if($this->session->userdata('days')) $this->session->unset_userdata('days');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "leave/myrequest/index/";
        $config["total_rows"] = $this->leave_m->jum_leave_personal($id_current_user);
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
        $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal($config["per_page"], $page, $id_current_user);

        $i = 0;
        foreach ($data['list_personal_leave'] as $leave){
            $id_leave = $data['list_personal_leave'][$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);

            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                    $data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
					$data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
			$data['list_personal_leave'][$i]['day'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype);

            $i++;

        }
        $this->load->view('lv_myrequest_v', $data);

    }

    public function search($sortby, $sorttype) {
        $id_current_user = $this->session->userdata('id_employee');
		$data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
		if ($data['current_user'][0]['id_city'] == 1 || $data['current_user'][0]['id_city'] == 2) $weekendtype = "satsun";
		else $weekendtype = "sun";

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
            $kondisi[] = "id_employee = ".$id_current_user."";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'leave/myrequest');
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

            $kondisi = "id_employee = ".$id_current_user."";
            $new_kondisi = "WHERE ". $kondisi;

            $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

			$i = 0;
			foreach ($data['list_personal_leave'] as $leave){
				$id_leave = $data['list_personal_leave'][$i]['id_leave'];
				$approval = $this->approval_m->select_leave_approval($id_leave);
				if ($approval != "") {
					$j = 1;
					foreach ($approval as $app) {
						$data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
						$data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
						$data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
						$j++;
					}
				}
				$data['list_personal_leave'][$i]['day'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype);
				$i++;
			}
        }

        $config["base_url"] = base_url() . "leave/myrequest/search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
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

        $i = 0;
        foreach ($data['list_personal_leave'] as $leave){
            $id_leave = $data['list_personal_leave'][$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                    $data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
					$data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
			$data['list_personal_leave'][$i]['days'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype);
            $i++;
        }

        $this->load->view('lv_myrequest_v', $data);
    }

    public function cancel_leave($id_leave){
        $id_current_user = $this->session->userdata('id_employee');

        $data['detail_member'] = $this->member_m->select_detil_employee($id_current_user);
        $data['cek_jum_approve'] = $this->approval_m->select_jum_approve_leave($id_leave);

        $all_approval = $this->approval_m->select_leave_approval($id_leave);
        if(!empty($all_approval)){
            foreach($all_approval as $appr){
                if($appr['level_user_approval'] == "hr") $hr_approve = TRUE;
            }
        }

        if(isset($hr_approve)){
            $list_leave = $this->leave_m->select_detil_leave($id_leave);
            $req_quota = $list_leave[0]['days'];
            $payroll_deduction = $list_leave[0]['payroll_deduction'];
            $leave_quota_ext_used = $list_leave[0]['leave_quota_ext'];
            //echo $req_quota." req_quota <br>";
            //echo $leave_quota_ext_used." leave kuota ext <br>";
            $dispensation_quota = $list_leave[0]['dispensation_quota'];
            $current_quota = $this->cek_jum_quota($id_current_user);
            $current_quota_ext = $this->cek_jum_quota_ext($id_current_user);
            //echo $current_quota." current_quota <br>";
            $current_employment = $this->get_current_employment($id_current_user);
			$leave_quota_ext = $current_employment[0]['leave_quota_ext'];

            $other_quota = $payroll_deduction + $dispensation_quota;
			$cek_sisa_payroll = $req_quota - $other_quota;
            //echo $cek_sisa_payroll." cek_sisa_payroll <br>";
			$cek_sisa_quota_ext = $cek_sisa_payroll - $leave_quota_ext_used;//
			//misal  req 2 days hasil dari pengurangan other kuota. quota ext terpakai 1 hari
			//dengan anggapan req 2 days, other kuota 0, jatah kuota minus 1 day
			//maka quota ext yang baru adalah 3 - jatah kuota minus(1 day) = 2

			//misal req 0 days hasil dari pengurangan other kuuta. quota ext pasti terpakai 0 hari, karena tidak ada sisa dari req utama
			$data_formem['leave_quota'] = $current_quota + $cek_sisa_quota_ext;
			$data_formem['leave_quota_ext'] = $leave_quota_ext + $leave_quota_ext_used;
			var_dump($data_formem);

            $this->employment_m->update_employment($current_employment[0]['id_employment'], $data_formem);
        }
        $data_formlv['cancel_status'] = 1;
        $this->leave_m->update_leave($id_leave, $data_formlv);
        $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan pembatalan pada cuti anda.');
        redirect(base_url().'leave/myrequest');
    }

    private function get_current_employment($id_employee){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        return $current_employment;
    }

    private function cek_jum_quota($id_employee){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $current_quota = $current_employment[0]['leave_quota'];

        return $current_quota;
    }

    private function cek_jum_quota_ext($id_employee){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $current_quota_ext = $current_employment[0]['leave_quota_ext'];

        return $current_quota_ext;
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

	private function joint_holiday() {
		$list_holiday = $this->holiday_m->select_all();
		$all_holiday = array();

		foreach($list_holiday as $holiday){
			$all_holiday[] = $holiday['date_holiday'];
		}

		return $all_holiday;
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
//        if($this->session->userdata('level')=="root" || $this->session->userdata('level')=="admin" || $this->session->userdata('level')=="super"){
//            if($this->session->userdata('status_aktif_admin')=="1"){
//                $usr = $this->session->userdata('username');
//                return isset($usr);
//            }
//        }
    }
}
