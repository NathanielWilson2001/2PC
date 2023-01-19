<?php
include('header.php');

?>

<body>
<div class="bodyTable">
        <?php
            $conn = include('db.php');
            $sql = "SELECT partNo477, partName477, partDescription477, currentPrice477,  quantityOnHand477 FROM parts477";
            $result = $conn->query($sql); 

            $partID = [];
            $partName = [];
            $partDescription = [];
            $currentPrice = [];
            $quantity = [];

            while ($row = $result->fetch_row()){

                array_push($partID, $row[0]);
                array_push($partName, $row[1]);
                array_push($partDescription, $row[2]);
                array_push($currentPrice, $row[3]);
                array_push($quantity, $row[4]);
            }
            
            echo "<table><tr class='tableHeader'><th class='tableHeaderRight'>Part ID</th><th>Part Name</th><th>Part Description</th><th class='tableHeaderLeft'>Price</th></tr>";
            for($i =0, $count = count($partID); $i < $count; $i++){
                echo "<tr>";
                echo"<td> $partID[$i] </td>";
                echo"<td> $partName[$i] </td>";
                echo"<td> $partDescription[$i] </td>";
                echo"<td> $" . number_format((float)$currentPrice[$i], 2, '.','') . " </td>";
                echo"<td style='display:none'> $quantity[$i] </td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button>  
</div>

</body>
</html>