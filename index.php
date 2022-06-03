<?php
    $version = "0.0.1";
    echo ("MAC-address converter (formatter) v".$version);
?>

<form method="POST">
    <table>
        <tr>
            <th>IP Address</th>
            <td><input type="text" id="db_ip" name="db_ip" required></td>
        </tr>

        <tr>
            <th>DB User</th>
            <td><input type="text" id="db_user" name="db_user" required></td>
        </tr>

        <tr>
            <th>DB Password</th>
            <td><input type="password" id="db_pass" name="db_pass"></td>
        </tr>
        
        <tr>
            <th>DB Name</th>
            <td><input type="text" id="db_name" name="db_name" value="abills" required></td>
        </tr>

        <tr>
            <th>DB Tabble</th>
            <td><input type="text" id="db_tabble" name="db_tabble" value="internet_main" required></td>
        </tr>

        <tr>
            <th>Column name</th>
            <td><input type="text" id="column" name="column" value="cid" required></td>
        </tr>
    </table>
    <input type="submit">
</form>


<?php
if( (isset($_POST["db_ip"])) && 
    (isset($_POST["db_user"])) && 
    (isset($_POST["db_pass"])) && 
    (isset($_POST["db_name"]))) {
    
        $conn = new mysqli(
            $_POST["db_ip"], 
            $_POST["db_user"], 
            $_POST["db_pass"], 
            $_POST["db_name"]);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        echo "Connected successfully<br>";

        $ids_a = get_ids_with_MAC_array($conn, $_POST["db_tabble"], $_POST["column"]);
        echo ("Found rows with MAC addresses: ".count($ids_a)."<br>");
        
        $ids_need_format_a = check_mac_addresses($conn, $ids_a, $_POST["db_tabble"], $_POST["column"]);
        echo ("Rows need to format: ".count($ids_need_format_a)."<hr>");

        reformat($conn, $ids_need_format_a, $_POST["db_tabble"], $_POST["column"]);
}

function get_ids_with_MAC_array ($conn, $db_tabble, $db_column) {
    $query = "SELECT id FROM " . $db_tabble . " WHERE NOT ".$db_column." = '';";
    $result = $conn -> query($query);

    $i = 0;
    $rows_with_mac_a = array();
    while ($row = $result->fetch_assoc()) {
        $rows_with_mac_a[$i] = $row["id"];
        $i++;
    }

    return $rows_with_mac_a;
}

function check_mac_addresses($conn, $ids_a, $db_tabble, $column) {
    $need_format_ids_a = array();
    $j = 0;
    for ( $i = 0; $i < count($ids_a); $i++ ) {
        $query = "SELECT ".$column." FROM ".$db_tabble." WHERE id = ".$ids_a[$i].";";
        $result = $conn -> query($query);
        $row = $result->fetch_assoc();
        $pattern = "/([0-9A-z]{2}:){5}([0-9A-z]{2})|any|ANY/";

        if (!preg_match($pattern, $row["$column"])) {
            $need_format_ids_a[$j] = $ids_a[$i];
            $j++;
        }
    }

    return $need_format_ids_a;
}

function reformat($conn, $ids_a, $db_tabble, $db_column) {
    $count = count($ids_a);
    for ($i=0; $i<$count; $i++) {
        $query = "SELECT ".$db_column.", uid FROM ".$db_tabble." WHERE id = ".$ids_a[$i].";";
        $result = $conn -> query($query);
        $row = $result -> fetch_assoc();
        echo("UID ".$row["uid"]." | ".$row[$db_column]." -> ");
        
        $row[$db_column] = clear($row[$db_column]);

        $str = preg_replace('~(..)(?!$)\.?~', '\1:', $row[$db_column]);
        echo ($str."<br>");

        $query = "UPDATE `".$db_tabble."` SET `".$db_column."` = '".$str."' WHERE `id` = ".$ids_a[$i].";";
        $conn -> query($query);
    }
}

function clear($string){
    $pattern = "/[^0-9A-z]/";
    $result = preg_replace($pattern, '', $string);
    return $result;
}
?>