$(function(){
    srchMemWiseInv();   
});
function srchMemWiseInv()
{
    var formdata=$('#srchpurchase_form').serialize();   

    $('#inv_table').html("<tr><td colspan='8' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>"); 
    $.ajax({
        type:'POST',
        url:base_url + 'INVENTORY/srchMemWiseInv',
        data:formdata,
        success:function(data)
        {
            $('#inv_table').html(data);
        }
    });    
}

function showMemberInfo(member,type,inv_for)
{    
    $('#invinfo_table').html("<tr><td colspan='10' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>"); 
    var srch_tdate=$('#srch_tdate').val();
    var srch_fdate=$('#srch_fdate').val();
    $.ajax({
        type:'POST',
        url:base_url + 'INVENTORY/showMemberInfo',
        data:{member:member,type:type,inv_for:inv_for,srch_fdate:srch_fdate,srch_tdate:srch_tdate},
        success:function(data)
        {
            $('#invinfo_table').html(data);
        }
    });
    
}

function fetchMembers()
{
    var mem_type=$('#srch_invtype').val();
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
            $('#srch_perticular').html(msg).trigger('change');
        }
    });
}
