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

