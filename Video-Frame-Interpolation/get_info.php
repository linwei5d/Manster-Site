<?php
header('Content-Type:application/json;charset=utf-8');

function get_info($mode){
    $servername = "192.168.143.132";
    $username = "root";
    $password = "040411";
    $dbname = "manster-box";
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);
    if($mode == 1)//全部信息
        $sql = "SELECT id, name, state, picture FROM rife";
    if($mode == 2)//未完成信息
        $sql = "SELECT id, name, state, picture  FROM rife WHERE state!=0";
    if($mode == 3)//已完成信息
        $sql = "SELECT id, name, state, picture  FROM rife WHERE state=0";
    $result = $conn->query($sql);
    if($result->num_rows == 0)
        echo "null";
    else echo json_encode(mysqli_fetch_all($result));
}
get_info($_GET['mode']);
?>