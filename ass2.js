/*jslint devel: true */
(function (){
    // Bind the event handler to the onload event
    document.addEventListener("DOMContentLoaded", onLoad);
    
    // Event handler for window load event. This event is triggered when all the resources
    // of the corresponding web page has been loaded.
    // Get the neccessary DOM elements and bind their event handlers.
    function onLoad(){
		var nameTB = document.getElementById('nameTB');
		var applesTB = document.getElementById('applesTB');
		var orangesTB = document.getElementById('orangesTB');
		var bananaTB = document.getElementById('bananaTB');
		var orderForm = document.getElementById('order-form');

		
		nameTB.addEventListener('keyup', onNameChange);
		applesTB.addEventListener('keyup', onValueChange);
		orangesTB.addEventListener('keyup', onValueChange);
		bananaTB.addEventListener('keyup', onValueChange);
        applesTB.addEventListener('focus', selectOnFocus);
		orangesTB.addEventListener('focus', selectOnFocus);
		bananaTB.addEventListener('focus', selectOnFocus);
		orderForm.addEventListener('submit', formSubmit);
        
        var addButtons = document.querySelectorAll('.addButton');
        for(let i = 0; i < addButtons.length; i++){
            addButtons[i].addEventListener('click',addValue);
            addButtons[i].tabIndex = -1;
        }
        var minusButtons = document.querySelectorAll('.minusButton');
        for(let i = 0; i < addButtons.length; i++){
            minusButtons[i].addEventListener('click',minusValue);
            minusButtons[i].tabIndex = -1;
        }
	}
    
    function minusValue(){
        var value = this.nextSibling.value;
        if(!checkNumeric(value)){
            return;
        }
        value = parseInt(value);
        if(value == 0){
            return;
        }
        else{
            value--;
            this.nextSibling.value = value;
        }
        updateCalculatedValue();
    }
    
    function addValue(){
        var value = this.previousSibling.value;
        if(!checkNumeric(value)){
            return;
        }
        value = parseInt(value);
        value++;
        this.previousSibling.value = value;
        updateCalculatedValue();
    }
    
	function checkIsAlpha(strToCheck){
			var patternToCheck = /^([a-zA-Z]+ ?)+$/;
			if(patternToCheck.test(strToCheck)){
				return true;
			}
			else{
				return false;
			}
	}
	
	function checkNumeric(strToCheck){
        var numericPattern = /^ *([0-9]+){1} *$/;
		if(!numericPattern.test(strToCheck)){
			return false;
		}
		return true;
	}
	
	function checkPositive(value){
		return value >= 0;
	}
	
	function onNameChange() {
		var value = this.value;
		var errorMessage = "May only contain letter and 1 trailing whitespace.";
		if(!checkIsAlpha(value)){
			this.nextSibling.innerHTML = errorMessage;
		}
		else{
			this.nextSibling.innerHTML = "";
		}
	}
	
	function onValueChange(event){
		var value = this.value;
        
		if(!checkNumeric(value)){
			this.parentElement.nextSibling.innerHTML = "Please only key in numeric values.";
			updateCalculatedValue();
			return;
		}
		var qty = parseInt(value);

		if(!checkPositive(qty)){
			this.parentElement.nextSibling.innerHTML = "Please only key in positive quantity.";
			updateCalculatedValue();
			return;
		}
        
		this.parentElement.nextSibling.innerHTML = "";
		updateCalculatedValue();
	}
	
	
	//functino to update the total sum.
	function updateCalculatedValue(){
		var total = 0;
		var applesTB = document.getElementById('applesTB');
		var orangesTB = document.getElementById('orangesTB');
		var bananaTB = document.getElementById('bananaTB');
		var sumTB = document.getElementById('sumTB');
        
        
		if(!checkNumeric(applesTB.value) || !checkNumeric(orangesTB.value) || !checkNumeric(bananaTB.value)){
			sumTB.value = "NaN";
			return;
		}
		
		//compute and update the textbox
		total = (applesTB.value * applesTB.dataset.cost) + (orangesTB.value * orangesTB.dataset.cost) + (bananaTB.value * bananaTB.dataset.cost);
		sumTB.value = '$'+total.toFixed(2);
	}
	
	// Handler for submit
	function formSubmit(event){
		var nameTB = document.getElementById('nameTB');
		var applesTB = document.getElementById('applesTB');
		var orangesTB = document.getElementById('orangesTB');
		var bananaTB = document.getElementById('bananaTB');
		var orderForm = document.getElementById('order-form');
		var payment_method = document.getElementsByName("payment_method");
		
		var name = nameTB.value;
		var applesQty = applesTB.value;
		var orangesQty = orangesTB.value;
		var bananaQty = bananaTB.value;
		
		var shouldExecuteFlag = true;
		var errorMessage = "";
		// Check if its valid string for name
		if(!checkIsAlpha(name)){
			shouldExecuteFlag = false;
			errorMessage += "Your name should only have alphabets.";
		}
		
		//Check if its valid qty value for apple, oranges and banana, valid string and positive
		if(!checkNumeric(applesQty) || !checkPositive(parseInt(applesQty))){
			shouldExecuteFlag = false;
			errorMessage += "\nApple quantity should only have whole numbers.";
		}
		if(!checkNumeric(orangesQty) || !checkPositive(parseInt(orangesQty))){
			shouldExecuteFlag = false;
			errorMessage += "\nOrange quantity should only have whole numbers.";
		}
		if(!checkNumeric(bananaQty) || !checkPositive(parseInt(bananaQty))){
			shouldExecuteFlag = false;
			errorMessage += "\nBanana quantity should only have whole numbers.";
		}
		
		if(shouldExecuteFlag){
			let totalQty = parseInt(applesQty) + parseInt(orangesQty) + parseInt(bananaQty);
			console.log(totalQty);
			if(totalQty	 <= 0){
				errorMessage += "\nYou should order at least 1 fruit!";
				shouldExecuteFlag = false;
			}
		}
		var haveOnePayment = false;
		for(let i = 0; i < payment_method.length; i++){
			if(payment_method[i].checked){
				haveOnePayment = true;
			}
		}
		
		if(!haveOnePayment){
			shouldExecuteFlag = false;
			errorMessage += "\nYou should specify at least 1 payment method!";
		}
		
		if(!shouldExecuteFlag){
			event.preventDefault();
			alert(errorMessage);
			return false;
		}
	}
    
    function selectOnFocus(){
        this.select();
    }
})();