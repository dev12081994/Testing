<div class="table-responsive">
	<table <?php if($is_export==1) echo "border=1"; ?> class="table table-bordered table-striped table-condensed mb-none">
		<thead>
			<tr>
				<th>S.N.</th>
                <th>Entry</th> 
                <th title="Expense Date">Expense</th> 
                <th>Vehicle No.</th> 
                <th>Member Type</th> 
                <th>Paid To</th> 
                <th>Ledger</th>                                 
                <th>Amount</th>
                <th>Remark</th>
                <?php if($is_export==0) { 
                	$colspan=12;
                	$colspan_footer=5;
                ?>
                <th class="h">View</th>  
                <th class="h">Edit</th>  
                <th class="h">Delete</th>
            	<?php }else {$colspan=9;$colspan_footer=2;} ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$sl=1;$tot_amt=0;
			
			if(count($payment)===0 || $payment[0]['acc_id']===null)
			{
				echo '<tr><td colspan="'.$colspan.'" class="no-data-found">Record Not Found</td></tr>';
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
				echo "<td>". $value['ledger']."</td>";				
				echo "<td>". $amt ."</td>";
				echo "<td>". $value['acc_remark'] ."</td>";
				
				if($is_export==0) 
				{ 
					if($value['acc_docs']!='')
						echo "<td class='h'><center><a class='btn btn-success btn-xs' target='_blank' href='".base_url().$value['acc_docs']."'><i class='fa fa-eye'></i></a></center></td>";
					else
						echo "<td></td>";
					
					echo "<td class='h'><center><a class='btn btn-info btn-xs' href='".base_url().'vehicle/addVehicleExpense/'.$value['acc_id']."'><span class='fa fa-pencil'></span></a></center></td>";
					echo "<td class='h'><center><a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Delete'  onclick='$(".'"#del_id"'.").val(".$value['acc_id'].");'  href='#delConfirm'>
		                    <span class='fa fa-trash-o'></span></a></center></td>";
              	}
				echo "</tr>";		
			}
			echo "<tr>";
			echo "<td colspan='7'><strong>Total Amount : </strong></td>";


			echo "<td colspan='".$colspan_footer."'><strong>". $tot_amt ."</strong></td>";
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
		</tbody>
	</table>
</div>