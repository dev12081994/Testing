gst_list='';

$(document).ready(function(){
	count=0;
    row_id=[];
    prod_opt=$('#prod_opt').html();    
    fetchGstList();    
});

function fetchGstList()
{   
    $.ajax({
        type:"POST",
        url:base_url + "COMMON/fetchGstList",
        data:{},
        success:function(msg)
        {
           gst_list=msg;
           addMoreItem();
        }
    });         
}

$('form').on('submit',function(){
	$('#prod_ids').val(row_id);
	return true;
});

$('#submit_prod').on('click',function(){        
    $('#add_prod_form').find('.form-control[required]').each(function(){ 
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


function addMember()
{
	var c_id=$('#inv_perticular').val();
	var mem_type=$('#member_type').val();
	$('#add_member').text('Submit');
	if(c_id!='')
	{
	    $('#c_id').val(c_id);	    
	    $('#add_member').text('Update');
	    $.ajax({
	    	url:base_url + 'COMMON/fetchMemberDetails',
	    	type:'post',
	    	data:{c_id:c_id},
	    	success:function(msg)
	    	{
	    		var obj=JSON.parse(msg);
	    		$('#c_firstname').val(obj[0].c_firstname);
	    		$('#c_middlename').val(obj[0].c_middlename);
	    		$('#c_type').val(obj[0].c_type);
	    		$('#c_lastname').val(obj[0].c_lastname);
	    		$('#c_salutation').val(obj[0].c_salutation);
	    		$('#c_mob1').val(obj[0].c_mob1);
	    	}
	    });
	}
	       
	$('#addmember_modal').modal('show');
}

function clearForm()
{
    $('#add_member_form').find('.form-control').each(function(){$(this).val("").trigger('change');});         
          
    $('#add_member').removeAttr('disabled').html('Submit');     
}

 $('#add_member').on('click',function(){
        var error_count=0
        $('#add_member_form').find('.form-control[required]').each(function(){ 
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
            $('#add_member').attr('disabled',true).html('Processing...');
            var formdata=$('#add_member_form').serialize();    
            $.ajax({
                type:"POST",
                url:base_url + "COMMON/addNewMember",
                data:formdata,
                success:function(msg)
                {
                    showNotification(msg);
                    var obj = JSON.parse(msg);                 

                    if(obj.process==='1')
                    {
                        clearForm();
                        fetchMembers();
                    }
                    else if(obj.process === '2')
                    {                    
                        clearForm();
                        fetchMembers();
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


function fetchMembers()
{
	var mem_type=$('#member_type').val();
	if(parseInt(mem_type)>4)
    {
        mem_type=parseInt(mem_type)-1;
        url=base_url + "COMMON/fetchVehicleProject";
        data={data_type:mem_type};
    }
    else
    {
        url=base_url + "COMMON/fetchMembers";
        data={mem_type:mem_type};
    }
	$.ajax({
		type:'post',
		url:url,
		data:data,
		success:function(msg){
			$('#inv_perticular').html(msg).trigger('change');
		}
	});
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
    
function addMoreItem()
{  
    row_id.push(count);  

    var row;
    row+='<tr id="row_'+ count +'">';
	row+='<td id="sn_' + count + '"></td>';
	row+='<td><div><select id="stkprod_' + count + '" name="stkprod_' + count + '" class="form-control" onchange="getProdUnit('+ count +')" required>' + prod_opt + '</select></div></td>';
	row+='<td id="stkunit_'+count+'"></td>';
	row+='<td><div><input type="text" id="stkqty_'+count+'" name="stkqty_'+count+'" class="form-control onlynumval cal-inp" onfocusout="getProdAvail('+ count +')" placeholder="Quantity" required  autocomplete="off"/></div></td>';
	row+='<td><div><input type="text" id="stkrate_'+count+'" name="stkrate_'+count+'" class="form-control onlynumval cal-inp" placeholder="Rate" required  autocomplete="off"/></div></td>';
	row+='<td style="vertical-align:middle;"><div id="stkgross_'+count+'"></div></td>';
	row+='<td><div><input type="text" id="stkdisc_'+count+'" name="stkdisc_'+count+'" class="form-control onlynumval cal-inp" placeholder="Discount"  autocomplete="off"/></div></td>';
	row+='<td><div><select id="stkdisctype_'+count+'" name="stkdisctype_'+count+'" class="form-control cal-change" required><option value="1">%</option><option value="2">Amt.</option></select></div></td>';
	row+='<td><div><select id="stkgst_'+count+'" name="stkgst_'+count+'" class="form-control cal-change" autocomplete="off">' + gst_list + '</select></div></td>';
	row+='<td><div><input type="checkbox" value="1" id="stkgstincl_'+count+'" name="stkgstincl_'+count+'[]" class="form-control" onclick="disableDisc('+ count +')" /></div></td>';
	row+='<td><div><input type="text" name="stktotal_'+count+'" id="stktotal_'+count+'" class="form-control onlynumval cal-inp tot"  placeholder="Total" disabled/></div></td>';
	row+='<td><div><input type="text" id="stkexpdt_'+count+'" name="stkexpdt_'+count+'" class="form-control"  placeholder="Date" autocomplete="off" /></div></td>';
	row+='<td><div><button type="button" id="stkqty_'+count+'" onclick="remove_row('+ count +')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button</div></td>';
	
    $('#product_table').append(row);
    $('#stkprod_'+count).select2();
    $('#stkexpdt_'+count).datepicker();
    reverseCal();
   	count++;
}


function reverseCal()
{
	if($('#stk_revcal').prop('checked'))
	{
		$('.tot').removeAttr('disabled');
		$('.cal').attr('onclick','calRate()');
		$('.cal-inp').attr('onkeyup','calRate()');
		$('.cal-change').attr('onchange','calRate()');
		calRate();
	}
	else
	{
		$('.tot').attr('disabled',true);
		$('.cal').attr('onclick','resetSeq()');
		$('.cal-inp').attr('onkeyup','resetSeq()');
		$('.cal-change').attr('onchange','resetSeq()');
		resetSeq();
	}
}

function remove_row(id)
{
    $('#row_'+id).remove(); 
    row_id.splice(row_id.indexOf(id),1);
    reverseCal();
}

function resetSeq()
{
	var i;
	var sn=1;
	var finalgross=0;
	var finaldisc=0;
	var finalgst=0;
	var finaltotal=0;
	let transport_charge=$("#inv_transportcharge").val();
	if(isNaN(transport_charge) || $.trim(transport_charge)=="")
		transport_charge=0;

	for(i in row_id)
	{
		$('#sn_'+row_id[i]).html(sn++);

		var qty=$('#stkqty_' + row_id[i]).val();
		var rate=$('#stkrate_' + row_id[i]).val();
		var disc=$('#stkdisc_' + row_id[i]).val();
		var gst=$('#stkgst_' + row_id[i]).val();
		var disctype=$('#stkdisctype_' + row_id[i]).val();

		if(isNaN(qty) || $.trim(qty)==='')
			qty=0;
		if(isNaN(rate) || $.trim(rate)==='')
			rate=0;
		if(isNaN(disc) || $.trim(disc)==='')
			disc=0;
		if(isNaN(gst) || $.trim(gst)==='')
			gst=0;
		
		var gross=(parseFloat(qty)*parseFloat(rate)).toFixed(2);
		$('#stkgross_' + row_id[i]).html(gross);

		var subtotal=0;
		var discamt=0;
		var total=0;
		var gstamt=0;

		if($('#stkgstincl_'+row_id[i]).prop('checked')==false)
		{
			if(disctype==='1')
			{
				discamt=((parseFloat(gross)*parseFloat(disc))/100).toFixed(2);
				subtotal=parseFloat(gross)-parseFloat(discamt);
			}
			else
			{
				discamt=parseFloat(disc).toFixed(2);
				subtotal=parseFloat(gross)-parseFloat(discamt);
			}
			total=((parseFloat(subtotal)*(100+parseFloat(gst)))/100).toFixed(2);
			gstamt=((parseFloat(subtotal)*parseFloat(gst))/100).toFixed(2);
		}
		else
		{
			gstamt =(parseFloat(gross)-(parseFloat(gross)*(100/(100+parseFloat(gst))))).toFixed(2);
			total=parseFloat(gross);
		}

		$('#stktotal_' + row_id[i]).val(total);

		finalgross = (parseFloat(finalgross) + parseFloat(gross)).toFixed(2);
		finaltotal = (parseFloat(finaltotal) + parseFloat(total)).toFixed(2);
		finaldisc = (parseFloat(finaldisc) + parseFloat(discamt)).toFixed(2);
		finalgst = (parseFloat(finalgst) + parseFloat(gstamt)).toFixed(2);
	}
	$('#gross').html(finalgross);
	$('#disc').html(finaldisc);
	$('#gst').html(finalgst);
	$('#subtot').html(finaltotal);
	$('#transport_chrg').html(transport_charge);
	$('#netamt').html(parseFloat(finaltotal) + parseFloat(transport_charge));
	$('#bkpnetamt').val(parseFloat(finaltotal) + parseFloat(transport_charge));
}

function calRate()
{
	var i;
	var sn=1;
	var finalgross=0;
	var finaldisc=0;
	var finalgst=0;
	var finaltotal=0;
	for(i in row_id)
	{
		$('#sn_'+row_id[i]).html(sn++);
		var qty=$('#stkqty_' + row_id[i]).val();
		//var rate=$('#stkrate_' + row_id[i]).val();
		var disc=$('#stkdisc_' + row_id[i]).val();
		var gst=$('#stkgst_' + row_id[i]).val();
		var disctype=$('#stkdisctype_' + row_id[i]).val();
		var stktotal=$('#stktotal_' + row_id[i]).val();

		if(isNaN(qty) || $.trim(qty)==='')
			qty=0;
		if(isNaN(disc) || $.trim(disc)==='')
			disc=0;
		if(isNaN(gst) || $.trim(gst)==='')
			gst=0;
		if(isNaN(stktotal) || $.trim(stktotal)==='')
			stktotal=0;

		var rate=0;

		if(disctype==='1')
		{
			rate=(parseFloat(parseFloat(stktotal)*10000)/parseFloat(parseFloat(parseFloat(qty)*10000)-parseFloat(parseFloat(qty)*parseFloat(disc)*100)+parseFloat(parseFloat(qty)*parseFloat(gst)*100)-(parseFloat(qty)*parseFloat(gst)*parseFloat(disc)))).toFixed(2);
		}
		else
		{	
			rate=((parseFloat(parseFloat(stktotal)*100)+parseFloat(parseFloat(disc)*100)+parseFloat(parseFloat(disc)*parseFloat(gst)))/((parseFloat(parseFloat(qty)+parseFloat(parseFloat(parseFloat(gst)*parseFloat(qty))/100))*100))).toFixed(2);
		}

		$('#stkrate_' + row_id[i]).val(rate);

		var gross=(parseFloat(qty)*parseFloat(rate)).toFixed(2);
		$('#stkgross_' + row_id[i]).html(gross);

		var subtotal=0;
		var discamt=0;
		
		if(disctype==='1')
		{
			discamt=(parseFloat(gross)*parseFloat(disc))/100;
			subtotal=parseFloat(gross)-parseFloat(discamt);
		}
		else
		{
			discamt=parseFloat(disc);
			subtotal=parseFloat(gross)-parseFloat(disc);
		}

		var total=(subtotal*(100+parseFloat(gst)))/100;

		//$('#stktotal_' + row_id[i]).val(total.toFixed(2));

		finalgross +=parseFloat(gross);
		finaltotal +=parseFloat(total);
		finaldisc +=parseFloat(discamt);
		finalgst +=((parseFloat(gross)-parseFloat(discamt))*parseFloat(gst))/100;
	}

	$('#gross').html(finalgross.toFixed(2));
	$('#disc').html(finaldisc.toFixed(2));
	$('#gst').html(finalgst.toFixed(2));
	$('#subtot').html(finaltotal.toFixed(2));
	$('#netamt').html(finaltotal.toFixed(2));
	$('#bkpnetamt').val(finaltotal.toFixed(2));
}

function calRoundOff()
{
	var netamt=$('#bkpnetamt').val();
	var rountoff=$('#inv_roundoff').val();
	if((isNaN(rountoff) || $.trim(rountoff)==='') && rountoff!=='-')
			rountoff=0;
	$('#inv_roundoff').val(rountoff);
	$('#netamt').html((parseFloat(netamt)+parseFloat(rountoff)).toFixed(2));
}


function getProdUnit(rowid)
{
	$('#stkunit_'+rowid).html('');
	var prodid=$('#stkprod_'+rowid).val();
	if(prodid!=='')
	{
		$.ajax({
			type:'post',
			url: base_url + 'INVENTORY/getProdData',
			data:{prodid:prodid,invtype:2},
			success:function(msg)
			{
				var obj=JSON.parse(msg);
				$('#stkunit_'+rowid).html(obj.unit);
				$('#stkrate_'+rowid).val(obj.rate);
				$('#stkgst_'+rowid).val(obj.gst);

				if(parseFloat(obj.gst)>0)
					$('#stkgst_'+rowid).val(obj.gst);
				else
					$('#stkgst_'+rowid).val('');

				if(obj.gst_inclusive==='1')
					$('#stkgstincl_'+rowid).prop('checked',true);
				else	
					$('#stkgstincl_'+rowid).prop('checked',false);

				reverseCal();
				disableDisc(rowid);
				getProdAvail(rowid);
			}
		});
	}
}

function disableDisc(rowid)
{
	if($('#stkgstincl_'+rowid).prop('checked'))
	{
		$('#stkdisc_'+rowid).val('').attr('disabled',true);
		$('#stkdisctype_'+rowid).attr('disabled',true);
	}
	else
	{
		$('#stkdisc_'+rowid).removeAttr('disabled');
		$('#stkdisctype_'+rowid).removeAttr('disabled');	
	}
	reverseCal();
}

function getProdAvail(rowid)
{
	var prodid=$('#stkprod_'+rowid).val();
	var qty=$('#stkqty_'+rowid).val();
	$('#err_msg').html("");
	if(prodid!=='' && qty!=='')
	{
		$.ajax({
			type:'post',
			url: base_url + 'INVENTORY/getProdAvail',
			data:{prodid:prodid},
			success:function(msg)
			{
				var obj=JSON.parse(msg);
				if(parseFloat(obj.stock) < parseFloat(qty))
				{
					$('#err_msg').html("This Quantity Is Not Available In Stock . Current Stock Of This Product Is : "+obj.stock);
					$('#stkqty_'+rowid).val("");
				}
			}
		});
	}
}

