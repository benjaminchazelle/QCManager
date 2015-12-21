
function resize() {
windowHeight = Math.max(document.documentElement.clientHeight);//, document.documentElement.offsetHeight);//, document.documentElement.scrollHeight);
// windowWidth = Math.max(document.documentElement.clientWidth, document.documentElement.offsetWidth, document.documentElement.scrollWidth);

//frameHeight = Math.max(document.getElementById("frame").clientHeight, document.getElementById("frame").offsetHeight, document.getElementById("frame").scrollHeight);

newContainerHeight = windowHeight - 67 - 1;

// if(frameHeight < newFrameHeight)
	
		document.getElementById("maincontainer").style.height = newContainerHeight + "px";	


}

setInterval(resize, 100);

// window.onload = resize;
// window.onresize = resize;