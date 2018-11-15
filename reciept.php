<?php 
session_save_path(getcwd()."/sessions");
session_start();
if(!isset($_SESSION['data'])){
    $_SESSION['error_message'] = "Please enter your order information!";
    header("Location: index.php");
}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="ass2.css">
		
		<title>
			Order Reciept
		</title>
	</head>
	<body>
		<div class='wrapper'>
			<h1>
                Order Information:
			</h1>	
			<div>
                <?php
                $ordersInfo = $_SESSION['data'];
                $customerName = $ordersInfo['order_by'];
                $paymentMethod = $ordersInfo['payment_method'];
                $orderList = $ordersInfo['items'];
                $totalCost = $ordersInfo['total_cost'];
                ?>
                

                <?php 
                echo "Name: ". htmlspecialchars($customerName) ;
                echo "<br>Payment Method: ". htmlspecialchars($paymentMethod); ?>
                <table style="width:50%">
                    <thead><td>Item</td><td>Quanity</td><td>Sub-total</td></thead>
                    <tbody>
                    <?php
                        foreach($orderList as $key => $item){
                            if($item['qty'] == 0){
                                continue;
                            }
                            echo "<tr>";
                            echo "<td>$key</td>";
                            echo "<td>".$item['qty']."</td>";
                            echo "<td>$".$item['sub_total']."</td>";
                            echo "</tr>";
                        }
                    ?>
                        <tr>
                            <td colspan="2">Total: </td>
                            <td><?php echo " <b>$$totalCost</b>"?></td>
                        </tr>
                    </tbody>
                </table>
			</div>
            <br>
            <br>
            Remember to save your order details! Once you exit this page, you will not be able to retrieve your order details!
            <a href="index.php">Back to ordering page.</a>
		</div>
		<script type="application/javascript" src="ass2.js"></script>
	</body>
</html>
<?php
session_write_close();
    