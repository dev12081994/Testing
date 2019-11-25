$(document).ready(function(){
	srchExpense();
	fetchSourceData();
});

function srchExpense()
{
	var formdata=$('#srchexp_form').serialize();
	$('#exp_table').html("<tr><td colspan='12' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
	$.ajax({
		type:'post',
		url: base_url + 'REPORTS/srchVehicleExp',
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



function printDiv(div_id) 
{    
    $('#print_head').css({display:'block'});      
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
    $('td').removeAttr('style');         
    $('th').removeAttr('style');         
}