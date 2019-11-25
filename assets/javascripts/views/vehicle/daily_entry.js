$(function(){
    srchVrunDetails();   
});

function calAmt()
{
    let rate=$("#vrun_rate").val();
    let qty=$("#vrun_qty").val();

    if(isNaN(rate) || $.trim(rate)=="")
        rate=0;
    if(isNaN(qty) || $.trim(qty)=="")
        qty=0;

    $("#vrun_fareamt").val((rate*qty).toFixed(2));
}

function calBal()
{
    var balamt=$('#remain_amt').val();
    var settle_amt=$('#settle_amt').val();
    if(parseFloat(settle_amt)<=parseFloat(balamt))
    {
        $('#bal_amt').val(parseFloat(balamt)-parseFloat(settle_amt));
        $('#err_msg').html('');
    }
    else
    {        
        $('#bal_amt').val(0);
        $('#settle_amt').val('');
        $('#err_msg').html('Amount Should Not Be Greater Than Remaining Amount');
    }
}

function billSattlement(vrun_id)
{
    $.ajax({
        type:"POST",
        url:base_url + "VEHICLE/fetchBillData",
        data:{vrun_id:vrun_id},
        success:function(msg)
        {
            var obj = JSON.parse(msg);
            $('#inv_id').val(vrun_id);
            $('#remain_amt').val(obj.bal_amt);
        }
    });         
}

function srchVrunDetails()
{
    var formdata=$('#srchvrun_form').serialize();    
    $('#vrun_table').html("<center><img src='" + base_url +"assets/images/loader/circle.gif' /></center>");
   
    $.ajax({
        type:'POST',
        url:base_url + 'VEHICLE/srchVrunDetails',
        data:formdata,
        success:function(data)
        {
            $('#vrun_table').html(data);
        }
    });
}

function fetchMembers(cid=null)
{
	var mem_type=$('#vrun_memtype').val();

	$.ajax({
		type:'post',
		url:base_url + 'INVENTORY/fetchMembers',
		data:{mem_type:mem_type},
		success:function(msg){
			$('#vrun_memid').html(msg).trigger('change');

			if(cid!==null)
			{
				$('#vrun_memid').val(cid).trigger('change');
			}
		}
	});
}

function clearForm()
{
    $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
    $('#submit').removeAttr('disabled').html('Submit');     
}

$('#vrun_runstatus').on('change',function(){
	if($(this).val()==='2')
	{
		$('.no-run').attr('disabled',true);
		$('.requir').removeAttr('required');
	}
	else
	{
		$('.requir').attr('required',true);
		$('.no-run').removeAttr('disabled');
	}
});

function fetchVehicle(vid=null)
{
	var vtype=$('#vrun_vtype').val();
	$.ajax({
		type:'post',
		url:base_url + 'VEHICLE/fetchVehicle',
		data:{vtype:vtype},
		success:function(msg){
			$('#vrun_vid').html(msg).trigger('change');

			if(vid!==null)
			{
				$('#vrun_vid').val(vid).trigger('change');
			}
		}
	});
}

$('.form-control[required]').on('change keyup',function(){
    if($(this).val()==='')
        $(this).closest('.form-group1').removeClass('has-success').addClass('has-error');
    else
        $(this).closest('.form-group1').removeClass('has-error');
});

$('#submit').on('click',function(){
    $('#add_vrun_form').find('.form-control[required]').each(function(){ 
        if($(this).val()==='')
        {
            $(this).closest('.form-group1').removeClass('has-success').addClass('has-error');            
        }
        else
            $(this).closest('.form-group1').removeClass('has-error');	
    });    
});

function fetchVrunData(vrun_id)
{  
    $.ajax({
        type:"POST",
        url:base_url + "VEHICLE/fetchVrunData",
        data:{vrun_id:vrun_id},
        success:function(msg)
        {
            var obj = JSON.parse(msg);
            $('#vrun_id').val(obj[0].vrun_id);
            $('#vrun_date').val(obj[0].vrun_date);
            $('#vrun_vtype').val(obj[0].v_typeid); 
           	fetchVehicle(obj[0]['v_id']);
            $('#vrun_runstatus').val(obj[0].vrun_runstatus);   
            $('#vrun_meterstart').val(obj[0].vrun_meterstart);   
            $('#vrun_meterstop').val(obj[0].vrun_meterstop);   
            $('#vrun_memtype').val(obj[0].c_type);   
           	fetchMembers(obj[0].vrun_memid);
           	$('#vrun_drivid').val(obj[0].vrun_drivid).trigger('change');
           	$('#vrun_work').val(obj[0].vrun_work);
           	$('#vrun_fareamt').val(obj[0].vrun_fareamt);
           	$('#vrun_from').val(obj[0].vrun_from);
           	$('#vrun_to').val(obj[0].vrun_to);
           	$('#vrun_remark').val(obj[0].vrun_remark);
            $('#vrun_qtytype').val(obj[0].vrun_qtytype);
            $('#vrun_qty').val(obj[0].vrun_qty);
            $('#vrun_rate').val(obj[0].vrun_rate);
            $('#vehicle_modal').modal('show');
        }
    });         
}

function confirm_deletion()
{
    var vrun_id=$('#del_id').val();
   
    $.ajax({
        type:"POST",
        url:base_url + "VEHICLE/deleteVrunDetails",
        data:{vrun_id:vrun_id},
        success:function(msg)
        {
            showNotification(msg);
            var obj = JSON.parse(msg);
            if(obj.type==='success')
            {
                $('#row_' + vrun_id).remove();
                $('.modal-dismiss').trigger('click');                                        
            }
        }
    });           
} 



function showBankOpt()
{
    var pay_type=$('#acc_trantype').val();  
    removePaymentOpt();
    if(pay_type==='2')
    {   
        $('.remark-div').removeClass('col-lg-9').addClass('col-lg-12'); 
        $('.chqdiv').removeClass('hide');
        $('.comndiv').removeClass('hide');
        $('.chqctrl').attr({required:true});
        $('.comnctrl').attr({required:true});

        $('.onldiv').addClass('hide');
        $('.onlctrl').removeAttr('required');
    }
    else if(pay_type==='3')
    {    
        $('.remark-div').removeClass('col-lg-9').addClass('col-lg-12'); 
        $('.onldiv').removeClass('hide');
        $('.onlctrl').attr({required:true});
        $('.comndiv').removeClass('hide');
        $('.comnctrl').attr({required:true});

        $('.chqdiv').addClass('hide');
        $('.chqctrl').removeAttr('required');
    }
    else
    {
        $('.remark-div').removeClass('col-lg-12').addClass('col-lg-9'); 
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

function getAccNo(rowid)
{
    var bankid=$('#acc_bankid').val();
    $.ajax({
        type:'post',
        url: base_url + 'INVENTORY/getAccNo',
        data:{bankid:bankid},
        success:function(msg)
        {
            $('#acc_bankaccid').html(msg).trigger('change');
        }
    });
}