$(function(){
    srchSales();   
});

function srchSales()
{
    var formdata=$('#srchpurchase_form').serialize();    
    $('#purchase_table').html("<tr><td colspan='13' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'REPORTS/srchSales',
        data:formdata,
        success:function(data)
        {
            $('#purchase_table').html(data);
        }
    });    
}



function fetchMembers()
{
    var mem_type=$('#srch_memtype').val();
    if(parseInt(mem_type)>4)
    {
        mem_type=parseInt(mem_type)-1;
        url=base_url + "COMMON/fetchVehicleProject";
        data={data_type:mem_type};
    }
    else
    {
        url=base_url + "COMMON/fetchMembers";
        data={mem_type:mem_type};
    }
    $.ajax({
        type:'post',
        url:url,
        data:data,
        success:function(msg){
            $('#srch_member').html(msg).trigger('change');
        }
    });
}
