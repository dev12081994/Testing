   
    $(function(){
        $('.reload-vd-table').trigger('click');
        $('.reload-vt-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit_vtype').attr('disabled',false).html('Submit');      
         $('#submit_vdetails').attr('disabled',false).html('Submit');
    }

    $('#submit_vdetails').on('click',function(){
        var error_count=0
        $('#add_vd_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_vd_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveVehicleInfo",
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
                        $('#submit_vdetails').attr('disabled',false).html('Submit');
                    }

                }
            });
        }         
    });

    $('#submit_vtype').on('click',function(){
        var error_count=0
        $('#add_vt_form').find('.form-control[required]').each(function(){ 
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
            $('#submit_vtype').attr('disabled',true).html('Processing...');
            var formdata=$('#add_vt_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveVehicleType",
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
                        $('#submit_vtype').attr('disabled',true).html('Submit').attr('disabled',false);
                    }

                }
            });
        }         
    });


    $('.reload-vd-table').on('click',function(){
        var srch_vtype=$('#srch_vtype').val();
        var srch_vnum=$('#srch_vnum').val();
       $('#v_table').html("<tr><td colspan='6' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchVehicleInfo",
            data:{srch_vnum:srch_vnum,srch_vtype:srch_vtype},
            success:function(msg)
            {
                $('#v_table').html(msg);
            }
        }); 
    });


    $('.reload-vt-table').on('click',function(){
        var srch_type=$('#srch_type').val();
        $('#vt_table').html("<tr><td colspan='4' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchVehicleType",
            data:{srch_type:srch_type},
            success:function(msg)
            {
                $('#vt_table').html(msg);
            }
        });
            
         $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchVehicleType",
            data:{},
            success:function(msg)
            {
                $('#v_typeid').html(msg).trigger('change');
                $('#srch_vtype').html(msg).trigger('change');
            }
        });
       
    });

    
    function fetchEditData(id,flag)
    {   
        var table,aliaz;
        if(flag==='1')
        {
            table='mas_vehicletype';
            aliaz='vt';
        }
        else
        {
            table='mas_vehicle';
            aliaz='v';
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
                    $('#vt_id').val(obj[0].vt_id);
                    $('#vt_name').val(obj[0].vt_name);   
                    $('#submit_vtype').html('Update');
                    $('#vt_name').focus();  
                    $('#vtype_modal').modal('show');
                }
                else
                {
                    $('#v_id').val(obj[0].v_id);
                    $('#v_num').val(obj[0].v_num);               
                    $('#v_typeid').val(obj[0].v_typeid).trigger('change');               
                    $('#v_remark').val(obj[0].v_remark);  
                    $('#submit_vdetails').html('Update');
                    $('#v_num').focus();  
                    $('#vmaster_modal').modal('show');
                }               
            }
        });         
    }

    function confirm_deletion()
    {
        var id=($('#del_id').val().split('@'));
        var table,alias;
        if(id[1]==='1')
        {
            table='mas_vehicletype';
            alias='vt';
        }
        else
        {
            table='mas_vehicle';
            alias='v';
        }

        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/deleteData",
            data:{id:id[0],table:table,alias:alias},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + id).remove();
                    $('.modal-dismiss').trigger('click');
                    if(id[1]==='1')
                        $('.reload-vt-table').trigger('click');
                    else
                        $('.reload-vd-table').trigger('click');
                    
                }
            }
        });           
    }   
