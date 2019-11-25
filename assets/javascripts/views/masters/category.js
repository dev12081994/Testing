   
    $(function(){
        $('.reload-table').trigger('click');
    });

    function clearForm()
    {
        $('form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
        $('#submit').removeAttr('disabled').html('Submit');     
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

        if(error_count === 0)
        {
            $('#submit').attr('disabled',true).html('Processing...');
            var formdata=$('#add_cat_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveCategory",
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
        }         
    });

    function categoryData(c_id)
    {
        var cfor=$('#srch_cfor').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/categoryData",
            data:{c_id:c_id},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#c_id').val(obj[0].c_id);
                $('#c_name').val(obj[0].c_name);               
                $('#c_remark').val(obj[0].c_remark);  
                if(cfor==='3')
                    $('#c_taxperc').val(obj[0].c_taxperc);  
                $('#submit').html('Update');
                $('#c_name').focus();  
                $('#categ_modal').modal('show');
            }
        });         
    }

    function confirm_deletion()
    {
        var c_id=$('#del_id').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/delCategory",
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


    $('.reload-table').on('click',function(){
        var srch_cat=$('#srch_cat').val();
        var srch_cfor=$('#srch_cfor').val();
        if(srch_cfor==='3')
            colspan='6';
        else
            colspan='5';
        $('#cat_table').html("<tr><td colspan=" + colspan + " class='cen'><img src='" + base_url +"assets/images/loader/circle.gif' /></td></tr>");

        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchCateg",
            data:{srch_cat:srch_cat,srch_cfor:srch_cfor},
            success:function(msg)
            {
                $('#cat_table').html(msg);
            }
        }); 
    });
