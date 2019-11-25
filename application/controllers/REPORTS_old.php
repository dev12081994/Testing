<?php

class Reports extends CI_Controller
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

	public function purchasedProduct()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');		

			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

			$this->data['product_list']=$this->CrudModel->select_data("select prod_id,prod_name from mas_product where prod_status=? ",array(1));

			$this->load->view('reports/purchased_product',$this->data);
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
         	if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {
                $qry_con .=" and inv_paidstatus  = ?";
                array_push($qry_param,$_REQUEST['srch_type']); 
            }
         	if(isset($_REQUEST['srch_prod']) && $_REQUEST['srch_prod']!='')
            {
                $qry_con .=" and stk_prodid  = ?";
                array_push($qry_param,$_REQUEST['srch_prod']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and inv_billno = ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $inventory_data=$this->CrudModel->select_data("select inv_paidstatus,inv_date,inv_for,inv_id,inv_docs,inv_type,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as vendor,inv_billno,stk_qty,stk_gstinclusive as inclusive,stk_rpu,stk_disc,stk_disctype,stk_gstper,stk_mfdt,stk_expdt,prod_name,prod_unit from s_stock st
				left join s_invoice s on s.inv_id=st.stk_invid 
				left join mas_members m on m.c_id=s.inv_perticular 
				left join mas_product pm on pm.prod_id=st.stk_prodid
				where stk_status=1 and inv_status=1 $qry_con order by inv_date desc",$qry_param);

            $sl=1;$tot_amt=0;$tot_disc=0;$tot_gst=0;$tot_net=0;
            if(count($inventory_data) < 1)
			{
				echo "<tr><td colspan='12' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
            
            foreach($inventory_data as $value){ 
               	$amt=$value['stk_rpu']*$value['stk_qty'];

               	if($value['stk_disctype']==1)
               		$disc=round(($amt*$value['stk_disc'])/100,2);
               	else
               		$disc=$value['stk_disc'];

                if($value['inclusive']==1)
                {
                    $gst=round($amt-(float)($amt*(100/(100+$value['stk_gstper']))),2);
                    $net=$amt;
                    $incl=" (inclusive)";
                }
                else
                {
                    $amt=$amt-$disc;
                    $gst=round(($amt*$value['stk_gstper'])/100,2);
                    $net= $amt+$gst;
                    $incl="";
                } 
               	$tot_amt+=$amt;
               	$tot_disc+=$disc;
               	$tot_gst+=$gst;
               	$tot_net+=$net;
       		?>
            <tr>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td>                  
                <td><?php echo ucwords(strtolower($value['vendor'])); ?></td>                     
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>                     
                <td><?php echo $value['stk_qty'].'&nbsp;'.$value['prod_unit']; ?></td>                     
                <td class="text-right"><?php echo $value['stk_rpu']; ?></td>                     
                <td class="text-right"><?php echo $amt; ?></td>                     
                <td class="text-right"><?php echo $disc; ?></td>                     
                <td class="text-right"><?php echo $gst; ?></td>                     
                <td class="text-right"><?php echo $net; ?></td>                     
                <td class="cen"><?php if($value['inv_paidstatus']!=2)echo '<span style="color:red">Credit</span>';else echo '<span style="color:green">Paid</span>'; ?></td>  
            </tr> 
            <?php } } ?>
            <tr>
            	<th colspan="7">Total : </th>
            	<th colspan="1" class="text-right"><?php echo $tot_amt; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_disc; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_gst; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_net; ?></th>
            	<th colspan="1">&nbsp;</th>
            </tr>
            <?php  
        }
		else
		{
			redirect('auth/login');
		}
    } 

    public function exportPurchasedProduct() 
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
         	if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {
                $qry_con .=" and inv_paidstatus  = ?";
                array_push($qry_param,$_REQUEST['srch_type']); 
            }
         	if(isset($_REQUEST['srch_prod']) && $_REQUEST['srch_prod']!='')
            {
                $qry_con .=" and stk_prodid  = ?";
                array_push($qry_param,$_REQUEST['srch_prod']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and inv_billno = ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $data['inventory_data']=$this->CrudModel->select_data("select inv_paidstatus,inv_date,inv_for,inv_id,inv_docs,inv_type,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as vendor,inv_billno,stk_qty,stk_gstinclusive as inclusive,stk_rpu,stk_disc,stk_disctype,stk_gstper,stk_mfdt,stk_expdt,prod_name,prod_unit from s_stock st
                left join s_invoice s on s.inv_id=st.stk_invid 
                left join mas_members m on m.c_id=s.inv_perticular 
                left join mas_product pm on pm.prod_id=st.stk_prodid
                where stk_status=1 and inv_status=1 $qry_con order by inv_date desc",$qry_param);
            $data['exprt_fdate']=$this->input->post('srch_fdate');
			$data['exprt_tdate']=$this->input->post('srch_tdate');
			//place where the excel file is created  
			$file_name = "purchasedrep_csv"; 		
			$myFile='uploads/'.$file_name.'.xls';
			//pass retrieved data into template and return as a string  
			$stringData = $this->parser->parse('reports/purchasedrep_csv',$data, true);
			//open excel and write string into excel  
			$fh = fopen($myFile, 'w') or die("can't open file");  
			fwrite($fh, $stringData);  
			fclose($fh);  
			$this->exportFile($file_name);              
        }
		else
		{
			redirect('auth/login');
		}	
	} 

	public function soldProduct()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');		

			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

			$this->data['product_list']=$this->CrudModel->select_data("select prod_id,prod_name from mas_product where prod_status=? ",array(1));

			$this->load->view('reports/sold_product',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}	

	public function srchSales()
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
                    $qry_con .=" and m.c_type  = ?";
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
                $qry_con .=" and inv_billno = ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $inventory_data=$this->CrudModel->select_data("select inv_date,inv_for,inv_perticular,inv_type,inv_id,inv_docs,inv_type,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as vendor,inv_billno,sl_qty,sl_gstinclusive as inclusive,sl_rpu,sl_disc,sl_disctype,sl_gstper,sl_mfdt,sl_expdt,prod_name,prod_unit from s_sale sl
				left join s_invoice s on s.inv_id=sl.sl_invid 
				left join mas_members m on m.c_id=s.inv_perticular 
				left join mas_product pm on pm.prod_id=sl.sl_prodid
				where sl_status=1 and inv_status=1 and inv_for=2 $qry_con order by inv_date desc",$qry_param);

            $sl=1;$tot_amt=0;$tot_disc=0;$tot_gst=0;$tot_net=0;

            if(count($inventory_data) < 1)
			{
				echo "<tr><td colspan='11' class='no-data-found'>No Record Found</td></tr>";
			}
			else {
            
            foreach($inventory_data as $value){ 
               
               	$amt=$value['sl_rpu']*$value['sl_qty'];

               	if($value['sl_disctype']==1)
               		$disc=round(($amt*$value['sl_disc'])/100,2);
               	else
               		$disc=$value['sl_disc'];

                if($value['inclusive']==1)
                {
                    $gst=round($amt-(float)($amt*(100/(100+$value['sl_gstper']))),2);
                    $net=$amt;
                    $incl=" (inclusive)";
                }
                else
                {
                    $amt=$amt-$disc;
                    $gst=round(($amt*$value['sl_gstper'])/100,2);
                    $net= $amt+$gst;
                    $incl="";
                } 

               	$tot_amt+=$amt;
               	$tot_disc+=$disc;
               	$tot_gst+=$gst;
               	$tot_net+=$net;

                if($value['inv_type']=='3' || $value['inv_type']=='4')
                    $cus_name=$this->CommonModel->vehicleProject($value['inv_perticular'],$value['inv_type']);
                else
                    $cus_name=$value['vendor'];

       		?>
            <tr>
                <td><?php echo $sl++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($value['inv_date'])); ?></td>
                <td><?php echo strtoupper($value['inv_billno']); ?></td>                  
                <td><?php echo ucwords(strtolower($cus_name)); ?></td>                     
                <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>                     
                <td><?php echo $value['sl_qty'].'&nbsp;'.$value['prod_unit']; ?></td>                     
                <td class="text-right"><?php echo $value['sl_rpu']; ?></td>                     
                <td class="text-right"><?php echo $amt; ?></td>                     
                <td class="text-right"><?php echo $disc; ?></td>                     
                <td class="text-right"><?php echo $incl.$gst; ?></td>                     
                <td class="text-right"><?php echo $net; ?></td>  
            </tr> 
            <?php } } ?>
            <tr>
            	<th colspan="7">Total : </th>
            	<th colspan="1" class="text-right"><?php echo $tot_amt; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_disc; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_gst; ?></th>
            	<th colspan="1" class="text-right"><?php echo $tot_net; ?></th>
            </tr>
            <?php  
        }
		else
		{
			redirect('auth/login');
		}
    } 

    public function exportSoldProduct() 
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
         	if(isset($_REQUEST['srch_type']) && $_REQUEST['srch_type']!='')
            {
                $qry_con .=" and inv_type  = ?";
                array_push($qry_param,$_REQUEST['srch_type']); 
            }
         	if(isset($_REQUEST['srch_prod']) && $_REQUEST['srch_prod']!='')
            {
                $qry_con .=" and stk_prodid  = ?";
                array_push($qry_param,$_REQUEST['srch_prod']); 
            }

            if(isset($_REQUEST['inv_billno']) && $_REQUEST['inv_billno']!='')
            {
                $qry_con .=" and inv_billno = ?";
                array_push($qry_param,$_REQUEST['inv_billno']); 
            }

            if(isset($_REQUEST['srch_fdate']) && $_REQUEST['srch_fdate']!='' && isset($_REQUEST['srch_tdate']) && $_REQUEST['srch_tdate']!='')
            {   
            	$srch_fdate=date('Y-m-d',strtotime($_REQUEST['srch_fdate']));
            	$srch_tdate=date('Y-m-d',strtotime($_REQUEST['srch_tdate']));
                $qry_con .=" and STR_TO_DATE(inv_date,'%Y-%m-%d') between ? and ?";
                array_push($qry_param,$srch_fdate,$srch_tdate); 
            }

            $data['inventory_data']=$this->CrudModel->select_data("select inv_date,inv_for,inv_perticular,inv_id,inv_docs,inv_type,CONCAT_WS(' ',m.c_firstname,m.c_middlename,m.c_lastname) as vendor,inv_billno,sl_qty,sl_gstinclusive as inclusive,sl_rpu,sl_disc,sl_disctype,sl_gstper,inv_paidstatus,sl_mfdt,sl_expdt,prod_name,prod_unit,prod_id from s_sale sl
				left join s_invoice s on s.inv_id=sl.sl_invid 
				left join mas_members m on m.c_id=s.inv_perticular 
				left join mas_product pm on pm.prod_id=sl.sl_prodid
				where sl_status=1 and inv_status=1 $qry_con order by inv_date,prod_id desc",$qry_param);

            $data['exprt_fdate']=$this->input->post('srch_fdate');
			$data['exprt_tdate']=$this->input->post('srch_tdate');

			//place where the excel file is created  
			$file_name = "soldrep_csv"; 		
			$myFile='uploads/'.$file_name.'.xls';
			//pass retrieved data into template and return as a string  

			if(isset($_POST['format1']))
				$stringData = $this->parser->parse('reports/soldrep1_csv',$data, true);
			else
				$stringData = $this->parser->parse('reports/soldrep2_csv',$data, true);

			
			//open excel and write string into excel  
			$fh = fopen($myFile, 'w') or die("can't open file");  
			fwrite($fh, $stringData);  
			fclose($fh);  
			$this->exportFile($file_name);              
        }
		else
		{
			redirect('auth/login');
		}	
	}

	public function vehicleRunRep()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->view('default/header');
			$this->load->view('default/sidebar');		

			$this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

			$this->data['product_list']=$this->CrudModel->select_data("select prod_id,prod_name from mas_product where prod_status=? ",array(1));

			$this->load->view('reports/vehicle_report',$this->data);
			$this->load->view('default/right_sidebar');	
			$this->load->view('default/footer');
		}
		else
		{
			redirect('auth/login');
		}	
	}	

	public function srchVehicleRep()
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
				array_push($qry_param,'%'.trim(str_replace(' ','', $_POST['srch_cus'])).'%');
			}
			if(isset($_POST['srch_driver']) && $_POST['srch_driver']!='')
			{
				$qry_con .=" and replace(concat(dmm.c_firstname,dmm.c_middlename,dmm.c_lastname),' ','') LIKE ?";
				array_push($qry_param,'%'.trim(str_replace(' ','', $_POST['srch_driver'])).'%');
			}

			$vehicle_data=$this->CrudModel->select_data("select v.vrun_date,mvt.vt_name,mv.v_num,concat_ws(' ',dmm.c_firstname,dmm.c_middlename,dmm.c_lastname) as driver,v.vrun_runstatus,v.vrun_meterstart,v.vrun_meterstop,concat_ws(' ',cmm.c_firstname,cmm.c_middlename,cmm.c_lastname) as customer,v.vrun_from,v.vrun_to,v.vrun_work,v.vrun_fareamt,v.vrun_remark,v.vrun_docs,v.vrun_id from vehicle v 
				left join mas_vehicle mv on mv.v_id=v.vrun_vid
				left join mas_vehicletype mvt on mvt.vt_id=mv.v_typeid
				left join mas_members cmm on cmm.c_id=v.vrun_memid
				left join mas_members dmm on dmm.c_id=v.vrun_drivid
				where vrun_status=1 $qry_con",$qry_param);
			//echo $this->db->last_query();die;
            if(count($vehicle_data) ==0  || $vehicle_data[0]['vrun_id']==null)
			{
				echo "<tr><td colspan='18' class='cen no-data-found'>Record Not Found</td></tr>";
				die();
			}

			$sn=1;$tot_amt=0;
			foreach ($vehicle_data as $key => $value) { 
				$tot_amt+=	$value['vrun_fareamt'];
			?>
			<tr>
				<td><?php echo $sn++; ?></td>
				<td><?php echo date('d-m-Y',strtotime($value['vrun_date'])); ?></td>
				<td><?php echo strtoupper($value['vt_name']); ?></td>
				<td><?php echo strtoupper($value['v_num']); ?></td>
				<td><?php echo ucwords(strtolower(str_replace('  ',' ',$value['driver']))); ?></td>
				<td><?php if($value['vrun_runstatus']==1)echo 'Running';else echo 'Stopped'; ?></td>
				<td><?php echo $value['vrun_meterstart']; ?></td>
				<td><?php echo $value['vrun_meterstop']; ?></td>
				<td><?php echo ucwords(strtolower(str_replace('  ',' ',$value['customer']))); ?></td>
				<td><?php echo ucwords(strtolower($value['vrun_from'])); ?></td>
				<td><?php echo ucwords(strtolower($value['vrun_to'])); ?></td>
				<td><?php echo ucwords(strtolower($value['vrun_work'])); ?></td>
				<td class="text-right"><?php echo $value['vrun_fareamt']; ?></td>
				<td><?php echo ucwords(strtolower($value['vrun_remark'])); ?></td>
				<td class='h cen' style="vertical-align: middle;">
					<?php if($value['vrun_docs']!=''){ ?>
					<a class='btn btn-success btn-xs'  target='_blank' href="<?php echo base_url().$value['vrun_docs']; ?>"><i class='fa fa-eye'></i></a>
					<?php } ?>
				</td>				
			</tr>
            <?php  } ?>
            <tr>
            	<th colspan="12" style="text-align: left;">Total : </th>
            	<th colspan="1" class="text-right"><?php echo $tot_amt; ?></th>
            	<th colspan="2">&nbsp;</th>
            </tr>
            <?php
        }
		else
		{
			redirect('auth/login');
		}
    } 

    public function exportVehicleRep() 
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
				array_push($qry_param,'%'.trim(str_replace(' ','', $_POST['srch_cus'])).'%');
			}
			if(isset($_POST['srch_driver']) && $_POST['srch_driver']!='')
			{
				$qry_con .=" and replace(concat(dmm.c_firstname,dmm.c_middlename,dmm.c_lastname),' ','') LIKE ?";
				array_push($qry_param,'%'.trim(str_replace(' ','', $_POST['srch_driver'])).'%');
			}

			$data['vehicle_data']=$this->CrudModel->select_data("select v.vrun_date,mvt.vt_name,mv.v_num,concat_ws(' ',dmm.c_firstname,dmm.c_middlename,dmm.c_lastname) as driver,v.vrun_runstatus,v.vrun_meterstart,v.vrun_meterstop,concat_ws(' ',cmm.c_firstname,cmm.c_middlename,cmm.c_lastname) as customer,v.vrun_from,v.vrun_to,v.vrun_work,v.vrun_fareamt,v.vrun_remark,v.vrun_docs,v.vrun_id,
                (select sum(acc_amt) from account where acc_vochfor=3 and acc_trantype=2 and acc_sourceid=mv.v_id and acc_trandt =v.vrun_date) as expense
             from vehicle v 
				left join mas_vehicle mv on mv.v_id=v.vrun_vid
				left join mas_vehicletype mvt on mvt.vt_id=mv.v_typeid
				left join mas_members cmm on cmm.c_id=v.vrun_memid
				left join mas_members dmm on dmm.c_id=v.vrun_drivid
				where vrun_status=1 $qry_con order by mv.v_num,v.vrun_date",$qry_param);

            $data['exprt_fdate']=$this->input->post('srch_fdate');
			$data['exprt_tdate']=$this->input->post('srch_tdate');

			//place where the excel file is created  
			$file_name = "soldrep_csv"; 		
			$myFile='uploads/'.$file_name.'.xls';
			//pass retrieved data into template and return as a string  

			if(isset($_POST['format1']))
				$stringData = $this->parser->parse('reports/vehiclerep1_csv',$data, true);
			else
				$stringData = $this->parser->parse('reports/vehiclerep2_csv',$data, true);
            //echo $stringData;die;
			
			//open excel and write string into excel  
			$fh = fopen($myFile, 'w') or die("can't open file");  
			fwrite($fh, $stringData);  
			fclose($fh);  
			$this->exportFile($file_name);              
        }
		else
		{
			redirect('auth/login');
		}	
	}

    public function vehicleExpRep()
    {
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');       

            $this->data['vendor']=$this->CrudModel->select_data("select c_id,CONCAT_WS(' ',c_firstname,c_middlename,c_lastname) as vendor from mas_members where c_type = ? and c_status=? ",array(2,1));

            $this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? and c_bankaccid=?",array(1,2,0));
            $this->load->view('reports/vehicleexp_report',$this->data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }   
    }   

    public function srchVehicleExp()
    {   
        if($this->ion_auth->logged_in())
        {
            $qry_con='';
            $qry_param=array(1,3,0);
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

            $vechicle_exp=$this->CrudModel->select_data("select a.acc_id,acc_cid,a.acc_vochfor,a.acc_sourceid,acc_trandt,a.acc_amt,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
                left join mas_category mc on mc.c_id = a.acc_ldgid 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = ? and acc_vochfor=? and acc_mode=? and acc_trantype=2 $qry_con order by acc_entrydt desc",$qry_param);
            

            $sl=1;$tot_amt=0;
            
            if(count($vechicle_exp)===0 || $vechicle_exp[0]['acc_id']===null)
            {
                echo '<tr><td colspan="10" class="no-data-found">Record Not Found</td></tr>';
                die();
            }
            foreach ($vechicle_exp as $key => $value) 
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

                echo "</tr>";       
            }
            echo "<tr>";
            echo "<td colspan='7'><strong>Total Amount : </strong></td>";
            echo "<td colspan='3'><strong>". $tot_amt ."</strong></td>";
            echo "</tr>";
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function exportVehicleExp() 
    {   
        if($this->ion_auth->logged_in())
        {
            $qry_con='';
            $qry_param=array(1,3,0);
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

            $data['vechicle_exp']=$this->CrudModel->select_data("select a.acc_id,acc_cid,a.acc_vochfor,a.acc_sourceid,acc_trandt,a.acc_amt,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
                left join mas_category mc on mc.c_id = a.acc_ldgid 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = ? and acc_vochfor=? and acc_mode=? and acc_trantype=2 $qry_con order by acc_entrydt desc",$qry_param);

            $data['exprt_fdate']=$this->input->post('srch_fdate');
            $data['exprt_tdate']=$this->input->post('srch_tdate');

            //place where the excel file is created  
            $file_name = "soldrep_csv";         
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  

            $stringData = $this->parser->parse('reports/vehicleexp_csv',$data, true);
            
            
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    }

    public function prodStock()
    {
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');       

            $this->data['ctg_list']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status=? and c_for=? order by c_name",array(1,1));

            $this->load->view('reports/prod_stock',$this->data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }   
    }   

    public function srchProdStock()
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

            $product_data=$this->CrudModel->select_data("select p.*,c.c_name,
            (select sum(stk_qty) from s_stock where stk_status =1 and stk_prodid=p.prod_id) as tot_purch,
            (select sum(sl_qty) from s_sale where sl_status =1 and sl_prodid=p.prod_id) as tot_sold from mas_product p
                left join mas_category c on c.c_id=p.prod_ctgid
                where p.prod_status=? $qry_con order by c.c_name desc",$qry_param);

            $sl=1;
            if(count($product_data) === 0 || $product_data[0]['prod_id']===null)
            {
                echo "<tr><td colspan='14' class='no-data-found'>No Record Found</td></tr>";
            }
            else
            {
                foreach($product_data as $value){ 
                   $gst_status='<span title="Not Applicable" style="color:red"><center>NA</center></span>';
                ?>
                <tr id='row_<?php echo $value["prod_id"]; ?>'>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo ucwords(strtolower($value['c_name'])); ?></td>  
                    <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>  
                    <td><?php echo $value['prod_hsn_sac']; ?></td>  
                    <td><?php echo ucwords(strtolower($value['prod_unit'])); ?></td>   
                    <td><?php echo $value['prod_openstock'];?></td>                     
                    <td><?php echo $value['tot_purch'];?></td>                     
                    <td><?php echo $value['tot_sold'];?></td>                     
                    <td><?php echo $value['prod_currstock'];?></td>                     
                    <td><?php echo $value['prod_purrate'];?></td>   
                    <td><?php echo $value['prod_salerate'];?></td>   
                    <td><?php if($value['prod_isgst']==='1')echo $value['prod_gstrate'].'%';else echo $gst_status; ?></td> 
                    <td><?php if($value['prod_purgstincl']==='0')echo $gst_status;else if($value['prod_purgstincl']==='1')echo 'Yes';else echo 'No'; ?></td>                       
                    <td><?php echo ucwords(strtolower($value['prod_remark'])); ?></td>  
                </tr> 
            <?php } }
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function exportProdStock() 
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

            $data['product_data']=$this->CrudModel->select_data("select p.*,c.c_name,
            (select sum(stk_qty) from s_stock where stk_status =1 and stk_prodid=p.prod_id) as tot_purch,
            (select sum(sl_qty) from s_sale where sl_status =1 and sl_prodid=p.prod_id) as tot_sold from mas_product p
                left join mas_category c on c.c_id=p.prod_ctgid
                where p.prod_status=? $qry_con order by c.c_name desc",$qry_param);

            //place where the excel file is created  
            $file_name = "prodstock_csv";        
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  
            $stringData = $this->parser->parse('reports/prodstock_csv',$data, true);
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    } 

    public function memReport()
    {
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');       

            $this->data['ledger_data']=$this->CrudModel->select_data("select c_id,c_name from mas_category where c_status>? and c_for=?",array(0,2));

            $this->load->view('reports/mem_report',$this->data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }   
    }   

    public function srchMemReport()
    {   
        if($this->ion_auth->logged_in())
        {
            $qry_con='';
            $qry_con2='';
            $qry_con3='';
            $qry_param=array(); 

            if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!=''){
                $ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
                $ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
                $qry_con .=' and str_to_date(a.acc_trandt,"%Y-%m-%d") BETWEEN ? and ?';
                array_push($qry_param,$ftdate,$ttdate);
            }

            if(isset($_POST['srch_cid']) && $_POST['srch_cid']!='')
            {
                $qry_con .=' and a.acc_cid = ? ';
                $qry_con2 .=' and vrun_vid = ? ';
                $qry_con3 .=' and acc_sourceid = ? ';
                array_push($qry_param,$_POST['srch_cid']);
            }

            if($_POST['srch_for']=='5' || $_POST['srch_for']=='6')
                $paidto=$this->CommonModel->vehicleProject($_POST['srch_cid'],$_POST['srch_for']-2);
            else
                $paidto='Member Name : '.$this->CommonModel->memberName($_POST['srch_cid']);

            if($_POST['srch_for']=='5')
                $paidto='Vehicle : '.strtoupper($paidto);
            elseif($_POST['srch_for']=='6')
                $paidto='Project : '.strtoupper($paidto);


            if($_POST['srch_for']=='6')
            {
                $ldg_data=$this->CrudModel->select_data("
                select 's_sale' as tbl,acc_id,acc_trantype,sl_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,inv_remark as remark,p.prod_name as descri,sl_qty as qty,p.prod_unit as unit,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_sale s on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid
                where sl_status=1 and acc_vochfor=4 and acc_vochfor>0 and acc_voucher IN (1,2) $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ) as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=4  and acc_voucher IN (3,4) $qry_con order by date asc",array_merge($qry_param,$qry_param));
            }
            if($_POST['srch_for']=='5')
            {
                $ldg_data=$this->CrudModel->select_data("
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,vrun_remark remark,v.vrun_work as descri,1 as qty,'' as unit,vrun_fareamt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join vehicle v on v.vrun_id = a.acc_sourceid
                left join mas_vehicle vm on vm.v_id = v.vrun_vid
                where vrun_status=1 and acc_vochfor=3 and acc_voucher IN (1,2) $qry_con2
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,'Expense On Vehicle' as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher=0 $qry_con3
                UNION ALL
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ) as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher IN (3,4) $qry_con order by date asc",array_merge($qry_param,$qry_param,$qry_param));
            }
            else
            {
                $ldg_data=$this->CrudModel->select_data("
                select 's_stock' as tbl,acc_id,acc_trantype,stk_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,acc_remark as remark,p.prod_name as descri,stk_qty as qty,p.prod_unit as unit,stk_rpu as rate,stk_gstper as gstper,stk_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_stock s on s.stk_invid= i.inv_id
                left join mas_product p on p.prod_id = s.stk_prodid                 
                where stk_status=1 and acc_vochfor=1 and acc_voucher=1 $qry_con
                UNION ALL
                select 's_sale' as tbl,acc_id,acc_trantype,sl_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,inv_remark as remark,p.prod_name as descri,sl_qty as qty,p.prod_unit as unit,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_sale s on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid                 
                where sl_status=1 and acc_vochfor=2 and acc_voucher=2 $qry_con 
                UNION ALL
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,vrun_remark remark,v.vrun_work as descri,1 as qty,'' as unit,vrun_fareamt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join vehicle v on v.vrun_id = a.acc_sourceid
                left join mas_vehicle vm on vm.v_id = v.vrun_vid
                where vrun_status=1 and acc_vochfor=3 and acc_voucher IN (1,2) $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,'Expense On Vehicle' as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher=0 $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,acc_sourceid as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ),1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_voucher IN (3,4) $qry_con order by date desc,tbl,inv_id",array_merge($qry_param,$qry_param,$qry_param,$qry_param,$qry_param));
            }

            //echo $this->db->last_query();die;

            $sl=1;$tot_dr=0;$tot_cr=0;$bal=0;$bal1=0;$member='Not Selected';
            
            if(count($ldg_data)===0 || $ldg_data[0]['acc_id']===null)  
            {
                echo '<tr><td colspan="14" class="no-data-found">No Record Found</td></tr>';
            }
            else
            {
                $transport_arr=[];
                foreach ($ldg_data as $key => $value) 
                {   
                    $dr_amt='';$cr_amt='';
                   if($value['disctype']==1)
                    {
                        $gross = round((((float)$value['qty']*(float)$value['rate'])*(100 - (float)$value['disc']))/100,2);
                        $net= $gross*(100+$value['gstper'])/100;
                        
                       $disc_sign=' %';
                    }
                    else
                    {
                       $gross = round(((float)$value['qty'] * (float)$value['rate'])-(float)$value['disc'],2);
                        $net= ((float)$gross * (100 + (float)$value['gstper']))/100;
                        $disc_sign=' /-';

                    }   

                    if($value['disc']=='')
                        $disc_sign="";
                    if($value['gstper']=='')
                        $gst_sign="";
                    else
                        $gst_sign=' %';


                    $transport_charge=0;$roundoff=0;
                    if(in_array($value['tbl'],array("s_sale","s_stock")))
                    {                        
                        if(array_search($value['inv_id'], $transport_arr)===false)
                        {
                            array_push($transport_arr,$value['inv_id']);
                            $transport_charge=$value['transport'];
                            $roundoff=$value['roundoff'];
                        }
                    }

                    if($value['acc_trantype']==='1')
                    {                    
                        $tot_dr+=$dr_amt=round($net,2)+$transport_charge+$roundoff;
                        $bal+=round($dr_amt,2);
                    }
                    else
                    {
                        $tot_cr+=$cr_amt=round($net,2)+$transport_charge+$roundoff;
                        $bal-=round($cr_amt,2);
                    }                    

                    ?>
                    <tr>
                    <?php
                    echo "<td>". $sl++ ."</td>";
                    echo "<td>". date('d-m-Y',strtotime($value['date'])) ."</td>";
                    echo "<td>". $value['inv_id']."</td>";
                    echo "<td>". $value['remark'] ."</td>";
                    echo "<td>". $value['descri'] ."</td>";
                    echo "<td>". $value['qty']. ' ' .$value['unit'] ."</td>";
                    echo "<td class='align-right'>". $value['rate'] ."</td>";
                    echo "<td class='align-right'>". $value['disc'] . $disc_sign . "</td>";
                    echo "<td class='align-right'>". $value['gstper'] .$gst_sign."</td>";
                    echo "<td class='align-right'>". $transport_charge ."</td>";
                    echo "<td class='align-right'>". $roundoff ."</td>";
                    echo "<td class='align-right'>". $dr_amt ."</td>";
                    echo "<td class='align-right'>". $cr_amt ."</td>";
                    echo "<td class='align-right'>". round($bal,2) ."</td>";
                    echo "</tr>";       
                }

                if($tot_cr>$tot_dr)
                    $bal1=abs($tot_cr-$tot_dr) . ' (Cr.)';
                elseif($tot_cr<$tot_dr)
                    $bal1=abs($tot_cr-$tot_dr) . ' (Dr.)';
                else
                    $bal1='0';
            }

            echo "<tr >";
            echo "<td colspan='11'><strong>Total Amount : </strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $tot_dr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $tot_cr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $bal1 ."</strong></td>";
            echo "</tr>";            
            ?>
            <script type="text/javascript">
                $(function(){
                    $('#memname').html('<?php echo  $paidto; ?>');
                    $('#membal').html('<?php echo $bal1; ?>');
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

    public function exportMemReport() 
    {   
        if($this->ion_auth->logged_in())
        {
            $qry_con='';
            $qry_con2='';
            $qry_con3='';
            $qry_param=array(); 

            if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!=''){
                $ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
                $ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
                $qry_con .=' and str_to_date(a.acc_trandt,"%Y-%m-%d") BETWEEN ? and ?';
                array_push($qry_param,$ftdate,$ttdate);
            }

            if(isset($_POST['srch_cid']) && $_POST['srch_cid']!='')
            {
                $qry_con .=' and a.acc_cid = ? ';
                $qry_con2 .=' and vrun_vid = ? ';
                $qry_con3 .=' and acc_sourceid = ? ';
                array_push($qry_param,$_POST['srch_cid']);
            }

            if($_POST['srch_for']=='5' || $_POST['srch_for']=='6')
                $paidto=$this->CommonModel->vehicleProject($_POST['srch_cid'],$_POST['srch_for']-2);
            else
                $paidto='Member Name : '.$this->CommonModel->memberName($_POST['srch_cid']);

            if($_POST['srch_for']=='5')
                $paidto='Vehicle : '.strtoupper($paidto);
            elseif($_POST['srch_for']=='6')
                $paidto='Project : '.strtoupper($paidto);

            $data['member']=$paidto;
            
            if($_POST['srch_for']=='6')
            {
                $data['ldg_data']=$this->CrudModel->select_data("
                select 's_sale' as tbl,acc_id,acc_trantype,sl_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,inv_remark as remark,p.prod_name as descri,sl_qty as qty,p.prod_unit as unit,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_sale s on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid
                where sl_status=1 and acc_vochfor=4 and acc_vochfor>0 and acc_voucher IN (1,2) $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ) as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=4  and acc_voucher IN (3,4) $qry_con order by date asc",array_merge($qry_param,$qry_param));
            }
            if($_POST['srch_for']=='5')
            {
                $data['ldg_data']=$this->CrudModel->select_data("
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,vrun_remark remark,v.vrun_work as descri,1 as qty,'' as unit,vrun_fareamt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join vehicle v on v.vrun_id = a.acc_sourceid
                left join mas_vehicle vm on vm.v_id = v.vrun_vid
                where vrun_status=1 and acc_vochfor=3 and acc_voucher IN (1,2) $qry_con2
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,'Expense On Vehicle' as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher=0 $qry_con3
                UNION ALL
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ) as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher IN (3,4) $qry_con order by date asc",array_merge($qry_param,$qry_param,$qry_param));
            }
            else
            {
                $data['ldg_data']=$this->CrudModel->select_data("
                select 's_stock' as tbl,acc_id,acc_trantype,stk_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,acc_remark as remark,p.prod_name as descri,stk_qty as qty,p.prod_unit as unit,stk_rpu as rate,stk_gstper as gstper,stk_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_stock s on s.stk_invid= i.inv_id
                left join mas_product p on p.prod_id = s.stk_prodid                 
                where stk_status=1 and acc_vochfor=1 and acc_voucher=1 $qry_con
                UNION ALL
                select 's_sale' as tbl,acc_id,acc_trantype,sl_disctype as disctype,a.acc_trandt as date,inv_billno as inv_id,inv_remark as remark,p.prod_name as descri,sl_qty as qty,p.prod_unit as unit,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,inv_transportcharge as transport,inv_roundoff as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join s_invoice i on i.inv_id = a.acc_sourceid
                left join s_sale s on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid                 
                where sl_status=1 and acc_vochfor=2 and acc_voucher=2 $qry_con 
                UNION ALL
                select 'vehicle' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,vrun_remark remark,v.vrun_work as descri,1 as qty,'' as unit,vrun_fareamt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a
                left join mas_members mm on mm.c_id=a.acc_cid 
                left join vehicle v on v.vrun_id = a.acc_sourceid
                left join mas_vehicle vm on vm.v_id = v.vrun_vid
                where vrun_status=1 and acc_vochfor=3 and acc_voucher IN (1,2) $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,'' as inv_id,acc_remark as remark,'Expense On Vehicle' as descri,1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_vochfor=3 and acc_voucher=0 $qry_con
                UNION ALL
                select 'account' as tbl,acc_id,acc_trantype,'' as disctype,a.acc_trandt as date,acc_sourceid as inv_id,acc_remark as remark,(CASE WHEN acc_voucher=3 THEN 'Amount Paid' ELSE 'Amount Received' END ),1 as qty,'' as unit,a.acc_amt as rate,'' as gstper,'' as disc,0 as transport,0 as roundoff,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as member from account a                 
                left join mas_members mm on mm.c_id=a.acc_cid 
                where acc_status = 1 and acc_voucher IN (3,4) $qry_con order by date desc,tbl,inv_id",array_merge($qry_param,$qry_param,$qry_param,$qry_param,$qry_param));
            }

            
            $tot_cr=0;$tot_dr=0;
            foreach ($data['ldg_data'] as $key => $value) 
            {   
                if($value['disctype']==='1')
                {
                    $gross = round((($value['qty']*$value['rate'])*(100 - $value['disc']))/100,2);
                    $net= round($gross*(100+$value['gstper'])/100,2);
                }
                else
                {
                    $gross = round(($value['qty']*$value['rate'])-$value['disc'],2);
                    $net= round($gross*(100+$value['gstper'])/100,2);
                }

                if($value['acc_trantype']==='1')
                {                    
                    $tot_dr+=$net;
                }
                else
                {
                    $tot_cr+=$net;
                }                
            }

            if($tot_cr>$tot_dr)
                $data['balance']=abs($tot_cr-$tot_dr) . ' (Cr.)';
            elseif($tot_cr<$tot_dr)
                $data['balance']=abs($tot_cr-$tot_dr) . ' (Dr.)';
            else
                $data['balance']='0';

            //place where the excel file is created  
            $file_name = "memrep_csv";        
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  
            $stringData = $this->parser->parse('reports/memrep_csv',$data, true);
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    } 

    public function gstRep()
    {
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');       

            $this->data['gst_list']=$this->CrudModel->select_data("select c_id,c_name,c_taxperc from mas_category where c_status>? and c_for=? order by c_taxperc",array(0,3));

            $this->load->view('reports/gst_report',$this->data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }   
    }   
    

    public function srchGstRep()
    {   
        if($this->ion_auth->logged_in())
        {
            $stkqry_con='';
            $slqry_con='';
            $qry_param=array(); 

            if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!=''){
                $ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
                $ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
                $stkqry_con .=' and str_to_date(i.inv_date,"%Y-%m-%d") BETWEEN ? and ?';
                $slqry_con .=' and str_to_date(i.inv_date,"%Y-%m-%d") BETWEEN ? and ?';
                array_push($qry_param,$ftdate,$ttdate);
            }

            if(isset($_POST['srch_gst']) && $_POST['srch_gst']!='')
            {
                $stkqry_con .=' and stk_gstper = ? ';
                $slqry_con .=' and sl_gstper = ? ';
                array_push($qry_param,$_POST['srch_gst']);
            }

            if(isset($_POST['srch_invid']) && $_POST['srch_invid']!='')
            {
                $stkqry_con .=' and i.inv_billno = ? ';
                $slqry_con .=' and i.inv_billno = ? ';
                array_push($qry_param,$_POST['srch_invid']);
            }

            $ldg_data=$this->CrudModel->select_data("
                select inv_id,inv_billno,inv_for,inv_date,inv_for,inv_location,stk_disctype as disctype,inv_remark as remark,p.prod_name as product,stk_qty as qty,stk_rpu as rate,stk_gstper as gstper,stk_disc as disc,stk_gstinclusive as inclusive from s_stock s
                left join s_invoice i on s.stk_invid= i.inv_id
                left join mas_product p on p.prod_id = s.stk_prodid                 
                where stk_status=1 and inv_for=1 and stk_gstper > 0 $stkqry_con
                UNION ALL
                select inv_id,inv_billno,inv_for,inv_date,inv_for,inv_location,sl_disctype as disctype,inv_remark as remark,p.prod_name as product,sl_qty as qty,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,sl_gstinclusive as inclusive from s_sale s
                left join s_invoice i on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid                 
                where sl_status=1 and inv_for=2 and sl_gstper > 0 $slqry_con order by inv_date asc",array_merge($qry_param,$qry_param));

            $sl=1;$tot_dr=0;$tot_cr=0;$tot_gross=0;$bal=0;$bal1=0;
            $totsgst_dr=0;$totcgst_dr=0;$totigst_dr=0;$totsgst_cr=0;
            $totcgst_cr=0;$totigst_cr=0;$totgst_cr=0;$totgst_dr=0;$row_bal=0;

            if(count($ldg_data)===0 || $ldg_data[0]['inv_id']===null)  
            {
                echo '<tr><td colspan="15" class="no-data-found">No Record Found</td></tr>';
                die();
            }
            foreach ($ldg_data as $key => $value) 
            {   
                $dr_amt='';$cr_amt='';

                $gross = round(($value['qty']*$value['rate']),2);
                if($value['disctype']==='1')
                    $disc = round(($gross*$value['disc'])/100,2);
                else
                    $disc=$value['disc'];

                if($value['inclusive']==1)
                {
                    $gst=round($gross-(float)($gross*(100/(100+$value['gstper']))),2);
                    $net=$gross;
                    $incl=" (inclusive)";
                }
                else
                {
                    $gross=$gross-$disc;
                    $gst=round(($gross*$value['gstper'])/100,2);
                    $net= $gross+$gst;
                    $incl="";
                }                   
                $tot_gross+=$gross;
                if($value['inv_for']==='1')
                {                    
                    $tot_dr+=$dr_amt=$net;
                    $bal+=$net;
                }
                else
                {
                    $tot_cr+=$cr_amt=$net;
                    $bal-=$net;
                }               

                $sgst_dr='';
                $cgst_dr='';
                $igst_dr='';
                $sgst_cr='';
                $cgst_cr='';
                $igst_cr='';
                $gst_dr=0;
                $gst_cr=0;

                if($value['inv_for']==1)
                {
                    $invtype='Purchase';

                    if($value['inv_location']==1)
                    {
                        $sgst_cr=round($gst/2,2);
                        $cgst_cr=round($gst/2,2);
                        $totsgst_cr+=$sgst_cr;
                        $totcgst_cr+=$sgst_cr;
                    }
                    else
                    {
                        $totigst_cr+=$gst;
                        $igst_cr=$gst;
                    }
                    $gst_cr=$gst;
                    $totgst_cr+=$gst_cr;
                    $row_bal+=$gst_cr;
                }
                else
                {
                    if($value['inv_location']==1)
                    {
                        $sgst_dr=round($gst/2,2);
                        $cgst_dr=round($gst/2,2);
                        $totsgst_dr+=$sgst_dr;
                        $totcgst_dr+=$sgst_dr;
                    }
                    else
                    {
                        $totigst_dr+=$gst;
                        $igst_dr=$gst;
                    }
                    $invtype='Sales';
                    $gst_dr=$gst;
                    $totgst_dr+=$gst_dr;
                    $row_bal-=$gst_dr;
                }

                echo "<tr>";          
                echo "<td>". $sl++ ."</td>";
                echo "<td>". date('d-m-Y',strtotime($value['inv_date'])) ."</td>";
                echo "<td>". $invtype."</td>";
                echo "<td>". $value['inv_billno']."</td>";
                echo "<td>". ucwords(strtolower($value['product'])) ."</td>";
                echo "<td>". ucwords(strtolower($value['remark'])) ."</td>";
                echo "<td class='align-right'>". $gross ."</td>";
                echo "<td>". $value['gstper'].'%' ."</td>";
                echo "<td class='align-right'>". $sgst_cr . "</td>";
                echo "<td class='align-right'>". $cgst_cr ."</td>";
                echo "<td class='align-right'>". $igst_cr ."</td>";
                echo "<td class='align-right'>". $sgst_dr . "</td>";
                echo "<td class='align-right'>". $cgst_dr ."</td>";
                echo "<td class='align-right'>". $igst_dr ."</td>";
                echo "<td class='align-right'>". $row_bal ."</td>";
                echo "</tr>";       
            }
            
            $bal1=$totgst_cr-$totgst_dr;

            echo "<tr>";
            echo "<td colspan='6'><strong>Total Amount : </strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $tot_gross ."</strong></td>";
            echo "<td colspan='1' class='align-right'>&nbsp;</td>";
            echo "<td colspan='1' class='align-right'><strong>". $totsgst_cr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $totcgst_cr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $totigst_cr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $totsgst_dr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $totcgst_dr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $totigst_dr ."</strong></td>";
            echo "<td colspan='1' class='align-right'><strong>". $bal1 ."</strong></td>";
            echo "</tr>";            
           
        }
        else
        {
            redirect('auth/login');
        }
    } 

    public function exportGstRep() 
    {   
        if($this->ion_auth->logged_in())
        {
            $stkqry_con='';
            $slqry_con='';
            $qry_param=array(); 

            if(isset($_POST['srch_ftdate']) && $_POST['srch_ftdate']!='' && isset($_POST['srch_ttdate']) && $_POST['srch_ttdate']!=''){
                $ftdate=date('Y-m-d',strtotime($_POST['srch_ftdate']));
                $ttdate=date('Y-m-d',strtotime($_POST['srch_ttdate']));
                $stkqry_con .=' and str_to_date(i.inv_date,"%Y-%m-%d") BETWEEN ? and ?';
                $slqry_con .=' and str_to_date(i.inv_date,"%Y-%m-%d") BETWEEN ? and ?';
                array_push($qry_param,$ftdate,$ttdate);
            }

            if(isset($_POST['srch_gst']) && $_POST['srch_gst']!='')
            {
                $stkqry_con .=' and stk_gstper = ? ';
                $slqry_con .=' and sl_gstper = ? ';
                array_push($qry_param,$_POST['srch_gst']);
            }

            if(isset($_POST['srch_invid']) && $_POST['srch_invid']!='')
            {
                $stkqry_con .=' and i.inv_billno = ? ';
                $slqry_con .=' and i.inv_billno = ? ';
                array_push($qry_param,$_POST['srch_invid']);
            }

            $data['ldg_data']=$this->CrudModel->select_data("
                select inv_id,inv_billno,inv_for,inv_date,inv_for,inv_location,stk_disctype as disctype,inv_remark as remark,p.prod_name as product,stk_qty as qty,stk_rpu as rate,stk_gstper as gstper,stk_disc as disc,stk_gstinclusive as inclusive from s_stock s
                left join s_invoice i on s.stk_invid= i.inv_id
                left join mas_product p on p.prod_id = s.stk_prodid                 
                where stk_status=1 and inv_for=1 and stk_gstper > 0 $stkqry_con
                UNION ALL
                select inv_id,inv_billno,inv_for,inv_date,inv_for,inv_location,sl_disctype as disctype,inv_remark as remark,p.prod_name as product,sl_qty as qty,sl_rpu as rate,sl_gstper as gstper,sl_disc as disc,sl_gstinclusive as inclusive from s_sale s
                left join s_invoice i on s.sl_invid= i.inv_id
                left join mas_product p on p.prod_id = s.sl_prodid                 
                where sl_status=1 and inv_for=2 and sl_gstper > 0 $slqry_con order by inv_date asc",array_merge($qry_param,$qry_param));
            
            

            //place where the excel file is created  
            $file_name = "gstrep_csv";        
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  
            $stringData = $this->parser->parse('reports/gstrep_csv',$data, true);
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    } 

    public function exportPayment() 
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

            $data['payment']=$this->CrudModel->select_data("select a.acc_id,acc_cid,a.acc_vochfor,a.acc_sourceid,acc_trandt,a.acc_amt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_onlineid,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
                left join mas_category mc on mc.c_id = a.acc_ldgid 
                left join mas_members mm on mm.c_id=a.acc_cid where acc_status = ? and acc_voucher=? $qry_con order by acc_entrydt desc",$qry_param);

            //place where the excel file is created  
            $file_name = "payment_csv";        
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  
            $stringData = $this->parser->parse('reports/payment_csv',$data, true);
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    } 
    public function exportReceive() 
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

            $data['receive']=$this->CrudModel->select_data("select a.acc_id,a.acc_vochfor,a.acc_sourceid,a.acc_cid,acc_trandt,a.acc_amt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_onlineid,a.acc_entrydt,a.acc_remark,a.acc_docs,mc.c_name as ledger,CONCAT_WS(' ',mm.c_salutation,mm.c_firstname,mm.c_middlename,mm.c_lastname) as paidto,mm.c_type from account a 
                left join mas_category mc on mc.c_id = a.acc_ldgid 
                left join mas_members mm on mm.c_id=a.acc_cid where acc_status = ? and acc_voucher=? $qry_con order by acc_entrydt desc",$qry_param);

            //place where the excel file is created  
            $file_name = "receive_csv";        
            $myFile='uploads/'.$file_name.'.xls';
            //pass retrieved data into template and return as a string  
            $stringData = $this->parser->parse('reports/receive_csv',$data, true);
            //open excel and write string into excel  
            $fh = fopen($myFile, 'w') or die("can't open file");  
            fwrite($fh, $stringData);  
            fclose($fh);  
            $this->exportFile($file_name);              
        }
        else
        {
            redirect('auth/login');
        }   
    } 

    public function salesBill()
    {   
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');               
            $inv_id=(int)$this->uri->segment(3);
            
            $data['inventory_data']=$this->CrudModel->select_data("select f_name,f_logo,f_contact,f_email,f_address,inv_date,inv_id,inv_for,inv_perticular,inv_id,inv_docs,inv_type,inv_billno,inv_transportcharge,inv_roundoff,sl_qty,sl_rpu,sl_disc,sl_disctype,sl_gstper,sl_mfdt,sl_expdt,prod_name,prod_unit from s_sale sl
                left join s_invoice s on s.inv_id=sl.sl_invid 
                left join mas_product pm on pm.prod_id=sl.sl_prodid
                left join mas_firm mf on mf.f_id=s.inv_firmid
                where sl_status=1 and inv_status=1 and inv_for=2 and inv_id=? order by inv_date desc",array($inv_id));
           //echo $this->db->last_query();die;
            $this->load->view('reports/sales_bill',$data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }
    }

    public function printSalesBill()
    {   
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');              
            $inv_id=(int)$this->uri->segment(3);
            
            $data['inventory_data']=$this->CrudModel->select_data("select f_name,f_logo,f_contact,f_email,f_address,inv_date,inv_id,inv_for,inv_perticular,inv_id,inv_docs,inv_type,inv_billno,inv_transportcharge,inv_roundoff,sl_qty,sl_rpu,sl_disc,sl_disctype,sl_gstper,sl_mfdt,sl_expdt,prod_name,prod_unit from s_sale sl
                left join s_invoice s on s.inv_id=sl.sl_invid 
                left join mas_product pm on pm.prod_id=sl.sl_prodid
                left join mas_firm mf on mf.f_id=s.inv_firmid
                where sl_status=1 and inv_status=1 and inv_for=2 and inv_id=? order by inv_date desc",array($inv_id));
           //echo $this->db->last_query();die;
            $this->load->view('reports/sales_billprint',$data);
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }
    }

    public function salesBillGst()
    {   
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');
            $this->load->view('default/sidebar');               
            $inv_id=(int)$this->uri->segment(3);
            
            $data['inventory_data']=$this->CrudModel->select_data("select f_name,f_logo,f_contact,f_email,f_address,f_pan,f_gstin,inv_date,inv_id,inv_for,inv_perticular,inv_id,inv_location,inv_docs,inv_type,inv_billno,sl_qty,sl_rpu,sl_disc,sl_disctype,sl_gstinclusive,sl_gstper,sl_mfdt,sl_expdt,prod_name,prod_hsn_sac,prod_unit,f_gstin,ms.state_name,ms.state_tin,mb.bank_name,mba.acc_num,mba.acc_branch,mba.acc_ifsc from s_sale sl
                left join s_invoice s on s.inv_id=sl.sl_invid 
                left join mas_product pm on pm.prod_id=sl.sl_prodid
                left join mas_firm mf on mf.f_id=s.inv_firmid
                left join mas_state ms on ms.state_id=mf.f_state
                left join mas_bankacc mba on mba.acc_id=mf.f_bankacc
                left join mas_bank mb on mb.bank_id=mba.acc_bankid
                where sl_status=1 and inv_status=1 and inv_for=2 and inv_id=? order by inv_date desc",array($inv_id));
           //echo $this->db->last_query();die;
            $this->load->view('reports/gst_salesbill',$data);
            $this->load->view('default/right_sidebar'); 
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }
    }

    public function printSalesBillGst()
    {   
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');              
            $inv_id=(int)$this->uri->segment(3);
            
            $data['inventory_data']=$this->CrudModel->select_data("select f_name,f_logo,f_contact,f_email,f_address,inv_date,inv_id,inv_for,inv_perticular,inv_id,inv_docs,inv_type,inv_billno,sl_qty,sl_rpu,sl_disc,sl_disctype,sl_gstper,sl_mfdt,sl_expdt,prod_name,prod_unit,f_pan from s_sale sl
                left join s_invoice s on s.inv_id=sl.sl_invid 
                left join mas_product pm on pm.prod_id=sl.sl_prodid
                left join mas_firm mf on mf.f_id=s.inv_firmid
                where sl_status=1 and inv_status=1 and inv_for=2 and inv_id=? order by prod_hsn_sac",array($inv_id));
            $this->load->view('reports/gst_salesbillprint',$data);
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }
    }


    public function printReceipt()
    {   
        if($this->ion_auth->logged_in())
        {
            $this->load->view('default/header');              

            $data['location']=$this->input->post('location');
            $chk_sel=implode(',',$this->input->post('chk_sel'));

            if($chk_sel=='')
            {
                $this->session->set_flashdata('message','Please Select Transactions To Print');
                $this->session->set_flashdata('alert_type','alert-danger');
                redirect($data['location']);
            }

            $data['payhist_data']=$this->CrudModel->select_data("select f_name,f_logo,f_contact,f_email,f_address,inv_billno,inv_date,a.acc_id,inv_gross,inv_disc,inv_gstamt,inv_roundoff,acc_amt,inv_for,inv_type,inv_perticular,a.acc_trandt,a.acc_mode,a.acc_chqno,a.acc_onlineid,a.acc_chqdt,a.acc_entrydt,a.acc_remark,mc.c_name as bank,mba.acc_num from account a
                left join mas_bankacc mba on mba.acc_id = a.acc_bankacc_id
                left join mas_category mc on mc.c_id = mba.acc_bankid
                left join s_invoice si on si.inv_id = a.acc_sourceid
                left join mas_firm mf on mf.f_id=si.inv_firmid
            where a.acc_id IN ($chk_sel) order by a.acc_entrydt",array());

            $this->load->view('reports/print_receipt',$data);
            $this->load->view('default/footer');
        }
        else
        {
            redirect('auth/login');
        }
    }
//----------- End Of Class --------------
}
