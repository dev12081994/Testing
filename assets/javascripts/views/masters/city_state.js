   
    $(function(){
        $('.reload-city-table').trigger('click');
        $('.reload-state-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit_city').attr('disabled',false).html('Submit');      
        $('#submit_state').attr('disabled',false).html('Submit');
    }

    $('#submit_city').on('click',function(){
        var error_count=0
        $('#add_city_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_city_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveCity",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm(); 
                        $('.reload-city-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {                                
                        clearForm();    
                        $('.reload-city-table').trigger('click');         
                        $('[ data-dismiss=modal]').trigger('click');                                                              
                    }
                    else
                    {
                        $('#submit_city').attr('disabled',false).html('Submit');
                    }
                }
            });
        }         
    });

    $('#submit_state').on('click',function(){
        var error_count=0
        $('#add_state_form').find('.form-control[required]').each(function(){ 
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
            $('#submit_state').attr('disabled',true).html('Processing...');
            var formdata=$('#add_state_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveState",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm();
                        $('.reload-state-table').trigger('click');                      
                    }
                    else if(obj.process === '2')
                    {   
                        clearForm();  
                        $('.reload-state-table').trigger('click');                      
                        $('[data-dismiss=modal]').trigger('click');                      
                    }
                    else
                    {
                        $('#submit_state').attr('disabled',true).html('Submit').attr('disabled',false);
                    }

                }
            });
        }         
    });


    $('.reload-city-table').on('click',function(){
        var srch_city=$('#srch_city').val();
        var srch_citystate=$('#srch_citystate').val();
        $('#city_table').html("<tr><td colspan='5' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchCity",
            data:{srch_citystate:srch_citystate,srch_city:srch_city},
            success:function(msg)
            {
                $('#city_table').html(msg);
            }
        }); 
    });


    $('.reload-state-table').on('click',function(){
        var srch_state=$('#srch_state').val();
        $('#state_table').html("<tr><td colspan='5' class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchState",
            data:{srch_state:srch_state},
            success:function(msg)
            {
                $('#state_table').html(msg);
            }
        });
            
         $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchStateList",
            data:{},
            success:function(msg)
            {
                $('#srch_citystate').html(msg).trigger('change');
                $('#city_state').html(msg).trigger('change');
            }
        });       
    });

    
    function fetchEditData(id,flag)
    {   
        var table,aliaz;
        if(flag==='1')
        {
            table='mas_state';
            aliaz='state';
        }
        else
        {
            table='mas_city';
            aliaz='city';
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
                    $('#state_id').val(obj[0].state_id);
                    $('#state_name').val(obj[0].state_name);   
                    $('#submit_state').html('Update');
                    $('#state_name').focus();  
                    $('#state_modal').modal('show');
                }
                else
                {
                    $('#city_id').val(obj[0].city_id);
                    $('#city_name').val(obj[0].city_name);               
                    $('#city_state').val(obj[0].city_state).trigger('change');                                   
                    $('#submit_city').html('Update');
                    $('#city_name').focus();  
                    $('#city_modal').modal('show');
                }               
            }
        });         
    }

    function confirm_deletion()
    {
        var id=($('#del_id').val().split('@'));
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/deleteCityState",
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
                        $('.reload-state-table').trigger('click');
                    else
                        $('.reload-city-table').trigger('click');
                    
                }
            }
        });           
    }   
