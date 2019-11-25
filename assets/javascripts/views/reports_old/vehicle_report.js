$(function(){
    srchVrunDetails();   
});

function srchVrunDetails()
{
    var formdata=$('#srchvrun_form').serialize();    
    $('#vrun_table').html("<tr><td colspan='15' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
   
    $.ajax({
        type:'POST',
        url:base_url + 'REPORTS/srchVehicleRep',
        data:formdata,
        success:function(data)
        {
            $('#vrun_table').html(data);
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