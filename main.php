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
// UPDATE `internet_main` SET `cid` = 'a0:c6ec01b4bd' WHERE `internet_main`.`id` = 893;
        $ids_a = get_ids_with_MAC_array($conn, $_POST["db_tabble"]);
        echo ("Found rows with MAC addresses: ".count($ids_a));
}

function get_ids_with_MAC_array ($conn ,$db_tabble) {
    $query = "SELECT id FROM " . $db_tabble . " WHERE NOT cid = '';";
    $result = $conn -> query($query);

    $i = 0;
    $rows_with_mac_a = array();
    while ($row = $result->fetch_assoc()) {
        $rows_with_mac_a[$i] = $row["id"];
        $i++;
    }

    return $rows_with_mac_a;
}
?>