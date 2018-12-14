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
        $this->load->model('adjustment_m', 'adjustment_m');
        $this->load->model('approval_m', 'approval_m');
        $this->load->model('dispensation_m', 'dispensation_m');
        $this->load->model('employment_m', 'employment_m');
        $this->load->model('holiday_m', 'holiday_m');
        $this->load->model('leavedebt_m', 'leavedebt_m');
        $this->load->model('leave_m', 'leave_m');
        $this->load->model('level_m', 'level_m');
        $this->load->model('member_m','member_m');
        $this->load->model('user_m', 'user_m');
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

		$year_select = date('Y');
		$leave_quota_employment = $this->get_employment_quota_leave($id_current_user, $year_select);
		$jum_quota_adjustment = $this->get_adjustment_quota($id_current_user, $year_select);
		$current_leave_quota = $this->get_final_leave_quota($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment);

		$quota_origin = $this->cek_employee_leave_extend($id_current_user, $year_select, $current_leave_quota['quota_origin']);

        $minus_quota_prev_year = $this->get_minus_quota_prev_year($id_current_user, $year_select);

		$leave_quota_remaining = $quota_origin - $minus_quota_prev_year;
		$leave_quota_debt_remaining = $current_leave_quota['debt_quota'];

		echo $leave_quota_remaining;
		echo $leave_quota_debt_remaining;






        //posisi tahun sekarang misalkan 2019
        // tetapi melihat tahun sebelumnya yaitu 2018
        //start menghitung sisa kuota tahun 2019 tanpa utang cuti
        $view_year_before = 2018;
        $year_now = date('Y');
        $year_select = $year_now - 1; //2018
        if ($view_year_before == $year_select){
            $leave_quota_employment_prev_year = $this->get_employment_quota_leave($id_current_user, $year_select);
            $jum_quota_adjustment_prev_year = $this->get_adjustment_quota($id_current_user, $year_select);
            $leave_quota_prev_year = $this->get_quota_left($id_current_user, $year_select, $leave_quota_employment_prev_year, $jum_quota_adjustment_prev_year);
            $leave_quota_remaining = $leave_quota_prev_year;
            echo $leave_quota_prev_year ;
            if ($leave_quota_prev_year > 0 ){
                //perhitungan extend 15 hari masa pengambilan cuti - tahun ini mengambil kuota tahun lalu
                $year_select_next = $year_now;
                $final_quota_leave_year_extend = $this->get_final_quota_leave_year_extend($id_current_user, $year_select_next);
                if ($final_quota_leave_year_extend == $leave_quota_remaining){
                    $leave_quota_remaining = $leave_quota_remaining + $leave_quota_prev_year;
                }
                else if ($final_quota_leave_year_extend < $leave_quota_prev_year){
                    $sisa_pengurangan = $leave_quota_prev_year - $final_quota_leave_year_extend;
                    $leave_quota_remaining =  $leave_quota_remaining + $sisa_pengurangan;
                }
                else if ($final_quota_leave_year_extend > $leave_quota_prev_year){
                    $sisa_kelebihan = $final_quota_leave_year_extend - $leave_quota_prev_year;
                    $leave_quota_remaining =  $leave_quota_remaining - $sisa_kelebihan;
                }
            }
        }

        //end menghitung sisa kuota tahun lalu tanpa utang cuti


/////////-----------------garapan on demand - END ---------------------------------------------------


		$data['current_quota'] = $leave_quota_remaining;
		$data['current_quota_ext'] = $leave_quota_debt_remaining;
		if($data['current_quota_ext'] == 0){
			$data['notif_current_quota'] = 0;
		}

