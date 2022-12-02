const doc = document;
let mode = 1, count = -1;
var url,
    music_title, 
    music_artist, 
    music_album,
    music_image;
    jsmediatags = window.jsmediatags;
function init() {
    title.innerHTML = "未知歌曲";
    author.innerHTML = "未知歌手";
    get_music();// 音乐资源路径
    audio.volume = 0.4; // 音量 0 ~ 1
}
function get_music_info(url){
    var a = document.createElement('A');
        a.href = url;  // 设置相对路径给Image, 此时会发送出请求
        url = a.href;  // 此时相对路径已经变成绝对路径
    jsmediatags.read(url, {
        onSuccess: function(tag) {
            console.log(tag);//调试
            var tags = tag.tags;
            if(typeof(tags.picture) != "undefined"){
                data = tags.picture.data;
                let base64String = "";
                for (i = 0; i < data.length; i++)
                    base64String += String.fromCharCode(data[i]);
                base64 = "data:" + data.format + ";base64," + window.btoa(base64String);
            }
            else base64 = "./Music-Displayer/default.jpg";
            
            music_title = tags.title || "未知歌曲";
            music_artist = tags.artist || "未知歌手";
            music_album = tags.album || "未知专辑";
            music_image = base64;
            audio.src = url;
            bgImg.src = music_image; // 插图
            title.innerHTML = music_title;
            author.innerHTML = music_artist;
        }
    });
}
function get_music() {
    var httpRequest = new XMLHttpRequest();//第一步：建立所需的对象
        httpRequest.open('GET', "./getmusic.php?mode="+mode+"&count="+count, true);//第二步：打开连接  将请求参数写在url中  ps:"./Ptest.php?name=test&nameone=testone"
        httpRequest.send();//第三步：发送请求  将请求参数写在URL中
        /**
         * 获取数据后的处理程序
         */
        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var json = httpRequest.responseText;//获取到json字符串，还需解析
                if(json != "null")get_music_info(json);
                else alert("No Music!");
                return;
            }
            else return;
        };
}
// DOM元素
const audio = doc.querySelector('#audio'); // 播放器
const bgImg = doc.querySelector('#bg-img'); // 插图
const controls = doc.querySelector('#controls'); // 按钮区域
const title = doc.querySelector('#title'); // 歌曲标题
const author = doc.querySelector('#author'); // 歌曲作者
const playBtn = doc.querySelector('#play'); // 播放按钮
const voiceBtn = doc.querySelector('#voice'); // 声音开关
// 是否在播放
let isPlay = false;
audio.addEventListener("timeupdate", function(){
    if(audio.currentTime >= audio.duration)
        nextSong();
});
// 按钮事件
controls.addEventListener('click', e => {
    switch (e.target?.id) {
        case 'voice': // 声音开关
            voiceControl();
            break;
        case 'pre': // 上一首
            preSong();
            break;
        case 'play': // 播放/暂停
            togglePlay();
            break;
        case 'next': // 下一首
            nextSong();
            break;
        case 'mode': // 播放模式
            mode = 1-mode;
            break;
        default:
            break;
    }
});

// 播放 / 暂停 切换
function togglePlay() {
    if (!isPlay) {
        // 暂停 图标切换
        songPlay();
    } else {
        // 播放 图标切换
        songPause();
    }
}

// 播放
function songPlay() {
    isPlay = true;
    playBtn.classList.remove('icon-24gf-play');
    playBtn.classList.add('icon-iconstop');
    audio.play();
}

// 暂停
function songPause() {
    isPlay = false;
    playBtn.classList.remove('icon-iconstop');
    playBtn.classList.add('icon-24gf-play');
    audio.pause();
}

// 上一首
function preSong() {
    count -= 1;
    get_music();
    songPlay();
}

// 下一首
function nextSong() {
    count += 1;
    get_music();
    songPlay();
}

// 声音控制
function voiceControl() {
    if (audio.volume > 0) {
        voiceOff();
    } else {
        voiceOn();
    }
}

// 声音开
function voiceOn() {
    audio.volume = 0.4;
    voiceBtn.classList.remove('icon-volume-mute-fill');
    voiceBtn.classList.add('icon-shengyin_shiti');
}

// 声音关
function voiceOff() {
    audio.volume = 0;
    voiceBtn.classList.remove('icon-shengyin_shiti');
    voiceBtn.classList.add('icon-volume-mute-fill');
}

// 初始化


init();
