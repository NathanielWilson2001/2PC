<?php
include('header.php');
?>

<body>
<div class="bodyTable">


    <div>
        <form class='searchContainer'>
            <input type="text" class="searchBar" name='search' placeholder="Search">
            <button type="submit" class="submitSearch"><i class="fa fa-search"></i></button>
        </form>
    </div>
        
        <?php

        if(isset($_GET['search'])){


            $purchaseOrder = $_GET['search'];
            $conn = include('db.php');
            $purchaseOrder = $_GET['search'];
                
            if(is_numeric($purchaseOrder)){
                        $sql = "SELECT lineNo477, quantityOnOrder477, purchasePrice477, parts477_partNo477 FROM lines477 WHERE purchaseOrder477_poNo477 = $purchaseOrder";
                        $result = $conn->query($sql); 

                        $lineNum = [];
                        $quantityOnOrder477 = [];
                        $purchasePrice477 = [];
                        $parts477_partNo477 = [];
                            while ($row = $result->fetch_row()){

                                array_push($lineNum, $row[0]);
                                array_push($quantityOnOrder477, $row[1]);
                                array_push($purchasePrice477, $row[2]);
                                array_push($parts477_partNo477, $row[3]);
                            }
                            
                            
                            if(sizeof($lineNum) > 0) {
                                $count = sizeof($lineNum);
                                echo "<table><tr class='tableHeader'><th class='tableHeaderRight'>Line Number</th><th>Quantity Ordered</th><th>PurchasePrice</th><th class='tableHeaderLeft'>Part</th></tr>";
                                for($i =0; $i < $count; $i++){
                                    echo "<tr>";
                                    echo"<td> $lineNum[$i] </td>";
                                    echo"<td> $quantityOnOrder477[$i] </td>";
                                    echo"<td> $" . number_format((float)$purchasePrice477[$i], 2, '.','') . " </td>";
                                    echo"<td> $parts477_partNo477[$i] </td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
                            // Output for invalid Purchase No
                            else {
                                $sql = "SELECT poNo477  FROM purchaseOrder477 ORDER BY poNo477 ASC";
                                $result = $conn->query($sql); 
                                $poNum = [];

                                while ($row = $result->fetch_row()){

                                    array_push($poNum, $row[0]);
                                }

                                echo "<br><h4 class='error'><em> It appears there were no results for your search Try:</em></h4>
                                    <table><tr class='tableHeader'><th class='tableHeaderSingle'>Purchase Order Numbers</th>";  
                                
                                    for($i =0, $count = count($poNum); $i < $count; $i++){
                                        echo "<tr>";
                                        echo"<td> $poNum[$i] </td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";

                            }
                }
                else {
                    echo "<h4 class='error'><em> It appears you entered an invalid Purchase Order Number, please enter an integer</em></h4>";
                }

            }
            
            
        
        ?>
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button>  
</div>

</body>
</html>
