$(document).ready(function(){
	srchPayment();
});

function srchPayment()
{
	var formdata=$('#srchpurchase_form').serialize();
	$('#payment_table').html("<tr><td colspan='14' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
	$.ajax({
		type:'post',
		url: base_url + 'ACCOUNTS/srchPayment',
		data:formdata,
		success:function(msg)
		{
			$('#payment_table').html(msg);
		}
	});
}

function fetchSourceData()
{
	var vochfor=$('#srch_vochfor').val();
	$('#srch_sourceid').html('<option value="">Select Option</option>').trigger('change');
	if(vochfor==='3' || vochfor==='4')
	{
		$('#srch_sourceid').removeAttr('disabled');
		$('#srch_ctype').val('').trigger('change').attr('disabled',true);
		$('#srch_cid').val('').trigger('change').attr('disabled',true);
		$.ajax({
			type:'post',
			url:base_url + 'COMMON/fetchVehicleProject',
			data:{data_type:vochfor},
			success:function(msg){
				$('#srch_sourceid').html(msg).trigger('change');
			}
		});
	}
	else
	{
		$('#srch_sourceid').val('').attr('disabled',true).closest('div').trigger('change');
		$('#srch_ctype').removeAttr('disabled');
		$('#srch_cid').removeAttr('disabled');
	}	
}

function fetchMembers()
{
	var mem_type=$('#srch_ctype').val();
	$.ajax({
		type:'post',
		url:base_url + 'INVENTORY/fetchMembers',
		data:{mem_type:mem_type},
		success:function(msg){
			$('#srch_cid').html(msg).trigger('change');
		}
	});
}


function confirm_deletion()
{
    var acc_id=$('#del_id').val();
   
    $.ajax({
        type:"POST",
        url:base_url + "ACCOUNTS/deleteVoucher",
        data:{acc_id:acc_id},
        success:function(msg)
        {
            showNotification(msg);
            var obj = JSON.parse(msg);
            if(obj.type==='success')
            {
                $('#row_' + acc_id).remove();
                $('.modal-dismiss').trigger('click');                                        
            }
        }
    });           
} 

$('form').on('submit',function(){
	$('#prod_ids').val(row_id);
	return true;
});

function showPayDiv()
{
	var inv_type=$('#inv_type').val();

	if(inv_type==='2')
	{		
		$('.paiddiv').removeClass('hide');
		$('#acc_trantype').attr({required:true});
	}
	else
	{		
		$('.paiddiv').addClass('hide');
		$('#acc_trantype').val('').removeAttr('required');
	}
}

function showBankOpt()
{
	var pay_type=$('#acc_trantype').val();
	
	$('.chqdiv').addClass('hide');
	$('.onldiv').addClass('hide');
	$('.chqctrl').removeAttr('required');
	$('.onlctrl').removeAttr('required');
	
	if(pay_type==='2')
	{		
		$('.chqdiv').removeClass('hide');
		$('.comndiv').removeClass('hide');
		$('.chqctrl').attr({required:true});
		$('.comnctrl').attr({required:true});

		$('.onldiv').addClass('hide');
		$('.onlctrl').removeAttr('required');
	}
	else if(pay_type==='3')
	{		
		$('.onldiv').removeClass('hide');
		$('.onlctrl').attr({required:true});
		$('.comndiv').removeClass('hide');
		$('.comnctrl').attr({required:true});

		$('.chqdiv').addClass('hide');
		$('.chqctrl').removeAttr('required');
	}
}

function getAccNo(rowid)
{
	var bankid=$('#acc_bankid').val();
	$.ajax({
		type:'post',
		url: base_url + 'INVENTORY/getAccNo',
		data:{bankid:bankid},
		success:function(msg)
		{
			$('#acc_bankaccid').html(msg);
		}
	});
}

function printDiv(div_id) 
{    
    $('#print_head').css({display:'block'});      
    $('.h').css({display:'none'});      
    $('table').css({'border':'1px solid black','border-collapse':'collapse','border-spacing':'0px'});   
    $('td').css({'border':'1px solid black','border-collapse':'collapse','border-spacing':'0px'});   
    $('th').css({'border':'1px solid black','border-collapse':'collapse','border-spacing':'0px'});   
   
    var divToPrint = document.getElementById(div_id);
    var popupWin = window.open('', '_blank');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
      window.close();
    popupWin.document.close();    
    
    $('#print_head').css({display:'none'}); 
    $('table').removeAttr('style');         
    $('.h').removeAttr('style');  
    $('td').removeAttr('style');         
    $('th').removeAttr('style');         
}