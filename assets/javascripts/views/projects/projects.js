    $(function(){
        srchProject();   
    });

    function srchProject()
    {
        var formdata=$('#srchproj_form').serialize();    
        $('#proj_table').html("<center><img src='" + base_url +"assets/images/loader/circle.gif' /></center>");
       
        $.ajax({
            type:'POST',
            url:base_url + 'PROJECTS/srchProject',
            data:formdata,
            success:function(data)
            {
                $('#proj_table').html(data);
            }
        });
    }

    function doneProject()
    {  
        var proj_id=$('#projid_done').val();
        $.ajax({
            type:'POST',
            url:base_url + 'PROJECTS/doneProject',
            data:{proj_id:proj_id},
            success:function(data)
            {
                srchProject();
                $('.modal-dismiss').trigger('click');
            }
        });
    }

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit').removeAttr('disabled').html('Submit');     
    }

    function setRequire()
    {
        var proj_type=$('#proj_durtype').val();
        var proj_id=$('#proj_id').val();
        if(proj_type==='1')
        {
            $('.dtdiv').show(800);
            $('.durdiv').hide();
            $('.dtctrl').attr('required',true);
            $('.durctrl').removeAttr('required');
            
            if(proj_id==='')
                $('.durctrl').val('');
        }
        else if(proj_type==='2')
        {            
            $('.durdiv').show(800);
            $('.dtdiv').hide();
            $('.durctrl').attr('required',true);
            $('.dtctrl').removeAttr('required');

            if(proj_id==='')
                $('.durctrl').val('');
        }
        else
        {
            $('.dtdiv').hide();
            $('.durdiv').hide();
            $('.dtctrl').val('').removeAttr('required');
            $('.durctrl').val('').removeAttr('required');
        }
    }

    $('.form-control[required]').on('change keyup',function(){
        if($(this).val()==='')
            $(this).closest('.form-group').removeClass('has-success').addClass('has-error');
        else
            $(this).closest('.form-group').removeClass('has-error');
    });

    function projectData(proj_id)
    {
        $.ajax({
            type:"POST",
            url:base_url + "PROJECTS/projectData",
            data:{proj_id:proj_id},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#proj_id').val(obj[0].proj_id);
                $('#proj_name').val(obj[0].proj_name);               
                $('#proj_type').val(obj[0].proj_type);
                $('#proj_amt').val(obj[0].proj_amt);
                
                if(obj[0].proj_enddt === '00-00-0000')
                {
                    $('#proj_durtype').val(2).trigger('change');
                    $('#proj_durin').val(obj[0].proj_durtype);
                    $('#proj_duration').val(obj[0].proj_duration);
                }
                else
                {
                    $('#proj_durtype').val(1).trigger('change');
                    $('#proj_enddt').val(obj[0].proj_enddt);
                }

                $('#proj_startdt').val(obj[0].proj_startdt).datepicker();
                $('#proj_remark').val(obj[0].proj_remark);  
                $('#submit').html('Update');
                $('#project_name').focus();  
                $('#project_modal').modal('show');
            }
        });         
    }

    function confirm_deletion()
    {
        var proj_id=$('#del_id').val();
        $.ajax({
            type:"POST",
            url:base_url + "PROJECTS/delProject",
            data:{proj_id:proj_id},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + proj_id).remove();
                    $('.modal-dismiss').trigger('click');
                }
            }
        });           
    }

    