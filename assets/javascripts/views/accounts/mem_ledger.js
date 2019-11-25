$(document).ready(function(){
	srchLedger();
});

$('.form-control[required]').on('change keyup',function(){
    if($(this).val()==='')
        $(this).closest('div').removeClass('has-success').addClass('has-error');
    else
        $(this).closest('div').removeClass('has-error');
});

function srchLedger()
{
	var formdata=$('#srchldg_form').serialize();
	$('#ledger_table').html('<tr><td colspan="7" class="no-data-found cen">Please Select Member</td></tr>');
	$('#memname').html('Member Name : Not Selected');
	$('#membal').html('0');
	if($('#srch_cid').val()==='')
	{
		$('#srch_cid').closest('div').removeClass('has-success').addClass('has-error');
		return false;
	}
	else
		$('#srch_cid').closest('div').removeClass('has-error');

	$.ajax({
		type:'post',
		url: base_url + 'ACCOUNTS/srchLedger',
		data:formdata,
		success:function(msg)
		{
			$('#ledger_table').html(msg);
		}
	});
	return true;
}

function fetchSourceData()
{
    var vochfor=$('#srch_for').val();
    var url="";
    var data="";
    $('#srch_cid').html('<option value="">Select Option</option>').trigger('change');

    if(parseInt(vochfor)>4)
    {
        vochfor=parseInt(vochfor)-2;
        url=base_url + "COMMON/fetchVehicleProject";
        data={data_type:vochfor};
    }
    else
    {
        url=base_url + "COMMON/fetchMembers";
        data={mem_type:vochfor};
    }

    $.ajax({
        type:'post',
        url:url,
        data:data,
        success:function(msg){
            $('#srch_cid').html(msg).trigger('change');
        }
    });
}

function printDiv(div_id) 
{    
    $('#print_head').css({display:'block'});      
    $('table').css({'border':'1px solid black','border-collapse':'collapse','border-spacing':'0px','width':'100%'});   
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
    $('td').removeAttr('style');         
    $('th').removeAttr('style');         
}