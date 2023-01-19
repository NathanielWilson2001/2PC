<?php
include('header.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$username = "root";
$conn = new PDO('mysql:host=localhost;dbname=compz', $username, "root");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function displayDefault(){

        echo "   <form id='PurchaseOrder'>";
        if (isset($_GET['success'])) {
            echo "<br><h4>Previous order Submitted</h4><br>";
        }
        if (isset($_GET['error'])) {
            echo "<br><h4>Transaction Failed, Changes Rolled Back</h4><br>";
        }
        if(isset($_GET['x'])) {
            echo  "<br><h4>Response From X: ".$_GET['x']."</h4><br>";
        }
        if(isset($_GET['y'])) {
            echo  "<br><h4>Response From Y: ".$_GET['y']."</h4><br>";
        }
        if (isset($_GET['invalid'])) {
            echo "<br><h4>Invalid Inputs, please use integers</h4><br>";
        }
        if (isset($_GET['invalidPO'])) {
            echo "<br><h4> Purchase Order ID exists, please try a new one</h4><br>";
        }
        if (isset($_GET['invalidCD'])) {
            echo "<br><h4>Client does not exist</h4><br>";
        }
        echo "      <input type='text' id='poNo' name='poNo' placeholder='Purhcase Order Number'>
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
        $error = 0;

            if(isset($_GET['poNo']) && isset($_GET['clientID']) && isset($_GET['numLines'])){
                $error = 0;

                if(!is_numeric($_GET['poNo']) || !is_numeric($_GET['clientID']) || !is_numeric($_GET['numLines'])) {

                    unset($_GET['poNo']);
                    unset($_GET['clientID']);
                    unset($_GET['numLines']);

                    header("Location: submitPurchase.php?invalid=1");
                }
                else {
                    $error = 0;
                    /* Check if IDs are valid */ 
                    
                    $conn->beginTransaction();
                    $clientID = $_GET['clientID'];
                    $sql = "SELECT clientID477 FROM client477 WHERE clientID477 = $clientID";
                    $result = $conn->query($sql);
                    
                    $poNo = $_GET['poNo'];
                    $sql2 = "SELECT poNo477 FROM purchaseOrder477 WHERE poNo477 = $poNo";
                    $result2 = $conn->query($sql2); 
                    $conn->commit();
        
                    // Client ID Check
                    if(($result->rowCount() != 0)){
                        // Purchase Order ID check
                         if(($result2->rowCount() == 0)){

                            $numLines = $_GET['numLines'];
                            echo "<form id='PurchaseOrder' method='post' onsubmit='window.location.reload()'>";
                            /* Display Lines to Submit */
                            for( $i = 0; $i < $numLines; $i++) {
                                    echo"  
                                                <h5 class='listTitle'> Part $i: </h5>
                                                <input type='text' id='partID$i' name='partID$i' placeholder='Part ID'>
                                                <input type='text' id='quantity$i' name='quantity$i' placeholder='Quanity'>
                                                <br>
                                            ";
                        }
                        echo "<button type='submit' id='submitForm' name='submit'>Submit</button> </form>";

                        $poNo = $_GET['poNo'];

                                if(isset($_POST['submit'])){

                                    /* Get Parts with Best Price, by first getting the list of all parts, then selecting which partIDs the client wants*/
                                    $ch1 = curl_init();
                                    curl_setopt($ch1, CURLOPT_URL, "http://localhost:81/4140Assignment4/compx/includes/partsForSale.php");
                                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                        
                                    // Store webpage HTML into results
                                    $result = curl_exec($ch1);
                        
                                   // Create a DOM and load the html
                                    $dom = new DOMDocument();
                                    libxml_use_internal_errors(true);
                                    $dom->loadHTML($result);
                                    
                                    $count = 0;
                                    $partID = [];
                                    $company = [];
                                    $currentPrice = [];

                                    $quantityX = [];
                                    $priceX = [];
                                    $partIDX= [];
                        
                                    // Scan through dom add to arrays
                                    foreach($dom->getElementsByTagName("td") as $column){
                                        
                                        if($count == 0){
                                            array_push($partID, $dom->saveHTML($column));
                                            array_push($partIDX, $dom->saveHTML($column));
                                            array_push($company, 'x');  
                                        }
                                        elseif($count == 3){
                                            array_push($currentPrice,$dom->saveHTML($column));
                                            array_push($priceX, $dom->saveHTML($column));  
                                        }
                                        elseif($count == 4){
                                            array_push($quantityX, $dom->saveHTML($column));
                                        }
                                        
                                        $count++;
                                        if($count == 5){
                                            $count = 0;
                                        }
                                    }
                                    curl_close($ch1);
                        
                                    // Scan through website for Company Y 
                                    $ch2 = curl_init();
                                    curl_setopt($ch2, CURLOPT_URL, "http://localhost:81/4140Assignment4/compy/includes/partsForSale.php");
                                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                        
                                    // Store webpage HTML into results
                                    $result = curl_exec($ch2);
                        
                                   // Create a DOM and load the html
                                    $dom = new DOMDocument();
                                    libxml_use_internal_errors(true);
                                    $dom->loadHTML($result);
                                    
                                    $count = 0;
                                    $notRepeat = true;

                                    $quantityy = [];
                                    $pricey = [];
                                    $partIDy= [];
                                    // Scan through dom add to arrays
                                    foreach($dom->getElementsByTagName("td") as $column){
                                        
                                        $partIDCheck;
                                        if(in_array($dom->saveHTML($column), $partID, true)) {
                                            $notRepeat = false;
                                            $partIDCheck = $dom->saveHTML($column);
                                        }
                
                                        if($notRepeat && $count == 0){
                                            array_push($partID, $dom->saveHTML($column));
                                            array_push($company, 'y');
                                           
                                        }
                                        elseif($count == 3 && $notRepeat){
                                            array_push($currentPrice,$dom->saveHTML($column));
                                        }

                                        if($count == 0){
                                            array_push($partIDy, $dom->saveHTML($column));
                                        }
                                        elseif($count == 3){
                                            array_push($pricey, $dom->saveHTML($column));
                                        }
                                        elseif($count == 4){
                                            array_push($quantityy, $dom->saveHTML($column));
                                        }
                        
                                        // Replace with lower price
                                        if(!$notRepeat && $count == 3){
                                            
                                                preg_match_all('/([0-9])*[.][0-9][0-9]/',  $dom->saveHTML($column), $string);
                                                preg_match_all('/([0-9])*[.][0-9][0-9]/',  $currentPrice[array_search($partIDCheck, $partID)], $string2);
                                                
                                                if( floatval($string2[0][0]) > floatval($string[0][0])){
                                                    $currentPrice[array_search($partIDCheck, $partID)] = $dom->saveHTML($column);
                                                    $company[array_search($partIDCheck, $partID)] = 'y';
                                                }
                                        }
                                        
                                        $count++;
                                        if($count == 5){
                                            $count = 0;
                                            $notRepeat = true;
                                        }
                                    }
                                    curl_close($ch2);

                                    $comToUse = ""; 
                                    $enough = true;
                                
                                    $sqlLinesZ = [];
                                    $logLines = [];
                                    $paramsX = [];
                                    $paramsY = [];

                                    $lineNumberX = 0;
                                    $lineNumberY = 0;
                                    $lineNumberZ = 0;
                                 
                                    for( $i = 0; $i < $numLines; $i++) {
                                        
                                        $part = $_POST['partID'.$i];
                                        $quantity = $_POST['quantity'.$i];
                                        $comToUse =  $company[array_search("<td> ".$part." </td>", $partID)];

                                        // If the part is orderd from X check if there is enough, if not check Y and switch if Y has enough
                                        if($comToUse == 'x')
                                        {
                                            if($quantity > $quantityX[array_search($part, $partIDX)])
                                            {
                                                $enough = false;

                                                if($quantity < $quantityy[array_search($part, $partIDy)]) {
                                                   
                                                    $comToUse = 'y';
                                                    $enough = true;

                                                    $paramsY[] = "partID".$lineNumberY."=".$part;
                                                    $paramsY[] = "quantity".$lineNumberY."=".$quantity;

                                                    $partPrice = $pricey[array_search($part, $partIDy)];
                                                    preg_match_all('/([0-9])*[.][0-9][0-9]/',  $partPrice, $string);
                                                    $partPrice = $string[0][0];
                                                    
                                                    $lineNumberY++;
                                                    $lineNumberZ++;
                                                    $logLines[] = "inserted $lineNumberZ, $poNo, $quantity,  $partPrice, $part into lines477";
                                                    $sqlLinesZ[] = "INSERT INTO `compz`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477, company477) VALUES ($lineNumberZ, $poNo, $quantity, $partPrice, $part, 'Y');";
                                               }
                                            }
                                            else
                                            {
                                         
                                                $comToUse = 'x';
                                                $paramsX[] = "partID".$lineNumberX."=".$part;
                                                $paramsX[] = "quantity".$lineNumberX."=".$quantity;
                                                $partPrice = $priceX[array_search($part, $partIDX)];
                                                preg_match_all('/([0-9])*[.][0-9][0-9]/',  $partPrice, $string);
                                                $partPrice = $string[0][0];

                                                $lineNumberX++;
                                                $lineNumberZ++;
                                                $logLines[] = "inserted $lineNumberZ, $poNo, $quantity,  $partPrice, $part into lines477";
                                                $sqlLinesZ[] = "INSERT INTO `compz`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477, company477) VALUES ($lineNumberZ, $poNo, $quantity, $partPrice, $part, 'X');";
                                                
                                            }
                                        }
                                        
                                        if($comToUse == 'y') {
                                            if($quantity > $quantityy[array_search($part, $partIDy)])
                                            {
                                                $enough = false;
                                                if($quantity < $quantityX[array_search($part, $partIDX)]) {
                                               
                                                    $comToUse = 'x';
                                                    $enough = true;

                                                    $paramsX[] = "partID".$lineNumberX."=".$part;
                                                    $paramsX[] = "quantity".$lineNumberX."=".$quantity;

                                                    $partPrice = $priceX[array_search($part, $partIDX)];
                                                    preg_match_all('/([0-9])*[.][0-9][0-9]/',  $partPrice, $string);
                                                    $partPrice = $string[0][0];

                                                    $lineNumberX++;
                                                    $lineNumberZ++;
                                                    $logLines[] = "inserted $lineNumberZ, $poNo, $quantity,  $partPrice, $part into lines477";
                                                    $sqlLinesZ[] = "INSERT INTO `compz`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477, company477) VALUES ($lineNumberZ, $poNo, $quantity, $partPrice, $part, 'X');";  
                                                }
                                            }
                                            else 
                                            { 
                                                $comToUse = 'y';
                                                $paramsY[] = "partID".$lineNumberY."=".$part;
                                                $paramsY[] = "quantity".$lineNumberY."=".$quantity;
                                                
                                                $partPrice = $pricey[array_search($part, $partIDy)];
                                                preg_match_all('/([0-9])*[.][0-9][0-9]/',  $partPrice, $string);
                                                $partPrice = $string[0][0];

                                                $lineNumberY++;
                                                $lineNumberZ++;
                                                $logLines[] = "inserted $lineNumberZ, $poNo, $quantity,  $partPrice, $part into lines477";
                                                $sqlLinesZ[] = "INSERT INTO `compz`.`lines477` (lineNo477, purchaseOrder477_poNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477, company477) VALUES ($lineNumberZ, $poNo, $quantity, $partPrice, $part, 'Y');";
                                              
                                            }
                                        }

                                        if(!$enough) {
                                            echo "<script>alert('Not enough quantity on hand for part: $part');</script>";
                                        } 
                                    }
                                    
                                   try {


                                        $conn->beginTransaction();
                                       

                                        //Log write Ahead
                                        $sql = "INSERT INTO log477 (record477) VALUES ('BEGIN PONum:" . $poNo ."');";
                                        $conn->query($sql);
                                       
                                        $urlX = "http://localhost:81/4140Assignment4/compx/includes/submitPurchase.php?poNo=".$poNo."&clientID=4&numLines=".$lineNumberX;
                                        $urlY = "http://localhost:81/4140Assignment4/compy/includes/submitPurchase.php?poNo=".$poNo."&clientID=4&numLines=".$lineNumberY;
                                  
                                        $paramNum = 0;

                                        foreach($paramsX as $value){
                                            $urlX = $urlX."&".$value;
                                        }
                                        
                                        foreach($paramsY as $value){
                                                    
                                                $urlY = $urlY."&".$value;
                                        }
                                       
                                        echo "<script>alert('SEND READY/BEGIN');</script>";

                                        $urlXbegin = $urlX."&submitLines=1&begin=1";
                                        $urlYbegin = $urlY."&submitLines=1&begin=1";

                                        $sql = "INSERT INTO log477 (record477) VALUES ('Send BEGIN to X and Y');";
                                        $conn->query($sql);
                                        $sql = "INSERT INTO log477 (record477) VALUES ('In Wait State');";
                                        $conn->query($sql);
                                        
                                        $time = date('Y-m-d');

                                        //Insert into Logs
                                        $sql = "INSERT INTO log477 (record477) VALUES ('inserted $poNo, $time, incomplete, $clientID into purchaseOrder477');";
                                        $conn->query($sql);

                                        $time = date('Y-m-d');
                                        $sql = "INSERT INTO `compz`.`purchaseOrder477` (`poNo477`, `datePO477`, `status477`, `clientID477`) VALUES ($poNo, '$time', 'incomplete', $clientID);";
                                     
                                        $conn->query($sql);

                                        // Record Inserts
                                        foreach($logLines as $query){
                                            $sql = "INSERT INTO log477 (record477) VALUES ('$query')";
                                            $conn->query($sql);
                    
                                        }
                                
                                        foreach($sqlLinesZ as $query){
                                          $conn->query($query);
                                        }



                                if (sizeof($paramsX) > 0) {
                                    $chX = curl_init();
                                    curl_setopt($chX, CURLOPT_URL, $urlXbegin);
                                    curl_setopt($chX, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($chX, CURLOPT_FOLLOWLOCATION, 1);
                                    $returnedX = curl_exec($chX);
                                    curl_close($chX);

                                    $domX = new DOMDocument();
                                    libxml_use_internal_errors(true);
                                    $domX->loadHTML($returnedX);

                                    $responseX = "";
                                    $successX = false;
                                    foreach ($domX->getElementsByTagName("h4") as $errorCheck) {

                                        if (strcmp($domX->saveHTML($errorCheck), "<h4>VOTE COMMIT</h4>") == 0) {
                                            $successX = true;
                                            $responseX = $responseY . "VOTE COMMIT";

                                        } elseif (strcmp($domX->saveHTML($errorCheck), "<h4>VOTE ABORT</h4>") == 0) {
                                            $responseX = $responseX . " VOTE ABORT";
                                        } elseif (strcmp($domX->saveHTML($errorCheck), "<h4>Invalid, please enter integers</h4>") == 0) {
                                            $responseX = $responseX . "<br>Invalid input, must use Integers";
                                        } elseif (strcmp($domX->saveHTML($errorCheck), "<h4> Purchase Order ID exists, please try a new one</h4>") == 0) {
                                            $responseX = $responseX . "<br>Purchase Order ID exists";
                                        } elseif (strcmp($domX->saveHTML($errorCheck), "<h4> Client does not exist</h4>") == 0) {
                                            $responseX = $responseX . "<br>CLient does not exist";
                                        } elseif (strcmp($domX->saveHTML($errorCheck), "<h4>Not enough product for PartID:</h4>") == 0) {
                                            $responseX = $responseX . "<br>Not enough Product on Hand";
                                        }
                                    }
                                } else {
                                    $successX = true; 
                                }

                                if (sizeof($paramsY)  > 0) {
                                    $chY = curl_init();
                                    curl_setopt($chY, CURLOPT_URL, $urlYbegin);
                                    curl_setopt($chY, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($chY, CURLOPT_FOLLOWLOCATION, 1);
                                    $returnedY = curl_exec($chY);
                                    curl_close($chY);

                                    $domY = new DOMDocument();
                                    libxml_use_internal_errors(true);
                                    $domY->loadHTML($returnedY);

                                    $responseY = "";
                                    $successY = false;
                                    foreach ($domY->getElementsByTagName("h4") as $errorCheck) {

                                        if (strcmp($domY->saveHTML($errorCheck), "<h4>VOTE COMMIT</h4>") == 0) {
                                            $successY = true;
                                            $responseY = $responseY . "VOTE COMMIT";

                                        } 
                                        elseif (strcmp($domY->saveHTML($errorCheck), "<h4>VOTE ABORT</h4>") == 0) {
                                            $responseY = $responseY . " VOTE ABORT";
                                        } elseif (strcmp($domY->saveHTML($errorCheck), "<h4>Invalid, please enter integers</h4>") == 0) {
                                            $responseY = $responseY . "<br> Invalid input, must use Integers";
                                        } elseif (strcmp($domY->saveHTML($errorCheck), "<h4> Purchase Order ID exists, please try a new one</h4>") == 0) {
                                            $responseY = $responseY . "<br> Purchase Order ID exists";
                                        } elseif (strcmp($domY->saveHTML($errorCheck), "<h4> Client does not exist</h4>") == 0) {
                                            $responseY = $responseY . "<br> CLient does not exist";
                                        } elseif (strcmp($domY->saveHTML($errorCheck), "<h4>Not enough product for PartID:</h4>") == 0) {
                                            $responseY = $responseY . "<br> Not enough Product on Hand";
                                        }
                                    }
                             } else {
                                    $successY = true; 
                                }
                                

                                        if($successX && $successY){
                                            
                                            echo "<script>alert('GLOBAL COMMIT')</script>";

                                            $sql = "INSERT INTO log477 (record477) VALUES ('sent glob com for PONum:" . $poID."');";
                                            $conn->query($sql);

                                                if (sizeof($paramsX) > 1) {
                                                    $chXcommit = curl_init();
                                                    $urlX = $urlX . "&submitLines=1&commit=1";
                                                    curl_setopt($chXcommit, CURLOPT_URL, $urlX);
                                                    curl_exec($chXcommit);
                                                    curl_close($chXcommit);
                                                }
                                                if (sizeof($paramsY) > 1) {
                                                    $chYcommit = curl_init();
                                                    $urlY = $urlY . "&submitLines=1&commit=1";
                                                    curl_setopt($chYcommit, CURLOPT_URL, $urlY);
                                                    curl_exec($chYcommit);
                                                    curl_close($chYcommit);
                                                }
                                            $conn->commit();

                                            $sql = "INSERT INTO log477 (record477) VALUES ('CHECKPOINT');";
                                            $conn->query($sql);

                                            if (sizeof($paramsX)  > 0 && sizeof($paramsY) > 0) {
                                                header("Location: submitPurchase.php?success=1&x=" . $responseX . "&y=" . $responseY);
                                            }
                                            elseif(sizeof($paramsX)  > 0){
                                             header("Location: submitPurchase.php?success=1&x=" . $responseX);
                                            }
                                            elseif(sizeof($paramsY) > 0){
                                                header("Location: submitPurchase.php?success=1&y=" . $responseY);
                                            }
                                            
                                         } else {

                                            echo "<script>alert('GLOBAL ABORT');</script>";
                                            $sql = "INSERT INTO log477 (record477) VALUES ('sent glob abor for PONum:" . $poID."');";
                                            $conn->query($sql);

                                            if (sizeof($paramsX) > 0) {
                                                $chXcommit = curl_init();
                                                $urlX = $urlX . "&submitLines=1&abort=1";
                                                curl_setopt($chXcommit, CURLOPT_URL, $urlX);
                                                curl_exec($chXcommit);
                                                curl_close($chXcommit);
                                            }
                                            if (sizeof($paramsY) > 0) {
                                                $chYcommit = curl_init();
                                                $urlY = $urlY . "&submitLines=1&abort=1";
                                                curl_setopt($chYcommit, CURLOPT_URL, $urlY);
                                                curl_exec($chYcommit);
                                                curl_close($chYcommit);
                                            }

                                            $conn->rollback();
                                            $conn->beginTransaction();
                                            // Log write Ahead
                                            $sql = "INSERT INTO log477 (record477) VALUES ('Aborted');";
                                            $conn->query($sql);
                                            $conn->commit();

                                            if (sizeof($paramsX) > 0 && sizeof($paramsY) > 0) {
                                                header("Location: submitPurchase.php?error=1&x=" . $responseX . "&y=" . $responseY);
                                            }
                                            elseif(sizeof($paramsX) > 0){
                                             header("Location: submitPurchase.php?error=1&x=" . $responseX);
                                            }
                                            elseif(sizeof($paramsY) > 0){
                                                header("Location: submitPurchase.php?error=1&y=" . $responseY);
                                            }
                                         }
                                    }
                                   catch(PDOException $error) {
                                       header("Location: submitPurchase.php?error=1"); 
                                    }  
                                }
                         
    
                       
                        
                    }
                    // Invalid Purchase Order Num
                    else{
                        header("Location: submitPurchase.php?invalidPO=1");
                        displayDefault();
                    }
                }
                // Invalid Client ID
                else {
                    unset($_GET['poNo']);
                    unset($_GET['clientID']);
                    unset($_GET['numLines']);
                    
                    header("Location: submitPurchase.php?invalidCD=1");
                }
                }
        
            }
            else {  
                displayDefault();
            }
        ?>
    </div>
        
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button> 
</div>

</body>
</html>