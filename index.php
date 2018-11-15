<?php 
//configure session saving directory and start the session.
session_save_path(getcwd()."/sessions");
session_start();
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="ass2.css">
		
		<title>
			Purchase your fruits!
		</title>
	</head>
	<body>
		<div class='wrapper'>
			<h1>
				Welcome! You can order your fruits from this page.
			</h1>	
			<div>
                <?php 
                //display any error messages in the session.
                //After displaying, remove the error message in the session.
                if(isset($_SESSION['error_message'])){
                    echo "<div class='main-error'>".$_SESSION['error_message']."</div>";
                    unset($_SESSION['error_message']);
                }
                session_write_close();
                ?>
				<form id="order-form" action="handleOrder.php" method="POST">
                    <h3>Select your products:</h3>
					<table class='table'>
                        <tbody>
                            <tr>
                                <td class='input-wrapper'>
                                    <img src="res/apple.jpg" alt="apple" class='products-image'/>
                                    <label>Price per apple: $0.69</label>
                                    <div class="custom-field"><button type="button" class='minusButton'>-</button><input type="text" name="no_of_apples" id='applesTB' data-cost='0.69' value="0"><button type="button" class='addButton'>+</button></div><label class="error-message"></label>
                                </td>
                                <td class='input-wrapper'>
                                    <img src="res/orange.jpg" alt="apple" class='products-image'/>
                                    <label>Price per orange: $0.59</label>
                                    <div class="custom-field"><button type="button" class='minusButton'>-</button><input type="text" name="no_of_oranges" id='orangesTB' data-cost='0.59' value="0"><button type="button" class='addButton'>+</button></div><label class="error-message"></label>
                                </td>
                                <td class='input-wrapper'>
                                    <img src="res/banana.jpg" alt="apple" class='products-image'/>
                                    <label>Price per banana: $0.39</label>
                                    <div class="custom-field"><button type="button" class='minusButton'>-</button><input type="text" name="no_of_banana" id='bananaTB' data-cost='0.39' value="0" class="flex-item"><button type="button" class='addButton'>+</button></div><label class="error-message"></label>
                                </td>
                            </tr>
                        </tbody>
					</table>
					
                        <h3>Billing information:</h3>
                    <div id="billing-info">
                        <div>
                            <label>Name:</label>
                            <input type="text" name="name" id='nameTB'><label class="error-message"></label>
                        </div>
                        <div>
                           <label>Total Price:</label>
                            <input type='text' value="$0.00" id="sumTB" disabled>
                        </div>
                        <div>
                         <label>Total Price:</label> 
                        <input type="radio" name="payment_method" value="visa">Visa
                        <input type="radio" name="payment_method" value="mastercard">Mastercard
                        <input type="radio" name="payment_method" value="discover">Discover
                        </div>
                        <input type="submit" value="Order Now!">
                    </div>
				</form>
			</div>
		</div>
		<script type="application/javascript" src="ass2.js"></script>
	</body>
</html>