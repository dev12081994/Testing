    $(function(){
        srchMember();   
    });
    function srchMember()
    {
        var formdata=$('#srchmember_form').serialize();    
        console.log(formdata);
        $.ajax({
            type:'POST',
            url:base_url + 'MASTERS/srchMember',
            data:formdata,
            success:function(data)
            {
                $('#member_table').html(data);
            }
        });
        
    }
    
    function confirm_deletion()
    {
        var c_id=$('#del_id').val();
       
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/deleteMember",
            data:{c_id:c_id},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + c_id).remove();
                    $('.modal-dismiss').trigger('click');                                        
                }
            }
        });           
    }   


//---------------- End Of members page

//-------------------- addMember Page ----------------------
    
    $(document).ready(function(){
        fetchGstType();
    });

    $('#submit_member').on('click',function(){        
        $('#add_member_form').find('.form-control[required]').each(function(){ 
            if($(this).val()==='')
                $(this).closest('div').removeClass('has-success').addClass('has-error');
            else
                $(this).closest('div').removeClass('has-error');
        });
        return true;
    });

    $('.form-control[required]').on('change keyup',function(){
        if($(this).val()==='')
            $(this).closest('div').removeClass('has-success').addClass('has-error');
        else
            $(this).closest('div').removeClass('has-error');
    });

    function fetchState(stateid=null)
    {
        var countryid=$('#c_country').val();
        var stateid=$('#c_oldstate').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchState",
            data:{countryid:countryid},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#c_state').find('option').remove().end().append('<option value="">Select State</option>').val('');
                
                for (value in obj)
                {
                    c_state.options.add(new Option(obj[value].state_name,obj[value].state_id));
                }
                $('#c_state').val(stateid).trigger('change');
            }
        });
    }

    function fetchCity()
    {
        var stateid=$('#c_state').val();
        var cityid=$('#c_oldcity').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/fetchCity",
            data:{stateid:stateid},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#c_city').find('option').remove().end().append('<option value="">Select City</option>').val('');
                
                for (value in obj)
                {
                    c_city.options.add(new Option(obj[value].city_name,obj[value].city_id));
                }   
                $('#c_city').val(cityid).trigger('change');                
            }
        });        
    }

    function getDesig(depid)
    {
        var desig_id=$('#c_olddesig').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/getDesig",
            data:{depid:depid},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#c_desig').find('option').remove().end().append('<option value="">Select Designation</option>').val('');
                
                for (value in obj)
                {
                    c_desig.options.add(new Option(obj[value].desig_name,obj[value].desig_id));
                }
                $('#c_desig').val(desig_id).trigger('change');
            }
        }); 
    }

    function showEmpDiv(opt)
    {
        if(opt==='3')
        {
            $('.empdiv').show(800);
            $('.empopt').attr('required',true);
        }
        else
        {
            $('.empdiv').hide(800);
            $('.empopt').attr('required',false);
        }
    }

    function clearForm()
    {
        $('#add_gsttype_form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#add_gsttype').removeAttr('disabled').html('Submit');     
    }

    $('#add_gsttype').on('click',function(){
        var error_count=0
        $('#add_gsttype_form').find('.form-control[required]').each(function(){ 
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
            $('#add_gsttype').attr('disabled',true).html('Processing...');
            var formdata=$('#add_gsttype_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveGstType",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm();
                        fetchGstType();
                    }
                    else if(obj.process === '2')
                    {                    
                        clearForm();
                        fetchGstType();
                        $('#gsttype_close').trigger('click');
                        $('[ data-dismiss=modal]').trigger('click');                                                                                     
                    }
                    else
                    {
                        $('#add_gsttype').removeAttr('disabled').html('Submit');
                    }

                }
            });
        }         
    });

    function gstRegTypeModal()
    {
        var grt_id=$('#c_gstregtype').val();
        var grt_type=$('#c_gstregtype option:selected').text();
       
        $('#grt_id').val('');
        $('#grt_type').val('');
        $('#add_gsttype').text('Submit');

        if(grt_id!='')
        {
            $('#grt_id').val($.trim(grt_id));
            $('#grt_type').val($.trim(grt_type));
            $('#add_gsttype').text('Update');
        }
               
        $('#gstregtype_modal').modal('show');
    }

    function fetchGstType()
    {
        $.ajax({
            type:"POST",
            url:base_url + "COMMON/fetchGstType",
            data:{},
            success:function(msg){
                $('#c_gstregtype').html(msg);
            }
        });
    }