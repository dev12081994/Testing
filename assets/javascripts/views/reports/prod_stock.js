$(function(){
    srchProdStock();   
});

function srchProdStock()
{
    var formdata=$('#srchproduct_form').serialize();    
    $('#product_table').html("<tr><td colspan='14' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'REPORTS/srchProdStock',
        data:formdata,
        success:function(data)
        {
            $('#product_table').html(data);
        }
    });    
}

