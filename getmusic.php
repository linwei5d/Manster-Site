<?php
header('Content-Type:application/json;charset=utf-8');
function getfiles($music_dir, $mode, $count){
    $musics  = glob($music_dir."/*.{mp3,flac}",  GLOB_BRACE);

    if(count($musics) == 0 || $mode != "0" && $mode != "1")return "null";

    if($mode == "0"){ //顺序播放
        while($count < 0)$count += count($musics);
        $count %= count($musics);
    }
    else if($mode == "1"){ //随机播放
        $count = array_rand($musics);
    }
    return $musics[$count];
}

echo getfiles('./Music-Displayer/music', $_GET["mode"], $_GET["count"]);
?>