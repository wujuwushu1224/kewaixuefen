document.write("<div class='QQbox' id='divQQbox' >");

document.write("<div class='Qlist' id='divOnline' onmouseout='hideMsgBox(event);' style='display : none;'>");

document.write("<div class='t'></div>");

document.write("<div class='con'>");

document.write("<h2>���߿ͷ�</h2>");

document.write("<ul>");

document.write("<li class=odd><a href='tencent://message/?uin=1291469611&Site=�������|�ݶ����|�������|���ķ��|���̷��--�����������������ͨ���豸&Menu=yes' target='_blank'><img src='images/qq.jpg'  border='0' alt='1291469611' />1291469611</a></li>");

document.write("<li><a href='tencent://message/?uin=417155765&Site=�������|�ݶ����|�������|���ķ��|���̷��--�����������������ͨ���豸&Menu=yes' target='_blank'><img src='images/qq.jpg'  border='0' alt='417155765' />417155765</a></li>");

document.write('<tr><td><li><a target="_blank" href="tencent://message/?uin=543130542&Site=�������|�ݶ����|�������|���ķ��|���̷��--�����������������ͨ���豸&Menu=yes" ><img border="0" src="images/qq.jpg" alt="543130542" />543130542</a></li></td></tr>');

document.write('<tr><td><li><a target="_blank" href="tencent://message/?uin=415789560&Site=�������|�ݶ����|�������|���ķ��|���̷��--�����������������ͨ���豸&Menu=yes" ><img border="0" src="images/qq.jpg"  alt="415789560" />415789560</a></li></td></tr>');

document.write("</ul>");document.write("</div>");

document.write("<div class='b'></div>");

document.write("</div>");

document.write("<div id='divMenu' onmouseover='OnlineOver();'><img src='/css/images/qq_1.png' class='press' alt='QQ�ͷ�����'></div>");

document.write("</div>");



//<![CDATA[

var tips; var theTop = 40/*����Ĭ�ϸ߶�,Խ��Խ����*/; var old = theTop;

function initFloatTips() {

tips = document.getElementById('divQQbox');

moveTips();

};

function moveTips() {

var tt=50;

if (window.innerHeight) {

pos = window.pageYOffset

}

else if (document.documentElement && document.documentElement.scrollTop) {

pos = document.documentElement.scrollTop

}

else if (document.body) {

pos = document.body.scrollTop;

}

pos=pos-tips.offsetTop+theTop;

pos=tips.offsetTop+pos/10;



if (pos < theTop) pos = theTop;

if (pos != old) {

tips.style.top = pos+"px";

tt=10;

//alert(tips.style.top);

}



old = pos;

setTimeout(moveTips,tt);

}

//!]]>

initFloatTips();







function OnlineOver(){

document.getElementById("divMenu").style.display = "none";

document.getElementById("divOnline").style.display = "block";

document.getElementById("divQQbox").style.width = "145px";

}



function OnlineOut(){

document.getElementById("divMenu").style.display = "block";

document.getElementById("divOnline").style.display = "none";



}



function hideMsgBox(theEvent){ //theEvent���������¼���Firefox�ķ�ʽ

 if (theEvent){

 var browser=navigator.userAgent; //ȡ�����������

 if (browser.indexOf("Firefox")>0){ //�����Firefox

���� if (document.getElementById('divOnline').contains(theEvent.relatedTarget)) { //�������Ԫ��

 return; //������ʽ

} 

} 

if (browser.indexOf("MSIE")>0){ //�����IE

if (document.getElementById('divOnline').contains(event.toElement)) { //�������Ԫ��

return; //������ʽ

}

}

}

/*Ҫִ�еĲ���*/

document.getElementById("divMenu").style.display = "block";

document.getElementById("divOnline").style.display = "none";

}