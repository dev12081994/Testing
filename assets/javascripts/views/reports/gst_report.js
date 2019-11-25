$(function(){
    srchGstRep();   
});

function srchGstRep()
{
    var formdata=$('#srchgst_form').serialize();    
    $('#gst_table').html("<tr><td colspan='15' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'REPORTS/srchGstRep',
        data:formdata,
        success:function(data)
        {
            $('#gst_table').html(data);
        }
    });    
}

