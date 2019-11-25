
$('#submit').on('click',function(){    
    $('#payment_form').find('.form-control[required]').each(function(){ 
        if($(this).val()==='')
            $(this).closest('div').removeClass('has-success').addClass('has-error');
        else
            $(this).closest('div').removeClass('has-error');
    });
    return true;
});

$('.form-control[required]').on('change keyup',function(){
    if($(this).val()==='')
        $(this).closest('div').removeClass('has-success').addClass('has-error');
    else
        $(this).closest('div').removeClass('has-error');
});

function fetchMembers(cid=null)
{
	var mem_type=$('#member_type').val();
	$.ajax({
		type:'post',
		url:base_url + 'INVENTORY/fetchMembers',
		data:{mem_type:mem_type},
		success:function(msg){
			$('#acc_cid').html(msg).trigger('change');

			if(cid!==null)
			{
				$('#acc_cid').val(cid).trigger('change');
			}
		}
	});
}

function fetchSourceData(cid=null)
{
	var vochfor=$('#acc_vochfor').val();
	$('#acc_sourceid').html('<option value="">Select Option</option>').trigger('change');
	if(vochfor==='3' || vochfor==='4')
	{
		$('#acc_sourceid').attr('required',true).removeAttr('disabled');
		$('.source').show();
		$.ajax({
			type:'post',
			url:base_url + 'COMMON/fetchVehicleProject',
			data:{data_type:vochfor},
			success:function(msg){
				$('#acc_sourceid').html(msg).trigger('change');
				if(cid!==null)
				{
					$('#acc_sourceid').val(cid).trigger('change');
				}
			}
		});
	}
	else
	{
		$('#acc_sourceid').val('').attr('disabled',true).removeAttr('required').closest('div').removeClass('has-error').trigger('change');
	}	
}