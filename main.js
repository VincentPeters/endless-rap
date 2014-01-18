
    var playing = 0;

    var lastFetched;

    var context;

    var first = true;

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

    var linesBuffer = [];

    function start()
    {
        initAudioApi();
        fetchMp3Url(0);
    }

    //initalize audio API
    function initAudioApi()
    {
        try {
            // Fix up for prefixing
            window.AudioContext = window.AudioContext||window.webkitAudioContext;
            context = new AudioContext();
        }
        catch(e) {
            alert('Web Audio API is not supported in this browser');
        }
    }

    //fetch new MP3 url
    function fetchMp3Url(lineNumber)
    {
        console.log("fetching number " + lineNumber);
        var line = lines[lineNumber];
        $.get(api+line).done(function(mp3FileName){
            loadSoundByUrl(mp3FileName, lineNumber);
        });
    }

    var request;

    function loadSoundByUrl(mp3FileName, lineNumber) {
        request = new XMLHttpRequest();
        request.open('GET', "mp3/"+mp3FileName, true);
        request.responseType = 'arraybuffer';

        //when mp3 is loaded,check if there needs to be fetched more and decode recieved mp3
        request.onload = onMp3Load( lineNumber );
        request.send();
    }

    function onMp3Load(lineNumber){
        //check if more mp3's need to beloaded
        lastFetched = lineNumber;

        if(playing + 3 > lastFetched){
            console.log("Fetch more!");
            fetchMp3Url(lastFetched + 1);
        }else{
            console.log("fetched enough!");
        }
        //decode recieved mp3
        console.log(request.response);
        context.decodeAudioData(request.response, function(buffer) {
            linesBuffer[lineNumber]  = buffer;
            if(first){
                play(0);
                first = false;
            }
        }, mp3FetchError);
    }

    function mp3FetchError()
    {
        alert("something went wrong fetching MP3, check console log");
        console.log(arguments);
    }

    function play(number){
        console.log("start playing");
        var source = context.createBufferSource(); 
        source.buffer = linesBuffer[number];
        source.connect(context.destination);
        source.onended = playNext;
        source.start(0);
        console.log(source);
    }

    function playNext()
    {
        linesBuffer[playing] = undefined;
        playing = playing + 1;
        play(playing);
        fetchMp3Url(lastFetched+1);
    }

    start();