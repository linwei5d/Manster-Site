var button = document.getElementById("button")
var Chinese_poem = document.getElementById("arrow1")
var English_poem = document.getElementById("arrow2")
var box_box = document.getElementById("nav")
var flag = 1;

function Clickbutton1(){
    if(flag == 1)
        show_marks();
    else
        hide_marks();
    flag = 1-flag;
}
function show_marks() {
    Chinese_poem.style.display="block"
    English_poem.style.display="none"
    box_box.style.display="block"
}
function hide_marks() {
    Chinese_poem.style.display="none"
    English_poem.style.display="block"
    box_box.style.display="none"
}
button.onclick = Clickbutton1