
<html>
<head>
    <title>Payment Report</title>
</head>
<body>
    <table border="1">
        <thead>            
            <tr>
                <td colspan="11" style="background:#330099;color:#ffffff;font-size:16px;">
                    <center>
                        <strong>Payment Report</strong>                       
                    </center>
                 </td>
            </tr>           
            <tr>
                <th>S.N.</th>
                <th>Entry</th> 
                <th title="Transaction Date">Transaction</th> 
                <th>Payment For</th> 
                <th>Type</th> 
                <th>Paid To</th> 
                <th>Ledger</th>
                <th>Mode</th> 
                <th>Chq./Tran. No.</th>                                     
                <th>Amount</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl=1;
            $tot_amt=0;
                foreach ($payment as $key => $value) 
                {
                    if($value['acc_mode']==='1')
                    {
                        $mode='Cash';
                        $tranid='';
                    }
                    else if($value['acc_mode']==='2')
                    {
                        $mode='Cheque';
                        $tranid=$value['acc_chqno'];
                    }
                    else if($value['acc_mode']==='3')
                    {
                        $mode='Online';
                        $tranid=$value['acc_onlineid'];
                    }
                    else
                    {
                        $mode='';
                        $tranid='';
                    }
                    
                    $amt=0;
                    $amt=$value['acc_amt'];
                    $tot_amt += $amt;
                    $payfor=$this->CommonModel->vehicleProject($value['acc_cid'],$value['acc_vochfor']);

                    if($payfor!='')
                        $payfor='-'.$payfor;
                    echo "<tr id='row_".$value['acc_id']."'>";          
                    echo "<td>". $sl++ ."</td>";
                    echo "<td>". date('d-m-Y',strtotime($value['acc_entrydt'])) ."</td>";
                    echo "<td>". date('d-m-Y',strtotime($value['acc_trandt'])) ."</td>";
                    echo "<td>". $this->config->item($value['acc_vochfor'],'tran_for'). $payfor ."</td>";
                    echo "<td>". $this->config->item($value['c_type'],'member_type') ."</td>";
                    echo "<td>". $value['paidto'] ."</td>";
                    echo "<td>". $value['ledger']."</td>";
                    echo "<td>". $mode ."</td>";
                    echo "<td>". $tranid ."</td>";
                    echo "<td>". $amt ."</td>";
                    echo "<td>". $value['acc_remark'] ."</td>";
                    echo "</tr>";       
                }
                echo "<tr>";
                echo "<td colspan='7'><strong>Total Amount : </strong></td>";
                echo "<td colspan='4'><strong>". $tot_amt ."</strong></td>";
                echo "</tr>";        
            ?>
        </tbody>
     </table>
    </body>
</html>