//        $data['current_quota'] = $this->cek_jum_quota();
//        $data['current_quota_ext'] = $this->cek_jum_quota_ext();
//        if($data['current_quota_ext'] == 0){
//            $data['notif_current_quota'] = 0;
//        }

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

	//////////////////////////////////////
	///  start new algoritma
	//////////////////////////////////////

    private function get_employment_quota_leave($id_current_user, $year_select){
		$first_employment = $this->employment_m->select_first_row_employment($id_current_user);
		if (!empty($first_employment)){
			$first_employment = json_decode(json_encode($first_employment),true); //konversi stdclass to array

			//$date_start_string = "2013-03-15";
			$date_start_string = $first_employment['tgl_mulai'];
			$date_start = date_create($date_start_string);
			date_add($date_start,date_interval_create_from_date_string("1 year"));
			$date_one_year = date_format($date_start,"Y-m-d");

			if( strtotime('now') >= strtotime($date_one_year) ) {

				//cek tahun -> if tahun selisih dua tahun maka januari prorate
				//jika kurang dari satu tahun maka prorate current month
				$year_current = $year_select;
				$start_date_select_current_year = $year_current."-01-01";
				$end_date_select_current_year = $year_current."-12-31";

				$get_year_date_one_year = date('Y', strtotime($date_one_year));

				$personal_employment_year = $this->employment_m->employment_staff_year($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
				$list_emp_level = array();
				$list_jum_leave = array();
				$bulan_akhir = 12;
				$list_month_employment_full_quota[0] = "1"; //sebagai bulan januari untuk array full quota


				foreach($personal_employment_year as $emp){
					$list_emp_level[] = $emp['id_level'];
					$data_level = $this->level_m->select_detil_level($emp['id_level']);
					$list_emp_quota[] = $data_level[0]['level_quota'];

					$number_month = date('n', strtotime($emp['tgl_mulai']));
					$number_date = date('i', strtotime($emp['tgl_mulai']));
					if ($number_date > 14) $month_number_new = $number_month + 1;
					else $month_number_new = $number_month;

					$list_month_employment_full_quota[] = $month_number_new;
					$list_month_employment_prorate[] =  $month_number_new;
				}

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


		} else {
			$total_jum_leave = 0;
		}

		return round($total_jum_leave);
	}

	private function get_adjustment_quota($id_current_user, $year_select){
		$year_current = $year_select;
		$start_date_select_current_year = $year_current."-01-01";
		$end_date_select_current_year = $year_current."-12-31";

		$list_adjusment_personal = $this->adjustment_m->select_adjustment_employee_thisyear($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
		$list_adjustment_quota = array();
		$list_deduction_quota = array();
		if(!empty($list_adjusment_personal)){

			$i=0;
			foreach ($list_adjusment_personal as $adj){
				if($list_adjusment_personal[$i]['adjustment_type'] == 'Adjustment'){
					$list_adjustment_quota[] = $list_adjusment_personal[$i]['quota'];
				}
				else if ($list_adjusment_personal[$i]['adjustment_type'] == 'Deduction'){
					$list_deduction_quota[] = $list_adjusment_personal[$i]['quota'];
				}
				$i++;
			}
			$jum_adjustment_quota = array_sum($list_adjustment_quota);
			$jum_deduction_quota = array_sum($list_deduction_quota);

			$result =  $jum_adjustment_quota - $jum_deduction_quota;
		}
		else $result = 0;

		return $result;
	}

	private function final_quota_minus_personal($id_current_user, $year_select, $user_city){
		$leave_quota_employment = $this->get_employment_quota_leave($id_current_user, $year_select);
		$jum_leave_personal = $this->get_leave_with_dispensation($id_current_user, $user_city, $year_select);
		$jum_quota_adjustment = $this->get_adjustment_quota($id_current_user, $year_select);

		$minus_quota = ($leave_quota_employment + $jum_quota_adjustment) - $jum_leave_personal;
		return $minus_quota;
	}

	private function get_minus_quota_leave_last_year($id_current_user, $year_select, $user_city){
		$first_employment = $this->employment_m->select_first_row_employment($id_current_user);
		$debt_quota_last_year = 0;
		if (!empty($first_employment)) {
			$first_employment = json_decode(json_encode($first_employment), true); //konversi stdclass to array
			$tgl_pertama = $first_employment['tgl_mulai'];
			$get_year_date = date('Y', strtotime($tgl_pertama));
			if ($get_year_date < $year_select){
				$list_leave_debt = $this->leavedebt_m->select_leave_debt_personal_year($id_current_user, $get_year_date);
				if(!empty($list_leave_debt)){
					foreach($list_leave_debt as $debt){
						$debt_quota_last_year = $debt['quota_debt'];
					}
				}
				else if(empty($list_leave_debt)){
					//melakukan perhitungan tahun kemarin
					//(employment+ adjustment) - leave request approved
					$year_now = date('Y');
					$year_select = $year_now - 1;
					$minus_quota_last_year = $this->final_quota_minus_personal($id_current_user, $year_select, $user_city);
					if($minus_quota_last_year >= 3) $debt_quota_last_year = 3;
					else $debt_quota_last_year = $minus_quota_last_year;

					$data_formdbt['id_employee'] = $id_current_user;
					$data_formdbt['quota_debt'] = $minus_quota_last_year;
					$data_formdbt['year'] =  $year_select;
					$this->leavedebt_m->insert_leave_debt($data_formdbt);
				}
			}
		}
		return $debt_quota_last_year;
	}

	private function get_range_date($start_date, $end_date){
		$starting_date = date($start_date);
		$ending_date = date($end_date);
		$starting_date1 = date('Y-m-d', strtotime($starting_date.'-1 day'));
		while (strtotime($starting_date1) < strtotime($ending_date))
		{
			$starting_date1 = date('Y-m-d',strtotime($starting_date1.'+1 day'));
			$dates[] = $starting_date1;
		}

		return $dates;
	}

	private function get_list_date_leave_personal($id_employee){
		$leave_personal = array();
		$all_leave_date_personal = array();
		$data_leave = $this->leave_m->select_personal_leave($id_employee);
		if(!empty($data_leave)) {
			foreach ($data_leave as $leave) {
				$get_range_leave = $this->get_range_date($leave['start_date'], $leave['end_date']);
				$all_leave_date_personal = array_merge($leave_personal, $get_range_leave);
			}
		}
		return $all_leave_date_personal;
	}



	private function get_jum_dispensation_personal($id_employee, $year_select){
    	$jum_all_dispensation_quota = 0;
		$data_dispensation = $this->dispensation_m->select_dispensation_employee_year($id_employee, $year_select);
		if(!empty($data_dispensation)){
			foreach($data_dispensation as $disp){
				$list_dispensation[] = $disp['dispensation_quota'];
			}
			$jum_all_dispensation_quota = array_sum($list_dispensation);
		}

		return $jum_all_dispensation_quota;
	}

	private function get_date_leave_personal_select_year($all_leave_date_personal, $year_select){
		$list_leave_current_year = array();
		foreach ($all_leave_date_personal as $date_leave){
			$get_year = date('Y', strtotime($date_leave));
			if ($get_year == $year_select){
				$list_leave_current_year[] = $date_leave;
			}

		}

		return $list_leave_current_year;
	}


	private function get_jum_leave_after_holiday($get_date_leave_personal_select_year, $data_joint_holiday){
		$jum_day_all_leave_filter = count($get_date_leave_personal_select_year);
		foreach ($data_joint_holiday as $holiday){
			if (in_array($holiday, $get_date_leave_personal_select_year)){
				$jum_day_all_leave_filter--;
			}

		}
		return $jum_day_all_leave_filter;
	}

	private function get_final_leave_quota($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
		$data_quota = array();
		$data_joint_holiday = $this->joint_holiday();
		$jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

		$all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
		if(!empty($all_leave_date_personal)){
			$get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
			if(!empty($get_date_leave_personal_current_year)){
				$get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);

				$quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);

				if (($quota_now >= -3 && $quota_now < 0)){
					$debt_quota = abs($quota_now);
					$quota_origin = 0;
				}
				if($quota_now < -3) {
					$debt_quota = 0; //debt quota saat ini, sudah terpakai oleh jatah leave
					$quota_origin = 0;
				}
				else if($quota_now > 0){
					$debt_quota = 3; //debt quota saat ini, sudah terpakai oleh jatah leave
					$quota_origin = $quota_now;
				}
			} else {
				$debt_quota = 3;
				$quota_origin = $leave_quota_employment;
			}

		} else {
			$debt_quota = 3;
			$quota_origin = $leave_quota_employment;
		}

		$data_quota['debt_quota'] = $debt_quota;
		$data_quota['quota_origin'] = $quota_origin;

		return $data_quota;
	}


	private function get_final_leave_quota_last_year($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
		$debt_quota = 3;
		$data_joint_holiday = $this->joint_holiday();
		$jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

		$all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
		if(!empty($all_leave_date_personal)){
			$get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
			if(!empty($get_date_leave_personal_current_year)){
				$get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);

				$quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);

				if (($quota_now >= -3 && $quota_now < 0)){
					$debt_quota = abs($quota_now);
				}
				if($quota_now < -3) {
					$debt_quota = 0; //debt quota saat ini, sudah terpakai oleh jatah leave
				}
				else if($quota_now > 0){
					$debt_quota = 3; //debt quota saat ini, sudah terpakai oleh jatah leave
				}
			}
		}
		return $debt_quota;
	}

    private function get_quota_left($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
        $quota_now = $leave_quota_employment;
        $data_joint_holiday = $this->joint_holiday();
        $jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

        $all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
        if(!empty($all_leave_date_personal)){
            $get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
            if(!empty($get_date_leave_personal_current_year)){
                $get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);
                $quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);
            }
        }
        return $quota_now;
    }

    //start algoritma extend utk employee dgn masa cuti +15hari
	private function get_list_date_leave_personal_extend($id_employee, $year_select){
		$year_next = $year_select + 1;
		$date_start_next = $year_select."-12-31";
		$date_end_next = $year_next."-01-16";

		$leave_personal = array();
		$all_leave_date_personal_extend = array();
		$data_leave = $this->leave_m->select_personal_leave_extend($id_employee, $date_start_next, $date_end_next);
		if(!empty($data_leave)) {
			foreach ($data_leave as $leave) {
				$get_range_leave = $this->get_range_date($leave['start_date'], $leave['end_date']);
				$all_leave_date_personal_extend = array_merge($leave_personal, $get_range_leave);
			}
		}
		return $all_leave_date_personal_extend;
	}

    private function get_final_jum_leave_personal_extend($all_leave_date_personal_extend, $year_select, $id_employee){
        $data_joint_holiday = $this->joint_holiday();
        $jum_leave_extend_after_holiday = 0;
        if(!empty($all_leave_date_personal_extend)){
            $jum_leave_extend_after_holiday = $this->get_jum_leave_after_holiday($all_leave_date_personal_extend, $data_joint_holiday);
        }
        $jum_dispensation_personal_extend = $this->get_jum_dispensation_personal_extend($id_employee, $year_select);

        $final_jum_leave_extend = $jum_leave_extend_after_holiday - $jum_dispensation_personal_extend;
        return $final_jum_leave_extend;

    }

    private function get_jum_dispensation_personal_extend($id_employee, $year_select){
        $year_next = $year_select + 1;
        $date_start_next = $year_select."-12-31";
        $date_end_next = $year_next."-01-16";

        $jum_dispensation_quota = 0;
        $list_id_leave = array();
        $list_quota_dispensation = array();
        $data_leave = $this->leave_m->select_personal_leave_extend($id_employee, $date_start_next, $date_end_next);
        if(!empty($data_leave)) {
            foreach ($data_leave as $leave) {
                $list_id_leave[] = $leave['id_leave'];
            }

            foreach($list_id_leave as $id_leave){
                $data_quota_dispensation = $this->dispensation_m->select_dispensation_leave($id_leave);
                if(!empty($data_quota_dispensation)){
                    foreach ($data_quota_dispensation as $disp){
                        $list_quota_dispensation[] = $disp['dispensation_quota'];
                    }
                }
            }
        }
        $jum_dispensation_quota = array_sum($list_quota_dispensation);
        return $jum_dispensation_quota;
    }

    private function get_final_quota_leave_year_extend($id_employeer, $year_select){
        $all_leave_date_personal_extend = $this->get_list_date_leave_personal_extend($id_employeer, $year_select);
        $final_quota_leave_year_extend = $this->get_final_jum_leave_personal_extend($all_leave_date_personal_extend, $year_select,$id_employeer);

        return $final_quota_leave_year_extend;
    }
    //end algoritma extend utk employee dgn masa cuti +15hari

    private function get_two_weeks_this_year($id_current_user, $quota_origin){
        //posisi tahun sekarang misalkan 2019
        //start menghitung sisa kuota tahun lalu tanpa utang cuti
        $year_select = date('Y');
        $year_select_previous = $year_select - 1;
        $leave_quota_employment_last_year = $this->get_employment_quota_leave($id_current_user, $year_select_previous);
        $jum_quota_adjustment_last_year = $this->get_adjustment_quota($id_current_user, $year_select_previous);
        $leave_quota_last_year = $this->get_quota_left($id_current_user, $year_select_previous, $leave_quota_employment_last_year, $jum_quota_adjustment_last_year);
        $leave_quota_remaining = $quota_origin; //quota tahun ini setelah dipotong berbagai adjustment
        if ($leave_quota_last_year > 0 ){
            //perhitungan extend 15 hari masa pengambilan cuti - tahun ini mengambil kuota tahun lalu
            $final_quota_leave_year_extend = $this->get_final_quota_leave_year_extend($id_current_user, $year_select);
            if ($final_quota_leave_year_extend == $leave_quota_remaining){
                $leave_quota_remaining = $quota_origin + $leave_quota_last_year;
            }
            else if ($final_quota_leave_year_extend < $leave_quota_last_year){
                $sisa_pengurangan = $leave_quota_last_year - $final_quota_leave_year_extend;
                $leave_quota_remaining =  $quota_origin + $sisa_pengurangan;
            }
            else if ($final_quota_leave_year_extend > $leave_quota_last_year){
                $sisa_kelebihan = $final_quota_leave_year_extend - $leave_quota_last_year;
                $leave_quota_remaining =  $quota_origin - $sisa_kelebihan;
            }
        }
        return $leave_quota_remaining;
        //end menghitung sisa kuota tahun lalu tanpa utang cuti
    }

    private function get_minus_quota_prev_year($id_current_user, $year_select){
        //start perhitungan tahun lalu untuk pengecekan minus
        $year_select_previous = $year_select - 1;
        $leave_quota_employment_last_year = $this->get_employment_quota_leave($id_current_user, $year_select_previous);
        $jum_quota_adjustment_last_year = $this->get_adjustment_quota($id_current_user, $year_select_previous);
        $debt_quota_last_year = $this->get_final_leave_quota_last_year($id_current_user, $year_select_previous, $leave_quota_employment_last_year, $jum_quota_adjustment_last_year);
        $minus_quota_prev_year = abs ($debt_quota_last_year - 3);

        return $minus_quota_prev_year;
        //end perhitungan tahun lalu untuk pengecekan minus
    }

    function cek_employee_leave_extend($id_current_user, $year_select, $current_leave_quota_origin){
		$dates = array();
		$list_employment_extend = $this->employment_m->employment_staff_extend($id_current_user);
		foreach($list_employment_extend as $emp){
			if($emp['tgl_berakhir'] == ""){
				$emp['tgl_berakhir'] = $year_select."-12-31";
			}
			while (strtotime($emp['tgl_mulai']) < strtotime($emp['tgl_berakhir']))
			{
				$emp['tgl_mulai'] = date('Y-m-d',strtotime($emp['tgl_mulai'].'+1 day'));
				$dates[] = date('Y',strtotime($emp['tgl_mulai']));
			}
		}
		$year_prev = $year_select - 1;
		if (in_array($year_prev, $dates)){
			$quota_origin = $this->get_two_weeks_this_year($id_current_user, $current_leave_quota_origin);
		}
		else {
			$quota_origin = $current_leave_quota_origin;
		}

		return $quota_origin;
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


//	private function get_leave_with_dispensation($id_current_user, $data_city_user, $year_select){
//		if ($data_city_user == 1 || $data_city_user == 2) $weekendtype = "satsun";
//		else $weekendtype = "sun";
//
//		$year_current = $year_select;
//		$start_date_select_current_year = $year_current."-01-01";
//		$end_date_select_current_year = $year_current."-12-31";
//
//		$list_personal_leave = $this->leave_m->select_personal_leave_non_cancel_thisyear($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
//		$list_jum_personal_leave = array();
//		$list_jum_personal_dispensation = array();
//		$jum_all_leave = 0;
//
//		if (!empty($list_personal_leave)){
//			$i=0;
//			foreach ($list_personal_leave as $leave){
//				$id_leave = $list_personal_leave[$i]['id_leave'];
//				$jum_approval = $this->approval_m->jum_approval_personal_leave($id_leave);
//				if ($jum_approval == 2){
//					$list_personal_leave[$i]['status_approve'] = TRUE;
//				} else {
//					$list_personal_leave[$i]['status_approve'] = FALSE;
//				}
//				$list_personal_leave[$i]['day'] = $this->count_days($list_personal_leave[$i]['start_date'], $list_personal_leave[$i]['end_date'], $weekendtype);
//
//				$i++;
//			}
//
//			$j=0;
//			foreach ($list_personal_leave as $leave){
//				if($list_personal_leave[$j]['status_approve'] == TRUE){
//					$list_jum_personal_leave[] = $this->count_days($list_personal_leave[$j]['start_date'], $list_personal_leave[$j]['end_date'], $weekendtype);
//					$list_jum_personal_dispensation[] = $list_personal_leave[$j]['dispensation_quota'];
//				}
//				$j++;
//			}
//			var_dump($list_personal_leave);
//			$final_jum_personal_leave = array_sum($list_jum_personal_leave);
//			$final_jum_personal_dispensation = array_sum($list_jum_personal_dispensation);
//
//			$jum_all_leave = $final_jum_personal_leave - $final_jum_personal_dispensation;
//		}
//
//		$result = $jum_all_leave;
//
//		return $result;
//
//	}


//	private function get_minus_quota_leave_last_year($id_current_user, $year_select, $user_city){
//		$first_employment = $this->employment_m->select_first_row_employment($id_current_user);
//		$debt_quota_last_year = 0;
//		if (!empty($first_employment)) {
//			$first_employment = json_decode(json_encode($first_employment), true); //konversi stdclass to array
//			$tgl_pertama = $first_employment['tgl_mulai'];
//			$get_year_date = date('Y', strtotime($tgl_pertama));
//			if ($get_year_date < $year_select){
//				$list_leave_debt = $this->leavedebt_m->select_leave_debt_personal_year($id_current_user, $get_year_date);
//				if(!empty($list_leave_debt)){
//					foreach($list_leave_debt as $debt){
//						$debt_quota_last_year = $debt['quota_debt'];
//					}
//				}
//				else if(empty($list_leave_debt)){
//					//melakukan perhitungan tahun kemarin
//					//(employment+ adjustment) - leave request approved
//					$year_now = date('Y');
//					$year_select = $year_now - 1;
//					$minus_quota_last_year = $this->final_quota_minus_personal($id_current_user, $year_select, $user_city);
//					if($minus_quota_last_year >= 3) $debt_quota_last_year = 3;
//					else $debt_quota_last_year = $minus_quota_last_year;
//
//					$data_formdbt['id_employee'] = $id_current_user;
//					$data_formdbt['quota_debt'] = $minus_quota_last_year;
//					$data_formdbt['year'] =  $year_select;
//					$this->leavedebt_m->insert_leave_debt($data_formdbt);
//				}
//			}
//		}
//		return $debt_quota_last_year;
//	}
}
