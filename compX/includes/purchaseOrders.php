<?php
include('header.php');
?>

<body>
<div class="bodyTable">


    <div 
    .>
        <form class='searchContainer'>
            <input type="text" id="searchBar" class="searchBar" name='search' placeholder="Search">
            <button type="submit" class="submitSearch"><i class="fa fa-search"></i></button>
        </form>
    </div>
        <?php
            if(isset($_GET['search'])){


            $username = "root";
            $conn = new PDO('mysql:host=localhost;dbname=compz', $username, "root");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $clientID = $_GET['search'];

            // Check for valid Int input
            if(is_numeric($clientID)){

                            
                            $conn->beginTransaction();
                            $sql = "SELECT poNo477, datePO477, status477  FROM purchaseorder477 WHERE clientID477 = $clientID";
                            $result = $conn->query($sql); 
                            $conn->commit();
                        

                            $poID = [];
                            $date = [];
                            $status = [];
                                while ($row = $result->fetch()){

                                    array_push($poID, $row[0]);
                                    array_push($date, $row[1]);
                                    array_push($status, $row[2]);
                                }
                                
                                if(sizeof($poID) > 0) {
                                    echo "<table><tr class='tableHeader'><th class='tableHeaderRight'>Purchase Order</th><th>Date Ordered</th><th class='tableHeaderLeft'>Status</th></tr>";
                                    for($i =0, $count = count($poID); $i < $count; $i++){
                                        echo "<tr>";
                                        echo"<td> $poID[$i] </td>";
                                        echo"<td> $date[$i] </td>";
                                        echo"<td> $status[$i] </td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }  
                                // Output for invalid Client ID
                                else {
                                    $conn->beginTransaction();
                                    $sql = "SELECT clientID477  FROM client477";
                                    $result = $conn->query($sql); 
                                    $conn->commit();
                                    $clientID = [];

                                    while ($row = $result->fetch()){

                                        array_push($clientID, $row[0]);
                                    }

                                    echo "<h4 class='error'><em> It appears there were no results for your search Try:</em></h4>
                                        <table><tr class='tableHeader'><th class='tableHeaderSingle'>ClientIDs</th>";  
                                    
                                        for($i =0, $count = count($clientID); $i < $count; $i++){
                                            echo "<tr>";
                                            echo"<td> $clientID[$i] </td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";

                                }
                    
                } 
                // Output error for invalid search
                else {
                    echo "<h4 class='error'><em> It appears you entered an invalid Client ID, please enter an integer</em></h4>";
                }
        }
        ?>
        <button onclick="location.href='../index.php'"  id="linkListButtonBack"> Back </button> 
</div>

</body>
</html>