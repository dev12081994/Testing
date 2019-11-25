<?php

class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');		
		$this->load->helper(array('url','language'));		
		$this->load->library(array('ion_auth', 'form_validation','pagination'));	
		$this->lang->load('auth');	
		$this->load->helper('file');
	}

	public function index()
	{
		
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			if(isset($_POST['srch_fdate']) && isset($_POST['srch_tdate']) && $_POST['srch_fdate']!="" && $_POST['srch_tdate']!="")
			{
				$fdate=date('Y-m-d',strtotime($_POST['srch_fdate']));
				$tdate=date('Y-m-d',strtotime($_POST['srch_tdate']));
			}
			else
			{
				$fdate=date('Y-m-d');
				$tdate=date('Y-m-d');
			}
			
			$this->data['fdate']=date('d-m-Y',strtotime($fdate));
			$this->data['tdate']=date('d-m-Y',strtotime($tdate));
			$this->data['today_pur']=$this->CrudModel->select_data("select count(*) as today_inv,sum(inv_gross-inv_disc+inv_gstamt+inv_roundoff) as amount from s_invoice where inv_for=1 and inv_status=1 and (str_to_date(inv_date,'%Y-%m-%d') BETWEEN ? and ?)",array($fdate,$tdate));

			$this->data['today_sales']=$this->CrudModel->select_data("select count(*) as today_inv,sum(inv_gross-inv_disc+inv_gstamt+inv_roundoff) as amount from s_invoice where inv_for=2 and inv_status=1 and (str_to_date(inv_date,'%Y-%m-%d') BETWEEN ? and ?)",array($fdate,$tdate));

			$this->data['today_pay']=$this->CrudModel->select_data("select sum(acc_amt) as amount from account where acc_voucher=3 and acc_status=1 and (str_to_date(acc_trandt,'%Y-%m-%d') BETWEEN ? and ?)",array($fdate,$tdate));

			$this->data['today_receive']=$this->CrudModel->select_data("select sum(acc_amt) as amount from account where acc_voucher=4 and acc_status=1 and (str_to_date(acc_trandt,'%Y-%m-%d') BETWEEN ? and ?)",array($fdate,$tdate));

			$this->data['tot_proj']=$this->CrudModel->select_data("select count(*) as project,prod_isdone from project where proj_status=? and (str_to_date(proj_startdt,'%Y-%m-%d') BETWEEN ? and ?) group by prod_isdone",array(1,$fdate,$tdate));

			$this->load->view('index',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}
	}

	//------------- End Of Class ------------------
}

?>
