$(document).ready(function(){
    srchMemReport();
});

function srchMemReport()
{
    var formdata=$('#srchldg_form').serialize();    
    
    $('#member_rep').html("<center><img src='" + base_url +"assets/images/loader/circle.gif' /></center>");
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
        $('#member_rep').html("<center class='cen no-data-found'>Please Select Member</center>");  
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


