<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
	<table border="1">
        <thead>            
            <tr>
                <td colspan="9" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Date Wise Expense Details On Vehicles</strong>                       
                    </center>
                 </td>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>Entry</th> 
                <th>Expense</th> 
                <th>Vehicle No.</th> 
                <th>Member Type</th> 
                <th>Paid To</th> 
                <th>Ledger</th>                                 
                <th>Amount</th>
                <th>Remark</th>                                  
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl=1;$tot_amt=0;
            if(count($vechicle_exp)===0 || $vechicle_exp[0]['acc_id']===null)
            {
                echo '<tr><td colspan="9" class="no-data-found">Record Not Found</td></tr>';
                die();
            }
            foreach ($vechicle_exp as $key => $value) 
            {
                $amt=$value['acc_amt'];
                $tot_amt += $amt;
                $payfor=$this->CommonModel->vehicleProject($value['acc_sourceid'],$value['acc_vochfor']);

                if($payfor!='')
                    $payfor=$payfor;
                echo "<tr id='row_".$value['acc_id']."'>";          
                echo "<td>". $sl++ ."</td>";
                echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
                echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
                echo "<td>". $payfor ."</td>";
                echo "<td>". $this->config->item($value['c_type'],'member_type') ."</td>";
                echo "<td>". $value['paidto'] ."</td>";
                echo "<td>". $value['ledger']."</td>";             
                echo "<td>". $amt ."</td>";
                echo "<td>". $value['acc_remark'] ."</td>";               
                echo "</tr>";       
            }
            echo "<tr>";
            echo "<td colspan='7'><strong>Total Amount : </strong></td>";
            echo "<td colspan='2'><strong>". $tot_amt ."</strong></td>";
            echo "</tr>";
            ?>
        </tbody>
     </table>
	</body>
</html>