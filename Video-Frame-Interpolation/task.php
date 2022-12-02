<?php
ignore_user_abort(true);
set_time_limit(600);
require 'vendor/autoload.php';
use FFMpeg\FFProbe;
use function UI\run;
//数据库连接
$servername = "192.168.143.132";
$username = "root";
$password = "040411";
$dbname = "manster-box";
//设置全局变量
global $ffmpeg_config, $conn, 
       $id, $name, $frame_rate, $frame_mode, $quality_mode, $picture_mode, $video_mode;
$conn = new mysqli($servername, $username, $password, $dbname);
// $name = $_GET['name'];
// $frame_mode = $_GET['frame_mode'];
// $quality_mode = $_GET['quality_mode'];
// $picture_mode = $_GET['picture_mode'];
// $video_mode = $_GET['video_mode'];
//FFmpeg配置
$ffmpeg_config = [
    'ffmpeg.binaries' => 'ffmpeg',//ffmpeg中bin目录下的ffmpeg路径
    'ffprobe.binaries' => 'ffprobe',//ffmpeg中bin目录下的ffprobe路径
    'timeout' => 3600,
    'ffmpeg.threads' => 4,];
function rife()
{
    global $ffmpeg_config, $conn,
            $id, $name, $frame_rate, $frame_mode, $quality_mode, $picture_mode, $video_mode;
        $id = 4;
    // echo $id;
    //信息处理
    if($picture_mode == 1)
        $picture_format = "%08d.jpg";
    else if($picture_mode == 2)
        $picture_format = "%08d.png";
    // 开始处理
    $ffmpeg = FFMpeg\FFMpeg::create($ffmpeg_config);
    $ffprobe = FFmpeg\FFProbe::create($ffmpeg_config);
    $video = $ffmpeg->open("./".$id."/".$name);
    $cover = "./".$id."/cover.jpg";
    $audio = "./".$id.'/audio.flac';
    // $frame_rate = FFMpeg\Coordinate\FrameRate($video);
    $frame_num = 0;
    $input_dir = "./".$id."/input_frames/";
    $output_dir = "./".$id."/output_frames/";
    mkdir("./".$id, 0777,true);
    mkdir($input_dir, 0777,true);
    mkdir($output_dir, 0777,true);
    //获取封面
    $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1))->save($cover);
    //获取背景音乐
    $video->save(new FFMpeg\Format\Audio\Flac(), $audio);
    //拆分帧
    exec("ffmpeg -i "."./".$id."/".$name." ".$input_dir.$picture_format);
    $result = $conn->query("UPDATE rife SET state=1  WHERE id=$id");
    //插帧
    exec("./rife-ncnn-vulkan -i ".$input_dir." -o ".$output_dir." -j 15:10:6 -f ".$picture_format." -m rife-v4.6");
    $result = $conn->query("UPDATE rife SET state=2  WHERE id=$id");
    //整合
    exec("ffmpeg -framerate ".($frame_rate*2)." -i ".$output_dir." ".$picture_format." -c:v hevc_qsv -i audio.flac -c:a copy -crf 22 -c:v libx264 -pix_fmt yuv420p "."output.mp4");
    $result = $conn->query("UPDATE rife SET state=3  WHERE id=$id");
    //开始处理 
}
function clear(){

}

function setup(){
    global $ffmpeg_config, $conn, 
       $id, $name, $frame_rate, $frame_mode, $quality_mode, $picture_mode, $video_mode;
    $name="test.mp4";
    $frame_mode=1;
    $frame_rate=25;
    $quality_mode=1;
    $picture_mode=1;
    $video_mode=1;
    //插入数据
    $sql = "INSERT INTO rife SET name='$name',frame_mode=$frame_mode,quality_mode=$quality_mode,picture_mode=$picture_mode,video_mode=$video_mode,frame_rate=$frame_rate";
    $result = $conn->query($sql);
    $sql = "SELECT id FROM rife WHERE name='$name' 
                                AND frame_mode='$frame_mode' 
                                AND quality_mode='$quality_mode' 
                                AND picture_mode='$picture_mode' 
                                AND video_mode='$video_mode'
                                ORDER BY id DESC";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $id = $row["id"];
}
setup();
rife();
clear();
?>