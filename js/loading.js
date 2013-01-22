var cSpeed=4;
var cWidth=88;
var cHeight=8;
var cTotalFrames=37;
var cFrameWidth=88;
var cImageSrc='image/sprites.gif';

var cImageTimeout=false;
var cIndex=0;
var cXpos=0;
var SECONDS_BETWEEN_FRAMES=0;

function startAnimation(){

        document.getElementById('loadingImage').style.backgroundImage='url('+cImageSrc+')';
        document.getElementById('loadingImage').style.width=cWidth+'px';
        document.getElementById('loadingImage').style.height=cHeight+'px';
        
        document.getElementById('social_loading').style.backgroundImage='url('+cImageSrc+')';
        document.getElementById('social_loading').style.width=cWidth+'px';
        document.getElementById('social_loading').style.height=cHeight+'px';

        //FPS = Math.round(100/(maxSpeed+2-speed));
        FPS = Math.round(100/cSpeed);
        SECONDS_BETWEEN_FRAMES = 1 / FPS;

        setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES/1000);

}

function continueAnimation(){

        cXpos += cFrameWidth;
        //increase the index so we know which frame of our animation we are currently on
        cIndex += 1;

        //if our cIndex is higher than our total number of frames, we're at the end and should restart
        if (cIndex >= cTotalFrames) {
                cXpos =0;
                cIndex=0;
        }

        document.getElementById('loadingImage').style.backgroundPosition=(-cXpos)+'px 0';
        document.getElementById('social_loading').style.backgroundPosition=(-cXpos)+'px 0';
        setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES*1000);
}

function imageLoader(s, fun)//Pre-loads the sprites image
{
            clearTimeout(cImageTimeout);
            cImageTimeout=0;
            genImage = new Image();
            genImage.onload=function (){cImageTimeout=setTimeout(fun, 0)};
            genImage.onerror=new Function('alert(\'Could not load the image\')');
            genImage.src=s;   
}

//The following code starts the animation
new imageLoader(cImageSrc, 'startAnimation()');