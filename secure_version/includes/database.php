<?php
function db_query($conn, $sql) {
    $res = mysqli_query($conn, $sql);
    return $res;
}
?>
