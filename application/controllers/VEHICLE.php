	<?php

class Vehicle extends CI_Controller
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
		$this->load->library('parser');
	}

	function exportFile($file_name)
	{  
        $myFile = "uploads/".$file_name.".xls";  
        header("Content-Length: " . filesize($myFile));  
        header('Content-Type: application/vnd.ms-excel');  
        header('Content-Disposition: attachment; filename='.$file_name.'.xls');  
        readfile($myFile);  
    }

	function dailyEntry()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			
			$this->data['vehi_qtytype']=$this->CrudModel->select_data("select vqt_id,vqt_name from mas_vehicle_qtytype where vqt_status = ? order by vqt_name",array(1));
			$this->data['vehi_type']=$this->CrudModel->select_data("select vt_id,vt_name from mas_vehicletype where vt_status = ? order by vt_name",array(1));
			$this->data['driver_list']=$this->CrudModel->select_data("select c_id,replace(concat_ws(' ',c_firstname,c_middlename,c_lastname),'  ',' ') as c_name from mas_members where c_status = ? and c_type = ? order by c_name",array(1,3));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			
			$this->load->view('vehicle/daily_entry',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	function srchVrunDetails()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array();
			
			if(isset($_POST['srch_vtype']) && $_POST['srch_vtype']!='')
			{
				$qry_con .=' and mv.typeid=?';
				array_push($qry_param,$_POST['srch_vtype']);
			}
			if(isset($_POST['srch_vnum']) && $_POST['srch_vnum']!='')
			{
				$qry_con .=' and mv.v_num = ?';
				array_push($qry_param,$_POST['srch_vnum']);
			}
			if(isset($_POST['srch_runstatus']) && $_POST['srch_runstatus']!='')
			{
				$qry_con .=' and vrun_runstatus = ?';
				array_push($qry_param,$_POST['srch_runstatus']);
			}
			if(isset($_POST['srch_from']) && $_POST['srch_from']!='')
			{
				$qry_con .=' and vrun_from LIKE ?';
				array_push($qry_param,'%'.$_POST['srch_from'].'%');
			}
			if(isset($_POST['srch_to']) && $_POST['srch_to']!='')
			{
				$qry_con .=' and vrun_to LIKE ?';
				array_push($qry_param,'%'.$_POST['srch_to'].'%');
			}
			if(isset($_POST['srch_fdate']) && $_POST['srch_fdate']!='')
			{
				$fdate=date('Y-m-d',strtotime($_POST['srch_fdate']));
				$tdate=date('Y-m-d',strtotime($_POST['srch_tdate']));
				$qry_con .=' and (str_to_date(vrun_date,"%Y-%m-%d") BETWEEN ? and ? )';
				array_push($qry_param,$fdate,$tdate);
			}
			if(isset($_POST['srch_cus']) && $_POST['srch_cus']!='')
			{
				$qry_con .=" and replace(concat(cmm.c_firstname,cmm.c_middlename,cmm.c_lastname),' ','') LIKE ?";
				array_push($qry_param,$_POST['srch_cus']);
			}
			if(isset($_POST['srch_driver']) && $_POST['srch_driver']!='')
			{
				$qry_con .=" and replace(concat(dmm.c_firstname,dmm.c_middlename,dmm.c_lastname),' ','') LIKE ?";
				array_push($qry_param,$_POST['srch_driver']);
			}

			$this->data['vrun_data']=$this->CrudModel->select_data("select v.vrun_date,mvt.vt_name,mv.v_num,concat_ws(' ',dmm.c_firstname,dmm.c_middlename,dmm.c_lastname) as driver,v.vrun_runstatus,v.vrun_meterstart,v.vrun_meterstop,concat_ws(' ',cmm.c_firstname,cmm.c_middlename,cmm.c_lastname) as customer,v.vrun_from,v.vrun_to,v.vrun_work,v.vrun_fareamt,vqt_name,vrun_rate,vrun_qty,v.vrun_remark,v.vrun_docs,v.vrun_id from vehicle v 
				left join mas_vehicle mv on mv.v_id=v.vrun_vid
				left join mas_vehicletype mvt on mvt.vt_id=mv.v_typeid
				left join mas_members cmm on cmm.c_id=v.vrun_memid
				left join mas_members dmm on dmm.c_id=v.vrun_drivid
				left join mas_vehicle_qtytype mvqt on mvqt.vqt_id=v.vrun_qtytype
				where vrun_status=1 $qry_con",$qry_param);

			
			$this->load->view('vehicle/daily_entry_srch',$this->data);
		}
		else
		{
			redirect('auth/login');
		}	
	}


	public function saveVehicles()
	{		
		if($this->ion_auth->logged_in())
		{	
			$vrun_id=$this->input->post('vrun_id');
			$data['vrun_user']=$this->session->userdata['user_id'];
			$data['vrun_entrydt']=date('Y-m-d H:i:s');
			$data['vrun_vid']=$this->input->post('vrun_vid');
			$data['vrun_runstatus']=$this->input->post('vrun_runstatus');
			$data['vrun_meterstart']=$this->input->post('vrun_meterstart');
			$data['vrun_meterstop']=$this->input->post('vrun_meterstop');
			$data['vrun_memid']=$this->input->post('vrun_memid');
			$data['vrun_drivid']=$this->input->post('vrun_drivid');
			$data['vrun_work']=trim($this->input->post('vrun_work'));
			$data['vrun_from']=trim($this->input->post('vrun_from'));
			$data['vrun_to']=trim($this->input->post('vrun_to'));
			$data['vrun_fareamt']=(int)($this->input->post('vrun_fareamt'));
			$data['vrun_remark']=trim($this->input->post('vrun_remark'));
			$data['vrun_qtytype']=trim($this->input->post('vrun_qtytype'));
			$data['vrun_qty']=trim($this->input->post('vrun_qty'));
			$data['vrun_rate']=trim($this->input->post('vrun_rate'));

			if($this->input->post('vrun_date')=='')
				$data['vrun_date']='0000-00-00';
			else
				$data['vrun_date']=date('Y-m-d',strtotime($this->input->post('vrun_date')));

			if(!empty($_FILES['vrun_docs']['name']))
			{				
				$path = './uploads/VehicleDocs';
				if(!is_dir($path))
				    mkdir($path);

				$path=$path.'Member_'.$this->input->post('vrun_memid');
				if(!is_dir($path))
				    mkdir($path);

				if(!$path)
				return;
					
				$config = array('upload_path'=>"./".$path."/",
				'allowed_types'=>"jpeg|jpg|png|gif|bmp|pdf");
				$this->load->library('upload',$config);
				$this->upload->do_upload('vrun_docs');
				$file_data = $this->upload->data();
				$data['vrun_docs'] =$path."/".$file_data['file_name'];
				$acc_data['acc_docs']=$path."/".$file_data['file_name'];
			}         
            
			if((int)($this->input->post('vrun_fareamt'))>0)
        	{     
        		$acc_drdata['acc_user']=$this->session->userdata['user_id'];
        		$acc_drdata['acc_vochfor']=3;           		
        		$acc_drdata['acc_cid']=$this->input->post('vrun_memid');
        		$acc_drdata['acc_voucher']=2;
        		$acc_drdata['acc_trantype']=1;
        		$acc_drdata['acc_ldgid']=3;
        		$acc_drdata['acc_amt']=(int)($this->input->post('vrun_fareamt'));
        		$acc_drdata['acc_remark']=trim($this->input->post('vrun_remark'));        		      		
        		$acc_drdata['acc_trandt']=date('Y-m-d',strtotime($this->input->post('vrun_date')));  
        	}

            $status=false;
            if($vrun_id=='')
            {
            	$status=$this->CrudModel->insert_data('vehicle',$data);
            	$lastid=$this->CrudModel->select_data("select max(vrun_id) as lastid from vehicle where 1=1",array());    
        		if(count($lastid) > 0 && $lastid[0]['lastid']!=null)
        			$lastid=$lastid[0]['lastid']; 
        		if($status===true && (int)($this->input->post('vrun_fareamt'))>0)
        		{
        			$acc_drdata['acc_sourceid']=$lastid;
        			$acc_drdata['acc_entrydt']=date('Y-m-d H:i:s');  
        			$this->CrudModel->insert_data('account',$acc_drdata);        			 
        		}
			}
			else
			{
        		$condition['vrun_id']=$vrun_id;
            	$status=$this->CrudModel->edit_record('vehicle',$data,$condition);
        		
                if($status===true && (int)($this->input->post('vrun_fareamt'))>0)
        		{ 
        			$acc_drdata['acc_sourceid']=$vrun_id;
        			$acc_editcon['acc_sourceid']=$vrun_id;
        			$acc_editcon['acc_vochfor']=3;
        			$acc_editcon['acc_trantype']=1;
        			$this->CrudModel->edit_record('account',$acc_drdata,$acc_editcon);
        		}
			}
			
			if($status==true && $vrun_id=='')
			{
				$this->session->set_flashdata('message','Vehicle Details Saved Successfully');
				$this->session->set_flashdata('alert_type','alert-success');
			}		
            elseif($status==true && $vrun_id!='')
            {
            	$this->session->set_flashdata('message','Vehicle Details Updated Successfully');
				$this->session->set_flashdata('alert_type','alert-success');
            }
			else
			{	
				$this->session->set_flashdata('message','Proccess Failed');
				$this->session->set_flashdata('alert_type','alert-danger');		
			}
			redirect('VEHICLE/dailyEntry');
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function fetchVrunData()
	{	
		if($this->ion_auth->logged_in())
		{
			$vrun_id=$_REQUEST['vrun_id'];
			$vrun_data=$this->CrudModel->select_data("select date_format(v.vrun_date,'%d-%m-%Y') as vrun_date,mv.v_typeid,mv.v_id,vrun_memid,vrun_drivid,v.vrun_runstatus,v.vrun_meterstart,v.vrun_meterstop,mm.c_type,v.vrun_from,v.vrun_to,v.vrun_work,vrun_qtytype,vrun_qty,vrun_rate,v.vrun_fareamt,v.vrun_remark,v.vrun_id from vehicle v 
				left join mas_vehicle mv on mv.v_id=v.vrun_vid 
				left join mas_members mm on mm.c_id=v.vrun_memid 
				where vrun_id=?",array($vrun_id));		
			echo json_encode($vrun_data);
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function deleteVrunDetails()
	{	
		if($this->ion_auth->logged_in())
		{
			$vrun_id=$_POST['vrun_id'];
			$fetchdata=array();
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Project Deletion Failed','type'=>'error'));
			}
			else
			{
				$acc_con['acc_sourceid']=$vrun_id;
				$acc_con['acc_vochfor']=3;
				$status1=$this->CrudModel->delete_record('account',$acc_con);
				if($status1 == true)
				{
					$vrun_con['vrun_id']=$vrun_id;
					if($this->CrudModel->delete_record('vehicle',$vrun_con)==true)
						echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));
					else
						echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
				}
				else
				{
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
				}
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function fetchVehicle()
	{
		if($this->ion_auth->logged_in())
		{
			$v_data=$this->CrudModel->select_data("select v_id,v_num from mas_vehicle where v_status = ? and v_typeid=?",array(1,$_POST['vtype']));
			echo "<option value=''>Select Vehicle</option>";
			foreach ($v_data as $key => $value) {
				echo "<option value='".$value['v_id']."'>".strtoupper($value['v_num'])."</option>";
			}

		}
		else
		{
			redirect("auth/login");
		}
	}

	public function vehicleExpense()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
			$this->load->view('vehicle/vehicle_expense',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchVehicleExpense()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array();
			if(isset($_POST['srch_sourceid']) && $_POST['srch_sourceid']!='')
			{
				$qry_con .=' and acc_sourceid=?';
				array_push($qry_param,$_POST['srch_sourceid']);
			}
			if(isset($_POST['srch_ctype']) && $_POST['srch_ctype']!='')
			{
				$qry_con .=' and mm.c_type=?';
				array_push($qry_param,$_POST['srch_ctype']);
			}
			if(isset($_POST['srch_cid']) && $_POST['srch_cid']!='')
			{
				$qry_con .=' and acc_cid=?';
				array_push($qry_param,$_POST['srch_cid']);
			}
			if(isset($_POST['srch_ldgid']) && $_POST['srch_ldgid']!='')
			{
				$qry_con .=' and acc_ldgid=?';
				array_push($qry_param,$_POST['srch_ldgid']);
			}
			if(isset($_POST['srch_amt']) && $_POST['srch_amt']!='')
			{
				$qry_con .=' and acc_amt=?';
				array_push($qry_param,$_POST['srch_amt']);
			}
			if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!='')
			{
				$ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
				$ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
				$qry_con .=' and str_to_date(acc_trandt,"%Y-%m-%d") BETWEEN ? and ?';
				array_push($qry_param,$ftdate,$ttdate);
			}
			if(isset($_POST['srch_fedate']) && $_POST['srch_fedate']!='' && isset($_POST['srch_tedate']) && $_POST['srch_tedate']!='')
			{
				$fedate=date('Y-m-d',strtotime($_POST['srch_fedate']));
				$tedate=date('Y-m-d',strtotime($_POST['srch_tedate']));
				$qry_con .=' and str_to_date(acc_entrydt,"%Y-%m-%d") BETWEEN ? and ?';
				array_push($qry_param,$fedate,$tedate);
			}

			$payment=$this->CrudModel->select_data("select a.acc_id,acc_cid,a.acc_vochfor,a.acc_sourceid,acc_trandt,a.acc_amt,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
				left join mas_category mc on mc.c_id = a.acc_ldgid 
				left join mas_members mm on mm.c_id=a.acc_cid 
				where acc_status =1 and acc_vochfor=3 and acc_voucher=3 $qry_con order by acc_entrydt desc",$qry_param);

			$data['payment']=$payment;

			//print_r($_POST);die;

			if(isset($_POST['exportbtn']))
			{
				$data['is_export']=1;
				//place where the excel file is created  
	            $file_name = "VehicleExpenses_csv";         
	            $myFile='uploads/'.$file_name.'.xls';
	            //pass retrieved data into template and return as a string  

	            $stringData = $this->parser->parse('vehicle/vehicle_expense_srch',$data, true);
	            
	            
	            //open excel and write string into excel  
	            $fh = fopen($myFile, 'w') or die("can't open file");  
	            fwrite($fh, $stringData);  
	            fclose($fh);  
	            $this->exportFile($file_name); 
			}
			else
			{
				$data['is_export']=0;
				
				$this->load->view('vehicle/vehicle_expense_srch',$data);
			}
			
   		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function addVehicleExpense()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$accid=(int)($this->uri->segment(3));
			$this->data['voucher']=$this->CrudModel->select_data("select a.*,mm.c_type from account a 				
				left join mas_members mm on mm.c_id=a.acc_cid where a.acc_id = ? and acc_vochfor=3",array($accid));
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
			
			$this->load->view('vehicle/add_vehicle_expense',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');	
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function saveVehicleExpense()
	{		
		if($this->ion_auth->logged_in())
		{				
			$acc_id=$this->input->post('acc_id');
			$data['acc_user']=$this->session->userdata['user_id'];
			$data['acc_vochfor']=3;
			$data['acc_trantype']=1;
			$data['acc_voucher']=3;
			$data['acc_entrydt']=date('Y-m-d H:i:s');
			$data['acc_amt']=$this->input->post('acc_amt');
			$data['acc_cid']=$this->input->post('acc_cid');
			$data['acc_ldgid']=$this->input->post('acc_ldgid');
			$data['acc_vochfor']=$this->input->post('acc_vochfor');			
			$data['acc_sourceid']=$this->input->post('acc_sourceid');		
			$data['acc_remark']=trim($this->input->post('acc_remark'));

			if($this->input->post('acc_trandt')=='')
				$data['acc_trandt']='0000-00-00';
			else
				$data['acc_trandt']=date('Y-m-d',strtotime($this->input->post('acc_trandt')));		   

			if(!empty($_FILES['accx_docs']['name']))
			{				
				$path = './uploads/PaymentDocs';
				if(!is_dir($path))
				    mkdir($path);

				$path = $path.'/Member_'.$this->input->post('acc_cid');	
				
				if(!is_dir($path))
				    mkdir($path);

				if(!$path)
				return;
					
				$config = array('upload_path'=>"./".$path."/",
				'allowed_types'=>"jpeg|jpg|png|gif|bmp|pdf");
				$this->load->library('upload',$config);
				$this->upload->do_upload('acc_docs');
				$file_data = $this->upload->data();
				$data['acc_docs'] =$path."/".$file_data['file_name'];
			}         
            
            if($acc_id=='')
            {
            	if($this->CrudModel->insert_data('account',$data)==true)
				{            
					$this->session->set_flashdata('message','Expense Details Saved Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Process Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('VEHICLE/vehicleExpense');
			}
			else
			{
				$edit_con['acc_id']=$acc_id;
				if($this->CrudModel->edit_record('account',$data,$edit_con)==true)
				{
	                $opdid=$this->db->insert_id();             
					$this->session->set_flashdata('message','Expense Details Updated Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Expense Details Updation Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('VEHICLE/vehicleExpense');
			}			
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function fetchBillData()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$vrun_id=(int)($this->input->post('vrun_id'));

    		$balamt_data=$this->CrudModel->select_data("select acc_sourceid,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor =3 group by acc_sourceid",array($vrun_id));

    		$balamt=0;
    		if(count($balamt_data)>0 && $balamt_data[0]['acc_sourceid']!=null)
    		{
				$balamt = abs($balamt_data[0]['cr_amt']- $balamt_data[0]['dr_amt']);
    		}
    		echo json_encode(array('bal_amt'=>$balamt));
    	}
    }

    public function receiveVehicleAmt()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$inv_id=(int)($this->input->post('inv_id'));
    		$inv_data=$this->CrudModel->select_data("select * from vehicle where vrun_id = ? ",array($inv_id));
    		$bill_amt=$inv_data[0]['vrun_fareamt'];

    		$balamt_data=$this->CrudModel->select_data("select acc_sourceid,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor =3 group by acc_sourceid",array($inv_id));
    		
    		$balamt=0;
    		$cr_amt=0;

    		if(count($balamt_data)>0 && $balamt_data[0]['acc_sourceid']!=null)
    		{
				$cr_amt = $balamt_data[0]['cr_amt'];
				$dr_amt=$balamt_data[0]['dr_amt'];
    		}
    			
			$sattled_amt=$cr_amt;    			

    		$acc_dr['acc_user']=$this->session->userdata['user_id'];
			$acc_dr['acc_cid']=$inv_data[0]['vrun_memid'];
			$acc_dr['acc_voucher']=4; // 4 for receive
			$acc_dr['acc_vochfor']=3;
			$acc_dr['acc_sourceid']=$inv_id;
			$acc_dr['acc_trantype']=2;// for 1 debit/ 2 Credit	
			$acc_dr['acc_trandt']=date('Y-m-d',strtotime($this->input->post('sattle_data')));			
			$acc_dr['acc_ldgid']=3;
			$acc_dr['acc_amt']=$this->input->post('settle_amt');
			$acc_dr['acc_entrydt']=date('Y-m-d H:i:s');
			$acc_dr['acc_mode']=$this->input->post('acc_trantype');					

			if($acc_dr['acc_mode']==='3')
			{
				$acc_dr['acc_bankacc_id']=$this->input->post('acc_bankaccid');
				$acc_dr['acc_onlineid']=$this->input->post('acc_onlineid');
			}
			if($acc_dr['acc_mode']==='2')
			{
				$chqdt=$this->input->post('acc_chqdt');
				if($chqdt !=='')
				{
					$chqdt=date('Y-m-d',strtotime($chqdt));
				}
				$acc_dr['acc_cusbank']=$this->input->post('acc_bankaccid');
				$acc_dr['acc_chqno']=$this->input->post('acc_chqno');
				$acc_dr['acc_chqdt']=$chqdt;
			}
			
			$this->CrudModel->insert_data('account',$acc_dr);

			if($sattled_amt == $this->input->post('settle_amt'))
				$data_inv['vrun_paidstatus']=2;
			else
				$data_inv['vrun_paidstatus']=1;

			$inv_con['vrun_id']=$inv_id;
			$this->CrudModel->edit_record('vehicle',$data_inv,$inv_con);
 			
			redirect("VEHICLE/dailyEntry");
    	}
    	else
    	{
    		redirect("auth/login");
    	}
    }

//---------------------- End Of Class ------------------	
}