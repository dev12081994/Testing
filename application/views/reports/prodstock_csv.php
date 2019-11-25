
<html>
<head>
    <title>Product Stock Report</title>
</head>
<body>
    <table border="1">
        <thead>            
            <tr>
                <td colspan="14" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Product Stock Report</strong>                       
                    </center>
                 </td>
            </tr>           
            <tr>
                <th>S.N.</th>
                <th>Category</th>
                <th>Product</th>
                <th>HSN/SAC</th>
                <th>Unit</th>
                <th>Opening Stock</th>
                <th>Purchased Qty.</th>
                <th>Sold Qty.</th>
                <th>Current Stock</th>
                <th>Purchase Rate</th>
                <th>Sale Rate</th>
                <th>GST</th>
                <th>GST Included In Rate</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php 
             $sl=1;
            if(count($product_data) === 0 || $product_data[0]['prod_id']===null)
            {
                echo "<tr><td colspan='14' class='no-data-found'>No Record Found</td></tr>";
            }
            else
            {
                foreach($product_data as $value){ 
                   $gst_status='<span title="Not Applicable" style="color:red"><center>NA</center></span>';
                ?>
                <tr id='row_<?php echo $value["prod_id"]; ?>'>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo ucwords(strtolower($value['c_name'])); ?></td>  
                    <td><?php echo ucwords(strtolower($value['prod_name'])); ?></td>  
                    <td><?php echo $value['prod_hsn_sac']; ?></td>  
                    <td><?php echo ucwords(strtolower($value['prod_unit'])); ?></td>   
                    <td><?php echo $value['prod_openstock'];?></td>                     
                    <td><?php echo $value['tot_purch'];?></td>                     
                    <td><?php echo $value['tot_sold'];?></td>                     
                    <td><?php echo $value['prod_currstock'];?></td>                     
                    <td><?php echo $value['prod_purrate'];?></td>   
                    <td><?php echo $value['prod_salerate'];?></td>   
                    <td><?php if($value['prod_isgst']==='1')echo $value['prod_gstrate'].'%';else echo $gst_status; ?></td> 
                    <td><?php if($value['prod_purgstincl']==='0')echo $gst_status;else if($value['prod_purgstincl']==='1')echo 'Yes';else echo 'No'; ?></td>                       
                    <td><?php echo ucwords(strtolower($value['prod_remark'])); ?></td>  
                </tr> 
            <?php } }   
            ?>
        </tbody>
     </table>
    </body>
</html>