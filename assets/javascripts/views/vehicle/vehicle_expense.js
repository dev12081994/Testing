$(document).ready(function(){
	srchExpense();
	fetchSourceData();
});

function srchExpense()
{
	var formdata=$('#srchexp_form').serialize();
	$('#exp_table').html("<center><img src='" + base_url +"assets/images/loader/circle.gif' /></center>");
	$.ajax({
		type:'post',
		url: base_url + 'VEHICLE/srchVehicleExpense',
		data:formdata,
		success:function(msg)
		{
			$('#exp_table').html(msg);
		}
	});
}

function fetchSourceData()
{
	var vochfor='3';
	$.ajax({
		type:'post',
		url:base_url + 'COMMON/fetchVehicleProject',
		data:{data_type:vochfor},
		success:function(msg){
			$('#srch_sourceid').html(msg).trigger('change');
		}
	});
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