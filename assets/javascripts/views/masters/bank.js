   
    $(function(){
        $('.reload-vd-table').trigger('click');
        $('.reload-vt-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit_bank').attr('disabled',false).html('Submit');      
         $('#submit_bankacc').attr('disabled',false).html('Submit');
    }

    $('#submit_bankacc').on('click',function(){
        var error_count=0
        $('#add_bankacc_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_bankacc_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveBankAcc",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm(); 
                        $('.reload-vd-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {                                
                        clearForm();    
                        $('.reload-vd-table').trigger('click');         
                        $('[ data-dismiss=modal]').trigger('click');                                                              
                    }
                    else
                    {
                        $('#submit_bankacc').removeAttr('disabled').html('Submit');
                    }

                }
            });
        }         
    });

    $('#submit_bank').on('click',function(){
        var error_count=0
        $('#add_bank_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_bank_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveBank",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm();
                        $('.reload-vt-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {   
                        clearForm();  
                        $('.reload-vt-table').trigger('click');                      
                        $('[ data-dismiss=modal]').trigger('click');                      
                    }
                    else
                    {
                        $('#submit_bank').removeAttr('disabled').html('Submit').attr('disabled',false);
                    }

                }
            });
        }         
    });


    $('.reload-vd-table').on('click',function(){
        var srch_accbank=$('#srch_accbank').val();
        var srch_accnum=$('#srch_accnum').val();
        $('#bankacc_table').html("<tr><td colspan='8' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchBankAcc",
            data:{srch_accnum:srch_accnum,srch_accbank:srch_accbank},
            success:function(msg)
            {
                $('#bankacc_table').html(msg);
            }
        }); 
    });


    $('.reload-vt-table').on('click',function(){
        var srch_bank=$('#srch_bank').val();
        $('#bank_table').html("<tr><td colspan='5' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchBank",
            data:{srch_bank:srch_bank},
            success:function(msg)
            {
                $('#bank_table').html(msg);
            }
        });
            
         $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchBank",
            data:{},
            success:function(msg)
            {
                $('#srch_accbank').html(msg).trigger('change');
                $('#acc_bankid').html(msg).trigger('change');
            }
        });
       
    });

    
    function fetchEditData(id,flag)
    {   
        var table,aliaz;
        if(flag==='2')
        {
            table='mas_bankacc';
            aliaz='acc';
        }
        else
        {
            table='mas_bank';
            aliaz='bank';
        }
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchEditData",
            data:{id:id,table:table,aliaz:aliaz},
            success:function(msg)
            {
                var obj = JSON.parse(msg);

                if(flag==='1')
                {
                    $('#bank_id').val(obj[0].bank_id);
                    $('#bank_name').val(obj[0].bank_name);   
                    $('#bank_short').val(obj[0].bank_short);   
                    $('#submit_vtype').html('Update');
                    $('#bank_name').focus();  
                    $('#bank_modal').modal('show');
                }
                else
                {
                    $('#acc_id').val(obj[0].acc_id);
                    $('#acc_num').val(obj[0].acc_num);               
                    $('#acc_firm').val(obj[0].acc_firm).trigger('change');               
                    $('#acc_bankid').val(obj[0].acc_bankid).trigger('change'); 
                    $('#submit_vdetails').html('Update');
                    $('#acc_num').focus();  
                    $('#bankacc_modal').modal('show');
                }               
            }
        });         
    }

    function confirm_deletion()
    {
        var id=($('#del_id').val().split('@'));
        var url;
        if(id[1]==='2')
        {
            url=base_url + "MASTERS/deleteBankAcc";
        }
        else
        {
            url=base_url + "MASTERS/deleteBank";
        }

        $.ajax({
            type:"POST",
            url:url,
            data:{id:id[0]},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    
                    $('.modal-dismiss').trigger('click');

                    if(id[1]==='1')
                    {
                        $('#vtrow_' + id).remove();
                        if($('#table_vtdata tr').length===1)
                            $('.reload-vt-table').trigger('click');
                    }
                    else 
                    {
                        $('#vrow_' + id).remove();
                        if($('#table_vddata tr').length===1)
                            $('.reload-vd-table').trigger('click');
                    }                 
                }
            }
        });           
    } 