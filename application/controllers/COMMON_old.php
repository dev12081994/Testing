<?php

class Common extends CI_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');		
		$this->load->model('CommonModel');
		$this->load->helper(array('url','language'));		
		$this->load->library(array('ion_auth', 'form_validation','pagination'));	
		$this->lang->load('auth');	
		$this->load->helper('file');
	}

	public function fetchVehicleProject()
	{
		if($this->ion_auth->logged_in())
		{
			$data_type=$_POST['data_type'];
			if ($data_type==4)
			{
	 			$member=$this->CrudModel->select_data("select proj_id as id,proj_name as name from project where proj_status = ?  order by proj_name",array(1));	
	 			$title='Select Project';
			}
			else if ($data_type==3)
			{
	 			$member=$this->CrudModel->select_data("select v_id as id,v_num as name from mas_vehicle where v_status = ?  order by v_num",array(1));	
	 			$title='Select Vehicle';
			}
			else
			{
				$title='Select Option';
				$member=array();
			}

			$mem_opts='<option value="">'.$title.'</option>';
			foreach ($member as $value) 
			{
				$mem_opts.='<option value="'.$value['id'].'">'.strtoupper($value['name']).'</option>';	
			}

			echo $mem_opts;
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function fetchMembers()
	{
		if($this->ion_auth->logged_in())
		{
			$mem_type=$_POST['mem_type'];
			$member=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as name from mas_members where c_type = ? and c_status = ? order by name",array($mem_type,1));

			if($mem_type==1)
				$title='Select Customer';
			elseif($mem_type==2)
				$title='Select Vendor';
			elseif($mem_type==3)
				$title='Select Employee';
			else
				$title='Select Option';
   

			$mem_opts='<option value="">'.$title.'</option>';
			foreach ($member as $value) 
			{
				$mem_opts.='<option value="'.$value['c_id'].'">'.strtoupper($value['name']).'</option>';	
			}

			echo $mem_opts;
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function fetchGstType()
	{
		if($this->ion_auth->logged_in())
		{
			$grt_type=$this->CrudModel->select_data("select grt_id,grt_type from mas_gstregtype where grt_status = ? order by grt_type",array(1));
			$grt_opts='<option value="">Select Type</option>';
			foreach ($grt_type as $value) 
			{
				$grt_opts.='<option value="'.$value['grt_id'].'">'.ucwords(strtolower($value['grt_type'])).'</option>';	
			}

			echo $grt_opts;
		}
		else
		{
			redirect("auth/login");
		}
	}
	public function fetchGstList()
	{
		if($this->ion_auth->logged_in())
		{
			$gst_list=$this->CrudModel->select_data("select c_taxperc,c_name from mas_category where c_for=? and c_status = ? order by c_taxperc",array(3,1));
			$gst_opts='<option value="" selected>GST</option>';
			foreach ($gst_list as $value) 
			{
				$gst_opts.='<option value="'.$value['c_taxperc'].'">'.round($value['c_taxperc'],2).'%</option>';	
			}

			echo $gst_opts;
		}
		else
		{
			redirect("auth/login");
		}
	}
	public function fetchMemberDetails()
	{
		if($this->ion_auth->logged_in())
		{
			$c_id=$_POST['c_id'];
			$c_data=$this->CrudModel->select_data("select * from mas_members where c_id=?",array($c_id));
			echo json_encode($c_data);
		}
		else
		{
			redirect("auth/login");
		}
	}

	public function addNewMember()
	{		
		if($this->ion_auth->logged_in())
		{				
			$data['c_mob1']=str_replace(['(',')'],['',''],$this->input->post('c_mob1'));
			$data['c_salutation']=$this->input->post('c_salutation');
			$data['c_firstname']=trim($this->input->post('c_firstname'));
			$data['c_middlename']=trim($this->input->post('c_middlename'));
			$data['c_lastname']=trim($this->input->post('c_lastname'));
			$data['c_entrydt']=date('Y-m-d H:i:s');
			$c_id=$this->input->post('c_id');
			$data['c_type']=$this->input->post('c_type'); 
			$name=str_replace(' ','',$data['c_firstname'].$data['c_middlename'].$data['c_lastname']);            
            
            if($c_id=='')
            {
				$check_exist=$this->CrudModel->select_data("select c_id from mas_members where REPLACE(concat_ws(' ',c_firstname,c_middlename,c_lastname),' ','') LIKE ? and c_mob1 = ?",array($name,$data['c_mob1']));
				if(count($check_exist)>0 && $check_exist[0]['c_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_members',$data);
			}
			else
			{
				$check_exist=$this->CrudModel->select_data("select c_id from mas_members where REPLACE(concat_ws(' ',c_firstname,c_middlename,c_lastname),' ','') LIKE ? and c_mob1 = ? and c_id <> ?",array($name,$data['c_mob1'],$c_id));

				if(count($check_exist)>0 && $check_exist[0]['c_id']!=null)
            		$status=0;
            	else
            	{
            		$edit_con['c_id']=$c_id;
                	$status=$this->CrudModel->edit_record('mas_members',$data,$edit_con);
                }
			}	

			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'This Member Already Exist','type'=>'error','process'=>'3'));	
			elseif($status==true && $c_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $c_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));		
		}
		else
		{
			redirect('auth/login');
		} 
	}
//----------------END OF CLASS-------------------------
}
?>