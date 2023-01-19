<?php
include('header.php');
?>

<body>
<div class="bodyTable">
        <?php


         // Using Website for Company X, scan through collect info
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, "http://localhost:81/4140Assignment3/compx/includes/partsForSale.php");
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

            // Store webpage HTML into results
            $result = curl_exec($ch1);

           // Create a DOM and load the html
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($result);
            
            $count = 0;
            $partID = [];
            $partName = [];
            $partDescription = [];
            $currentPrice = [];


            // Scan through dom add to arrays
            foreach($dom->getElementsByTagName("td") as $column){
                
                if($count == 0){
                    array_push($partID, $dom->saveHTML($column));
                   
                }
                elseif($count == 1){
                    array_push($partName, $dom->saveHTML($column));
                   
                }
                elseif($count == 2){
                    array_push($partDescription,$dom->saveHTML($column));
                 
                }
                elseif($count == 3){
                    array_push($currentPrice,$dom->saveHTML($column));
                   
                }
                $count++;
                if($count == 5){
                    $count = 0;
                }
            }
            curl_close($ch1);

            // Scan through website for Company Y 
            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_URL, "http://localhost:81/4140Assignment3/compy/includes/partsForSale.php");
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

            // Store webpage HTML into results
            $result = curl_exec($ch2);

           // Create a DOM and load the html
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($result);
            
            $count = 0;
            $notRepeat = true;
        

            // Scan through dom add to arrays
            foreach($dom->getElementsByTagName("td") as $column){
                
                $partIDCheck;
                if(in_array($dom->saveHTML($column), $partID, true)) {
                    $notRepeat = false;
                    $partIDCheck = $dom->saveHTML($column);
                }
                
                if($notRepeat && $count == 0){
                    array_push($partID, $dom->saveHTML($column));
                   
                }
                elseif($count == 1 && $notRepeat){
                    array_push($partName, $dom->saveHTML($column));
                   
                }
                elseif($count == 2 && $notRepeat){
                    array_push($partDescription,$dom->saveHTML($column));
                 
                }
                elseif($count == 3 && $notRepeat){
                    array_push($currentPrice,$dom->saveHTML($column));
                   
                }

                // Replace with lower price
                if(!$notRepeat && $count == 3){
                    
                   
                        preg_match_all('/([0-9])*[.][0-9][0-9]/',  $dom->saveHTML($column), $string);
                        preg_match_all('/([0-9])*[.][0-9][0-9]/',  $currentPrice[array_search($partIDCheck, $partID)], $string2);
             

                        if( floatval($string2[0][0]) > floatval($string[0][0])){
                            $currentPrice[array_search($partIDCheck, $partID)] = $dom->saveHTML($column);
                        }
                    
                    
                }
            
                $count++;
                if($count == 5){
                    $count = 0;
                    $notRepeat = true;
                }
            }
            curl_close($ch2);
            
         

            

            echo "<table><tr class='tableHeader'><th class='tableHeaderRight'>Part ID</th><th>Part Name</th><th>Part Description</th><th class='tableHeaderLeft'>Price</th></tr>";
            for($i =0, $count = count($partID); $i < $count; $i++){
                echo "<tr>";
                echo" $partID[$i]";
                echo"$partName[$i]";
                echo"$partDescription[$i]";
                echo"$currentPrice[$i]";
                echo "</tr>";
            }
            echo "</table>";
        ?>
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button>  
</div>

</body>
</html>