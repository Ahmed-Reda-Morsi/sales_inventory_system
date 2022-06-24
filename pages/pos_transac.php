<?php
include '../includes/connection.php';
session_start();

$trans_QTY =$_POST ['quantity'];
$date = $_POST['date'];
$customer = $_POST['customer'];
$subtotal = $_POST['subtotal'];
$addvat = $_POST['addvat'];
$total = $_POST['total'];
$cash = $_POST['cash'];
$emp = $_POST['employee'];
$rol = $_POST['role'];
$today = date("mdGis");
$countID = count($_POST['name']);

$QTY_valid=1;
// compare of required QTY is available 
//print_r(count($_POST['name']));
for ($i=0; $i <$countID ; $i++) { 
    
    $query_qty_avail="SELECT QTY_STOCK FROM product where name ='{$_POST['name'][$i]}'";
    $qty_avail=mysqli_query($db, $query_qty_avail) or die(mysqli_error($db));
    $qty=mysqli_fetch_assoc($qty_avail);
    
    if($qty['QTY_STOCK']<$trans_QTY[$i]){
       $QTY_valid=0;
        break;}
}


if ($_GET['action']=='add' and $QTY_valid==1){

       
        for ($i = 0; $i < $countID; $i++) {
            
            $query = "INSERT INTO `transaction_details`
                               ( `TRANS_D_ID`, `PRODUCTS`, `QTY`, `PRICE`, `EMPLOYEE`, `ROLE`)
                               VALUES ( '{$today}', '" . $_POST['name'][$i] . "', '" . $_POST['quantity'][$i] . "', '" . $_POST['price'][$i] . "', '{$emp}', '{$rol}')";

            $query_update="UPDATE `product` SET `QTY_STOCK`=`QTY_STOCK`-'" . $_POST['quantity'][$i] . "' where name ='" . $_POST['name'][$i] . "'";
            
            mysqli_query($db, $query) or die(mysqli_error($db));
             
            mysqli_query($db, $query_update) or die(mysqli_error($db));
             }

             $query111 = "INSERT INTO `transaction`
             (`CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `ADDVAT`,`GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID`)
             VALUES ('{$customer}','{$countID}','{$subtotal}','{$addvat}','{$total}','{$cash}','{$date}','{$today}')";
                mysqli_query($db, $query111) or die(mysqli_error($db));


        
        echo ' <script type="text/javascript">
            alert("Success Submission.");
           </script>';  
      
    }

else{
    echo ' <script type="text/javascript">
    alert("Sorry (_._._)  Required Quantities are not availabe.");
   </script>';
}
    unset($_SESSION['pointofsale']);
    ?>
   <script type="text/javascript">
    window.location = "pos.php";
   </script>
</div>



