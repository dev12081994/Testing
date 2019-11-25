<div class="table-responsive">
	<table class="table table-bordered table-striped table-condensed mb-none">
		<thead>
			<tr>
				<th>S.N.</th>
				<th>Date</th>
				<th>Vehicle</th>
				<th>Number</th>
				<th>Driver</th>
				<th>Status</th>
				<th>Meter Start</th>
				<th>Meter Stop</th>
				<th>Customer</th>
				<th>From</th>
				<th>To</th>							
				<th>Work</th>
				<th>Duration</th>
				<th>Rate</th>
				<th>Amount</th>
				<th>Remark</th>
				<th>View</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($vrun_data) ==0  || $vrun_data[0]['vrun_id']==null)
			{
				echo "<tr><td colspan='20' class='cen no-data-found'>Record Not Found</td></tr>";
				die();
			}

			$sn=1;
			foreach ($vrun_data as $key => $value) { 
						
			?>
			<tr id="row_<?php echo $value['vrun_id']; ?>">
				<td><?php echo $sn++; ?></td>
				<td><?php echo date('d-m-Y',strtotime($value['vrun_date'])); ?></td>
				<td><?php echo strtoupper($value['vt_name']); ?></td>
				<td><?php echo strtoupper($value['v_num']); ?></td>
				<td><?php echo str_replace('  ',' ',$value['driver']); ?></td>
				<td><?php if($value['vrun_runstatus']==1)echo 'Running';else echo 'Stopped'; ?></td>
				<td><?php echo $value['vrun_meterstart']; ?></td>
				<td><?php echo $value['vrun_meterstop']; ?></td>
				<td><?php echo str_replace('  ',' ',$value['customer']); ?></td>
				<td><?php echo $value['vrun_from']; ?></td>
				<td><?php echo $value['vrun_to']; ?></td>
				<td><?php echo $value['vrun_work']; ?></td>
				<td><?php echo round($value['vrun_qty'],2)." " . $value['vqt_name']; ?></td>
				<td><?php echo $value['vrun_rate']; ?></td>
				<td><?php echo $value['vrun_fareamt']; ?></td>
				<td><?php echo $value['vrun_remark']; ?></td>
				<td class='h cen' style="vertical-align: middle;">
					<?php if($value['vrun_docs']!=''){ ?>
					<a class='btn btn-success btn-xs'  target='_blank' href="<?php echo base_url().$value['vrun_docs']; ?>"><i class='fa fa-eye'></i></a>
					<?php } ?>
				</td>
				<td class='h cen'  style="vertical-align: middle;">
					<a class='btn btn-info btn-xs' onclick="fetchVrunData('<?php echo $value['vrun_id']; ?>')"><span class='fa fa-pencil'></span></a>
				</td>
				<td class='h cen'  style="vertical-align: middle;">
					<a class='mb-xs mt-xs mr-xs btn btn-danger btn-xs modal-basic' title='Delete' onclick="$('#del_id').val('<?php echo $value['vrun_id']; ?>');"  href='#delConfirm'>
                        <span class='fa fa-trash-o'></span></a>
				</td>
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
			
		</tbody>
	</table>
</div>