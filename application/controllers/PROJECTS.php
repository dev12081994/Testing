<?php

class Projects extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');		
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

	function projects()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			
			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

			$this->load->view('projects/projects',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	function srchProject()
	{
		if($this->ion_auth->logged_in())
		{
			$qry_con='';
			$qry_param=array();
			
			if(isset($_POST['srch_projtype']) && $_POST['srch_projtype']!='')
			{
				$qry_con .=' and proj_type=?';
				array_push($qry_param,$_POST['srch_projtype']);
			}
			if(isset($_POST['srch_projname']) && $_POST['srch_projname']!='')
			{
				$qry_con .=' and proj_name LIKE ?';
				array_push($qry_param,'%'.$_POST['srch_projname'].'%');
			}
			if(isset($_POST['srch_projamt']) && $_POST['srch_projamt']!='')
			{
				$qry_con .=' and proj_amt=?';
				array_push($qry_param,$_POST['srch_projamt']);
			}

			$data['proj_data']=$this->CrudModel->select_data("select * from project where proj_status=1 $qry_con",$qry_param);
			
			if(isset($_POST['exportbtn']))
            {
                $data['is_export']=1;                
                //place where the excel file is created  
                $file_name = "project_report_csv";        
                $myFile='uploads/'.$file_name.'.xls';
                //pass retrieved data into template and return as a string  
                $stringData = $this->parser->parse('projects/project_srch',$data, true);
                //open excel and write string into excel  
                $fh = fopen($myFile, 'w') or die("can't open file");  
                fwrite($fh, $stringData);  
                fclose($fh);  
                $this->exportFile($file_name);
            }
            else
            {
                $data['is_export']=0;
                $this->load->view('projects/project_srch',$data);
            }
			
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function fetchProjectAmount()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$proj_id=(int)($this->input->post('proj_id'));

    		$balamt_data=$this->CrudModel->select_data("select acc_sourceid,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor =4 group by acc_sourceid",array($proj_id));

    		$balamt=0;
    		if(count($balamt_data)>0 && $balamt_data[0]['acc_sourceid']!=null)
    		{
				$balamt = abs($balamt_data[0]['cr_amt']- $balamt_data[0]['dr_amt']);
    		}
    		echo json_encode(array('bal_amt'=>$balamt));
    	}
    }

	public function doneProject()
	{
		if($this->ion_auth->logged_in())
		{
			$edit_con['proj_id']=$_POST['proj_id'];
			$proj_done['prod_isdone']=1;
			$proj_done['prod_donedt']=date('Y-m-d');
			$proj_done['prod_doneuser']=$this->session->userdata['user_id'];
			$this->CrudModel->edit_record('project',$proj_done,$edit_con);

		}
		else
		{
			redirect('auth/login');
		}
	}

	public function saveProjects()
	{		
		if($this->ion_auth->logged_in())
		{	
			$proj_id=$this->input->post('proj_id');
			$data['proj_user']=$this->session->userdata['user_id'];
			$data['proj_entrydt']=date('Y-m-d H:i:s');
			$data['proj_amt']=$this->input->post('proj_amt');
			$data['proj_durtype']=$this->input->post('proj_durtype');
			$data['proj_type']=$this->input->post('proj_type');
			$data['proj_name']=trim($this->input->post('proj_name'));
			$data['proj_remark']=trim($this->input->post('proj_remark'));

			if($this->input->post('proj_startdt')=='')
				$data['proj_startdt']='0000-00-00';
			else
				$data['proj_startdt']=date('Y-m-d',strtotime($this->input->post('proj_startdt')));

			
			
			if($data['proj_durtype']==='1')
			{
				if($_POST['proj_enddt']=='')
					$data['proj_enddt']='0000-00-00';
				else
					$data['proj_enddt']=date('Y-m-d',strtotime($this->input->post('proj_enddt')));

				$data['proj_durtype']='';
				$data['proj_duration']='';
			}    	
			else
			{
				$data['proj_durtype']=$this->input->post('proj_durin');
				$data['proj_duration']=$this->input->post('proj_duration');
				$data['proj_enddt']='0000-00-00';
			}   

			if(!empty($_FILES['proj_docs']['name']))
			{				
				$path = './uploads/ProjectDocs';
				if(!is_dir($path))
				    mkdir($path);
								
				if(!$path)
				return;
					
				$config = array('upload_path'=>"./".$path."/",
				'allowed_types'=>"jpeg|jpg|png|gif|bmp|pdf");
				$this->load->library('upload',$config);
				$this->upload->do_upload('proj_docs');
				$file_data = $this->upload->data();
				$data['proj_docs'] =$path."/".$file_data['file_name'];
			}         
            
            $status=false;
            if($proj_id=='')
            {

            	$cat=$this->CrudModel->select_data("select proj_id from project where proj_status=? and proj_name LIKE ?",array(1,$data['proj_name']));
            	if(count($cat)>0 && $cat[0]['proj_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('project',$data);
			}
			else
			{
				$cat=$this->CrudModel->select_data("select proj_id from project where proj_status=? and proj_name LIKE ? and proj_id <> ?",array(1,$data['proj_name'],$proj_id));
            	if(count($cat)>0 && $cat[0]['proj_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['proj_id']=$proj_id;
                	$status=$this->CrudModel->edit_record('project',$data,$condition);
                }
			}

			if($status===0)
			{	
				$this->session->set_flashdata('message','Project Already Exist With Same Name');
				$this->session->set_flashdata('alert_type','alert-danger');		
			}
			elseif($status==true && $proj_id=='')
			{
				$this->session->set_flashdata('message','Project Details Saved Successfully');
				$this->session->set_flashdata('alert_type','alert-success');
			}		
            elseif($status==true && $proj_id!='')
            {
            	$this->session->set_flashdata('message','Project Details Updated Successfully');
				$this->session->set_flashdata('alert_type','alert-success');
            }
			else
			{	
				$this->session->set_flashdata('message','Proccess Failed');
				$this->session->set_flashdata('alert_type','alert-danger');		
			}
			redirect('PROJECTS/projects');
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function projectData()
	{	
		if($this->ion_auth->logged_in())
		{
			$proj_id=$_REQUEST['proj_id'];
			$proj_data=$this->CrudModel->select_data("select proj_id,proj_type,proj_name,date_format(proj_startdt,'%d-%m-%Y') as proj_startdt,date_format(proj_enddt,'%d-%m-%Y') as proj_enddt,proj_durtype,proj_duration,proj_amt,proj_remark,proj_docs from project where proj_status=? and proj_id=?",array(1,$proj_id));			
			echo json_encode($proj_data);
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function delProject()
	{	
		if($this->ion_auth->logged_in())
		{
			$proj_id=$_POST['proj_id'];
			$fetchdata=array();
			//$fetchdata=$this->CrudModel->select_data("select accm_id from account_master where accm_bankid=? and accm_status=?",array($b_id,1));
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Project Deletion Failed','type'=>'error'));
			}
			else
			{
				$inv_con['inv_perticular']=$proj_id;
				$inv_con['inv_for']=5;
				$acc_con['acc_vochfor']=5;
				$acc_con['acc_cid']=$proj_id;
				$status1=$this->CrudModel->delete_record('account',$acc_con);
				$status2=$this->CrudModel->delete_record('s_invoice',$inv_con);
				if($status1 == true && $status2 == true)
				{
					$proj_con['proj_id']=$proj_id;
					if($this->CrudModel->delete_record('project',$proj_con)==true)
						echo json_encode(array('title'=>'Delete Status','msg'=>'Project Deleted Successfully','type'=>'success'));
					else
						echo json_encode(array('title'=>'Delete Status','msg'=>'Project Deletion Failed','type'=>'error'));
				}
				else
				{
					echo json_encode(array('title'=>'Delete Status','msg'=>'Project Deletion Failed','type'=>'error'));
				}
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	
//---------------------- End Of Class ------------------	
}