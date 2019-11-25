$(function(){
    srchPurchase();   
});

function srchPurchase()
{
    var formdata=$('#srchpurchase_form').serialize();    
    $('#purchase_table').html("<tr><td colspan='12' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:'POST',
        url:base_url + 'INVENTORY/srchPurchase',
        data:formdata,
        success:function(data)
        {
            $('#purchase_table').html(data);
        }
    });    
}

function showInvoiceInfo(invid,invfor)
{    
    $.ajax({
        type:'POST',
        url:base_url + 'INVENTORY/showInvoiceInfo',
        data:{invid:invid,invfor:invfor},
        success:function(data)
        {
            $('#invinfo_table').html(data);
        }
    });    
}


function confirm_deletion()
{
    var inv_id=$('#del_id').val();
   
    $.ajax({
        type:"POST",
        url:base_url + "INVENTORY/deleteInvoice",
        data:{inv_id:inv_id},
        success:function(msg)
        {
            showNotification(msg);
            var obj = JSON.parse(msg);
            if(obj.type==='success')
            {
                $('#row_' + inv_id).remove();
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
            $('#prod_name').val(obj[0].prod_name);   
            $('#submit_product').html('Update');
            $('#prod_name').focus();  
            $('#addproduct_modal').modal('show');
        }
    });         
}

function billSattlement(inv_id)
{
    $.ajax({
        type:"POST",
        url:base_url + "INVENTORY/fetchBillData",
        data:{inv_id:inv_id},
        success:function(msg)
        {
            var obj = JSON.parse(msg);
            $('#inv_id').val(inv_id);
            $('#remain_amt').val(obj.bal_amt);
        }
    });         
}

function showPaymentHistory(inv_id)
{
    $('#payhist_table').html("<tr><td colspan='12' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
    $.ajax({
        type:"POST",
        url:base_url + "INVENTORY/showPaymentHistory",
        data:{inv_id:inv_id},
        success:function(msg)
        {
            $('#hist_invid').html(inv_id);
            $('#payhist_table').html(msg);
        }
    });         
}

function calBal()
{
    var balamt=$('#remain_amt').val();
    var settle_amt=$('#settle_amt').val();
    if(parseFloat(settle_amt)<=parseFloat(balamt))
    {
        $('#bal_amt').val(parseFloat(balamt)-parseFloat(settle_amt));
        $('#err_msg').html('');
    }
    else
    {        
        $('#bal_amt').val(0);
        $('#settle_amt').val('');
        $('#err_msg').html('Amount Should Not Be Greater Than Remaining Amount');
    }
}

function showBankOpt()
{
    var pay_type=$('#acc_trantype').val();  
    removePaymentOpt();
    if(pay_type==='2')
    {   
        $('.remark-div').removeClass('col-lg-9').addClass('col-lg-12'); 
        $('.chqdiv').removeClass('hide');
        $('.comndiv').removeClass('hide');
        $('.chqctrl').attr({required:true});
        $('.comnctrl').attr({required:true});

        $('.onldiv').addClass('hide');
        $('.onlctrl').removeAttr('required');
    }
    else if(pay_type==='3')
    {    
        $('.remark-div').removeClass('col-lg-9').addClass('col-lg-12'); 
        $('.onldiv').removeClass('hide');
        $('.onlctrl').attr({required:true});
        $('.comndiv').removeClass('hide');
        $('.comnctrl').attr({required:true});

        $('.chqdiv').addClass('hide');
        $('.chqctrl').removeAttr('required');
    }
    else
    {
        $('.remark-div').removeClass('col-lg-12').addClass('col-lg-9'); 
    }
}

function removePaymentOpt()
{
    $('.chqdiv').addClass('hide');
    $('.onldiv').addClass('hide');
    $('.comndiv').addClass('hide');
    $('.chqctrl').val('').trigger('change').removeAttr('required');
    $('.onlctrl').val('').trigger('change').removeAttr('required');
    $('.comnctrl').val('').trigger('change').removeAttr('required');
}

function getAccNo(rowid)
{
    var bankid=$('#acc_bankid').val();
    $.ajax({
        type:'post',
        url: base_url + 'INVENTORY/getAccNo',
        data:{bankid:bankid},
        success:function(msg)
        {
            $('#acc_bankaccid').html(msg).trigger('change');
        }
    });
}