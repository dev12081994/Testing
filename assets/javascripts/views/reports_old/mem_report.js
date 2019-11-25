$(document).ready(function(){
    srchMemReport();
});

function srchMemReport()
{
    var formdata=$('#srchldg_form').serialize();    
    
    $('#member_rep').html("<tr><td colspan='16' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
   var mem_id=$('#srch_cid').val(); 
   if(mem_id!=='')
   {
        $.ajax({
            type:'POST',
            url:base_url + 'REPORTS/srchMemReport',
            data:formdata,
            success:function(data)
            {
                $('#member_rep').html(data);
            }
        });
    }
    else
    {
        $('#member_rep').html("<tr><td colspan='14' class='cen no-data-found'>Please Select Member</td></tr>");  
    }    
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