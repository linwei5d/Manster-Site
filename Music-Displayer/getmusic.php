<?php
header("Content-Type:&nbsp;application/json");
function getfiles($music_dir, $mode, $count){
    $musics  = glob($music_dir."/*.mp3", GLOB_NOSORT);

    if(count($musics) == 0 || $mode != "1" && $mode != "2")return "null";

    if($mode == "1"){ //顺序播放
        $count = abs($count+1)%count($musics);
    }
    else if($mode == "2"){ //随机播放
        while(($temp = array_rand($musics)) == $count);
        $count = $temp;
    }
    return $musics[$count];
}

echo getfiles('.', $_GET["mode"], $_GET["count"]);
?>