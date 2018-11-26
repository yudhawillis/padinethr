<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Myschedule extends CI_Controller{
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
		$this->load->model('level_m', 'level_m');
        $this->load->model('holiday_m', 'holiday_m');
        $this->load->model('employment_m', 'employment_m');
        $this->load->model('approval_m', 'approval_m');
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'mem_login');
    }

    public function index(){
        if($this->session->userdata('start_date')) $this->session->unset_userdata('start_date');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('end_date');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        if($this->session->userdata('current_quota')) $this->session->unset_userdata('current_quota');
        if($this->session->userdata('req_quota')) $this->session->unset_userdata('req_quota');
        if($this->session->userdata('debt_leave_quota')) $this->session->unset_userdata('debt_leave_quota');
        if($this->session->userdata('payroll_deduction')) $this->session->unset_userdata('payroll_deduction');
        if($this->session->userdata('leave_quota_updated')) $this->session->unset_userdata('leave_quota_updated');
        if($this->session->userdata('leave_quota_ext_updated')) $this->session->unset_userdata('leave_quota_ext_updated');

        $warning_request_block = $this->cek_employment_empty();
        if(empty($warning_request_block)){
            $this->session->set_flashdata('pesan', 'Anda tidak diperbolehkan melakukan request cuti.');
            redirect(base_url().'leave/myschedule/blocked_request');
        }
 /////////-----------------garapan on demand---------------------------------------------------


        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $first_employment = $this->employment_m->select_first_row_employment($id_current_user);
        $first_employment = json_decode(json_encode($first_employment),true); //konversi stdclass to array
        //echo $first_employment;


// %d outputs the number of days that is not aclready covered by the month
        //$date_start_string = "2013-03-15";
		$date_start_string = $first_employment['tgl_mulai'];
        $date_start = date_create($date_start_string);
        date_add($date_start,date_interval_create_from_date_string("1 year"));
        $date_one_year = date_format($date_start,"Y-m-d");

        if( strtotime('now') >= strtotime($date_one_year) ) {

            //cek tahun -> if tahun selisih dua tahun maka januari prorate
            //jika kurang dari satu tahun maka prorate current month
            $year_current = date('y');
            $get_year_date_one_year = date('y', strtotime($date_one_year));

			$start_date_select_current_year = $year_current."-01-01";
			$end_date_select_current_year = $year_current."-12-31";

            $personal_employment_year = $this->employment_m->employment_staff_year($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
			$list_emp_level = array();
			$list_jum_leave = array();
			$bulan_akhir = 12;
			$list_month_employment_full_quota[0] = "1"; //sebagai bulan januari untuk array full quota
			var_dump($personal_employment_year);
			foreach($personal_employment_year as $emp){
				$list_emp_level[] = $emp['id_level'];
				$data_level = $this->level_m->select_detil_level($emp['id_level']);
				$list_emp_quota[] = $data_level[0]['level_quota'];
				$list_month_employment_full_quota[] = date('n', strtotime($emp['tgl_mulai']));;
				$list_month_employment_prorate[] =  date('n', strtotime($emp['tgl_mulai']));
			}
			var_dump($list_month_employment_prorate);
			if ($year_current - $get_year_date_one_year == 0) {
				// harus dibedakan antara employment tahun pertama
				// karena tahun pertama adalah dihitung tgl start kebawah dari employment pertama
				// sedangkan tahun kedua dihitung per januari, dan dihitung dari employmen kedua dst
				$i = 0;
				foreach ($list_month_employment_prorate as $month) {
					$j = $i + 1;

					if (isset($list_month_employment_prorate[$j])) {
						$jarak_bulan = $list_month_employment_prorate[$j] - $list_month_employment_prorate[$i];
						$list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
					} else {
						$jarak_bulan = $bulan_akhir - $list_month_employment_prorate[$i] + 1 ;
						$list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
					}
					$i++;
				}
				$total_jum_leave = array_sum($list_jum_leave);

			} else if ($year_current - $get_year_date_one_year >= 1) {//jika tahun kedua
				// pengecekan next array untuk menghitung jarak antar bulan
				// berlaku jika ada 3 array / 3 bulan dalam satu tahun
				// array pertama pasti januari. array bulan kedua menghitung jumlah bulan sampai bulan array ke tiga

				$i = 0;
				foreach ($list_month_employment_full_quota as $month) {
					$j = $i + 1;
					if (isset($list_month_employment_full_quota[$j])) {
						$jarak_bulan = $list_month_employment_full_quota[$j] - $list_month_employment_full_quota[$i];
						$list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
					} else {
						$jarak_bulan = $bulan_akhir - $list_month_employment_full_quota[$i] + 1;
						$list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
					}
					$i++;
				}
				$total_jum_leave = array_sum($list_jum_leave);
			} else {
				$total_jum_leave = 0;
				//tanpa cuti
			}
        } else {
            $total_jum_leave = 0;
            //tanpa cuti
        }
        echo round($total_jum_leave);




            if ($data['current_user'][0]['id_city'] == 1 || $data['current_user'][0]['id_city'] == 2) $weekendtype = "satsun";
        else $weekendtype = "sun";


        ///select semua leave
        /// //belum ada filter berdasarkan current year
        $list_personal_leave = $this->leave_m->select_personal_leave_non_cancel($id_current_user);
//        echo $id_current_user;
//        var_dump($list_personal_leave);
        if (!empty($list_personal_leave)){
            $i=0;
            foreach ($list_personal_leave as $leave){
                $id_leave = $list_personal_leave[$i]['id_leave'];
                $jum_approval = $this->approval_m->jum_approval_personal_leave($id_leave);
                if ($jum_approval == 2){
                    $list_personal_leave[$i]['status_approve'] = TRUE;
                } else {
                    $list_personal_leave[$i]['status_approve'] = FALSE;
                }
                $list_personal_leave[$i]['day'] = $this->count_days($list_personal_leave[$i]['start_date'], $list_personal_leave[$i]['end_date'], $weekendtype);

                $i++;
            }
        }

        var_dump($list_personal_leave);

/////////-----------------garapan on demand - END ---------------------------------------------------









        $data['current_quota'] = $this->cek_jum_quota();
        $data['current_quota_ext'] = $this->cek_jum_quota_ext();
        if($data['current_quota_ext'] == 0){
            $data['notif_current_quota'] = 0;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('start_date', 'Date Start', 'required');//human name urutan kedua
        $this->form_validation->set_rules('end_date', 'Date End', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        if ($this->form_validation->run()==FALSE){
            $data['status_form']='add';
            $this->load->view('lv_myschedule_v', $data);
        }
        else {
            if ($data['current_user'][0]['id_city'] == 1 || $data['current_user'][0]['id_city'] == 2) $weekendtype = "satsun";
            else $weekendtype = "sun";

            $data_formlv['start_date'] = $this->input->post('start_date');
            $data_formlv['end_date'] = $this->input->post('end_date');
            $data_formlv['description'] = $this->input->post('description');
            $data_formlv['submission_date'] = $this->today_datetime();
            $data_formlv['id_employee'] = $data['current_user'][0]['id_employee'];

            $data_formlv['days'] = $this->count_days($data_formlv['start_date'], $data_formlv['end_date'], $weekendtype);

            $req_quota = $data_formlv['days'];
            $cek_req_quota = $data['current_quota'] - $data_formlv['days']; //abs agar hasilnya selalu postifi

            //$cek_req_quota hanya utk pengecekan jika melampaui < 0
            //total request adalah variable : $req_quota

            if($cek_req_quota < 0) {
                $leave_quota_updated = 0;
                $cek_req_quota_ext = $data['current_quota_ext'] - abs($cek_req_quota);
                $payroll_deduction = 0;
                $debt_leave_quota = 0;
                if($cek_req_quota_ext < 0){
                    //jika hasilnya kurang dari nol maka sisanya adalah dibuat sbg bilangan positif sebagai payrol_deduction
                    $payroll_deduction = abs($cek_req_quota_ext);
                } else {
                    //jika hasilnya 0 atau lebih dari nol maka tetap sebagai hasil positif dan mengupdate $debt_leave_quota
                    $debt_leave_quota = $cek_req_quota_ext;

                }
                $leave_quota_ext_updated = 3 - $debt_leave_quota;
                $scheduler = array(
                    "submission_date" => $data_formlv['submission_date'],
                    "start_date" => $this->input->post('start_date'),
                    "end_date" => $this->input->post('end_date'),
                    "description" => $this->input->post('description'),
                    "current_quota" => $data['current_quota'],
                    "req_quota" => $req_quota,
                    "debt_leave_quota" => $debt_leave_quota,
                    "payroll_deduction" => $payroll_deduction,
                    "leave_quota_updated" => $leave_quota_updated,
                    "leave_quota_ext_updated" => $leave_quota_ext_updated
                );
                $this->session->set_userdata($scheduler);
                $this->session->set_flashdata('pesan', 'Kuota cuti berada pada limit.');
                redirect(base_url().'leave/myschedule/review');
            } else if($cek_req_quota >= 0){
                $this->leave_m->insert_leave($data_formlv);
                $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan input request cuti.');
                redirect(base_url().'leave/myrequest');
            }
//            if($final_req_quota >= -3 && $final_req_quota < 0){ //cuti di dalam minus
//                $debt_leave_quota = $final_req_quota;
//                $scheduler = array(
//                    "start_date" => $this->input->post('start_date'),
//                    "end_date" => $this->input->post('end_date'),
//                    "description" => $this->input->post('description'),
//                    "current_quota" => $current_quota,
//                    "req_quota" => $req_quota,
//                    "final_req_quota" => $final_req_quota,
//                    "debt_leave_quota" => $debt_leave_quota,
//                    "payroll_deduction" => 0
//                );
//                $this->session->set_userdata($scheduler);
//                $this->session->set_flashdata('pesan', 'Kuota cuti berada pada limit.');
//                redirect(base_url().'leave/myschedule/review');
//
//            }
//            else if($current_quota != -3 && $final_req_quota < -3){ //cuti sampai potong gaji
//                $debt_leave_quota = 3;
//                $payroll_deduction = ($current_quota + $debt_leave_quota) - $final_req_quota;
//
//                $scheduler = array(
//                    "start_date" => $this->input->post('start_date'),
//                    "end_date" => $this->input->post('end_date'),
//                    "description" => $this->input->post('description'),
//                    "current_quota" => $current_quota,
//                    "req_quota" => $req_quota,
//                    "final_req_quota" => $final_req_quota,
//                    "debt_leave_quota" => $debt_leave_quota,
//                    "payroll_deduction" => $payroll_deduction
//                );
//                $this->session->set_userdata($scheduler);
//                $this->session->set_flashdata('pesan', 'Kuota cuti berada pada limit.');
//                redirect(base_url().'leave/myschedule/review');
//
//            } else if ($final_req_quota >= 0) { //cuti tidak sampai minus
//                $this->leave_m->insert_leave($data_formlv);
//                $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan input request cuti.');
//                redirect(base_url().'leave/myrequest');
//
//            }
        }
    }
    public function blocked_request(){
        $this->load->view('lv_myblocked_v');
    }

    public function review(){
        if ($this->session->userdata('start_date')){
            $id_current_user = $this->session->userdata('id_employee');
            $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

            $data['submission_date'] = $this->session->userdata('submission_date');
            $data['start_date'] = $this->session->userdata('start_date');
            $data['end_date'] = $this->session->userdata('end_date');
            $data['description'] = $this->session->userdata('description');
            $data['current_quota'] = $this->session->userdata('current_quota');
            $data['req_quota'] = $this->session->userdata('req_quota');
            $data['debt_leave_quota'] = $this->session->userdata('debt_leave_quota');
            $data['payroll_deduction'] = $this->session->userdata('payroll_deduction');
            $data['leave_quota_updated'] = $this->session->userdata('leave_quota_updated');
            $data['leave_quota_ext_updated'] = $this->session->userdata('leave_quota_ext_updated');

            $this->load->view('lv_myreview_v', $data);
        } else {
            redirect(base_url().'leave/myschedule');
        }
    }

    public function review_submit(){
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data_formlv['submission_date'] = $this->session->userdata('submission_date');
        $data_formlv['start_date'] = $this->session->userdata('start_date');
        $data_formlv['end_date'] = $this->session->userdata('end_date');
        $data_formlv['days'] = $this->session->userdata('req_quota');
        $data_formlv['description'] = $this->session->userdata('description');
        $data_formlv['leave_quota_ext'] = $this->session->userdata('leave_quota_ext_updated');
        $data_formlv['payroll_deduction'] = $this->session->userdata('payroll_deduction');
        $data_formlv['id_employee'] = $data['current_user'][0]['id_employee'];

        $this->leave_m->insert_leave($data_formlv);
        if ($data_formlv['payroll_deduction'] > 0){
            $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan input request cuti dengan potongan gaji.');
        } else {
            $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan input request cuti dengan request hutang cuti.');
        }

        redirect(base_url().'leave/myrequest');
    }

    private function cek_employment_empty(){
        $id_current_user = $this->session->userdata('id_employee');
        $current_employment = $this->employment_m->select_detil_employment_active($id_current_user);

        return $current_employment;
    }

    private function cek_jum_quota(){
        $id_current_user = $this->session->userdata('id_employee');
        $current_employment = $this->employment_m->select_detil_employment_active($id_current_user);
        $current_quota = $current_employment[0]['leave_quota'];

        return $current_quota;
    }

    private function cek_jum_quota_ext(){
        $id_current_user = $this->session->userdata('id_employee');
        $current_employment = $this->employment_m->select_detil_employment_active($id_current_user);
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
