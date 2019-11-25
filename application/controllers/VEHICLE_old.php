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
        		$acc_drdata['acc_sourceid']=$this->input->post('vrun_vid');        		
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
        			$acc_editcon['acc_sourceid']=$vrun_id;
        			$acc_editcon['acc_vochfor']=3;
        			$acc_editcon['acc_trantype']=1;
        			$this->CrudModel->edit_record('account',$acc_drdata,$acc_editcon);        			 
        		}
			}
			
			if($status==true && $vrun_id=='')
			{
				$this->session->set_flashdata('message','Project Details Saved Successfully');
				$this->session->set_flashdata('alert_type','alert-success');
			}		
            elseif($status==true && $vrun_id!='')
            {
            	$this->session->set_flashdata('message','Project Details Updated Successfully');
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
				where acc_status =1 and acc_vochfor=3 and acc_mode=0 $qry_con order by acc_entrydt desc",$qry_param);
			

			$sl=1;$tot_amt=0;
			
			if(count($payment)===0 || $payment[0]['acc_id']===null)
			{
				echo '<tr><td colspan="12" class="no-data-found">Record Not Found</td></tr>';
				die();
			}
			foreach ($payment as $key => $value) 
			{
				$amt=$value['acc_amt'];
				$tot_amt += $amt;
				$payfor=$this->CommonModel->vehicleProject($value['acc_sourceid'],$value['acc_vochfor']);

				if($payfor!='')
					$payfor=$payfor;
				echo "<tr id='row_".$value['acc_id']."'>";			
				echo "<td>". $sl++ ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
				echo "<td>". $payfor ."</td>";
				echo "<td>". $this->config->item($value['c_type'],'member_type') ."</td>";
				echo "<td>". $value['paidto'] ."</td>";
				echo "<td>". ucwords(strtolower($value['ledger']))."</td>";				
				echo "<td>". $amt ."</td>";
				echo "<td>". ucwords(strtolower($value['acc_remark'])) ."</td>";
				if($value['acc_docs']!='')
					echo "<td class='h'><center><a class='btn btn-success btn-xs' target='_blank' href='".base_url().$value['acc_docs']."'><i class='fa fa-eye'></i></a></center></td>";
				else
					echo "<td></td>";
				
				echo "<td class='h'><center><a class='btn btn-info btn-xs' href='".base_url().'vehicle/addVehicleExpense/'.$value['acc_id']."'><span class='fa fa-pencil'></span></a></center></td>";
				echo "<td class='h'><center><a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Delete'  onclick='$(".'"#del_id"'.").val(".$value['acc_id'].");'  href='#delConfirm'>
                        <span class='fa fa-trash-o'></span></a></center></td>";
				echo "</tr>";		
			}
			echo "<tr>";
			echo "<td colspan='7'><strong>Total Amount : </strong></td>";
			echo "<td colspan='5'><strong>". $tot_amt ."</strong></td>";
			echo "</tr>";
			 ?>
            <script type="text/javascript">
				$(function(){
					$('.modal-basic').magnificPopup({
						type: 'inline',
						preloader: false,
						modal: true
					});
				});
			</script>
            <?php
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

			if(!empty($_FILES['acc_docs']['name']))
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

//---------------------- End Of Class ------------------	
}