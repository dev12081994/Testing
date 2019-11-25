
    
    $(function(){
        $('.reload-table').trigger('click');
    });

    $('#reset').click(function(){
        $('#sc_category').val('').trigger('change');
        $('#submit').attr('disabled',false).html('Submit');
    });

    $('#submit').on('click',function(){
        var error_count=0
        $('#add_subcat_form').find('.form-control[required]').each(function(){ 
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
            var formdata=$('#add_subcat_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "MASTERS/saveSubCategory",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        $('#reset').trigger('click');
                    }
                    else if(obj.process === '2')
                    {                    
                        $('.reload-table').trigger('click');
                        $('#reset').trigger('click');                        
                    }
                    else
                    {
                        $('#submit').attr('disabled',true).html('Submit').attr('disabled',false);
                    }

                }
            });
        }         
    });

    function subCategoryData(sc_id)
    {
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/subCategoryData",
            data:{sc_id:sc_id},
            success:function(msg)
            {
                var obj = JSON.parse(msg);
                $('#sc_id').val(obj[0].sc_id);
                $('#sc_name').val(obj[0].sc_name);
                $('#sc_category').val(obj[0].sc_category).trigger('change');
                $('#sc_remark').val(obj[0].sc_remark);  
                $('#submit').html('Update');
                $('#sc_name').focus();  
                $('#categ_modal').modal('show');
            }
        });         
    }

    function confirm_deletion()
    {
        var sc_id=$('#del_id').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/delSubCategory",
            data:{sc_id:sc_id},
            success:function(msg)
            {
                showNotification(msg);
                var obj = JSON.parse(msg);
                if(obj.type==='success')
                {
                    $('#row_' + sc_id).remove();
                    $('.modal-dismiss').trigger('click');
                }
            }
        });           
    }



    $('.reload-table').on('click',function(){
        var srch_cat=$('#srch_cat').val();
        var srch_subcat=$('#srch_subcat').val();
        $.ajax({
            type:"POST",
            url:base_url + "MASTERS/srchSubCateg",
            data:{srch_subcat:srch_subcat,srch_cat:srch_cat},
            success:function(msg)
            {
                $('#subcat_table').html(msg);
            }
        }); 
    });
