<?php
class Inventory extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');		
		$this->load->model('CrudModel');		
		$this->load->helper(array('url','language'));		
		$this->load->library(array('ion_auth', 'form_validation','pagination'));	
		$this->lang->load('auth');	
		$this->load->helper('file');
	}

	public function purchase()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			
			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));

			$this->load->view('inventory/purchase',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchPurchase()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
         
         	if(isset($_REQUEST['srch_vendor']) && $_REQUEST['srch_vendor']!='')
            {
                $qry_con .=" and inv_perticular  = ?";
                array_push($qry_param,$_REQUEST['srch_vendor']); 
            }
         	if(isset($_REQUEST['srch_invfor']) && $_REQUEST['srch_invfor']!='')
            {
                $qry_con .=" and inv_for  = ?";
                array_push($qry_param,$_REQUEST['srch_invfor']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and prod_name LIKE ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $inventory_data=$this->CrudModel->select_data("select inv_date,inv_for,inv_id,inv_docs,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as vendor,inv_billno,inv_gross,inv_disc,inv_gstamt,inv_roundoff
				from s_invoice s left join mas_members m on m.c_id=s.inv_perticular where inv_status=1 $qry_con order by inv_date desc",$qry_param);

            if(count($inventory_data) < 1)
			{
				echo "<tr><td colspan='12' class='no-data-found'>No Record Found</td></tr>";
			}
			else 
			{
            	$sl=1;
           		foreach($inventory_data as $value){ 
            	$balamt_data=$this->CrudModel->select_data("select sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor IN (1,2) group by acc_sourceid",array($value['inv_id']));

               	$bill_amt=$value['inv_gross']+$value['inv_gstamt']-$value['inv_disc']+$value['inv_roundoff'];
               	$paid_amt=$balamt_data[0]['dr_amt'];
               	$bal=$bill_amt-$paid_amt;
       		?>
            <tr id='row_<?php echo $value["inv_id"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td>  
                <td><?php echo ucwords(strtolower($value['vendor'])); ?></td> 
                <td><?php echo $bill_amt; ?></td>
                <td><?php echo $paid_amt; ?></td>                       
                <td><?php echo $bal; ?></td>                       
                
                <?php //if($this->ion_auth->is_admin()){ ?> 
                 <td class="h cen">
                 	<?php if($bal > 0) { ?>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs  btn-primary" onclick="billSattlement('<?php echo $value['inv_id']; ?>')" title="Click Settle Bill" data-toggle="modal" data-target="#addamt_modal">
                        <span class="fa fa-plus"></span>
                    </a>
                    <?php }else {echo "<span style='color:green;font-weight:bold;'>Sattled</span>";} ?>
                </td> 
                <td class="h">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs  btn-warning" onclick="showPaymentHistory('<?php echo $value['inv_id']; ?>')" title="View Payment History" data-toggle="modal" data-target="#payhist_modal">
                        <span class="fa fa-eye"></span>
                    </a>
                    </center>
                </td>
                <td class="h">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs btn-success" onclick="showInvoiceInfo('<?php echo $value['inv_id']; ?>','<?php echo $value['inv_for']; ?>')" title="View Invoice Details" data-toggle="modal" data-target="#invinfo_modal">
                        <span class="fa fa-eye"></span>
                    </a>
                    </center>
                </td> 
                   
                <?php 
                if($value['inv_docs']!='')
					echo "<td class='h'><center><a class='mb-xs mt-xs mr-xs btn btn-xs btn-info' target='_blank' title='View Uploaded File' href='".base_url().$value['inv_docs']."'><i class='fa fa-eye'></i></a></center></td>";
				else
					echo "<td></td>";   
				?>   
                <td class="h" hidden>
                	<center>
                    <a class="btn btn-info btn-xs" onclick="fetchEditData('<?php echo $value['inv_id']; ?>')" title="Update">
                        <span class="fa fa-pencil"></span>
                    </a>
                    </center>
                </td>          
                <td class="h">
                    <center>
                        <a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['inv_id']; ?>');"  href="#delConfirm">
                        <span class="fa fa-trash-o"></span></a>
                    </center>
                </td>                
                <?php //} ?>

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

	public function showInvoiceInfo()
    {	
		if($this->ion_auth->logged_in())
		{    
			$invfor=$_REQUEST['invfor'];
			$invid=$_REQUEST['invid'];

			if($invfor==='2')
			{
				$inv_info=$this->CrudModel->select_data("select p.prod_name,p.prod_unit,sl_qty as qty,sl_rpu as rpu,sl_disc as disc,sl_disctype as disctype,sl_gstper as gst,sl_gstinclusive inclusive,sl_expdt as expdt,(select inv_roundoff from s_invoice where inv_id =?) as roundoff,(select inv_transportcharge from s_invoice where inv_id =?) as inv_transportcharge
				from s_sale s left join mas_product p on p.prod_id=s.sl_prodid where sl_invid=? and sl_status=?",array($invid,$invid,$invid,1));
			}
			else
			{
				$inv_info=$this->CrudModel->select_data("select p.prod_name,p.prod_unit,stk_qty as qty,stk_rpu as rpu,stk_disc as disc,stk_disctype as disctype,stk_gstper as gst,stk_gstinclusive inclusive,stk_expdt as expdt,(select inv_roundoff from s_invoice where inv_id =?) as roundoff,(select inv_transportcharge from s_invoice where inv_id =?) as inv_transportcharge
				from s_stock s left join mas_product p on p.prod_id=s.stk_prodid where stk_invid=? and stk_status=?",array($invid,$invid,$invid,1));
			}

            if(count($inv_info) < 1)
			{
				echo "<tr><td colspan='8' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
            $sl=1;$finalamt=0;$roundoff=0;$tot_gst=0;$inv_transportcharge=0;
            foreach($inv_info as $value){ 
            	$gross=0;
            	$net=0;

            	$gross = round(($value['qty']*$value['rpu']),2);
              	if($value['disctype']==='1')
	       			$disc = round(($gross*$value['disc'])/100,2);
	       		else
	       			$disc=$value['disc'];

	       		if($value['inclusive']==1)
	       		{
	       			$gst=round($gross-(float)($gross*(100/(100+$value['gst']))),2);
	       			$net=$gross;
	       			$incl=" (inclusive)";
	       		}
	       		else
	       		{
	       			$gross=$gross-$disc;
	       			$gst=round(($gross*$value['gst'])/100,2);
	       			$net= $gross+$gst;
	       			$incl="";
	       		}       			

	       		$finalamt+=round($net,2);
	       		$roundoff=$value['roundoff'];
	       		$inv_transportcharge=$value['inv_transportcharge'];
       		?>
            <tr>
                <td><?php echo $sl++; ?></td>                
                <td><?php echo strtoupper($value['prod_name']); ?></td>  
                <td><?php echo strtoupper($value['prod_unit']); ?></td>  
                <td><?php echo $value['qty']; ?></td>
                <td><?php echo $value['rpu']; ?></td>
                <td><?php if($value['disctype']==='1')echo $value['disc'].'%';else echo $value['disc']; ?></td>
                <td><?php echo $value['gst'].'%'.$incl; ?></td>
                <td style="text-align:right"><?php echo round($net,2); ?></td>
            </tr> 
            <?php } } ?>
            <tr>
            	<th colspan="7">Sub Total</th>
            	<th style="text-align:right"><?php echo $finalamt; ?></th>
            </tr>
            <tr>
            	<th colspan="7">Tranportation Charge</th>
            	<th style="text-align:right"><?php echo $inv_transportcharge; ?></th>
            </tr>
            <tr>
            	<th colspan="7">Round Off</th>
            	<th style="text-align:right"><?php echo $roundoff; ?></th>
            </tr>
            <tr>
            	<th colspan="7">Net Amount</th>
            	<th style="text-align:right"><?php echo $finalamt+$roundoff+$inv_transportcharge; ?></th>
            </tr>
            <?php
        }
		else
		{
			redirect('auth/login');
		}
    }    

    public function addPurchaseBill()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$inv_id=(int)($this->uri->segment(3));

			$this->data['vendor_data']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));
			$this->data['inv_data']=$this->CrudModel->select_data("select * from s_invoice where inv_id=? ",array($inv_id));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			$this->data['prod_data']=$this->CrudModel->select_data("select prod_id,prod_name from mas_product where prod_status=1",array(1));

			$this->data['firm_data']=$this->CrudModel->select_data("select f_id,f_name from mas_firm where f_status=1",array(1));

			$this->load->view('inventory/add_purchasebill',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}	

	public function deleteInvoice()
	{	
		if($this->ion_auth->logged_in())
		{
			$inv_id=(int)($_POST['inv_id']);

			
			if($inv_id>0)
			{				
				$inv_data=$this->CrudModel->select_data('select inv_for from s_invoice where inv_id=?',array($inv_id));
				$edit_stock=array();
				if($inv_data[0]['inv_for']=='1')
				{
					$stock_data=$this->CrudModel->select_data('select stk_qty,stk_prodid,stk_id from s_stock where stk_invid=?',array($inv_id));					
					foreach ($stock_data as $key => $value) 
					{
						$exist_stock=$this->CrudModel->select_data("select prod_currstock,prod_openstock from mas_product where prod_id=?",array($value['stk_prodid']));
						$edit_stock[$value['stk_prodid']]=array('prod_currstock'=>$exist_stock[0]['prod_currstock']-$value['stk_qty']);
					}            		
				}
				else if($inv_data[0]['inv_for']=='2')
				{
					$sale_data=$this->CrudModel->select_data('select sl_qty,sl_prodid,sl_id from s_sale where sl_invid=?',array($inv_id));
					foreach ($sale_data as $key => $value) 
					{
						$exist_stock=$this->CrudModel->select_data("select prod_currstock,prod_openstock from mas_product where prod_id=?",array($value['sl_prodid']));
						$edit_stock[$value['sl_prodid']]=array('prod_currstock'=>$exist_stock[0]['prod_currstock']+$value['sl_qty']);
					}
				}
				

				$sl_con['sl_invid']=$inv_id;
				$stk_con['stk_invid']=$inv_id;
				$account_con['acc_sourceid']=$inv_id;
				$this->CrudModel->delete_record('s_stock',$stk_con);
				$this->CrudModel->delete_record('s_sale',$sl_con);
				$this->CrudModel->delete_record('account',$account_con);			

				$inv_con['inv_id']=$inv_id;
				if($this->CrudModel->delete_record('s_invoice',$inv_con))	
				{
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deleted Successfully','type'=>'success'));	

					foreach($edit_stock as $key => $value) 
					{						
	            		$condition['prod_id']=$key;
	               		$status=$this->CrudModel->edit_record('mas_product',$value,$condition);
					}					
				}	
				else
					echo json_encode(array('title'=>'Delete Status','msg'=>'Record Deletion Failed','type'=>'error'));
			}			
		}
		else
		{
			redirect('auth/login');
		}
	}

	public function savePurchaseBill()
	{		
		if($this->ion_auth->logged_in())
		{	
			$inv_data['inv_user']=$this->session->userdata['user_id'];
			$inv_data['inv_perticular']=$this->input->post('inv_perticular');
			$inv_data['inv_firmid']=$this->input->post('inv_firmid');
			$inv_data['inv_billno']=$this->input->post('inv_billno');
			$inv_data['inv_date']=date('Y-m-d',strtotime($this->input->post('inv_date')));
			$inv_data['inv_for']=1;
			$inv_data['inv_type']=2;
			$inv_data['inv_roundoff']=$this->input->post('inv_roundoff');
			$inv_data['inv_location']=$this->input->post('inv_location');
			$inv_data['inv_remark']=trim($this->input->post('inv_remark'));
			$inv_data['inv_entrydt']=date('Y-m-d H:i:s');

			if(!empty($_FILES['inv_docs']['name']))
			{				
				$path = './uploads/PurchaseInvoiceDocs';
				if(!is_dir($path))
				    mkdir($path);

				$path = $path.'/Inv_'.$this->input->post('inv_perticular');	
				
				if(!is_dir($path))
				    mkdir($path);

				if(!$path)
				return;
					
				$config = array('upload_path'=>"./".$path."/",
				'allowed_types'=>"jpeg|jpg|png|gif|bmp|pdf");
				$this->load->library('upload',$config);

				$this->upload->do_upload('inv_docs');
				$file_data = $this->upload->data();
				$inv_data['inv_docs'] =$path."/".$file_data['file_name'];
			}  

			$status=$this->CrudModel->insert_data('s_invoice',$inv_data);			

			if($status===true)
			{
				$rows=$this->input->post('prod_ids');
				$rows=explode(',',$rows);

				$last_id=$this->CrudModel->select_data("select (CASE WHEN max(inv_id) IS NULL THEN 0 ELSE max(inv_id) END) as lastid from s_invoice where 1=1",array())[0]['lastid'];

				$gross=0;
				$disc=0;
				$gstamt=0;
				$roundoff=0;
				foreach ($rows as $value) 
				{				
					$s_qty=(float)($this->input->post('stkqty_'.$value));
					$s_rate=$this->input->post('stkrate_'.$value);
					$s_disc=$this->input->post('stkdisc_'.$value);
					$s_disctype=$this->input->post('stkdisctype_'.$value);
					$s_gstper=$this->input->post('stkgst_'.$value);
					$gst_incl=$this->input->post('stkgstincl_'.$value);
					
					if($gst_incl!='')
						$gst_incl=implode(',',$gst_incl);

					if($gst_incl==null)
						$gst_incl=0;

					if($s_disc==null)
						$s_disc='';

					if($s_disctype==null)
						$s_disctype='';

					$s_discamt=0;
					$s_gst=0;

					$expdt=$this->input->post('stkexpdt_'.$value);
					if($expdt !=='')
						$expdt=date('Y-m-d',strtotime($expdt));

					$stock_data['stk_user']=$this->session->userdata['user_id'];
					$stock_data['stk_invid']=$last_id;
					$stock_data['stk_prodid']=$this->input->post('stkprod_'.$value);
					$stock_data['stk_qty']=$s_qty;
					$stock_data['stk_rpu']=$s_rate;
					$stock_data['stk_disc']=$s_disc;
					$stock_data['stk_disctype']=$s_disctype;
					$stock_data['stk_gstper']=$s_gstper;

					if($s_gstper>0)
						$stock_data['stk_gstinclusive']=$gst_incl;

					$stock_data['stk_mfdt']=$expdt;
					$stock_data['stk_expdt']=$expdt;
					$stock_data['stk_entrydt']=date('Y-m-d H:i:s');
					$this->CrudModel->insert_data('s_stock',$stock_data);

					$exist_stock=$this->CrudModel->select_data("select prod_currstock from mas_product where prod_id=?",array($this->input->post('stkprod_'.$value)));

            		$prod_data['prod_currstock']=$exist_stock[0]['prod_currstock']+$s_qty; 
            		$prod_con['prod_id']=$this->input->post('stkprod_'.$value);
	                $status=$this->CrudModel->edit_record('mas_product',$prod_data,$prod_con);

					$gross+=$s_qty*$s_rate;
					
					if($gst_incl!=1)
					{
						if($s_disctype==1)
							$s_discamt=(($s_qty*$s_rate)*$s_disc)/100;
						else
							$s_discamt=$s_disc;

						$s_gst=((($s_qty*$s_rate)-$s_discamt)*$s_gstper)/100;
					}

					$disc +=$s_discamt;
					$gstamt+=$s_gst;
				}

				$inv_editdata['inv_gross']=$gross;
				$inv_editdata['inv_disc']=$disc;
				$inv_editdata['inv_gstamt']=$gstamt;
				$edit_con['inv_id']=$last_id;

				$this->CrudModel->edit_record('s_invoice',$inv_editdata,$edit_con);

				$acc_cr['acc_user']=$this->session->userdata['user_id'];
				$acc_cr['acc_cid']=$this->input->post('inv_perticular');
				$acc_cr['acc_voucher']=1;
				$acc_cr['acc_vochfor']=1;
				$acc_cr['acc_sourceid']=$last_id;
				$acc_cr['acc_trantype']=2; // for credit
				$acc_cr['acc_trandt']=date('Y-m-d',strtotime($this->input->post('inv_date')));
				$acc_cr['acc_ldgid']=1; // category id 1 For Purchase
				$acc_cr['acc_amt']=$gross-$disc+$gstamt+$this->input->post('inv_roundoff');
				$acc_cr['acc_entrydt']=date('Y-m-d H:i:s');
				$this->CrudModel->insert_data('account',$acc_cr);	

			}

			$this->session->set_flashdata('message','Record Saved Successfully!!!');
            $this->session->set_flashdata('alert_type','alert-success');
			
			redirect('INVENTORY/purchase');
		}	
		else
		{
			redirect('auth/login');
		} 
	}

	public function sale()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			
			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));

			$this->load->view('inventory/sale',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchSale()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
         
         	if(isset($_REQUEST['srch_memtype']) && $_REQUEST['srch_memtype']!='')
            {
            	if($_REQUEST['srch_memtype']>4)
            	{
            		$memtype=$_REQUEST['srch_memtype']-1;
            		$qry_con .=" and inv_type  = ?";
            	}
            	else
            	{
            		$memtype=$_REQUEST['srch_memtype'];
            		$qry_con .=" and mm.c_type  = ?";
            	}                
                array_push($qry_param,$memtype); 
            }
         	if(isset($_REQUEST['srch_member']) && $_REQUEST['srch_member']!='')
            {
                $qry_con .=" and inv_perticular  = ?";
                array_push($qry_param,$_REQUEST['srch_member']); 
            }

            if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {
                $qry_con .=" and inv_paidstatus = ?";
                array_push($qry_param,$_REQUEST['srch_type']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and prod_name LIKE ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $inventory_data=$this->CrudModel->select_data("select inv_date,inv_type,inv_perticular,inv_for,inv_id,inv_billno,inv_gross,inv_disc,inv_gstamt,inv_roundoff,inv_transportcharge,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as mem_name
				from s_invoice si 
				left join mas_members mm on mm.c_id=si.inv_perticular
				where inv_status=1 and inv_for=2 $qry_con order by inv_date desc",$qry_param);

            if(count($inventory_data) < 1)
			{
				echo "<tr><td colspan='13' class='no-data-found'>No Record Found</td></tr>";
			}
			else 
			{
            $sl=1;
            foreach($inventory_data as $value)
            { 
            	if($value['inv_type']==2)
            		$acc_vochfor='1,2,5';
            	else
            		$acc_vochfor=$value['inv_type'];

                $balamt_data=$this->CrudModel->select_data("select sum(case when acc_trantype =2 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor IN ($acc_vochfor) group by acc_sourceid",array($value['inv_id']));

               	if($value['inv_type']=='3' || $value['inv_type']=='4')
               		$cus_name=$this->CommonModel->vehicleProject($value['inv_perticular'],$value['inv_type']);
               	else
               		$cus_name=$value['mem_name'];

               $bill_amt=$value['inv_gross']+$value['inv_gstamt']-$value['inv_disc']+$value['inv_roundoff']+$value['inv_transportcharge'];
               $paid_amt=$balamt_data[0]['dr_amt'];
               $bal=$bill_amt-$paid_amt;
       		?>
            <tr id='row_<?php echo $value["inv_id"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td> 
                <td><?php echo ucwords(strtolower($cus_name)); ?></td> 
                <td><?php echo $bill_amt; ?></td>
                <td><?php echo $paid_amt; ?></td>                       
                <td><?php echo $bal; ?></td>                       
                
                <?php //if($this->ion_auth->is_admin()){ ?> 
                 <td class="h cen">
                 	<?php if($bal > 0) { ?>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs  btn-primary" onclick="billSattlement('<?php echo $value['inv_id']; ?>')" title="Click Settle Bill" data-toggle="modal" data-target="#addamt_modal">
                        <span class="fa fa-plus"></span>
                    </a>
                    <?php }else {echo "<span style='color:green;font-weight:bold;'>Sattled</span>";} ?>
                </td> 
                <td class="h">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs  btn-warning" onclick="showPaymentHistory('<?php echo $value['inv_id']; ?>')" title="View Payment History" data-toggle="modal" data-target="#payhist_modal">
                        <span class="fa fa-eye"></span>
                    </a>
                    </center>
                </td>
                <td class="h">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs btn-success" onclick="showInvoiceInfo('<?php echo $value['inv_id']; ?>','<?php echo $value['inv_for']; ?>')" title="View Invoice Details" data-toggle="modal" data-target="#invinfo_modal">
                        <span class="fa fa-eye"></span>
                    </a>
                    </center>
                </td>    
                <td class="h" hidden>
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs btn-info" onclick="fetchEditData('<?php echo $value['inv_id']; ?>')" title="Update">
                        <span class="fa fa-pencil"></span>
                    </a>
                    </center>
                </td>  
                <td class="cen">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" href="<?php echo base_url().'REPORTS/salesBill'.'/'.$value['inv_id']; ?>" target="_blank" title="Update">
                        <span class="fa fa-print"></span>
                    </a>
                    </center>
                </td>  
                <td class="cen">
                	<center>
                    <a class="mb-xs mt-xs mr-xs btn btn-xs btn-warning" href="<?php echo base_url().'REPORTS/salesBillGst'.'/'.$value['inv_id']; ?>" target="_blank" title="Update">
                        <span class="fa fa-print"></span>
                    </a>
                    </center>
                </td>          
                <td class="h">
                    <center>
                        <a class="mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic" title="Delete"  onclick="$('#del_id').val('<?php echo $value['inv_id']; ?>');"  href="#delConfirm">
                        <span class="fa fa-trash-o"></span></a>
                    </center>
                </td>                
                <?php //} ?>

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

    public function addSalesBill()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			$inv_id=(int)($this->uri->segment(3));

			$this->data['vendor_data']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));
			$this->data['inv_data']=$this->CrudModel->select_data("select * from s_invoice where inv_id=? ",array($inv_id));
			$this->data['bank_data']=$this->CrudModel->select_data("select * from mas_bank where bank_status=1",array(1));
			$this->data['prod_data']=$this->CrudModel->select_data("select prod_id,prod_name from mas_product where prod_status=1",array(1));

			$this->data['firm_data']=$this->CrudModel->select_data("select f_id,f_name from mas_firm where f_status=1",array(1));

			$this->load->view('inventory/add_salesbill',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}	

	public function saveSalesBill()
	{		
		if($this->ion_auth->logged_in())
		{	
			$inv_data['inv_user']=$this->session->userdata['user_id'];
			$inv_data['inv_perticular']=$this->input->post('inv_perticular');
			$inv_data['inv_firmid']=$this->input->post('inv_firmid');
			$inv_data['inv_date']=date('Y-m-d',strtotime($this->input->post('inv_date')));
			$inv_data['inv_for']=2;
			$inv_transportcharge=$this->input->post('inv_transportcharge');
			$inv_data['inv_transportcharge']=$inv_transportcharge;
			$invtype=2;

			if($this->input->post('member_type')>4)
			{
				$invtype=$this->input->post('member_type')-1;
			}
			$inv_data['inv_type']=$invtype;
			$inv_data['inv_roundoff']=$this->input->post('inv_roundoff');
			$inv_data['inv_remark']=trim($this->input->post('inv_remark'));
			$inv_data['inv_location']=$this->input->post('inv_location');
			$inv_data['inv_entrydt']=date('Y-m-d H:i:s');

			$last_invid=$this->CrudModel->select_data("select max(inv_billno) as last_invid from s_invoice where inv_for=?",array(2));

			if(count($last_invid)>0 && $last_invid[0]['last_invid']!=null)
				$inv_data['inv_billno']=(int)($last_invid[0]['last_invid'])+1;
			else
				$inv_data['inv_billno']=1;
			

			$status=$this->CrudModel->insert_data('s_invoice',$inv_data);			

			if($status===true)
			{
				$rows=$this->input->post('prod_ids');
				$rows=explode(',',$rows);

				$last_id=$this->CrudModel->select_data("select (CASE WHEN max(inv_id) IS NULL THEN 0 ELSE max(inv_id) END) as lastid from s_invoice where 1=1",array())[0]['lastid'];

				$gross=0;
				$disc=0;
				$gstamt=0;
				$roundoff=0;
				foreach ($rows as $value) 
				{				
					$s_qty=$this->input->post('stkqty_'.$value);
					$s_rate=$this->input->post('stkrate_'.$value);
					$s_disc=$this->input->post('stkdisc_'.$value);
					$s_disctype=$this->input->post('stkdisctype_'.$value);
					$s_gstper=$this->input->post('stkgst_'.$value);
					$gst_incl=$this->input->post('stkgstincl_'.$value);

					if($gst_incl!='')
						$gst_incl=implode(',',$gst_incl);

					if($gst_incl==null)
						$gst_incl=0;


					if($s_disc==null)
						$s_disc='';

					if($s_disctype==null)
						$s_disctype='';


					$s_discamt=0;
					$s_gst=0;

					$expdt=$this->input->post('stkexpdt_'.$value);
					if($expdt !=='')
					{
						$expdt=date('Y-m-d',strtotime($expdt));
					}

					$stock_data['sl_user']=$this->session->userdata['user_id'];
					$stock_data['sl_invid']=$last_id;
					$stock_data['sl_prodid']=$this->input->post('stkprod_'.$value);
					
					if($s_gstper>0)
						$stock_data['sl_gstinclusive']=$gst_incl;					
					
					$stock_data['sl_qty']=$s_qty;
					$stock_data['sl_rpu']=$s_rate;
					$stock_data['sl_disc']=$s_disc;
					$stock_data['sl_disctype']=$s_disctype;
					$stock_data['sl_gstper']=$s_gstper;
					$stock_data['sl_mfdt']=$expdt;
					$stock_data['sl_expdt']=$expdt;
					$stock_data['sl_entrydt']=date('Y-m-d H:i:s');
					$this->CrudModel->insert_data('s_sale',$stock_data);

					$exist_stock=$this->CrudModel->select_data("select prod_currstock from mas_product where prod_id=?",array($this->input->post('stkprod_'.$value)));

            		$prod_data['prod_currstock']=$exist_stock[0]['prod_currstock']-$s_qty; 
            		$prod_con['prod_id']=$this->input->post('stkprod_'.$value);
	                $status=$this->CrudModel->edit_record('mas_product',$prod_data,$prod_con);

					$gross+=$s_qty*$s_rate;
					
					if($gst_incl!=1)
					{
						if($s_disctype==1)
							$s_discamt=(($s_qty*$s_rate)*$s_disc)/100;
						else
							$s_discamt=$s_disc;

						$s_gst=((($s_qty*$s_rate)-$s_discamt)*$s_gstper)/100;
					}

					$disc +=$s_discamt;
					$gstamt+=$s_gst;
				}

				$inv_editdata['inv_gross']=$gross;
				$inv_editdata['inv_disc']=$disc;
				$inv_editdata['inv_gstamt']=$gstamt;
				$edit_con['inv_id']=$last_id;
				$edit_con['inv_id']=$last_id;

				$this->CrudModel->edit_record('s_invoice',$inv_editdata,$edit_con);

				$acc_cr['acc_user']=$this->session->userdata['user_id'];
				$acc_cr['acc_cid']=$this->input->post('inv_perticular');
				$acc_cr['acc_voucher']=2;
				$acc_cr['acc_vochfor']=$invtype;
				$acc_cr['acc_sourceid']=$last_id;
				$acc_cr['acc_trantype']=1; // for credit
				$acc_cr['acc_trandt']=date('Y-m-d',strtotime($this->input->post('inv_date')));
				$acc_cr['acc_ldgid']=2; // category id 1 For Sales
				$acc_cr['acc_amt']=$gross-$disc+$gstamt+$this->input->post('inv_roundoff')+$inv_transportcharge;
				$acc_cr['acc_entrydt']=date('Y-m-d H:i:s');
				$this->CrudModel->insert_data('account',$acc_cr);				
			}
			
		 	$this->session->set_flashdata('message','Record Saved Successfully!!!');
            $this->session->set_flashdata('alert_type','alert-success');
			redirect('INVENTORY/sale');
		}	
		else
		{
			redirect('auth/login');
		} 
	}

	public function getAccNo()
    {
    	if($this->ion_auth->logged_in())
    	{

    		$accdata=$this->CrudModel->select_data("select acc_id,acc_num from mas_bankacc where acc_bankid=? and  acc_status=? order by acc_num",array($_REQUEST['bankid'],1));
    		
    		echo "<option value=''>Select A/C No.</option>";
    		foreach ($accdata as $key => $value) 
    		{
    			echo "<option value='".$value['acc_id']."'>".ucwords(strtolower($value['acc_num']))."</option>";
    		}
    	}
		else
		{
			redirect('auth/login');
		}
    }

	public function getProdData()
	{
		if($this->ion_auth->logged_in())
		{
			$prodid=(int)($_REQUEST['prodid']);
			$invtype=(int)($_REQUEST['invtype']);
			$prod_data=$this->CrudModel->select_data("select prod_unit,prod_isgst,prod_purgstincl,prod_gstrate,prod_purrate,prod_salerate,prod_purgstincl  from mas_product where prod_id=? ",array($prodid));
			
			if(count($prod_data) > 0 && $prod_data[0]['prod_unit']!==null)
				$unit=$prod_data[0]['prod_unit'];
			else
				$unit='<span style="color:red">NA</span>';

			if($invtype===1)
				$rate=$prod_data[0]['prod_purrate'];
			else
				$rate=$prod_data[0]['prod_salerate'];

			$gst_rate='';

			/*if($prod_data[0]['prod_isgst']==='1')
			{
				if($prod_data[0]['prod_purgstincl']==='1')
					$rate=round(($rate*100)/(100+$prod_data[0]['prod_gstrate']),2);				
			}*/
			echo json_encode(array('unit'=>$unit,'rate'=>$rate,'gst'=>$prod_data[0]['prod_gstrate'],'gst_inclusive'=>$prod_data[0]['prod_purgstincl']));
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function getProdAvail()
	{
		if($this->ion_auth->logged_in())
		{
			$prodid=(int)($_REQUEST['prodid']);
			$prod_data=$this->CrudModel->select_data("select prod_currstock from mas_product where prod_id=?",array($prodid));
			
			if(count($prod_data) > 0 && $prod_data[0]['prod_currstock']!==null)
				$stock=$prod_data[0]['prod_currstock'];
			else
				$stock=0;

			echo json_encode(array('stock'=>$stock));
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
	
	
	public function memWiseInvoice()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');				
			
			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

			$this->load->view('inventory/memwise_invoice',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}

	public function srchMemWiseInv()
    {	
		if($this->ion_auth->logged_in())
		{
            $qry_con="";
            $qry_param=array();
            $qry_con1="";
            $qry_param1=array();
            $qry_cons1="";
         
         	if(isset($_REQUEST['srch_perticular']) && $_REQUEST['srch_perticular']!='')
            {
                $qry_con .=" and inv_perticular  = ?";
                array_push($qry_param,$_REQUEST['srch_perticular']); 
            }

         	if(isset($_REQUEST['srch_invtype']) && $_REQUEST['srch_invtype']!='')
            {            	
				if($this->input->post('member_type')>4)					
                	$qry_con .=" and inv_type  = ?";
               	else
               		$qry_con .=" and c_type  = ?";
                array_push($qry_param,$_REQUEST['srch_invtype']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and prod_name LIKE ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate);

                $qry_cons1 .=" and STR_TO_DATE(s1.inv_date,'%Y-%m-%d') between '".$srch_fdate."' and '".$srch_tdate."'";

                $qry_con1 .=" and STR_TO_DATE(acc_trandt ,'%Y-%m-%d') between ? and ?";
                array_push($qry_param1,$srch_fdate,$srch_tdate); 
            }


            $inv_data=$this->CrudModel->select_data("select inv_type,c_type,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as member,inv_perticular,sum(case when inv_for=1 then (inv_gross-(inv_disc+inv_billdisc)+inv_gstamt+inv_roundoff) else 0 end) as purchaseamt,sum(case when inv_for=2 then (inv_gross-(inv_disc+inv_billdisc)+inv_gstamt+inv_roundoff) else 0 end) as salesamt,
            	(select count(*) from s_invoice s1 where s1.inv_status=1 and s1.inv_for=1 and s1.inv_perticular=s.inv_perticular $qry_cons1) as tot_pur,
            	(select count(*) from s_invoice s1 where s1.inv_status=1 and s1.inv_for=2 and s1.inv_perticular=s.inv_perticular  $qry_cons1) as tot_sales
				from s_invoice s left join mas_members m on m.c_id=s.inv_perticular where inv_status=1 $qry_con  group by inv_type,inv_perticular order by member",$qry_param);

            if(count($inv_data) < 1)
			{
				echo "<tr><td colspan='9' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
            $sl=1;
            foreach($inv_data as $value){ 
               $balamt=0;
               $baldata=$this->CrudModel->select_data("select acc_trantype,sum(case when acc_amt > 0 then acc_amt else 0 end) as bal from account where acc_status=1 and acc_cid='".$value['inv_perticular']."' $qry_con1 group by acc_trantype order by acc_trantype",$qry_param1);

               foreach ($baldata as $bal) {
               	 if($bal['acc_trantype']==='1')
               	 	$balamt-=$bal['bal'];
               	 else 
               	 	$balamt+=$bal['bal'];
               }

               	if($value['inv_type']==5)
               		$type="Project";
               	else if($value['c_type']==1)
                    $type='Customer';
                elseif($value['c_type']==2)
                    $type='Vendor';
                elseif($value['c_type']==3)
                    $type='Employee'; 
               	else
               		$type="Other";
       		?>
            <tr id='row_<?php echo $value["inv_perticular"]; ?>'>
                <td><?php echo $sl++; ?></td>
                <td><?php echo $type; ?></td>
                <td><?php echo ucwords(strtolower($value['member'])); ?></td>
                <td>
                	<a style="cursor:pointer;" onclick="showMemberInfo(<?php echo $value['inv_perticular']; ?>,<?php echo $value['inv_type'] ?>,'1')" title="View Invoice Details" data-toggle="modal" data-target="#invinfo_modal">
                		<?php echo $value['tot_pur']; ?>
                	</a>                	
                </td>  
                <td>
                	<a style="cursor:pointer;" onclick="showMemberInfo(<?php echo $value['inv_perticular']; ?>,<?php echo $value['inv_type'] ?>,'2')" title="View Invoice Details" data-toggle="modal" data-target="#invinfo_modal">
                		<?php echo $value['tot_sales']; ?>
                	</a>
            	</td>  
                <td><?php echo $value['purchaseamt']; ?></td>  
                <td><?php echo $value['salesamt']; ?></td>  
                <td><?php if($balamt<0)echo $balamt.' (Dr)';else if($balamt>0)echo $balamt.' (Cr)';else echo 0; ?></td>       
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

    public function showMemberInfo()
    {	
		if($this->ion_auth->logged_in())
		{    
			$member=$_REQUEST['member'];
			$type=$_REQUEST['type'];
			$inv_for=$_REQUEST['inv_for'];

			$qry_con="";
			$qry_param=array();
			if($member!="")
			{
				$qry_con .=" and inv_perticular = ?";
				array_push($qry_param,$member);
			}
			if($inv_for!="")
			{
				$qry_con .=" and inv_for = ?";
				array_push($qry_param,$inv_for);
			}
			if($type > 4)
			{
				$type=$type-1;
				$qry_con .=" and inv_type = ?";
				array_push($qry_param,$type);
			}

			if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate);                
            }


			$inv_info=$this->CrudModel->select_data("select inv_date,inv_gross,inv_gstamt,(inv_disc+inv_billdisc) as inv_disc,inv_roundoff,inv_remark,inv_docs, inv_billno,inv_perticular,replace(CONCAT_WS(' ',c_firstname,c_middlename,c_lastname),' ',' ') as member
				from s_invoice s left join mas_members m on m.c_id=s.inv_perticular where inv_status=1 $qry_con",$qry_param);
			
			$sl=1;$inv_gross=0;$roundoff=0;$tot_gst=0;$finalnet=0;$tot_disc=0;
            if(count($inv_info) < 1)
			{
				echo "<tr><td colspan='10' class='no-data-found'>No Record Found</td></tr>";
			}
			else {            
            foreach($inv_info as $value){ 
            	
	       		$inv_gross+=$value['inv_gross'];   
	       		$tot_disc+=$value['inv_disc'];   
	       		$tot_gst+=$value['inv_gstamt'];  	       		
	       		$roundoff+=$value['inv_roundoff'];
	       		$net=round($value['inv_gross']+$value['inv_gstamt']-$value['inv_disc']+$value['inv_roundoff'],2);
	       		$finalnet+=$net;

	       		if($type==4)
	       			$member=$this->CommonModel->vehicleProject($value['inv_perticular'],$type);
	       		else
	       			$member=$value['member'];
       		?>
            <tr>
                <td><?php echo $sl++; ?></td>                
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>  
                <td><?php echo strtoupper($value['inv_billno']); ?></td>  
                <td><?php echo $member; ?></td>
                <td style="text-align:right"><?php echo $value['inv_gross']; ?></td>
                <td style="text-align:right"><?php echo $value['inv_gstamt']; ?></td>
                <td style="text-align:right"><?php echo $value['inv_disc']; ?></td>
                <td style="text-align:right"><?php echo $value['inv_roundoff']; ?></td>
                <td style="text-align:right"><?php echo $net; ?></td>
                <td><?php echo $value['inv_remark']; ?></td>                
            </tr> 
            <?php } } ?>
            <tr>
            	<th colspan="4">Total</th>
            	<th style="text-align:right"><?php echo $inv_gross; ?></th>
            	<th style="text-align:right"><?php echo $tot_gst; ?></th>
            	<th style="text-align:right"><?php echo $tot_disc; ?></th>
            	<th style="text-align:right"><?php echo $roundoff; ?></th>
            	<th style="text-align:right"><?php echo $finalnet; ?></th>
            	<th style="text-align:right">&nbsp;</th>
            </tr>
            <?php
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
    		$inv_id=(int)($this->input->post('inv_id'));

    		$inv_type=$this->CrudModel->select_data("select inv_type from s_invoice where inv_id=?",array($inv_id));

    		if($inv_type[0]['inv_type']==2)
    			$acc_vochfor='1,2,5';    		
        	else
        		$acc_vochfor=$inv_type[0]['inv_type'];

    		$balamt_data=$this->CrudModel->select_data("select acc_sourceid,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor IN ($acc_vochfor) group by acc_sourceid",array($inv_id));

    		$balamt=0;
    		if(count($balamt_data)>0 && $balamt_data[0]['acc_sourceid']!=null)
    		{
				$balamt = abs($balamt_data[0]['cr_amt']- $balamt_data[0]['dr_amt']);
    		}
    		echo json_encode(array('bal_amt'=>$balamt));
    	}
    }

    public function sattleBill()
    {
    	if($this->ion_auth->logged_in())
    	{
    		

    		$inv_id=(int)($this->input->post('inv_id'));
    		$inv_data=$this->CrudModel->select_data("select * from s_invoice where inv_id = ? ",array($inv_id));
    		$bill_amt=$inv_data[0]['inv_gross']+$inv_data[0]['inv_gstamt']-$inv_data[0]['inv_disc']+$inv_data[0]['inv_roundoff'];

    		$balamt_data=$this->CrudModel->select_data("select acc_sourceid,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt from account where acc_sourceid=? and acc_vochfor IN (1,2) group by acc_sourceid",array($inv_id));
    		$balamt=0;

    		if(count($balamt_data)>0 && $balamt_data[0]['acc_sourceid']!=null)
    		{
				$cr_amt = $balamt_data[0]['cr_amt'];
				$dr_amt=$balamt_data[0]['dr_amt'];
    		}

    		if($inv_data[0]['inv_for']==1)
    			$sattled_amt=$dr_amt;
    		else    			
    			$sattled_amt=$cr_amt;

    		$acc_dr['acc_user']=$this->session->userdata['user_id'];
			$acc_dr['acc_cid']=$inv_data[0]['inv_perticular'];
			$acc_dr['acc_voucher']=$inv_data[0]['inv_for']+2; // 3 for payment 4 for receive

			if($inv_data[0]['inv_type']>2)
				$acc_dr['acc_vochfor']=$inv_data[0]['inv_type'];
			else
				$acc_dr['acc_vochfor']=$inv_data[0]['inv_for'];

			$acc_dr['acc_sourceid']=$inv_id;
			$acc_dr['acc_trantype']=$inv_data[0]['inv_for'];// for 1 debit/ 2 Credit	
			$acc_dr['acc_trandt']=date('Y-m-d',strtotime($this->input->post('sattle_data')));
			$acc_dr['acc_ldgid']=$inv_data[0]['inv_for']; // category id 2 For Sales ,1 purchase
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

			if(($sattled_amt + $this->input->post('settle_amt')) == $bill_amt)
				$data_inv['inv_paidstatus']=2;
			else
				$data_inv['inv_paidstatus']=1;

			$inv_con['inv_id']=$inv_id;

			$this->CrudModel->edit_record('s_invoice',$data_inv,$inv_con);


			if($inv_data[0]['inv_for']==1)
    			redirect("INVENTORY/purchase");
    		else    			
    			redirect("INVENTORY/sale");
    	}
    	else
    	{
    		redirect("auth/login");
    	}
    }

    public function showPaymentHistory()
    {
    	if($this->ion_auth->logged_in())
    	{
    		$inv_id=$_POST['inv_id'];
    		$inv_data=$this->CrudModel->select_data("select inv_for from s_invoice where inv_id = ? ",array($inv_id));

    		$acc_vochfor=null;
    		if(count($inv_data) > 0 && $inv_data[0]['inv_for']!=null)
    		{
    			if($inv_data[0]['inv_for']==1)
    				$acc_vochfor=1;
    			else if($inv_data[0]['inv_for']==2)
    				$acc_vochfor=2;
    		}

    		$payhist_data=$this->CrudModel->select_data("select a.acc_id,acc_amt,a.acc_trandt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_entrydt,a.acc_remark,mb.bank_name as bank,mba.acc_num from account a
    			left join mas_bankacc mba on mba.acc_id = a.acc_bankacc_id
    			left join mas_bank mb on mb.bank_id = mba.acc_bankid
    			where acc_sourceid=? and acc_trantype=? order by a.acc_entrydt",array($inv_id,$acc_vochfor));
    		
    		$sl=1;$tot_amt=0;
			
			if(count($payhist_data)===0 || $payhist_data[0]['acc_id']===null)
			{
				echo '<tr><td colspan="12" class="no-data-found">Record Not Found</td></tr>';
				die();
			}
			foreach ($payhist_data as $key => $value) 
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
				
				if($value['acc_chqdt']!='0000-00-00')
					$chq_dt=date('d-m-Y',strtotime($value['acc_chqdt']));
				else
					$chq_dt="";

				$amt=0;
				$amt=$value['acc_amt'];
				$tot_amt += $amt;
				echo "<tr id='row_".$value['acc_id']."'>";			
				echo "<td>". $sl++ ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
				echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
				echo "<td>". $amt ."</td>";
				echo "<td>". $mode ."</td>";
				echo "<td>". strtoupper($value['bank'])."</td>";
				echo "<td>". $value['acc_num']."</td>";
				echo "<td>". $value['acc_chqno'] ."</td>";
				echo "<td>". $chq_dt."</td>";
				echo "<td>". $value['acc_onlineid']."</td>";
				echo "<td>". ucwords(strtolower($value['acc_remark'])) ."</td>";
				echo "<td class='cen'><input type='checkbox' name='chk_sel[]' value='".$value['acc_id']."' /></td>";
				echo "</tr>";		
			}
			echo "<tr>";
			echo "<td colspan='3'><strong>Total Amount : </strong></td>";
			echo "<td colspan='9'><strong>". $tot_amt ."</strong></td>";
			echo "</tr>";

    	}
    	else
    	{
    		redirect("auth/login");
    	}
    }

    //------ END Of Class------
}