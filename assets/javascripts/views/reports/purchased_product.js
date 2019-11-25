$(function(){
    srchPurchase();   
});

function srchPurchase()
{
    var formdata=$('#srchpurchase_form').serialize();    
    $('#purchase_table').html("<tr><td colspan='13' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'REPORTS/srchPurchase',
        data:formdata,
        success:function(data)
        {
            $('#purchase_table').html(data);
        }
    });    
}

