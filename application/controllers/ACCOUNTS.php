<?php

class Accounts extends CI_Controller
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

	public function payment()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
			$this->load->view('accounts/payment',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}
	
	public function savePayment()
	{		
		if($this->ion_auth->logged_in())
		{				
			$acc_id=$this->input->post('acc_id');
			$data['acc_user']=$this->session->userdata['user_id'];
			$data['acc_voucher']=3;
			$data['acc_trantype']=1;
			$data['acc_entrydt']=date('Y-m-d H:i:s');
			$data['acc_amt']=$this->input->post('acc_amt');			
			$data['acc_ldgid']=$this->input->post('acc_ldgid');
			$data['acc_mode']=$this->input->post('acc_mode');
			$data['acc_vochfor']=$this->input->post('acc_vochfor');

			if($data['acc_vochfor'] === '3' || $data['acc_vochfor']==='4')
				$data['acc_cid']=$this->input->post('acc_sourceid');
			else
				$data['acc_cid']=$this->input->post('acc_cid');

			$data['acc_remark']=trim($this->input->post('acc_remark'));

			if($this->input->post('acc_trandt')=='')
				$data['acc_trandt']='0000-00-00';
			else
				$data['acc_trandt']=date('Y-m-d',strtotime($this->input->post('acc_trandt')));

			
			if($data['acc_mode']!=='1')
			{
				$data['acc_bankacc_id']=$this->input->post('acc_bankaccid');            
				
				if($data['acc_mode']==='2')
				{
					$data['acc_chqno']=trim($this->input->post('acc_chqno'));
					if($this->input->post('acc_chqdt')=='')
						$data['acc_chqdt']='0000-00-00';
					else
						$data['acc_chqdt']=date('Y-m-d',strtotime($this->input->post('acc_chqdt')));
				}
				else if($data['acc_mode']==='3')
				{
					$data['acc_onlineid']=trim($this->input->post('acc_onlineid'));
				}
			}    			   

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
					$this->session->set_flashdata('message','Payment Details Saved Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Process Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('ACCOUNTS/addPayment');
			}
			else
			{
				$edit_con['acc_id']=$acc_id;
				if($this->CrudModel->edit_record('account',$data,$edit_con)==true)
				{
	                $opdid=$this->db->insert_id();             
					$this->session->set_flashdata('message','Payment Details Updated Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Payment Details Updation Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('ACCOUNTS/payment');
			}			
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function srchPayment()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array(1,3);
			if(isset($_POST['srch_vochfor']) && $_POST['srch_vochfor']!='')
			{
				$qry_con .=' and acc_vochfor=?';
				array_push($qry_param,$_POST['srch_vochfor']);
			}
			if(isset($_POST['srch_sourceid']) && $_POST['srch_sourceid']!='')
			{
				$qry_con .=' and acc_cid=?';
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
			if(isset($_POST['srch_mode']) && $_POST['srch_mode']!='')
			{
				$qry_con .=' and acc_mode=?';
				array_push($qry_param,$_POST['srch_mode']);
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
			if(isset($_POST['srch_chqno']) && $_POST['srch_chqno']!='')
			{
				$qry_con .=' and acc_chqno=?';
				array_push($qry_param,$_POST['srch_chqno']);
			}
			if(isset($_POST['srch_onlineid']) && $_POST['srch_onlineid']!='')
			{
				$qry_con .=' and acc_onlineid=?';
				array_push($qry_param,$_POST['srch_onlineid']);
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

			$payment=$this->CrudModel->select_data("select a.acc_id,acc_cid,a.acc_vochfor,a.acc_sourceid,acc_trandt,a.acc_amt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_onlineid,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
				left join mas_category mc on mc.c_id = a.acc_ldgid 
				left join mas_members mm on mm.c_id=a.acc_cid where acc_status = ? and acc_voucher=? $qry_con order by acc_entrydt desc",$qry_param);
			//echo $this->db->last_query();die;

			$sl=1;$tot_amt=0;
			
			if(count($payment)===0 || $payment[0]['acc_id']===null)
			{
				echo '<tr><td colspan="14" class="no-data-found">Record Not Found</td></tr>';
				die();
			}
			foreach ($payment as $key => $value) 
			{
				if($value['acc_mode']==='1')
				{
					$mode='Cash';
					$tranid='';
				}
				else if($value['acc_mode']==='2')
				{
					$mode='Cheque';
					$tranid=$value['acc_chqno'];
				}
				else if($value['acc_mode']==='3')
				{
					$mode='Online';
					$tranid=$value['acc_onlineid'];
				}
				else
				{
					$mode='';
					$tranid='';
				}
				
				$amt=0;
				$amt=$value['acc_amt'];
				$tot_amt += $amt;
				

				if($value['acc_vochfor']=='3' || $value['acc_vochfor']=='4')
				{
					$paidto=$this->CommonModel->vehicleProject($value['acc_cid'],$value['acc_vochfor']);
					$mem_type="";
				}
				else
				{
					$paidto=$value['paidto'];
					$mem_type=$this->config->item($value['c_type'],'member_type');
				}

				echo "<tr id='row_".$value['acc_id']."'>";			
				echo "<td>". $sl++ ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
				echo "<td>". $this->config->item($value['acc_vochfor'],'tran_for')."</td>";
				echo "<td>". $mem_type ."</td>";
				echo "<td>". $paidto ."</td>";
				echo "<td>". ucwords(strtolower($value['ledger']))."</td>";
				echo "<td>". $mode ."</td>";
				echo "<td>". $tranid ."</td>";
				echo "<td>". $amt ."</td>";
				echo "<td>". ucwords(strtolower($value['acc_remark'])) ."</td>";
				if($value['acc_docs']!='')
					echo "<td class='h'><center><a class='btn btn-success btn-xs' target='_blank' href='".base_url().$value['acc_docs']."'><i class='fa fa-eye'></i></a></center></td>";
				else
					echo "<td></td>";
				
				echo "<td class='h'><center><a class='btn btn-info btn-xs' href='".base_url().'ACCOUNTS/addPayment/'.$value['acc_id']."'><span class='fa fa-pencil'></span></a></center></td>";
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

	public function addPayment()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$accid=(int)($this->uri->segment(3));
			$this->data['voucher']=$this->CrudModel->select_data("select a.*,ba.acc_bankid as bank,mm.c_type from account a 
				left join mas_bankacc ba on ba.acc_id = a.acc_bankacc_id 
				left join mas_members mm on mm.c_id=a.acc_cid where a.acc_id = ? and acc_voucher=3",array($accid));

			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
			
			$this->load->view('accounts/add_payment',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');	
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function receive()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$this->data['']=array();
			$this->load->view('accounts/receive',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveReceive()
	{		
		if($this->ion_auth->logged_in())
		{				
			$acc_id=$this->input->post('acc_id');
			$data['acc_user']=$this->session->userdata['user_id'];
			$data['acc_voucher']=4;
			$data['acc_trantype']=2;
			$data['acc_entrydt']=date('Y-m-d H:i:s');
			$data['acc_amt']=$this->input->post('acc_amt');
			$data['acc_ldgid']=$this->input->post('acc_ldgid');
			$data['acc_mode']=$this->input->post('acc_mode');
			$data['acc_vochfor']=$this->input->post('acc_vochfor');
		
			if($data['acc_vochfor']==='4')
				$data['acc_cid']=$this->input->post('acc_sourceid');
			else
				$data['acc_cid']=$this->input->post('acc_cid');			

			$data['acc_remark']=trim($this->input->post('acc_remark'));

			if($this->input->post('acc_trandt')=='')
				$data['acc_trandt']='0000-00-00';
			else
				$data['acc_trandt']=date('Y-m-d',strtotime($this->input->post('acc_trandt')));
			
			if($data['acc_mode']!=='1')
			{
				$data['acc_bankacc_id']=$this->input->post('acc_bankaccid');            
				
				if($this->input->post('acc_mode')==='2')
				{
					$data['acc_chqno']=trim($this->input->post('acc_chqno'));
					if($this->input->post('acc_chqdt')==='')
						$data['acc_chqdt']='0000-00-00';
					else
						$data['acc_chqdt']=date('Y-m-d',strtotime($this->input->post('acc_chqdt')));
				}
				else if($this->input->post('acc_mode')==='3')
				{
					$data['acc_onlineid']=trim($this->input->post('acc_onlineid'));
				}
			}    			   

			if(!empty($_FILES['acc_docs']['name']))
			{				
				$path = './uploads/ReceiveDocs';
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
					$this->session->set_flashdata('message','Receive Details Saved Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Process Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('ACCOUNTS/addReceive');
			}
			else
			{
				$edit_con['acc_id']=$acc_id;
				if($this->CrudModel->edit_record('account',$data,$edit_con)==true)
				{
	                $opdid=$this->db->insert_id();             
					$this->session->set_flashdata('message','Receive Details Updated Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Payment Details Updation Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('ACCOUNTS/receive');
			}			
		}
		else
		{
			redirect('auth/login');
		} 
	}
	
	public function srchReceive()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array(1,4);
			if(isset($_POST['srch_vochfor']) && $_POST['srch_vochfor']!='')
			{
				$qry_con .=' and acc_vochfor=?';
				array_push($qry_param,$_POST['srch_vochfor']);
			}
			if(isset($_POST['srch_sourceid']) && $_POST['srch_sourceid']!='')
			{
				$qry_con .=' and acc_cid=?';
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
			if(isset($_POST['srch_mode']) && $_POST['srch_mode']!='')
			{
				$qry_con .=' and acc_mode=?';
				array_push($qry_param,$_POST['srch_mode']);
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
			if(isset($_POST['srch_chqno']) && $_POST['srch_chqno']!='')
			{
				$qry_con .=' and acc_chqno=?';
				array_push($qry_param,$_POST['srch_chqno']);
			}
			if(isset($_POST['srch_onlineid']) && $_POST['srch_onlineid']!='')
			{
				$qry_con .=' and acc_onlineid=?';
				array_push($qry_param,$_POST['srch_onlineid']);
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

			$payment=$this->CrudModel->select_data("select a.acc_id,a.acc_vochfor,a.acc_sourceid,a.acc_cid,acc_trandt,a.acc_amt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_onlineid,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
				left join mas_category mc on mc.c_id = a.acc_ldgid 
				left join mas_members mm on mm.c_id=a.acc_cid 
				where acc_status = ? and acc_voucher=? $qry_con order by acc_entrydt desc",$qry_param);
			//echo $this->db->last_query();die;

			$sl=1;$tot_amt=0;
			
			if(count($payment)===0 || $payment[0]['acc_id']===null)
			{
				echo '<tr><td colspan="14" class="no-data-found">Record Not Found</td></tr>';
				die();
			}
			foreach ($payment as $key => $value) 
			{
				if($value['acc_mode']==='1')
				{
					$mode='Cash';
					$tranid='';
				}
				else if($value['acc_mode']==='2')
				{
					$mode='Cheque';
					$tranid=$value['acc_chqno'];
				}
				else if($value['acc_mode']==='3')
				{
					$mode='Online';
					$tranid=$value['acc_onlineid'];
				}
				else
				{
					$mode='';
					$tranid='';
				}
				$amt=0;
				$amt=$value['acc_amt'];
				$tot_amt += $amt;
				$payfor=$this->CommonModel->vehicleProject($value['acc_cid'],$value['acc_vochfor']);

				if($payfor!='')
					$payfor='-'.$payfor;
				echo "<tr id='row_".$value['acc_id']."'>";			
				echo "<td>". $sl++ ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
				echo "<td>". $this->config->item($value['acc_vochfor'],'tran_for'). $payfor ."</td>";
				echo "<td>". $this->config->item($value['c_type'],'member_type') ."</td>";
				echo "<td>". $value['paidto'] ."</td>";
				echo "<td>". ucwords(strtolower($value['ledger']))."</td>";
				echo "<td>". $mode ."</td>";
				echo "<td>". $tranid ."</td>";
				echo "<td>". $amt ."</td>";
				echo "<td>". ucwords(strtolower($value['acc_remark'])) ."</td>";
				if($value['acc_docs']!='')
					echo "<td class='h'><center><a class='btn btn-success btn-xs' target='_blank' href='".base_url().$value['acc_docs']."'><i class='fa fa-eye'></i></a></center></td>";
				else
					echo "<td></td>";				
				echo "<td class='h'><center><a class='btn btn-info btn-xs' href='".base_url().'ACCOUNTS/addReceive/'.$value['acc_id']."'><span class='fa fa-pencil'></span></a></center></td>";
				echo "<td class='h'><center><a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Delete'  onclick='$(".'"#del_id"'.").val(".$value['acc_id'].");'  href='#delConfirm'>
                        <span class='fa fa-trash-o'></span></a></center></td>";
				echo "</tr>";		
			}
			echo "<tr>";
			echo "<td colspan='9'><strong>Total Amount : </strong></td>";
			echo "<td colspan='2'><strong>". $tot_amt ."</strong></td>";
			echo "<td colspan='3' class='h'>&nbsp;</td>";
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

	public function addReceive()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');		
			$accid=(int)($this->uri->segment(3));

			$this->data['voucher']=$this->CrudModel->select_data("select a.*,ba.acc_bankid as bank,mm.c_type from account a 
				left join mas_bankacc ba on ba.acc_id = a.acc_bankacc_id 
				left join mas_members mm on mm.c_id=a.acc_cid where a.acc_id = ? and acc_voucher=4",array($accid));

			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
			
			$this->load->view('accounts/add_receive',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');	
		}
		else
		{
			redirect('auth/login');
		}
	}


	public function deleteVoucher()
	{	
		if($this->ion_auth->logged_in())
		{
			$acc_id=(int)($_POST['acc_id']);

			if($acc_id>0)
			{	
				$account_con['acc_id']=$acc_id;
				if($this->CrudModel->delete_record('account',$account_con))				
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}	
			else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));		
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function memLedger()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status>? and c_for=?",array(0,2));
			$this->load->view('accounts/mem_ledger',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchLedger()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array(1);			

			if(isset($_POST['srch_for']) && $_POST['srch_for']!='')
			{
				if($_POST['srch_for']>4)
				{
					$qry_con .=' and acc_vochfor=?';
					array_push($qry_param,($_POST['srch_for']-2));
				}				
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
			if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!='')
			{
				$ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
				$ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
				$qry_con .=' and str_to_date(acc_trandt,"%Y-%m-%d") BETWEEN ? and ?';
				array_push($qry_param,$ftdate,$ttdate);
			}

			if($_POST['srch_for']=='5' || $_POST['srch_for']=='6')
               	$paidto=$this->CommonModel->vehicleProject($_POST['srch_cid'],$_POST['srch_for']-2);
           	else
           		$paidto='Member Name : '.$this->CommonModel->memberName($_POST['srch_cid']);

           	if($_POST['srch_for']=='5')
           		$paidto='Vehicle : '.strtoupper($paidto);
           	elseif($_POST['srch_for']=='6')
           		$paidto='Project : '.strtoupper($paidto);

			$ldg_data=$this->CrudModel->select_data("select a.acc_id,a.acc_cid,acc_vochfor,acc_trandt,a.acc_amt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_entrydt,a.acc_remark,a.acc_docs,a.acc_trantype,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto from account a 
				left join mas_category mc on mc.c_id = a.acc_ldgid 
				left join mas_members mm on mm.c_id=a.acc_cid where acc_status = ? $qry_con order by acc_entrydt desc",$qry_param);
			//echo $this->db->last_query();die;
			$sl=1;$tot_dr=0;$tot_cr=0;$bal=0;
			
			if(count($ldg_data)===0 || $ldg_data[0]['acc_id']===null)
			{
				echo '<tr><td colspan="7" class="no-data-found">Record Not Found</td></tr>';
				die();
			}
			foreach ($ldg_data as $key => $value) 
			{	
				$dr_amt='';$cr_amt='';
				if($value['acc_trantype']==='1')
					$tot_dr+=$dr_amt=$value['acc_amt'];
				else
					$tot_cr+=$cr_amt=$value['acc_amt'];			

				echo "<tr id='row_".$value['acc_id']."'>";			
				echo "<td>". $sl++ ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
				echo "<td>". ucwords(strtolower($value['ledger']))."</td>";
				echo "<td>". ucwords(strtolower($value['acc_remark'])) ."</td>";
				echo "<td class='align-right'>". $dr_amt ."</td>";
				echo "<td class='align-right'>". $cr_amt ."</td>";

				/*if($value['acc_docs']!='')
					echo "<td class='h'><center><a class='btn btn-success btn-xs' target='_blank' href='".base_url().$value['acc_docs']."'><i class='fa fa-eye'></i></a></center></td>";
				else
					echo "<td></td>";*/			
				
				echo "</tr>";		
			}
			echo "<tr >";
			echo "<td colspan='4'><strong>Total Amount : </strong></td>";
			echo "<td colspan='1' class='align-right'><strong>". $tot_dr ."</strong></td>";
			echo "<td colspan='1' class='align-right'><strong>". $tot_cr ."</strong></td>";
			echo "</tr>";

			if($tot_cr>$tot_dr)
				$bal=abs($tot_cr-$tot_dr) . ' (Cr.)';
			elseif($tot_cr<$tot_dr)
				$bal=abs($tot_cr-$tot_dr) . ' (Dr.)';
			else
				$bal='0';
			 ?>
            <script type="text/javascript">
				$(function(){
					$('#memname').html('<?php echo $paidto; ?>');
					$('#membal').html('<?php echo $bal; ?>');
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

	public function fetchSourceData()
	{
		if($this->ion_auth->logged_in())
		{
			if($_POST['vochfor']!='')
			{
				
			}
		}
	}
//----------------END OF CLASS-------------------------
}
?>