<?php
class Masters extends CI_Controller
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

	public function fetchEditData()
	{
		if($this->ion_auth->logged_in())
		{
            $id=$_REQUEST['id'];
            $table=$_REQUEST['table'];
            $con=$_REQUEST['aliaz'].'_status=? and '.$_REQUEST['aliaz'].'_id=?';
            
            $data=$this->CrudModel->select_data("select * from $table where $con ",array(1,$id));
			
			echo json_encode($data);
        }
        else
        {
            redirect('auth/login');
        }
	}
	
	public function category()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->load->view('masters/category');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}
	
	public function users()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			
			$this->data['user_data']=$this->CrudModel->select_data("select u.company,u.username,u.email, u.id,u.first_name,u.last_name,u.active,group_concat(g.name) as name from users u
			inner join users_groups ug on ug.user_id=u.id
			inner join groups g on g.id=ug.group_id			
			where status= ? group by u.id order by u.first_name ",array(0));

			$this->load->view('masters/users_master',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}
	public function ledger()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');
			
			$this->load->view('masters/ledger');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function gst()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');
			
			$this->load->view('masters/gst_master');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveCategory()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['c_entrydt']=date('Y-m-d H:i:s');
			$data['c_user']=$this->session->userdata['user_id'];
			$data['c_name']=trim($this->input->post('c_name'));	
			$data['c_for']=trim($this->input->post('c_for'));	

			if($this->input->post('c_for')==='3')
				$data['c_taxperc']=trim($this->input->post('c_taxperc'));

			$data['c_remark']=trim($this->input->post('c_remark'));	
			$c_id=$this->input->post('c_id');	
            
            $status=false;
            
            if($c_id=='')
            {
            	$cat=$this->CrudModel->select_data("select c_id from mas_category where c_status=? and c_name LIKE ?",array(1,$data['c_name']));
            	if(count($cat)>0 && $cat[0]['c_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_category',$data);
            }
            else
            {
            	$cat=$this->CrudModel->select_data("select c_id from mas_category where c_status=? and c_name LIKE ? and c_id <> ?",array(1,$data['c_name'],$c_id));
            	if(count($cat)>0 && $cat[0]['c_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['c_id']=$c_id;
                	$status=$this->CrudModel->edit_record('mas_category',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Category Already Exist With Same Name','type'=>'error','process'=>'3'));	
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

	public function categoryData()
    {
        if($this->ion_auth->logged_in())
		{
            $c_id=$_REQUEST['c_id'];
            
            $cat=$this->CrudModel->select_data("select * from mas_category where c_status=? and c_id=? ",array(1,$c_id));
			
			echo json_encode($cat);
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function srchCateg()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array($_REQUEST['srch_cfor']);         
           
            if(isset($_REQUEST['srch_cat']) && $_REQUEST['srch_cat']!='')
            {                
                $qry_con .=" and c_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_cat'].'%'); 
            }
                    
            $category=$this->CrudModel->select_data("select * from mas_category c where c_status>0 and c_for=? $qry_con order by c_status,c_name",$qry_param);

         	$sl=1; 
			if(count($category) < 1)
			{
				echo "<tr><td colspan='5' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($category as $key => $value) { ?>
			<tr id="<?php echo 'row_'.$value['c_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['c_name'])); ?></td>
				<?php if($_REQUEST['srch_cfor']=='3') { ?>
				<td><?php echo $value['c_taxperc'].'%'; ?></td>
				<?php } ?>
				<td><?php echo ucwords(strtolower($value['c_remark'])); ?></td>
				
				<td>
					<center>
					<?php if($value['c_status']!=='2'){ ?>
					<button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="categoryData(<?php echo $value['c_id']; ?>)" title="Update"><icon class="fa fa-pencil"></icon></button>
					<?php } ?>
					</center>
				</td>
				<td>
					<center>
						<?php if($value['c_status']!=='2'){ ?>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['c_id']; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
						<?php } ?>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function delCategory()
	{	
		if($this->ion_auth->logged_in())
		{
			$c_id=$_POST['c_id'];
			$fetchdata=array();
			//$fetchdata=$this->CrudModel->select_data("select accm_id from account_master where accm_bankid=? and accm_status=?",array($b_id,1));
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Category Deletion Failed','type'=>'error'));
			}
			else
			{
				$condition['c_id']=$c_id;

				if($this->CrudModel->delete_record('mas_category',$condition)==true)
					echo json_encode(array('title'=>'Delete Status','msg'=>'Category Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Category Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function subCategory()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->data['category']=$this->CrudModel->select_data("select c_id,c_name from mas_category where              c_status=? order by c_name",array(1));

			$this->load->view('masters/sub_category',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveSubCategory()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['sc_entrydt']=date('Y-m-d H:i:s');
			$data['sc_user']=$this->session->userdata['user_id'];
			$data['sc_name']=trim($this->input->post('sc_name'));	
			$data['sc_remark']=trim($this->input->post('sc_remark'));	
			$data['sc_category']=trim($this->input->post('sc_category'));	
			$sc_id=$this->input->post('sc_id');	
            
            $status=false;
            
            if($sc_id=='')
            {
            	$subcat=$this->CrudModel->select_data("select sc_id from mas_subcategory where sc_status=? and sc_name LIKE ?",array(1,$data['sc_name']));
            	if(count($subcat)>0 && $subcat[0]['sc_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_subcategory',$data);
            }
            else
            {
            	$subcat=$this->CrudModel->select_data("select sc_id from mas_subcategory where sc_status=? and sc_name LIKE ? and sc_id <> ?",array(1,$data['sc_name'],$sc_id));
            	if(count($subcat)>0 && $subcat[0]['sc_id']!=null)
            		$status=0;
            	else
            	{
	            	$condition['sc_id']=$sc_id;
	                $status=$this->CrudModel->edit_record('mas_subcategory',$data,$condition);
	            }
            }
           
           	if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'SubCategory Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $sc_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $sc_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function subCategoryData()
    {
        if($this->ion_auth->logged_in())
		{
            $sc_id=$_REQUEST['sc_id'];
            
            $subcat=$this->CrudModel->select_data("select * from mas_subcategory where sc_status=? and sc_id=? ",array(1,$sc_id));
			
			echo json_encode($subcat);
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function srchSubCateg()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
         
           
            if(isset($_REQUEST['srch_subcat']) && $_REQUEST['srch_subcat']!='')
            {                
                $qry_con .=" and sc_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_subcat'].'%'); 
            }
        
            if(isset($_REQUEST['srch_cat']) && $_REQUEST['srch_cat']!='')
            {
                $qry_con .=" and sc_category=?";
                array_push($qry_param,$_REQUEST['srch_cat']); 
            }
            
            $subcategory=$this->CrudModel->select_data("select sc.*,c.c_name from mas_category c left join  	mas_subcategory sc on sc.sc_category = c.c_id where sc_status=1 $qry_con order by sc_name",$qry_param);

         	$sl=1; 
			if(count($subcategory) < 1)
			{
				echo "<tr><td colspan='6' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($subcategory as $key => $value) { ?>
			<tr id="<?php echo 'row_'.$value['sc_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['c_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['sc_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['sc_remark'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="subCategoryData(<?php echo $value['sc_id']; ?>)" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['sc_id']; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function delSubCategory()
	{	
		if($this->ion_auth->logged_in())
		{
			$sc_id=$_POST['sc_id'];
			$fetchdata=array();
			//$fetchdata=$this->CrudModel->select_data("select accm_id from account_master where accm_bankid=? and accm_status=?",array($b_id,1));
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}
			else
			{
				$condition['sc_id']=$sc_id;

				if($this->CrudModel->delete_record('mas_subcategory',$condition)==true)
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function vehicle()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->data['category']=$this->CrudModel->select_data("select * from mas_category where              c_status=? order by c_name",array(1));
			
			$this->load->view('masters/vehicle',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveVehicleType()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['vt_entrydt']=date('Y-m-d H:i:s');
			$data['vt_user']=$this->session->userdata['user_id'];
			$data['vt_name']=strtoupper(trim($this->input->post('vt_name')));	
			$vt_id=$this->input->post('vt_id');	
            
            $status=false;
            
            if($vt_id=='')
            {
            	$cat=$this->CrudModel->select_data("select vt_id from mas_vehicletype where vt_status=? and vt_name LIKE ?",array(1,$data['vt_name']));
            	if(count($cat)>0 && $cat[0]['vt_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_vehicletype',$data);
            }
            else
            {
            	$cat=$this->CrudModel->select_data("select vt_id from mas_vehicletype where vt_status=? and vt_name LIKE ? and vt_id <> ?",array(1,$data['vt_name'],$vt_id));
            	if(count($cat)>0 && $cat[0]['vt_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['vt_id']=$vt_id;
                	$status=$this->CrudModel->edit_record('mas_vehicletype',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Vehicle Type Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $vt_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $vt_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function saveVehicleInfo()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['v_entrydt']=date('Y-m-d H:i:s');
			$data['v_user']=$this->session->userdata['user_id'];
			$data['v_typeid']=$this->input->post('v_typeid');	
			$data['v_num']=trim($this->input->post('v_num'));	
			$data['v_remark']=trim($this->input->post('v_remark'));	
			$v_id=$this->input->post('v_id');	
            
            $status=false;
            
            if($v_id=='')
            {
            	$cat=$this->CrudModel->select_data("select v_id from mas_vehicle where v_status=? and v_num LIKE ?",array(1,$data['v_num']));
            	if(count($cat)>0 && $cat[0]['v_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_vehicle',$data);
            }
            else
            {
            	$cat=$this->CrudModel->select_data("select v_id from mas_vehicle where v_status=? and v_num LIKE ? and v_id <> ?",array(1,$data['v_num'],$v_id));
            	if(count($cat)>0 && $cat[0]['v_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['v_id']=$v_id;
                	$status=$this->CrudModel->edit_record('mas_vehicle',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Vehicle Already Exist With Same Number','type'=>'error','process'=>'3'));	
			elseif($status==true && $v_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $v_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function srchVehicleInfo()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
         
           
            if(isset($_REQUEST['srch_vnum']) && $_REQUEST['srch_vnum']!='')
            {                
                $qry_con .=" and v_num LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_vnum'].'%'); 
            }
        
            if(isset($_REQUEST['srch_vtype']) && $_REQUEST['srch_vtype']!='')
            {
                $qry_con .=" and v_typeid=?";
                array_push($qry_param,$_REQUEST['srch_vtype']); 
            }
            
            $vehicle=$this->CrudModel->select_data("select v.*,vt.vt_name from mas_vehicle v left join  	mas_vehicletype vt on vt.vt_id = v.v_typeid where v_status=1 $qry_con order by vt_name",$qry_param);

         	$sl=1; 
			if(count($vehicle) < 1)
			{
				echo "<tr><td colspan='6' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($vehicle as $key => $value) { ?>
			<tr id="<?php echo 'vrow_'.$value['v_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['vt_name'])); ?></td>
				<td><?php echo strtoupper($value['v_num']); ?></td>
				<td><?php echo ucwords(strtolower($value['v_remark'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['v_id']; ?>','2')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['v_id'].'@'.'2'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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
	public function srchVehicleType()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {                
                $qry_con .=" and vt_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_type'].'%'); 
            }
            
            $vehicletype=$this->CrudModel->select_data("select vt.* from mas_vehicletype vt where vt_status=1 $qry_con order by vt_name",$qry_param);

         	$sl=1; 
			if(count($vehicletype) < 1)
			{
				echo "<tr><td colspan='4' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($vehicletype as $key => $value) { ?>
			<tr id="<?php echo 'vtrow_'.$value['vt_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['vt_name'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['vt_id']; ?>','1')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['vt_id'].'@'.'1'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function fetchVehicleType()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$vtype=$this->CrudModel->select_data("select vt_id,vt_name from mas_vehicletype where vt_status=?",array(1));
    		
    		echo "<option value=''>Select Type</option>";
    		foreach ($vtype as $key => $value) 
    		{
    			echo "<option value='".$value['vt_id']."'>".ucwords(strtolower($value['vt_name']))."</option>";
    		}
    	}
		else
		{
			redirect('auth/login');
		}
    }

    public function deleteData()
	{	
		if($this->ion_auth->logged_in())
		{
			$id=$_POST['id'];
			$table=$_POST['table'];
			$alias=$_POST['alias'];
			$fetchdata=array();
			//$fetchdata=$this->CrudModel->select_data("select accm_id from account_master where accm_bankid=? and accm_status=?",array($b_id,1));
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}
			else
			{
				$condition[$alias.'_id']=$id;

				if($this->CrudModel->delete_record($table,$condition)==true)
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}  

    public function deleteBankAcc()
	{	
		if($this->ion_auth->logged_in())
		{
			$id=$_POST['id'];
			$fetchdata=array();
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}
			else
			{
				$acc_con['acc_id']=$id;
				$cat_con['c_bankaccid']=$id;

				if($this->CrudModel->delete_record('mas_bankacc',$acc_con)==true)
				{
					$this->CrudModel->delete_record('mas_category',$cat_con);
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
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

    public function deleteBank()
	{	
		if($this->ion_auth->logged_in())
		{
			$id=$_POST['id'];
			$fetchdata=array();
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}
			else
			{
				$bank_con['acc_id']=$id;

				if($this->CrudModel->delete_record('mas_bank',$acc_con)==true)
				{
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
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

	public function members()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			
			$this->load->view('masters/members');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function addMember()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));

			$this->data['depdata']=$this->CrudModel->select_data("select * from mas_department where dep_status=? order by dep_name",array(1));
			
			$this->data['countrydata']=$this->CrudModel->select_data("select id, country_name from mas_country where country_status=? order by country_name",array(1));

			$this->data['statedata']=$this->CrudModel->select_data("select * from mas_state where state_status= ? and state_country=? order by state_name",array(1,99));
            
            $this->data['citydata']=$this->CrudModel->select_data("select * from mas_city where city_status= ? and city_state=? order by city_name",array(1,19));
                        
			$this->data['gardiandata']=$this->CrudModel->select_data("select * from mas_gardian where g_status=? order by g_name",array(1));
			
			$c_id=(int)($this->uri->segment(3));

			$this->data['cus_data']=$this->CrudModel->select_data("select * from mas_members where c_status=? and c_id=?",array(1,$c_id));

			$this->load->view('masters/add_member',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchMember()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array(1);
         
         	if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {
                $qry_con .=" and c_type= ?";
                array_push($qry_param,$_REQUEST['srch_type']); 
            }

            if(isset($_REQUEST['srch_contact']) && $_REQUEST['srch_contact']!='')
            {
                $qry_con .=" and (c_phone LIKE ? || c_mob1 LIKE ? || c_mob2 LIKE ?)";
                array_push($qry_param,'%'.$_REQUEST['srch_contact'].'%','%'.$_REQUEST['srch_contact'].'%','%'.$_REQUEST['srch_contact'].'%'); 
            }

            if(isset($_REQUEST['srch_cgardian']) && $_REQUEST['srch_cgardian']!='')
            {
                $name='%'.$_REQUEST['srch_cgardian'].'%';
                $qry_con .=" and c_gardianname LIKE ?";
                array_push($qry_param,$name); 
            }

            if(isset($_REQUEST['srch_name']) && $_REQUEST['srch_name']!='')
            {
                $name='%'.$_REQUEST['srch_name'].'%';
                $qry_con .=" and CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) LIKE ?";
                array_push($qry_param,$name); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(c_entrydt,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $old_cus=$this->CrudModel->select_data("select cus.*,gm.g_name as p_gardiantype,c.city_name,u.username from mas_members cus
			 	left join mas_gardian gm on gm.g_id=cus.c_gardiantype 
			 	left join mas_city c on c.city_id=cus.c_city  
	            left join users u on u.id=cus.c_user
			 	where cus.c_status=? $qry_con order by c_id desc",$qry_param);


            //echo $this->db->last_query();die;
            $sl=1;
            foreach($old_cus as $value){ 
                
                $caste='';
                $sex='';

                if($value['c_gender']==1)
                    $sex='Male';
                elseif($value['c_gender']==2)
                    $sex='Female';
                else
                    $sex='Other'; 

                if($value['c_type']==1)
                    $type='Customer';
                elseif($value['c_type']==2)
                    $type='Vendor';
                elseif($value['c_type']==3)
                    $type='Employee'; 
               	else
               		$type="Other";


               $pname=ucwords(strtolower($value['c_firstname'])).' '.ucwords(strtolower($value['c_middlename'])).' '.ucwords(strtolower($value['c_lastname']));

               $address='City - '.ucwords(strtolower($value['city_name']));
       
        ?>
            <tr id='row_<?php echo $value["c_id"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['c_entrydt'])); ?></td>
                <td>
                    <a href="#" data-toggle="popover" title="Customer Extra Details" html="false" style="text-decoration: none;" data-content="<table class='table table-bordered'>
                        <tr>
                            <th>User</th>
                            <td><?php echo ucwords(strtolower($value['username'])); ?></td>
                        </tr>
                        <tr>
                            <th>Sex</th>
                            <td><?php echo $sex; ?></td>
                        </tr>

                        <tr>
                            <th>Father/Husband</th>
                            <td>
                                <?php echo ucwords(strtolower($value['c_gardiantype'])).' '.ucwords(strtolower($value['c_gardianname'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Address</th>
                            <td>
                                <?php echo $address; ?>
                            </td>
                        </tr>
                    </table>">
                    <?php echo $type; ?></a>
                </td>
                <td><?php echo $pname; ?></td>
                <td><?php echo ucwords(strtolower($value['c_gardiantype'])).' '.ucwords(strtolower($value['c_gardianname'])); ?></td>  
                <td><?php echo $value['c_phone'].'/'.$value['c_mob1'].'/'.$value['c_mob2'];?></td>
                <td><?php echo $value['c_email'];?></td>                                                 
                <td><?php echo $value['c_panno'];?></td>                                                 
                <td><?php echo $value['c_gstno'];?></td>                                                 
                <td><?php echo $value['c_tinno'];?></td>   
                
                <?php //if($this->ion_auth->is_admin()){ ?>      
                <td class="h"><center>
                    <a class="btn btn-info btn-xs" href="<?php echo base_url('MASTERS/addMember/'.$value['c_id']);?>" title="Update">
                        <span class="fa fa-pencil"></span>
                    </a>
                    </center>
                </td>          
                <td class="h">
                    <center>
                        <a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['c_id']; ?>');"  href="#delConfirm">
                        <span class="fa fa-trash-o"></span></a>
                    </center>
                </td>                
                <?php //} ?>

            </tr> 
            <?php } ?>
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

    public function deleteMember()
	{	
		if($this->ion_auth->logged_in())
		{
			$c_id=(int)($_POST['c_id']);

			if($c_id>0)
			{				
				$inv_data=$this->CrudModel->select_data('select inv_id from s_invoice where inv_perticular=?',array($c_id));
					
				foreach ($inv_data as $invvalue) {
					$condition=array();
					$sl_con['sl_invid']=$invvalue['inv_id'];
					$stk_con['stk_invid']=$invvalue['inv_id'];
					$this->CrudModel->delete_record('s_stock',$stk_con);
					$this->CrudModel->delete_record('s_sale',$sl_con);
				}

				$acc_con['acc_cid']=$c_id;
				$this->CrudModel->delete_record('account',$acc_con);

				$inv_con['inv_perticular']=$c_id;
				$this->CrudModel->delete_record('s_invoice',$inv_con);

				$mem_con['c_id']=$c_id;
				if($this->CrudModel->delete_record('mas_members',$mem_con))				
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function saveMember()
	{		
		if($this->ion_auth->logged_in())
		{				
			$data['c_phone']=$this->input->post('c_phone');
			$data['c_mob1']=str_replace(['(',')'],['',''],$this->input->post('c_mob1'));
			$data['c_mob2']=str_replace(['(',')'],['',''],$this->input->post('c_mob2'));
			$data['c_email']=$this->input->post('c_email');
			$data['c_salutation']=$this->input->post('c_salutation');
			$data['c_firstname']=trim($this->input->post('c_firstname'));
			$data['c_middlename']=trim($this->input->post('c_middlename'));
			$data['c_lastname']=trim($this->input->post('c_lastname'));
			$c_id=$this->input->post('c_id');
			
			if($this->input->post('c_dob')=='')
				$data['c_dob']='0000-00-00';
			else
				$data['c_dob']=date('Y-m-d',strtotime($this->input->post('c_dob')));

			
			$data['c_gender']=$this->input->post('c_gender');
			$data['c_gardiantype']=$this->input->post('c_gardiantype');
			$data['c_gardianname']=trim($this->input->post('c_gardianname'));
			$data['c_country']=$this->input->post('c_country');
			$data['c_state']=$this->input->post('c_state');
			$data['c_city']=$this->input->post('c_city');
			/*$data['c_block']=$this->input->post('c_block');
			$data['c_panchayat']=$this->input->post('c_panchayat');
			$data['c_village']=$this->input->post('c_village');*/
			$data['c_address']=$this->input->post('c_address');			
			$data['c_remark']=trim($this->input->post('c_remark')); 	           
			$data['c_type']=$this->input->post('c_type');      

			if($data['c_type']=='3')
			{
				$data['c_dep']=$this->input->post('c_dep');            
				$data['c_desig']=$this->input->post('c_desig');

				if($this->input->post('c_doj')=='')
					$data['c_doj']='0000-00-00';
				else
					$data['c_doj']=date('Y-m-d',strtotime($this->input->post('c_doj')));

			}    
			            
			$data['c_aadharno']=trim($this->input->post('c_aadharno'));            
			$data['c_panno']=trim($this->input->post('c_panno'));            
			$data['c_gstno']=trim($this->input->post('c_gstno'));            
			$data['c_tinno']=trim($this->input->post('c_tinno'));            
			$data['c_bank']=$this->input->post('c_bank');            
			$data['c_branch']=trim($this->input->post('c_branch'));            
			$data['c_accno']=trim($this->input->post('c_accno'));            
			$data['c_ifsc']=trim($this->input->post('c_ifsc'));            
			$data['c_entrydt']=date('Y-m-d H:i:s');
			$data['c_user']=$this->session->userdata['user_id'];				

			$name=str_replace(' ','',$data['c_firstname'].$data['c_middlename'].$data['c_lastname']);
            
            
            if($c_id=='')
            {
				$check_exist=$this->CrudModel->select_data("select c_id from mas_members where REPLACE(concat_ws(' ',c_firstname,c_middlename,c_lastname),' ','') LIKE ? and c_mob1 = ?",array($name,$data['c_mob1']));

	            if(count($check_exist) > 0 && $check_exist[0]['c_id'] !== null)
	            {
	            	$this->session->set_flashdata('message','Member Already Exist With Same Name And Contact Number');
	            	$this->session->set_flashdata('alert_type','alert-danger');
	            	redirect('MASTERS/addMember');
	            }
				else if($this->CrudModel->insert_data('mas_members',$data)==true)
				{            
					$this->session->set_flashdata('message','Member Registered Successfully');
					$this->session->set_flashdata('alert_type','alert-info');
				}
				else
				{
					$this->session->set_flashdata('message','Member Registration Failed');
					$this->session->set_flashdata('alert_type','alert-danger');
				}
				redirect('MASTERS/addMember');
			}
			else
			{
				$edit_con['c_id']=$c_id;
				if($this->CrudModel->edit_record('mas_members',$data,$edit_con)==true)
				{
	                $opdid=$this->db->insert_id();             
					$this->session->set_flashdata('message','Member Updated Successfully');
				}
				else
				{
					$this->session->set_flashdata('message','Member Updation Failed');
				}
				redirect('MASTERS/members');
			}			
		}
		else
		{
			redirect('auth/login');
		} 
	}   

	public function product()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->data['ctg_list']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? order by c_name",array(1,1));
			
			$this->load->view('masters/product',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchProduct()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array(1);
         
         	if(isset($_REQUEST['srch_ctgid']) && $_REQUEST['srch_ctgid']!='')
            {
                $qry_con .=" and prod_ctgid = ?";
                array_push($qry_param,$_REQUEST['srch_ctgid']); 
            }
         	if(isset($_REQUEST['srch_unit']) && $_REQUEST['srch_unit']!='')
            {
                $qry_con .=" and prod_unit = ?";
                array_push($qry_param,$_REQUEST['srch_unit']); 
            }

            if(isset($_REQUEST['srch_name']) && $_REQUEST['srch_name']!='')
            {
                $name='%'.$_REQUEST['srch_name'].'%';
                $qry_con .=" and prod_name LIKE ?";
                array_push($qry_param,$name); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(prod_entrydt,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $product_data=$this->CrudModel->select_data("select p.*,c.c_name from mas_product p
			 	left join mas_category c on c.c_id=p.prod_ctgid
			 	where p.prod_status=? $qry_con order by c.c_name desc",$qry_param);

            $sl=1;
            foreach($product_data as $value){ 
               $gst_status='<span title="Not Applicable" style="color:red"><center>NA</center></span>';
       		?>
            <tr id='row_<?php echo $value["prod_id"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['prod_entrydt'])); ?></td>
                <td><?php echo ucwords(strtolower($value['c_name'])); ?></td>  
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>  
                <td><?php echo $value['prod_hsn_sac']; ?></td>  
                <td><?php echo ucwords(strtolower($value['prod_unit'])); ?></td>   
                <td><?php echo $value['prod_openstock'];?></td>                     
                <td><?php echo $value['prod_purrate'];?></td>   
                <td><?php echo $value['prod_salerate'];?></td>   
                <td><?php if($value['prod_isgst']==='1')echo $value['prod_gstrate'].'%';else echo $gst_status; ?></td> 
                <td><?php if($value['prod_purgstincl']==='0')echo $gst_status;else if($value['prod_purgstincl']==='1')echo 'Yes';else echo 'No'; ?></td>                       
                <td><?php echo ucwords(strtolower($value['prod_remark'])); ?></td>                       
                
                <?php //if($this->ion_auth->is_admin()){ ?>      
                <td class="h"><center>
                    <a class="btn btn-info btn-xs" onclick="fetchEditData('<?php echo $value['prod_id']; ?>')" title="Update">
                        <span class="fa fa-pencil"></span>
                    </a>
                    </center>
                </td>          
                <td class="h">
                    <center>
                        <a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['prod_id']; ?>');"  href="#delConfirm">
                        <span class="fa fa-trash-o"></span></a>
                    </center>
                </td>                
                <?php //} ?>

            </tr> 
            <?php } ?>
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

    public function saveProduct()
	{		
		if($this->ion_auth->logged_in())
		{	
			$data['prod_ctgid']=$this->input->post('prod_ctgid');
			$data['prod_name']=trim($this->input->post('prod_name'));
			$data['prod_remark']=trim($this->input->post('prod_remark'));
			$data['prod_unit']=$this->input->post('prod_unit'); 
			$data['prod_purrate']=$this->input->post('prod_purrate'); 
			$data['prod_salerate']=$this->input->post('prod_salerate'); 
			$data['prod_isgst']=$this->input->post('prod_isgst'); 
			$data['prod_purgstincl']=$this->input->post('prod_purgstincl'); 
			$data['prod_hsn_sac']=$this->input->post('prod_hsn_sac'); 
			$data['prod_gstrate']=$this->input->post('prod_gstrate'); 
			$data['prod_openstock']=(float)($this->input->post('prod_openstock')); 
			$data['prod_entrydt']=date('Y-m-d H:i:s');
			$data['prod_user']=$this->session->userdata['user_id'];

			$prod_id=$this->input->post('prod_id');						
			
			if($prod_id=='')
            {
            	$data['prod_currstock']=(float)($this->input->post('prod_openstock')); 
            	$check_exist=$this->CrudModel->select_data("select prod_id from mas_product where REPLACE(prod_name,' ','') LIKE ?",array(str_replace(' ', '',$data['prod_name'])));
            	if(count($check_exist)>0 && $check_exist[0]['prod_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_product',$data);
            }
            else
            {
            	$check_exist=$this->CrudModel->select_data("select prod_id from mas_product where REPLACE(prod_name,' ','') LIKE ? and prod_id<>?",array(str_replace(' ', '',$data['prod_name']),$prod_id));
            	if(count($check_exist)>0 && $check_exist[0]['prod_id']!=null)
            		$status=0;
            	else
            	{
            		$exist_stock=$this->CrudModel->select_data("select prod_currstock,prod_openstock from mas_product where prod_id=?",array($prod_id));

            		$data['prod_currstock']=$exist_stock[0]['prod_currstock']-$exist_stock[0]['prod_openstock']+(float)($this->input->post('prod_openstock')); 
	            	$condition['prod_id']=$prod_id;
	                $status=$this->CrudModel->edit_record('mas_product',$data,$condition);
	            }
            }
           
           	if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Product Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $prod_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Product Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $prod_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Product Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		
		}
		else
		{
			redirect('auth/login');
		} 
	}

    public function deleteProduct()
	{	
		if($this->ion_auth->logged_in())
		{
			$prod_id=(int)($_POST['prod_id']);

			if($prod_id>0)
			{				
				//$inv_data=$this->CrudModel->select_data('select inv_id from s_invoice where inv_perticular=?',array($c_id));
					
				//foreach ($inv_data as $invvalue) {
					$sl_con['sl_prodid']=$prod_id;
					$stk_con['stk_prodid']=$prod_id;
					$this->CrudModel->delete_record('s_stock',$stk_con);
					$this->CrudModel->delete_record('s_sale',$sl_con);
				//}

				$prod_con['prod_id']=$prod_id;
				if($this->CrudModel->delete_record('mas_product',$prod_con))				
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}	

	public function fetchState()
	{	
		if($this->ion_auth->logged_in())
		{
			$countryid=$_REQUEST['countryid'];
			$statedata=$this->CrudModel->select_data("select state_id,state_name from mas_state where state_status=? and state_country=? order by state_name",array(1,$countryid));			
			echo json_encode($statedata);
		}
		else
		{
			redirect('auth/login');
		}
	}
    
	public function fetchCity()
	{	
		if($this->ion_auth->logged_in())
		{
			$stateid=$_REQUEST['stateid'];
			$citydata=$this->CrudModel->select_data("select city_id,city_name from mas_city where city_status=? and city_state=? order by city_name",array(1,$stateid));			
			echo json_encode($citydata);
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function getDesig()
	{	
		if($this->ion_auth->logged_in())
		{
			$depid=$_REQUEST['depid'];
			$desigdata=$this->CrudModel->select_data("select desig_id,desig_name from mas_designation where desig_status=? and desig_depid=? order by desig_name",array(1,$depid));			
			echo json_encode($desigdata);
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function bank()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->data['firm']=$this->CrudModel->select_data("select f_id,f_name from mas_firm where f_status=? order by f_name",array(1));
			
			$this->load->view('masters/bank',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveBank()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['bank_entrydt']=date('Y-m-d H:i:s');
			$data['bank_user']=$this->session->userdata['user_id'];
			$data['bank_name']=strtoupper(trim($this->input->post('bank_name')));	
			$data['bank_short']=strtoupper(trim($this->input->post('bank_short')));	
			$bank_id=$this->input->post('bank_id');	
            
            $status=false;
            
            if($bank_id=='')
            {
            	$cat=$this->CrudModel->select_data("select bank_id from mas_bank where bank_status=? and bank_name LIKE ?",array(1,$data['bank_name']));
            	if(count($cat)>0 && $cat[0]['bank_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_bank',$data);
            }
            else
            {
            	$cat=$this->CrudModel->select_data("select bank_id from mas_bank where bank_status=? and bank_name LIKE ? and bank_id <> ?",array(1,$data['bank_name'],$bank_id));
            	if(count($cat)>0 && $cat[0]['bank_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['bank_id']=$bank_id;
                	$status=$this->CrudModel->edit_record('mas_bank',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Bank Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $bank_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $bank_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function saveBankAcc()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['acc_entrydt']=date('Y-m-d H:i:s');
			$data['acc_user']=$this->session->userdata['user_id'];
			$data['acc_bankid']=$this->input->post('acc_bankid');	
			$data['acc_firm']=$this->input->post('acc_firm');	
			$data['acc_num']=trim($this->input->post('acc_num'));	
			$data['acc_branch']=trim($this->input->post('acc_branch'));	
			$data['acc_ifsc']=trim($this->input->post('acc_ifsc'));	
			$acc_id=$this->input->post('acc_id');	
            
            $bankname=$this->CrudModel->select_data("select bank_short from mas_bank where bank_id = ? and bank_status =? ",array($data['acc_bankid'],1));

            if(count($bankname) > 0 && $bankname[0]['bank_short']!==null)
            {
            	$bank=$bankname[0]['bank_short'];
            }
            else
            {
            	echo json_encode(array('title'=>'Status','msg'=>'This Bank Is Not Registered','type'=>'error','process'=>'3'));
            	die();
            }

			$bank_ldg['c_name']=str_replace([' ','.','-'],['','',''],$bank).'-'.$data['acc_num'];
			$bank_ldg['c_user']=$this->session->userdata['user_id'];
			$bank_ldg['c_remark']='Ledger';
			$bank_ldg['c_for']='2';
			$bank_ldg['c_entrydt']=date('Y-m-d H:i:s');

            $status=false;
            
            if($acc_id=='')
            {
            	$cat=$this->CrudModel->select_data("select acc_id from mas_bankacc where acc_status=? and acc_num LIKE ?",array(1,$data['acc_num']));
            	if(count($cat)>0 && $cat[0]['acc_id']!=null)
            		$status=0;
            	else
            	{
                	$status=$this->CrudModel->insert_data('mas_bankacc',$data);

                	$bank_ldg['c_bankaccid']=$this->CrudModel->select_data("select max(acc_id) as lastid from mas_bankacc where 1=1",array())[0]['lastid'];
                	$status=$this->CrudModel->insert_data('mas_category',$bank_ldg);
            	}
            }
            else
            {
            	$cat=$this->CrudModel->select_data("select acc_id from mas_bankacc where acc_status=? and acc_num LIKE ? and acc_id <> ?",array(1,$data['acc_num'],$acc_id));
            	if(count($cat)>0 && $cat[0]['acc_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['acc_id']=$acc_id;
            		$cat_con['c_bankaccid']=$acc_id;
                	$status=$this->CrudModel->edit_record('mas_bankacc',$data,$condition);
                	$status=$this->CrudModel->edit_record('mas_category',$bank_ldg,$cat_con);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'A/C Already Exist With Same Number','type'=>'error','process'=>'3'));	
			elseif($status==true && $acc_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $acc_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function srchBankAcc()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
         
           
            if(isset($_REQUEST['srch_accnum']) && $_REQUEST['srch_accnum']!='')
            {                
                $qry_con .=" and acc_num LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_accnum'].'%'); 
            }
        
            if(isset($_REQUEST['srch_accbank']) && $_REQUEST['srch_accbank']!='')
            {
                $qry_con .=" and acc_bankid=?";
                array_push($qry_param,$_REQUEST['srch_accbank']); 
            }
            
            $bankacc=$this->CrudModel->select_data("select ac.*,mf.f_name,b.bank_name from mas_bankacc ac left join  	mas_bank b on b.bank_id = ac.acc_bankid left join mas_firm mf on mf.f_id=ac.acc_firm where acc_status=1 $qry_con order by acc_num",$qry_param);

         	$sl=1; 
			if(count($bankacc) < 1)
			{
				echo "<tr><td colspan='6' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($bankacc as $key => $value) { ?>
			<tr id="<?php echo 'vrow_'.$value['acc_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['bank_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['f_name'])); ?></td>
				<td><?php echo strtoupper($value['acc_num']); ?></td>
				<td><?php echo ucwords(strtolower($value['acc_branch'])); ?></td>
				<td><?php echo strtoupper($value['acc_ifsc']); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['acc_id']; ?>','2')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['acc_id'].'@'.'2'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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
	public function srchBank()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_bank']) && $_REQUEST['srch_bank']!='')
            {                
                $qry_con .=" and bank_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_bank'].'%'); 
            }
            
            $bank=$this->CrudModel->select_data("select b.* from mas_bank b where bank_status=1 $qry_con order by bank_name",$qry_param);

         	$sl=1; 
			if(count($bank) < 1)
			{
				echo "<tr><td colspan='4' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($bank as $key => $value) { ?>
			<tr id="<?php echo 'vtrow_'.$value['bank_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['bank_name'])); ?></td>
				<td><?php echo strtoupper($value['bank_short']); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['bank_id']; ?>','1')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['bank_id'].'@'.'1'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function fetchBank()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$vtype=$this->CrudModel->select_data("select bank_id,bank_name from mas_bank where  bank_status=?",array(1));
    		
    		echo "<option value=''>Select Type</option>";
    		foreach ($vtype as $key => $value) 
    		{
    			echo "<option value='".$value['bank_id']."'>".ucwords(strtolower($value['bank_name']))."</option>";
    		}
    	}
		else
		{
			redirect('auth/login');
		}
    }

    public function depDesig()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	

			$this->load->view('masters/dep_desig');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchDep()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_dep']) && $_REQUEST['srch_dep']!='')
            {                
                $qry_con .=" and dep_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_dep'].'%'); 
            }
            $depdata=$this->CrudModel->select_data("select * from mas_department where dep_status=1 $qry_con order by dep_name",$qry_param);

         	$sl=1; 
			if(count($depdata) < 1)
			{
				echo "<tr><td colspan='4' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($depdata as $key => $value) { ?>
			<tr id="<?php echo 'vtrow_'.$value['dep_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['dep_name'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['dep_id']; ?>','1')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['dep_id'].'@'.'1'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

	public function srchDesig()
	{	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_desig']) && $_REQUEST['srch_desig']!='')
            {                
                $qry_con .=" and desig_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_desig'].'%'); 
            }
        
            if(isset($_REQUEST['srch_desigdep']) && $_REQUEST['srch_desigdep']!='')
            {
                $qry_con .=" and desig_depid=?";
                array_push($qry_param,$_REQUEST['srch_desigdep']); 
            }
            
            $desig_data=$this->CrudModel->select_data("select desig_id,desig_name,dep_name from mas_designation mdg left join mas_department mdp on mdp.dep_id=mdg.desig_depid where desig_status=1 $qry_con order by desig_name",$qry_param);

         	$sl=1; 
			if(count($desig_data) < 1)
			{
				echo "<tr><td colspan='6' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($desig_data as $key => $value) { ?>
			<tr id="<?php echo 'vrow_'.$value['desig_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['dep_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['desig_name'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['desig_id']; ?>','2')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['desig_id'].'@'.'2'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function fetchdep()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$depid=$this->CrudModel->select_data("select dep_id,dep_name from mas_department where dep_status=?",array(1));
    		
    		echo "<option value=''>Select Department</option>";
    		foreach ($depid as $key => $value) 
    		{
    			echo "<option value='".$value['dep_id']."'>".ucwords(strtolower($value['dep_name']))."</option>";
    		}
    	}
		else
		{
			redirect('auth/login');
		}
    }

	public function saveDep()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['dep_entrydt']=date('Y-m-d H:i:s');
			$data['dep_user']=$this->session->userdata['user_id'];
			$data['dep_name']=trim($this->input->post('dep_name'));	
			$dep_id=$this->input->post('dep_id');	
            
            $status=false;
            
            if($dep_id=='')
            {
            	$dep_data=$this->CrudModel->select_data("select dep_id from mas_department where dep_status=? and dep_name LIKE ?",array(1,$data['dep_name']));
            	if(count($dep_data)>0 && $dep_data[0]['dep_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_department',$data);
            }
            else
            {
            	$dep_data=$this->CrudModel->select_data("select dep_id from mas_department where dep_status=? and dep_name LIKE ? and dep_id <> ?",array(1,$data['dep_name'],$dep_id));
            	if(count($dep_data)>0 && $dep_data[0]['dep_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['dep_id']=$dep_id;
                	$status=$this->CrudModel->edit_record('mas_department',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Department Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $dep_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $dep_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function saveDesig()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['desig_entrydt']=date('Y-m-d H:i:s');
			$data['desig_user']=$this->session->userdata['user_id'];
			$data['desig_depid']=$this->input->post('desig_depid');	
			$data['desig_name']=trim($this->input->post('desig_name'));	
			$desig_id=$this->input->post('desig_id');	
            
            $status=false;
            
            if($desig_id=='')
            {
            	$desig_data=$this->CrudModel->select_data("select desig_id from mas_designation where desig_status=? and desig_name LIKE ?",array(1,$data['desig_name']));
            	if(count($desig_data)>0 && $desig_data[0]['desig_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_designation',$data);
            }
            else
            {
            	$desig_data=$this->CrudModel->select_data("select desig_id from mas_designation where desig_status=? and desig_name LIKE ? and desig_id <> ?",array(1,$data['desig_name'],$desig_id));
            	if(count($desig_data)>0 && $desig_data[0]['desig_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['desig_id']=$desig_id;
                	$status=$this->CrudModel->edit_record('mas_designation',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'Designation Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $desig_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $desig_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function deleteDepDesig()
	{	
		if($this->ion_auth->logged_in())
		{
			$id=$_POST['id'];
			$type=$_POST['type'];
			$fetchdata=array();

			if($type==1) // 1 For Delete Department
			{
				$fetchdata=$this->CrudModel->select_data('select desig_id from mas_designation where desig_depid = ?',array($id));
				$table="mas_department";
				$condition['dep_id']=$id;
				$msg="Record Deletion Failed !!! Some Designation Are Registered Under This Department";
			}
			else
			{
				$fetchdata=$this->CrudModel->select_data('select c_id from mas_members where c_desig = ?',array($id));
				$table="mas_designation";
				$condition['desig_id']=$id;
				$msg="Record Deletion Failed !!! Some Employees Are Registered Under This Designation";
			}
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>$msg,'type'=>'error'));
			}
			else
			{
				if($this->CrudModel->delete_record($table,$condition)==true)
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Succesfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}	
					
		}
		else
		{
			redirect('auth/login');
		}
	}

    public function cityState()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');	
			
			$this->load->view('masters/city_state');
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchState()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_state']) && $_REQUEST['srch_state']!='')
            {                
                $qry_con .=" and state_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_state'].'%'); 
            }
            $statedata=$this->CrudModel->select_data("select * from mas_state where state_status=1 $qry_con order by state_name",$qry_param);

         	$sl=1; 
			if(count($statedata) < 1)
			{
				echo "<tr><td colspan='4' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($statedata as $key => $value) { ?>
			<tr id="<?php echo 'vtrow_'.$value['state_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['state_name'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['state_id']; ?>','1')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['state_id'].'@'.'1'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

	public function srchCity()
	{	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_city']) && $_REQUEST['srch_city']!='')
            {                
                $qry_con .=" and city_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_city'].'%'); 
            }
        
            if(isset($_REQUEST['srch_citystate']) && $_REQUEST['srch_citystate']!='')
            {
                $qry_con .=" and city_state=?";
                array_push($qry_param,$_REQUEST['srch_citystate']); 
            }
            
            $city_data=$this->CrudModel->select_data("select city_id,city_name,state_name from mas_city mct left join mas_state ms on ms.state_id=mct.city_state where city_status=1 $qry_con order by city_name",$qry_param);

         	$sl=1; 
			if(count($city_data) < 1)
			{
				echo "<tr><td colspan='6' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($city_data as $key => $value) { ?>
			<tr id="<?php echo 'vrow_'.$value['city_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['state_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['city_name'])); ?></td>
				<td><center><button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="fetchEditData('<?php echo $value['city_id']; ?>','2')" title="Update"><icon class="fa fa-pencil"></icon></button></center></td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['city_id'].'@'.'2'; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function fetchStateList()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$depid=$this->CrudModel->select_data("select state_id,state_name from mas_state where state_status=?",array(1));
    		
    		echo "<option value=''>Select State</option>";
    		foreach ($depid as $key => $value) 
    		{
    			echo "<option value='".$value['state_id']."'>".ucwords(strtolower($value['state_name']))."</option>";
    		}
    	}
		else
		{
			redirect('auth/login');
		}
    }

	public function saveState()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['state_entrydt']=date('Y-m-d H:i:s');
			$data['state_user']=$this->session->userdata['user_id'];
			$data['state_name']=trim($this->input->post('state_name'));	
			$state_id=$this->input->post('state_id');	
            
            $status=false;
            
            if($state_id=='')
            {
            	$state_data=$this->CrudModel->select_data("select state_id from mas_state where state_status=? and state_name LIKE ?",array(1,$data['state_name']));
            	if(count($state_data)>0 && $state_data[0]['state_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_state',$data);
            }
            else
            {
            	$state_data=$this->CrudModel->select_data("select state_id from mas_state where state_status=? and state_name LIKE ? and state_id <> ?",array(1,$data['state_name'],$state_id));
            	if(count($state_data)>0 && $state_data[0]['state_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['state_id']=$state_id;
                	$status=$this->CrudModel->edit_record('mas_state',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'State Already Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $state_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $state_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function saveCity()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['city_entrydt']=date('Y-m-d H:i:s');
			$data['city_user']=$this->session->userdata['user_id'];
			$data['city_state']=$this->input->post('city_state');	
			$data['city_name']=trim($this->input->post('city_name'));	
			$city_id=$this->input->post('city_id');	
            
            $status=false;
            
            if($city_id=='')
            {
            	$city_data=$this->CrudModel->select_data("select city_id from mas_city where city_status=? and city_name LIKE ?",array(1,$data['city_name']));
            	if(count($city_data)>0 && $city_data[0]['city_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_city',$data);
            }
            else
            {
            	$city_data=$this->CrudModel->select_data("select city_id from mas_city where city_status=? and city_name LIKE ? and city_id <> ?",array(1,$data['city_name'],$city_id));
            	if(count($city_data)>0 && $city_data[0]['city_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['city_id']=$city_id;
                	$status=$this->CrudModel->edit_record('mas_city',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'City Exist With Same Name','type'=>'error','process'=>'3'));	
			elseif($status==true && $city_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $city_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function deleteCityState()
	{	
		if($this->ion_auth->logged_in())
		{
			$id=$_POST['id'];
			$type=$_POST['type'];
			$fetchdata=array();

			if($type==1) // 1 For Delete Department
			{
				$fetchdata=$this->CrudModel->select_data('select city_id from mas_city where city_state = ?',array($id));
				$table="mas_state";
				$condition['state_id']=$id;
				$msg="Record Deletion Failed !!! Some Cities Are Registered Under This State";
			}
			else
			{
				$fetchdata=$this->CrudModel->select_data('select c_id from mas_members where c_city = ?',array($id));
				$table="mas_city";
				$condition['city_id']=$id;
				$msg="Record Deletion Failed !!! Some Employees Are Registered Under This City";
			}
			
			if(count($fetchdata)>0 && !empty($fetchdata))
			{
				echo json_encode(array('title'=>'Delete Status','msg'=>$msg,'type'=>'error'));
			}
			else
			{
				if($this->CrudModel->delete_record($table,$condition)==true)
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Succesfully','type'=>'success'));	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}	
					
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function firm()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');
			$this->data['statedata']=$this->CrudModel->select_data("select * from mas_state where state_status= ? and state_country=? order by state_name",array(1,99));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			$this->load->view('masters/firm',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function saveFirm()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['f_entrydt']=date('Y-m-d H:i:s');
			$data['f_user']=$this->session->userdata['user_id'];
			$data['f_name']=trim($this->input->post('f_name'));	
			$data['f_contact']=trim($this->input->post('f_contact'));	
			$data['f_email']=trim($this->input->post('f_email'));	
			$data['f_gstin']=trim($this->input->post('f_gstin'));	
			$data['f_pan']=trim($this->input->post('f_pan'));	
			$data['f_bankacc']=trim($this->input->post('f_bankacc'));	
			$data['f_state']=trim($this->input->post('f_state'));	
			$data['f_address']=trim($this->input->post('f_address'));	
			$f_id=$this->input->post('f_id');	
            
            if(!empty($_FILES['f_logo']['name']))
			{				
				$path = './uploads/Firm_Logo';
				if(!is_dir($path))
				    mkdir($path);

				if(!$path)
				return;
					
				$config = array('upload_path'=>"./".$path."/",
				'allowed_types'=>"jpeg|jpg|png|gif|bmp|pdf");
				$this->load->library('upload',$config);
				$this->upload->do_upload('f_logo');
				$file_data = $this->upload->data();
				$data['f_logo'] =$path."/".$file_data['file_name'];
			} 


            $status=false;
            
            if($f_id=='')
            {
            	$firm=$this->CrudModel->select_data("select f_id from mas_firm where f_status=? and f_name LIKE ?",array(1,$data['f_name']));
            	if(count($firm)>0 && $firm[0]['f_id']!=null)
            	{
            		$this->session->set_flashdata('message','Firm Already Exist With Same Name');
					$this->session->set_flashdata('alert_type','alert-danger');
            	}
            	else
            	{
                	$status=$this->CrudModel->insert_data('mas_firm',$data);
                	if($status)
                	{
	                	$this->session->set_flashdata('message','Record Inserted Succesfully');
						$this->session->set_flashdata('alert_type','alert-info');
					}
					else
					{
						$this->session->set_flashdata('message','Process Failed');
						$this->session->set_flashdata('alert_type','alert-danger');
					}
            	}
            }
            else
            {
            	$firm=$this->CrudModel->select_data("select f_id from mas_firm where f_status=? and f_name LIKE ? and f_id <> ?",array(1,$data['f_name'],$f_id));
            	if(count($firm)>0 && $firm[0]['f_id']!=null)
            	{
            		$this->session->set_flashdata('message','Firm Already Exist With Same Name');
					$this->session->set_flashdata('alert_type','alert-danger');
            	}
            	else
            	{
            		$condition['f_id']=$f_id;
                	$status=$this->CrudModel->edit_record('mas_firm',$data,$condition);
                	if($status)
                	{
	                	$this->session->set_flashdata('message','Record Updated Succesfully');
						$this->session->set_flashdata('alert_type','alert-info');
					}
					else
					{
						$this->session->set_flashdata('message','Process Failed');
						$this->session->set_flashdata('alert_type','alert-danger');
					}
                }
            }

            redirect("MASTERS/firm");
		}
		else
		{
			redirect('auth/login');
		} 
	}

	public function firmData()
    {
        if($this->ion_auth->logged_in())
		{
            $f_id=$_REQUEST['f_id'];
            
            $cat=$this->CrudModel->select_data("select * from mas_firm mf left join mas_bankacc mba on mba.acc_id=mf.f_bankacc where f_status=? and f_id=? ",array(1,$f_id));
			
			echo json_encode($cat);
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function srchFirm()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();         
           
            if(isset($_REQUEST['srch_firm']) && $_REQUEST['srch_firm']!='')
            {                
                $qry_con .=" and f_name LIKE ?";
                array_push($qry_param,'%'.$_REQUEST['srch_firm'].'%'); 
            }
                    
            $category=$this->CrudModel->select_data("select mf.*,mb.bank_name,mba.acc_num,ms.state_name from mas_firm mf
            left join mas_bankacc mba on mba.acc_id = mf.f_bankacc 
            left join mas_bank mb on mb.bank_id = mba.acc_bankid
            left join mas_state ms on ms.state_id = mf.f_state
            where f_status=1 $qry_con order by f_name",$qry_param);

         	$sl=1; 
			if(count($category) < 1)
			{
				echo "<tr><td colspan='13' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
			foreach ($category as $key => $value) { ?>
			<tr id="<?php echo 'row_'.$value['f_id']; ?>">
				<td><?php echo $sl++; ?></td>
				<td><?php echo ucwords(strtolower($value['f_name'])); ?></td>
				<td><?php echo $value['f_contact']; ?></td>
				<td><?php echo $value['f_email']; ?></td>
				<td><?php echo strtoupper($value['f_gstin']); ?></td>
				<td><?php echo strtoupper($value['f_pan']); ?></td>
				<td><?php echo ucwords(strtolower($value['bank_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['acc_num'])); ?></td>
				<td><?php echo ucwords(strtolower($value['state_name'])); ?></td>
				<td><?php echo ucwords(strtolower($value['f_address'])); ?></td>
				<td class="cen">
					<?php if($value['f_logo']!=''){ ?>
					<a class='btn btn-success btn-xs' target='_blank' href="<?php echo base_url().$value['f_logo']; ?>"><i class='fa fa-eye'></i></a>
					<?php }else echo "<span style='color:red'>NA</span>"; ?>
				</td>
				<td>
					<center>					
						<button class="mb-xs mt-xs mr-xs btn btn-primary btn-xs" type="button" onclick="categoryData(<?php echo $value['f_id']; ?>)" title="Update"><icon class="fa fa-pencil"></icon></button>					
					</center>
				</td>
				<td>
					<center>
						<a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['f_id']; ?>');"  href="#delConfirm">
						<icon class="fa fa-trash-o"></icon> </a>
					</center>
				</td>
			</tr>
			<?php } } ?>

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

    public function delFirm()
	{	
		if($this->ion_auth->logged_in())
		{
			$f_id=$_POST['f_id'];
		
			$condition['f_id']=$f_id;

			if($this->CrudModel->delete_record('mas_firm',$condition)==true)
				echo json_encode(array('title'=>'Delete Status','msg'=>'Firm Deleted Successfully','type'=>'success'));	
			else
				echo json_encode(array('title'=>'Delete Status','msg'=>'Firm Deletion Failed','type'=>'error'));
					
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function saveGstType()
	{
		if($this->ion_auth->logged_in())
		{	
            $data['grt_entrydt']=date('Y-m-d H:i:s');
			$data['grt_user']=$this->session->userdata['user_id'];
			$data['grt_type']=trim($this->input->post('grt_type'));
			$grt_id=$this->input->post('grt_id');	
            
            $status=false;
            
            if($grt_id=='')
            {
            	$grttype=$this->CrudModel->select_data("select grt_id from mas_gstregtype where grt_status=? and grt_type LIKE ?",array(1,$data['grt_type']));
            	if(count($grttype)>0 && $grttype[0]['grt_id']!=null)
            		$status=0;
            	else
                	$status=$this->CrudModel->insert_data('mas_gstregtype',$data);
            }
            else
            {
            	$grttype=$this->CrudModel->select_data("select grt_id from mas_gstregtype where grt_status=? and grt_type LIKE ? and grt_id <> ?",array(1,$data['grt_type'],$grt_id));
            	if(count($grttype)>0 && $grttype[0]['grt_id']!=null)
            		$status=0;
            	else
            	{
            		$condition['grt_id']=$grt_id;
                	$status=$this->CrudModel->edit_record('mas_gstregtype',$data,$condition);
                }
            }
           
			if($status===0)
           		echo json_encode(array('title'=>'Status','msg'=>'This Registration Type Already Exist','type'=>'error','process'=>'3'));	
			elseif($status==true && $grt_id=='')				
				echo json_encode(array('title'=>'Status','msg'=>'Record Inserted Succesfully','type'=>'success','process'=>'1'));	
            elseif($status==true && $grt_id!='')
				echo json_encode(array('title'=>'Status','msg'=>'Record Updated Succesfully','type'=>'success','process'=>'2'));
			else
				echo json_encode(array('title'=>'Status','msg'=>'Process Failed','type'=>'error','process'=>'3'));	
		}
		else
		{
			redirect('auth/login');
		} 
	}

	//------------- End Of Class ------------------
}

?>