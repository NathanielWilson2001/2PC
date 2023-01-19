<?php
include('header.php');

function displayDefault(){
    
        echo"   <form id='PurchaseOrder'>";
                 if(isset($_GET['errors'])){
                     echo "<br><h4>Not enough product for PartID:</h4>";

                    for($i = 1; $i <= $_GET['errors']; $i++)
                    echo "<br><h4 class='quantError'>".$_GET['partID'.$i]."</h4><br>";
                    } 
                if(isset($_GET['success'])){
                        echo "<br><h4>Previous order Submitted</h4><br>";
                     } 
                if(isset($_GET['invalid'])){
                        echo "<br><h4>Invalid, please enter integers</h4><br>";
                     } 
                if(isset($_GET['invalidPO'])){
                        echo "<br><h4> Purchase Order ID exists, please try a new one</h4><br>";
                     } 
                if(isset($_GET['invalidCD'])){
                        echo "<br><h4>Client does not exist</h4><br>";
                     }    
                if(isset($_GET['voteC'])){
                        echo "<br><h4>VOTE COMMIT</h4><br>";
                     }
                if(isset($_GET['voteAbort'])){
                        echo "<br><h4>VOTE ABORT</h4><br>";
                     }        

                echo "    <input type='text' id='poNo' name='poNo' placeholder='Purhcase Order Number'>
                    <input type='text' id='clientID' name='clientID' placeholder='Client ID'>
                    <input type='text' id='numLines' name='numLines' placeholder='Number of Lines'>
                    <button type='submit'  id='submitForm'>Submit</button>
                </form>";   
                }
                
?>

