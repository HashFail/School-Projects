function determineWidth()
{
	centerVar.style.width = window.screen.availWidth*.4 + "px";
	leftMarginVar.style.width = window.screen.availWidth*.2 + "px";
	rightMarginVar.style.width = window.screen.availWidth*.2 + "px";
}
function adjustMargins()
{
	leftMarginVar.style.left = window.innerWidth/2-2-window.screen.availWidth*.4+"px"
	rightMarginVar.style.left = window.innerWidth/2+2+window.screen.availWidth*.2+"px"
}
window.onload = function()
	{
		centerVar = document.getElementById("center"); 
		leftMarginVar = document.getElementById("leftMargin");
		rightMarginVar = document.getElementById("rightMargin");
		determineWidth();
		adjustMargins();
		var cssStyle = document.createElement('style');
		cssStyle.type = 'text/css';
		cssStyle.innerHTML = ".content{max-width:" + window.screen.availWidth*.32 +"px;} textarea.content{width:" + window.screen.availWidth*.24 +"px;} iframe.content{width:" + window.screen.availWidth*.32 +"px; height:" + window.screen.availWidth*.32*.75 +"px;";
		document.head.appendChild(cssStyle);
	};
window.onresize = adjustMargins;
function logOut()
{
	if (window.XMLHttpRequest)
		var xmlhttp = new XMLHttpRequest();
	else
		var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			location.reload(true);
    	}
  	}
	xmlhttp.open("GET","scripts/logout.php",true);
	xmlhttp.send();
}