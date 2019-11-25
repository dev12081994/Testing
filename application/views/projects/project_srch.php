<table <?php if($is_export > 0) echo "border=1"; ?> class="table table-bordered table-striped table-condensed mb-none">
	<thead>
		<tr>
			<th>S.N.</th>
			<th>Type</th>
			<th>Project</th>
			<th>Start Date</th>
			<th>Duration</th>
			<th>Amount</th>
			<th>Expense</th>
			<th>Received</th>
			<th>Remark</th>
			<th>Complete Date</th>
			<?php if($is_export == 0) { $colspan=14; ?> 
			<th>Status</th>
			<th>View</th>
			<th>Edit</th>
			<th>Delete</th>
			<?php }else { $colspan=10; } ?>
		</tr>
	</thead>
	<tbody>
		<?php 

			if(count($proj_data) ==0  || $proj_data[0]['proj_id']==null)
			{
				echo "<tr><td colspan='".$colspan."' class='cen no-data-found'>Record Not Found</td></tr>";
				
			}
			else
			{
				$sn=1;
				foreach ($proj_data as $key => $value) 
				{ 
					if($value['proj_durtype']=='1')
						$duration=$value['proj_duration'].' Days';
					else if($value['proj_durtype']=='2')
						$duration=$value['proj_duration'].' Months';
					else if($value['proj_durtype']=='3')
						$duration=$value['proj_duration'].' Years';
					else if($value['proj_enddt']!='0000-00-00')
						$duration=ceil((strtotime($value['proj_enddt'])-strtotime($value['proj_startdt']))/86400).' Days';
					else
						$duration='';	

					$balamt_data=$this->CrudModel->select_data("select acc_id,sum(case when acc_trantype =2 then acc_amt else 0 end) as cr_amt,sum(case when acc_trantype =1 then acc_amt else 0 end) as dr_amt  from account where acc_cid=? and acc_vochfor=4 group by acc_cid",array($value['proj_id']));	

					$proj_amt=round($value['proj_amt'],2);
					$exp_amt=0;
					$received_amt=0;

					if(count($balamt_data)>0 && $balamt_data[0]['acc_id']!=null)
					{
						$exp_amt=round($balamt_data[0]['dr_amt'],2);
						$received_amt=round($balamt_data[0]['cr_amt'],2);
					}				
					
					?>
					<tr id="row_<?php echo $value['proj_id']; ?>">
						<td><?php echo $sn++; ?></td>
						<td><?php if($value['proj_type']=='1') echo "Govt."; else echo "Private"; ?></td>
						<td><?php echo ucwords(strtolower($value['proj_name'])); ?></td>
						<td><?php echo date('d-m-Y',strtotime($value['proj_startdt'])); ?></td>
						<td><?php echo $duration; ?></td>
						<td><?php echo $proj_amt; ?></td>
						<td><?php echo $exp_amt; ?></td>
						<td><?php echo $received_amt; ?></td>
						<td><?php echo $value['proj_remark']; ?></td>
						<td><?php if($value['prod_donedt']!='0000-00-00')echo date('d-m-Y',strtotime($value['prod_donedt'])); ?></td>

						<?php if($is_export == 0) { ?> 
						<td class="cen">
							<?php 
							if($value['prod_isdone']==1)
								echo '<span style="font-weight:bold;color:green">Done</span>';		
							else { ?>
								<a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Click To Complete' onclick='$("#projid_done").val(<?php echo $value['proj_id']; ?>);' href='#done_proj'>Pending</a>
							<?php } ?>
						 </td>
						<td class='h cen'>
							<?php if($value['proj_docs']!=''){ ?>
							<a class='btn btn-success btn-xs mb-xs mt-xs mr-xs' target='_blank' href="<?php echo base_url().$value['proj_docs']; ?>"><i class='fa fa-eye'></i></a>
							<?php } ?>
						</td>
						<td class='h cen'>
							<a class='btn btn-info btn-xs mb-xs mt-xs mr-xs' onclick="projectData('<?php echo $value['proj_id']; ?>')" <?php if($value['prod_isdone']==1)echo "disabled"; ?>><span class='fa fa-pencil'></span></a>
						</td>
						<td class='h cen'>
							<a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Delete' onclick="$('#del_id').val('<?php echo $value['proj_id']; ?>');"  href='#delConfirm'  <?php if($value['prod_isdone']==1)echo "disabled"; ?>	>
		                        <span class='fa fa-trash-o'></span></a>
						</td>
					<?php } ?>
					</tr>
					<?php
				}
			}
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