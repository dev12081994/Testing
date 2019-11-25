$(function(){
    srchProduct();   
    fetchGstList();
});

function srchProduct()
{
    var formdata=$('#srchproduct_form').serialize();    
    $('#product_table').html("<tr><td colspan='14' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'MASTERS/srchProduct',
        data:formdata,
        success:function(data)
        {
            $('#product_table').html(data);
        }
    });
    
}

function confirm_deletion()
{
    var prod_id=$('#del_id').val();
   
    $.ajax({
        type:"POST",
        url:base_url + "MASTERS/deleteProduct",
        data:{prod_id:prod_id},
        success:function(msg)
        {
            showNotification(msg);
            var obj = JSON.parse(msg);
            if(obj.type==='success')
            {
                $('#row_' + prod_id).remove();
                $('.modal-dismiss').trigger('click');                                        
            }
        }
    });           
}   


function clearForm()
{
    $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
    $('#submit_product').attr('disabled',false).html('Submit');      
}

$('#submit_product').on('click',function(){
    var error_count=0
    $('#add_prod_form').find('.form-control[required]').each(function(){ 
        if($(this).val()==='')
        {
            $(this).closest('div').removeClass('has-success').addClass('has-error');
            error_count++;
        }
        else
            $(this).closest('div').removeClass('has-error');
    });

    if(error_count === 0)
    {
        $(this).attr('disabled',true).html('Processing...');
        var formdata=$('#add_prod_form').serialize();    
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/saveProduct",
            data:formdata,
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);                 

                if(obj.process==='1')
                {
                    clearForm(); 
                    $('.reload-table').trigger('click');                      
                }
                else if(obj.process === '2')
                {                                
                    clearForm();    
                    $('.reload-table').trigger('click');         
                    $('[ data-dismiss=modal]').trigger('click');                                                              
                }
                else
                {
                    $('#submit_product').attr('disabled',false).html('Submit');
                }

            }
        });
    }         
});

$('.form-control[required]').on('change keyup',function(){
    if($(this).val()==='')
        $(this).closest('div').removeClass('has-success').addClass('has-error');
    else
        $(this).closest('div').removeClass('has-error');
});


function fetchEditData(id)
{   
    var table,aliaz;
    
    table='mas_product';
    aliaz='prod';
    $.ajax({
        type:"POST",
        url:base_url + "MASTERS/fetchEditData",
        data:{id:id,table:table,aliaz:aliaz},
        success:function(msg)
        {
            var obj = JSON.parse(msg);
            $('#prod_id').val(obj[0].prod_id);
            $('#prod_ctgid').val(obj[0].prod_ctgid).trigger('change');   
            $('#prod_unit').val(obj[0].prod_unit).trigger('change');   
            $('#prod_remark').val(obj[0].prod_remark);   
            $('#prod_purrate').val(obj[0].prod_purrate);   
            $('#prod_salerate').val(obj[0].prod_salerate);   
            $('#prod_isgst').val(obj[0].prod_isgst);   
            $('#prod_purgstincl').val(obj[0].prod_purgstincl);   
            $('#prod_hsn_sac').val(obj[0].prod_hsn_sac);   
            $('#prod_gstrate').val(obj[0].prod_gstrate);   
            $('#prod_name').val(obj[0].prod_name);   
            $('#prod_openstock').val(obj[0].prod_openstock);   
            $('#submit_product').html('Update');
            $('#prod_name').focus();  

            gstRequired();

            $('#addproduct_modal').modal('show');
        }
    });         
}

function fetchGstList()
{   
    $.ajax({
        type:"POST",
        url:base_url + "COMMON/fetchGstList",
        data:{},
        success:function(msg)
        {
            $('#prod_gstrate').html(msg);
        }
    });         
}
function gstRequired()
{
    var prod_isgst=$('#prod_isgst').val();
    
    if(prod_isgst==='1')
    {
        $('#prod_purgstincl').attr({'required':true});
        $('#prod_gstrate').attr({'required':true});        
    }
    else
    {
        $('#prod_purgstincl').removeAttr('required').val('').closest('div').removeClass('has-error');;
        $('#prod_gstrate').removeAttr('required').val('').closest('div').removeClass('has-error');;
    }
}