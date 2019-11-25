   
    $(function(){
        $('.reload-desig-table').trigger('click');
        $('.reload-dep-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit_desig').attr('disabled',false).html('Submit');      
         $('#submit_dep').attr('disabled',false).html('Submit');
    }

    $('#submit_desig').on('click',function(){
        var error_count=0
        $('#add_desig_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_desig_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveDesig",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm(); 
                        $('.reload-desig-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {                                
                        clearForm();    
                        $('.reload-desig-table').trigger('click');         
                        $('[ data-dismiss=modal]').trigger('click');                                                              
                    }
                    else
                    {
                        $('#submit_desig').attr('disabled',false).html('Submit');
                    }
                }
            });
        }         
    });

    $('#submit_dep').on('click',function(){
        var error_count=0
        $('#add_dep_form').find('.form-control[required]').each(function(){ 
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
            $('#submit_dep').attr('disabled',true).html('Processing...');
            var formdata=$('#add_dep_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveDep",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm();
                        $('.reload-dep-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {   
                        clearForm();  
                        $('.reload-dep-table').trigger('click');                      
                        $('[data-dismiss=modal]').trigger('click');                      
                    }
                    else
                    {
                        $('#submit_dep').attr('disabled',true).html('Submit').attr('disabled',false);
                    }

                }
            });
        }         
    });


    $('.reload-desig-table').on('click',function(){
        var srch_desig=$('#srch_desig').val();
        var srch_desigdep=$('#srch_desigdep').val();
        $('#desig_table').html("<tr><td colspan='5' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchDesig",
            data:{srch_desigdep:srch_desigdep,srch_desig:srch_desig},
            success:function(msg)
            {
                $('#desig_table').html(msg);
            }
        }); 
    });


    $('.reload-dep-table').on('click',function(){
        var srch_dep=$('#srch_dep').val();
        $('#dep_table').html("<tr><td colspan='4' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchDep",
            data:{srch_dep:srch_dep},
            success:function(msg)
            {
                $('#dep_table').html(msg);
            }
        });
            
         $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchdep",
            data:{},
            success:function(msg)
            {
                $('#srch_desigdep').html(msg).trigger('change');
                $('#desig_depid').html(msg).trigger('change');
            }
        });       
    });

    
    function fetchEditData(id,flag)
    {   
        var table,aliaz;
        if(flag==='1')
        {
            table='mas_department';
            aliaz='dep';
        }
        else
        {
            table='mas_designation';
            aliaz='desig';
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
                    $('#dep_id').val(obj[0].dep_id);
                    $('#dep_name').val(obj[0].dep_name);   
                    $('#submit_dep').html('Update');
                    $('#desig_name').focus();  
                    $('#dep_modal').modal('show');
                }
                else
                {
                    $('#desig_id').val(obj[0].desig_id);
                    $('#desig_name').val(obj[0].desig_name);               
                    $('#desig_depid').val(obj[0].desig_depid).trigger('change');                                   
                    $('#submit_desig').html('Update');
                    $('#desig_name').focus();  
                    $('#desig_modal').modal('show');
                }               
            }
        });         
    }

    function confirm_deletion()
    {
        var id=($('#del_id').val().split('@'));
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/deleteDepDesig",
            data:{id:id[0],type:id[1]},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + id).remove();
                    $('.modal-dismiss').trigger('click');
                    if(id[1]==='1')
                        $('.reload-dep-table').trigger('click');
                    else
                        $('.reload-desig-table').trigger('click');
                    
                }
            }
        });           
    }   
