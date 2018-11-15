<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    redirectWithMessage("index.php", "Please enter your order information!", true);
}

$keys = array("no_of_apples", "no_of_oranges", "no_of_banana", "name", "payment_method");

$pass = validatePOSTData($keys);

$params = array();
foreach($keys as $key){
    $params[$key] = $_POST[$key];
}

$prices = [
    'no_of_apples'=> 0.69,
    'no_of_oranges'=>0.59,
    'no_of_banana'=>0.39
];

$reciept = generateRecieptArray($params, $prices);


redirectWithMessage("reciept.php", $reciept);

$fileName = 'order.txt';
saveOrderInformation($fileName, $reciept);





//helper functions

function validatePOSTData($params){
    $errorMessage = "";
    foreach($params as $key){
        if(!isset($_POST[$key])){
            $errorMessage .= "One required parameters is unset. Redirecting you to the orderpage.";
            header("refresh:5;url=index.php");
            die($errorMessage);
        }
    }
}


function generateRecieptArray($params, $prices){
    $reciept = array();

    $totalCost = 0;

    foreach($prices as $key=>$value){
        $qty        = $params[$key];
        $subTotal   = $params[$key] * $value;
        $totalCost  += $subTotal;
        $newKey     = substr($key, 6);
        $reciept['items'][$newKey]  = ['qty'=>intval($params[$key]), 'price'=>$value, 'sub_total'=>$subTotal];
    }
    $reciept['order_by'] = $params['name'];
    $reciept['payment_method'] = $params['payment_method'];
    $reciept['total_cost'] = $totalCost;
    return $reciept;
}

function saveOrderInformation($fileName, $reciept){
    $f;
    if(file_exists($fileName)){
        $f = fopen($fileName, "r+");    
    }
    else{
        $f = fopen($fileName, "w");
    }
    
    if(flock($f, LOCK_EX)){
        $oldContent = readOldOrderFromFile($f, $fileName);
    }
    else{
        //echo("Unable to acquire file lock");
        header("Location: index.php");
    }
    
    $newFileContents = "";
    $temp;
    
    foreach($reciept['items'] as $key => $value){
        preg_match("/^Total number of $key: *\d+/m", $oldContent, $temp);
        if(count($temp) == 0){
            $newLine = "Total number of $key: " . $value['qty'].PHP_EOL;
            $newFileContents .= $newLine;
        }
        else{
            $search = $key.":";
            $pos = strpos($temp[0], $search);
            $pos += strlen($search);
            $oldValueStr = substr($temp[0], $pos, strlen($temp[0])-$pos);
            $oldValue = intval($oldValueStr);
            $newValue = $oldValue + $value['qty'];
            $newLine = "Total number of $key: ".$newValue . PHP_EOL;
            $newFileContents .= $newLine;
        }
    }
    rewind($f);
    writeNewOrdersToFile($f, $newFileContents);
    flock($f, LOCK_UN);
    fflush($f);
    fclose($f);
}

function readOldOrderFromFile($f, $fileName){
    $filesize = filesize($fileName);
    $oldContent;
    
    if($filesize > 0){
        $oldContent = fread($f, $filesize); 
    }
    return $oldContent;
}

function writeNewOrdersToFile($f, $newFileContents){
    if(!fwrite($f, $newFileContents)){
        die("Something went wrong while writing to the text file. Please check again");
    }
}

function redirectWithMessage($url, $message, $errors=false){
    session_save_path(getcwd()."/sessions");
    session_start();
    
    if($errors){
        $_SESSION['error_message'] = $message;
    }
    else{
        $_SESSION['data'] = $message;
    }
    session_write_close();

    header("Location: $url");

}