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
            <td><input type="password" id="db_pass" name="db_pass" required></td>
        </tr>
    </table>
    <input type="submit">
</form>


<?php
if(isset($_POST["db_ip"])) {
    // Create connection
    $conn = new mysqli($_POST["db_ip"], $_POST["db_user"], $_POST["db_pass"]);

    echo(var_dump($conn));
    
    echo("POST present");
    echo("<pre>");
    print_r($_POST);
    echo("</pre>");
}
?>