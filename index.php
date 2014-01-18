<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Endless Rap!</title>
</head>
<body>
    ENDLESS RAP YO
    <a href="#">Tits</a>
</body>
<script src="http://code.createjs.com/createjs-2013.12.12.min.js"></script>

<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<script>

var linesbuffered = 0;

var linesPlayed = 0;

var playing = 0;

var api = "fetch.php?line=";

var lines = [
    "The only thing hotter than my flow is the block",
    "That's why I left this snow biz, and got into show biz",
    "Let's get this clear, it ain't on 'til I say it's on, it's on",
    "I'm eatin', ya'll niggas fastin' like it's Rimadon",
    "Bowlish way in Lebanon, know 50 the bomb",
    "I be at the edge of the bar, sippin' a Don",
    "I keep the bottle just in case, you never know when it's on",
    "This worries bump, I can't go wrong, my team's too strong",
    "You want war? I take you to war, now that my money long",
    "Why you broke? cat's buy the by lines and fantasize",
    "The way I'm spittin', put TV's in everything I'm sittin'",
    "While I'm hot to death, I'm gonna say this to all you playa haters",
    "Ya'll should hate the game, not the playas (c'mon)"
];

var queue;

var dataSound;

function getLine(lineNumber){
    console.log("get line " + lineNumber);
    var line = lines[lineNumber];
    $.get(api+line).done(function(data){
        console.log(lineNumber);
        dataSound = data;
        queue.loadFile({id:"sound"+lineNumber, src:"mp3/"+data});
    });
}


function start()
{
    queue = new createjs.LoadQueue();
    queue.installPlugin(createjs.Sound);
    queue.on("complete", handleComplete, this);
    getLine(0);
}
var started = false;

function handleComplete(){
    loadSoundByUrl("mp3/"+dataSound);
    //if(!started) startSound();
    started = true;

    console.log("got line!");

    linesbuffered = linesbuffered +1;

    if(playing + 5 > linesbuffered){
        console.log("fetch more!");
        getLine(linesbuffered);
    }else{
        console.log("fetch all!");
    }
}

var context;

window.addEventListener('load', init, false);

function init() {
  try {
    // Fix up for prefixing
    window.AudioContext = window.AudioContext||window.webkitAudioContext;
    context = new AudioContext();
    console.log(context);
  }
  catch(e) {
    alert('Web Audio API is not supported in this browser');
  }
}

function loadSoundByUrl(url) {
  var request = new XMLHttpRequest();
  request.open('GET', url, true);
  request.responseType = 'arraybuffer';

  // Decode asynchronously
  request.onload = function() {
    context.decodeAudioData(request.response, function(buffer) {
        playSound(buffer);
    }, onError);
  };

  request.send();
}

function playSound(buffer) {
  var source = context.createBufferSource(); // creates a sound source
  source.buffer = buffer;                    // tell the source which sound to play
  source.connect(context.destination);       // connect the source to the context's destination (the speakers)
  source.start(0);                           // play the source now
                                             // note: on older systems, may have to use deprecated noteOn(time);
}

function onError()
{
    console.log(arguments);
}

$("a").on("click", function(e){
    e.preventDefault();
    //start playing
});

/*function checkNew()
{
    if(playing + 5 > linesbuffered){
        console.log("fetch more!: check");
        getLine(linesbuffered);
    }else{
        console.log("fetch all!: check");
    }
}

function startSound(){
    console.log("START SOUND");
    //var instance = createjs.Sound.play("sound"+playing);
}

$("a").on("click", function(e){
    var instance = createjs.Sound.play("sound"+playing);
    instance.addEventListener("complete", createjs.proxy(playNext, this));
});

function playNext(){
    playing = playing + 1;
    console.log(playing);
    var instance = createjs.Sound.play("sound"+playing);
    console.log(instance);
    instance.addEventListener("complete", createjs.proxy(playNext, this));
    checkNew();
}*/

start();

</script>
</html>