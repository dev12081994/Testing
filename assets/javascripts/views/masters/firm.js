    global_acc='';
    $(function(){
        $('.reload-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit').removeAttr('disabled').html('Submit');     
    }

    function getAccNo()
    {
        var bankid=$('#f_bank').val();
        $.ajax({
            type:'post',
            url: base_url + 'INVENTORY/getAccNo',
            data:{bankid:bankid},
            success:function(msg)
            {
                $('#f_bankacc').html(msg).val(global_acc).trigger('change');
            }
        });
    }

    $('#submit').on('click',function(){
        var error_count=0
        $('#add_cat_form').find('.form-control[required]').each(function(){ 
            if($(this).val()==='')
            {
                $(this).closest('div').removeClass('has-success').addClass('has-error');
                error_count++;
            }
            else
                $(this).closest('div').removeClass('has-error');
        });

        /*if(error_count === 0)
        {
            $('#submit').attr('disabled',true).html('Processing...');
            var formdata=$('#add_cat_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveFirm",
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
                        $('#submit').removeAttr('disabled').html('Submit');
                    }

                }
            });
        } */        
    });

    function categoryData(f_id)
    {
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/firmData",
            data:{f_id:f_id},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                global_acc=obj[0].acc_id;
                $('#f_id').val(obj[0].f_id);
                $('#f_name').val(obj[0].f_name);               
                $('#f_contact').val(obj[0].f_contact);               
                $('#f_email').val(obj[0].f_email);               
                $('#f_gstin').val(obj[0].f_gstin);               
                $('#f_state').val(obj[0].f_state).trigger('change');  
                $('#f_bank').val(obj[0].acc_bankid).trigger('change'); 
                $('#f_pan').val(obj[0].f_pan);  
                $('#f_address').val(obj[0].f_address);  
                $('#submit').html('Update');
                $('#f_name').focus();  
                $('#categ_modal').modal('show');
            }
        });         
    }

    function confirm_deletion()
    {
        var f_id=$('#del_id').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/delFirm",
            data:{f_id:f_id},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + f_id).remove();
                    if($('#table_data tr').length===1)
                    {
                        $('.reload-table').trigger('click');
                    }
                    $('.modal-dismiss').trigger('click');
                }
            }
        });           
    }


    $('.reload-table').on('click',function(){

        var srch_firm=$('#srch_firm').val();
        $('#cat_table').html("<tr><td colspan='13' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchFirm",
            data:{srch_firm:srch_firm},
            success:function(msg)
            {
                $('#cat_table').html(msg);
            }
        }); 
    });