<body>
<div class="bodyTable">


    <div class='formContainer'>
        <?php

            if(isset($_GET['poNo']) && isset($_GET['clientID']) && isset($_GET['numLines'])){
                
                if(!is_numeric($_GET['poNo']) || !is_numeric($_GET['clientID']) || !is_numeric($_GET['numLines'])) {

                    unset($_GET['poNo']);
                    unset($_GET['clientID']);
                    unset($_GET['numLines']);
                    
                    header("Location: submitPurchase.php?invalid=1");
                }
                else {

                    /* Check if IDs are valid */ 
                    $conn = include('db.php');
                    $clientID = $_GET['clientID'];
                    $sql = "SELECT clientID477 FROM client477 WHERE clientID477 = $clientID";
                    $result = $conn->query($sql); 
                    
                    $conn2 = include('db.php');
                    $poNo = $_GET['poNo'];
                    $sql2 = "SELECT poNo477 FROM purchaseOrder477 WHERE poNo477 = $poNo";
                    $result2 = $conn2->query($sql2); 
        
                    // Client ID Check
                    if(($result->num_rows != 0)) { 
                        // Purchase Order ID check
                        if($result2->num_rows == 0){

                            $count = $_GET['numLines'];
                            echo "<form id='PurchaseOrder' method='post'>";
                            /* Display Lines to Submit */
                            for( $i = 0; $i < $count; $i++) {
                                    echo"  
                                                <h5 class='listTitle'> Part $i: </h5>
                                                <input type='text' id='partID$i' name='partID$i' placeholder='Part ID'>
                                                <input type='text' id='quantity$i' name='quantity$i' placeholder='Quanity'>
                                                <br>
                                            ";
                            }
                            echo "<button type='submit' id='submitForm' name='submitLines'>Submit</button> </form>";

                        $lineNumber = 1;

                        if(isset($_POST['submitLines'])){

                            // Being transaction Store in logs
                            $sql = "INSERT INTO log477 (record477) VALUES ('Begin Transaction PONum:" . $poNo ."')";
                            $conn->query($sql);

                            $sql = "START TRANSACTION;";
                            $conn->query($sql);

                            $headerLink = "Location: submitPurchase.php";
                            $quantErrorCount = 0;
                            $sqlLines = [];
                            $logLines = [];
                            $failure = false;
                           
                            for( $i = 0; $i < $count; $i++) {
                                
                                
                                $part = $_POST['partID'.$i];
                                $quantity = $_POST['quantity'.$i];
                                $sql2 = "SELECT currentPrice477, quantityOnHand477 FROM parts477 WHERE partNo477 = $part;";
                    
                          
                                $result = $conn->query($sql2);
                                $row = $result->fetch_row();
                                $notEnough = false;
                        
                                /* Makes sure there is enough product */
                                if(intval($row[1]) >= intval($quantity)){
                                 
                                    $sqlLines[] = "INSERT INTO `compy`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477) VALUES ($lineNumber, $poNo, $quantity, $row[0], $part);";
                                    $logLines[] = "inserted $lineNumber, $poNo, $quantity, $row[0], $part into lines477";
                                    $lineNumber++;
                                }
                                else {

                                    $failure = true;
                                    $notEnough = true;
                                    $quantErrorCount++;
                                    if($quantErrorCount == 1){
                                        $headerLink = $headerLink."?partID$quantErrorCount=$part";
                                    }
                                    else {
                                        $headerLink = $headerLink."&partID$quantErrorCount=$part";
                                    }
                                    
                                }
                        
                            }
                            /* Insert into Purchaseorder477 */
                            if($failure){
                                $sql = "INSERT INTO log477 (record477) VALUES ('globally aborted for PONum:" . $poNo."')";
                                $conn->query($sql);
                                $headerLink = $headerLink."&errors=$quantErrorCount";
                                header($headerLink);
                            }
                            else {
                                //Log Record 
                                $sql = "INSERT INTO log477 (record477) VALUES ('inserted $poNo, $time, incomplete, $clientID into purchaseOrder477')";
                                $conn->query($sql);

                                $time = date('Y-m-d');
                                $sql = "INSERT INTO `compy`.`purchaseOrder477` (`poNo477`, `datePO477`, `status477`, `clientID477`) VALUES ($poNo, '$time', 'incomplete', $clientID);";
                                $conn->query($sql);

                                // Record Inserts
                                foreach($logLines as $query){
                                    $sql = "INSERT INTO log477 (record477) VALUES ('$query')";
                                    $conn->query($sql);
            
                                }

                                // Insert Lines
                                foreach($sqlLines as $query){
                                    $conn->query($query);
                                }
                                
                                $sql = "INSERT INTO log477 (record477) VALUES ('globally com for PONum:" . $poNo."')";
                                $conn->query($sql);
                                header($headerLink."?success=1");
                                
                            }
                            
                        }
                        elseif(isset($_GET['submitLines'])) {

                            if(isset($_GET['begin'])){
                                // Log write Ahead
                                $sql = "INSERT INTO log477 (record477) VALUES ('Begin Transaction PONum:" . $poNo ."')";
                                $conn->query($sql);

                                $sql = "START TRANSACTION;";
                                $conn->query($sql);
                            }

                            $headerLink = "Location: submitPurchase.php";
                            $quantErrorCount = 0;
                            $sqlLines = [];
                            $logLines = [];
                            $failure = false;

                            for( $i = 0; $i < $count; $i++) {
                                
                                
                                $part = $_GET['partID'.$i];
                                $quantity = $_GET['quantity'.$i];
                                $sql2 = "SELECT currentPrice477, quantityOnHand477 FROM parts477 WHERE partNo477 = $part;";
                    
                          
                                $result = $conn->query($sql2);
                                $row = $result->fetch_row();
                                $notEnough = false;
                        
                                /* Makes sure there is enough product */
                                if(intval($row[1]) >= intval($quantity)){
                                 
                                    $sqlLines[] = "INSERT INTO `compy`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477) VALUES ($lineNumber, $poNo, $quantity, $row[0], $part);";
                                    $logLines[] = "inserted $lineNumber, $poNo, $quantity, $row[0], $part into lines477";
                                    $lineNumber++;
                                }
                                else {
                                 
                                    $notEnough = true;
                                    $failure = true;
                                    $quantErrorCount++;
                                    if($quantErrorCount == 1){
                                        $headerLink = $headerLink."?partID$quantErrorCount=$part";
                                    }
                                    else {
                                        $headerLink = $headerLink."&partID$quantErrorCount=$part";
                                    }
                                    
                                }
    
                            }
                            /* Insert into Purchaseorder477 */
                            if($failure){
                                $sql = "INSERT INTO log477 (record477) VALUES ('globally aborted for PONum:" . $poNo."')";
                                $conn->query($sql);

                                $headerLink = $headerLink."&errors=$quantErrorCount&voteAbort=1";
                                header($headerLink);
                            }
                            else {

                                // Insert into Logs
                                $sql = "INSERT INTO log477 (record477) VALUES ('inserted $poNo, $time, incomplete, $clientID into purchaseOrder477')";
                                $conn->query($sql);

                                $time = date('Y-m-d');
                                $sql = "INSERT INTO `compy`.`purchaseOrder477` (`poNo477`, `datePO477`, `status477`, `clientID477`) VALUES ($poNo, '$time', 'incomplete', $clientID);";
                                $conn->query($sql);

                                // Record Inserts
                                foreach($logLines as $query){
                                    $sql = "INSERT INTO log477 (record477) VALUES ('$query')";
                                    $conn->query($sql);
            
                                }

                                foreach($sqlLines as $query){
                                    $conn->query($query);
                                }

                                if (isset($_GET['begin'])) {
                                    $sql = "INSERT INTO log477 (record477) VALUES ('voted com for PONum:" . $poID."')";
                                    $conn->query($sql);
                                    $conn = include('db.php');
                                    $sql = "INSERT INTO log477 (record477) VALUES ('Ready')";
                                    if ($conn->query($sql)) {
                                        header($headerLink . "?voteC=1");
                                    }
                                } else {
                                    header($headerLink . "?success=1");
                                }
                                
                            }
                        }
    

                        
                    }
                    // Invalid Purchase Order ID
                    else{
                        unset($_GET['poNo']);
                        unset($_GET['clientID']);
                        unset($_GET['numLines']);
                        
                        $sql = "INSERT INTO log477 (record477) VALUES ('voted abor for PONum:" . $poID."')";
                        $conn->query($sql);

                        header("Location: submitPurchase.php?invalidPO=1&voteAbort=1");
                    }
                }
                // Invalid Client ID
                else{
                        unset($_GET['poNo']);
                        unset($_GET['clientID']);
                        unset($_GET['numLines']);
                        
                        $sql = "INSERT INTO log477 (record477) VALUES ('voted abor for PONum:" . $poID."')";
                        $conn->query($sql);

                        header("Location: submitPurchase.php?invalidCD=1&voteAbort=1");
                    }
                    
                }
        
            }
            else {  
                displayDefault();
            }

            if(isset($_GET['commit'])){
                $conn = include('db.php');
            
                $sql = "INSERT INTO log477 (record477) VALUES ('globally com for PONum:" . $poNo."')";
                $conn->query($sql);
            
                $sql = "COMMIT;";
                $conn->query($sql); 
                
                $sql = "INSERT INTO log477 (record477) VALUES ('CHECKPOINT')";
                $conn->query($sql);
            }
            if(isset($_GET['abort'])){
                $conn = include('db.php');
            
                
                $sql = "ROLLBACK;";
                $conn->query($sql); 

                $sql = "INSERT INTO log477 (record477) VALUES ('globally aborted for PONum:" . $poNo."')";
                $conn->query($sql);
            
                
            }
        ?>
    </div>
        <?php?>
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button> 
</div>

</body>
</html>