
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
		$('#member_type').removeAttr('required').attr('disabled',true).val('').trigger('change');
		$('#acc_cid').removeAttr('required').attr('disabled',true).val('').trigger('change');
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
		$('#member_type').attr('required',true).removeAttr('disabled');
		$('#acc_cid').attr('required',true).removeAttr('disabled');
	}
	
}

function showBankOpt(mode=null)
{
	var pay_type=$('#acc_mode').val();	

	if(mode===null)
		removePaymentOpt();

	if(pay_type==='2')
	{		
		$('.chqdiv').removeClass('hide');
		$('.comndiv').removeClass('hide');
		$('.chqctrl').attr({required:true});
		$('.comnctrl').attr({required:true});

		$('.col3').removeClass('col-lg-3').addClass('col-lg-2');

		$('.onldiv').addClass('hide');
		$('.onlctrl').removeAttr('required');
	}
	else if(pay_type==='3')
	{		
		$('.onldiv').removeClass('hide');
		$('.onlctrl').attr({required:true});
		$('.comndiv').removeClass('hide');
		$('.comnctrl').attr({required:true});

		$('.col3').removeClass('col-lg-2').addClass('col-lg-3');

		$('.chqdiv').addClass('hide');
		$('.chqctrl').removeAttr('required');
	}
}

function removePaymentOpt()
{
	$('.chqdiv').addClass('hide');
	$('.onldiv').addClass('hide');
	$('.comndiv').addClass('hide');
	$('.chqctrl').val('').trigger('change').removeAttr('required');
	$('.onlctrl').val('').trigger('change').removeAttr('required');
	$('.comnctrl').val('').trigger('change').removeAttr('required');
}
    

function getAccNo(accno=null)
{
	var bankid=$('#acc_bankid').val();
	$.ajax({
		type:'post',
		url: base_url + 'INVENTORY/getAccNo',
		data:{bankid:bankid},
		success:function(msg)
		{
			$('#acc_bankaccid').html(msg).trigger('change');
			if(accno!==null)
				$('#acc_bankaccid').val(accno).trigger('change');
		}
	});
}