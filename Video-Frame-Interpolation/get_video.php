<?php

function get_video($dir, $id){
    header('Content-Type:application/json;charset=utf-8');
    $servername = "192.168.143.132";
    $username = "root";
    $password = "040411";
    $dbname = "manster-box";
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT 'id', 'name', 'state', 'picture' FROM rife WHERE 'id'='$id'";
    $result = $conn->query($sql);
    
    echo json_encode(mysqli_fetch_all($result));
}
echo get_video('./', $_GET["id"]);
?>