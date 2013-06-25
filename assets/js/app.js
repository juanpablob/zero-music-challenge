var app = {
    /* Variables */
    baseUrl: '/',
    
    fbAppId: '',
    fbScope: '',
    fbAppUrl: '',
    
    scoreRegular: 0,
    scorePlus: 0,
    scoreMinus: 0,
    
    triviaData: '',
    
    questionCount: 1,
    answerTracking: {
        regular: 0,
        plus: 0,
        minus: 0
    },
    score: 0,
    
    /* FB Session */
    fbSession: function(callback) {
        FB.getLoginStatus(function(response) {
            if(response.status === 'connected') {
                var signedRequest = response.authResponse.signedRequest;
                var accessToken = response.authResponse.accessToken;
                //console.log('consumer url: ' + app.baseUrl + 'login/' + signedRequest + '/' + accessToken);
                
                $.ajax({
                    url: app.baseUrl + 'login/' + signedRequest + '/' + accessToken,
                    beforeSend: function() {
                        $('.fb-connect').html('Un momento…');
                        $('.fb-connect').unbind('click');
                    },
                    success: function(data) {
                        if(data.logged === true) {
                            callback();
                        }
                        else {
                            alert(data.error);
                            
                            window.location = app.fbAppUrl;
                        }
                    }
                });
            }
        });
    },
    
    /* Constructor */
    gameConstructor: function() {
        // Pull Q&A
        $.ajax({
            dataType: 'json',
            url: app.baseUrl + 'app/trivia/',
            success: function(data) {
                app.triviaData = data;
                
                // Setup Soundmanager
                soundManager.setup({
                    url: app.baseUrl + 'assets/aux/',
                    debugMode: true,
                    useConsole: true,
                    onready: function() {
                        // Start Counter
                        $('.game .counter .c-three').fadeIn(200, function() {
                            $(this).delay(200).fadeOut(200, function() {
                                $('.game .counter .c-two').delay(200).fadeIn(200, function() {
                                    $(this).delay(200).fadeOut(200, function() {
                                        $('.game .counter .c-one').delay(200).fadeIn(200, function() {
                                            $(this).delay(200).fadeOut(200, function() {
                                                // Lets Play!
                                                $('.game .trivia').fadeIn(500, function() {
                                                    app.trivia(1);
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    }
                });
            }
        });
    },
    
    /* Trivia Controller */
    trivia: function(question) {
        // Setup
        app.questionCount = question;
        $('.content').removeClass('lose');
        $('.content').removeClass('win');
        
        $('.game .nr em').html(app.questionCount);
        $('.game .time').html('07');
        
        $('.answers .button').bind('click');
        
        // Time Control
        var remaining = 7;
        var timer = setInterval(function() {
            remaining = --remaining;
            
            $('.game .time').html('0' + remaining);
            
            if(remaining === 0) {
                clearInterval(timer);
                
                if($('.answers .button').data('clicked') !== true) {
                    song.stop();
                    soundManager.destroySound('song');
                    error.play();
                    $(this).addClass('incorrect');
                    $('.content').addClass('lose');
                    
                    app.score = parseInt(app.score) - parseInt(app.scoreMinus);
                    app.answerTracking.minus++;
                    $('.score strong').html(app.score);
                    
                    setTimeout(function() {
                        if(question === 4) {
                            $.ajax({
                                type: 'post',
                                url: app.baseUrl + 'app/update_score/',
                                data: app.answerTracking,
                                success: function(data) {
                                    window.location = app.baseUrl + 'app/step04';
                                    //console.log(data);
                                }
                            });
                        }
                        else {
                            app.trivia(question + 1);
                        }
                    }, 3000);
                }
            }
        }, 1000);
        
        // Q&A
        $('.game .answers').html('');
        
        $.each(app.triviaData[question - 1].answers, function(i, item) {
            $('.game .answers').append('<a href="#" class="button" data-songid="' + item.id + '">' + item.title + '</a>');
        });
        
        $('.answers .button').textFit({
            multiLine: true,
            alignHoriz: true,
            alignVert: true,
            minFontSize: 13,
            maxFontSize: 23
        });
        
        // Audio
        var song = soundManager.createSound({
            id: 'song',
            url: app.baseUrl + 'media/' + app.triviaData[question - 1].correct.filename + '.mp3',
            volume: 100,
            autoplay: true
        });
        
        var success = soundManager.createSound({
            id: 'success',
            url: app.baseUrl + 'media/success.mp3',
            volume: 100,
            autoplay: true
        });
        
        var error = soundManager.createSound({
            id: 'error',
            url: app.baseUrl + 'media/error.mp3',
            volume: 100,
            autoplay: true
        });
        
        song.play();
        
        // Answer
        $('.answers .button').click(function(e) {
            e.preventDefault();
            
            if(remaining > 0) {
                var songid = $(this).attr('data-songid');
                
                song.stop();
                soundManager.destroySound('song');
                
                if(songid === app.triviaData[question -1].correct.id) {
                    success.play();
                    $(this).addClass('correct');
                    $('.content').addClass('win');
                    
                    // Score
                    if(remaining > 5) { // Excellent!
                        app.score = parseInt(app.score) + parseInt(app.scorePlus);
                        app.answerTracking.plus++;
                        
                        // Do an excellent shit
                    }
                    else {
                        app.score = parseInt(app.score) + parseInt(app.scoreRegular);
                        app.answerTracking.regular++;
                    }
                    
                    $('.score strong').html(app.score);
                }
                else {
                    error.play();
                    $(this).addClass('incorrect');
                    
                    $(this).effect('shake', {
                        direction: 'left',
                        distance: 8,
                        times: 3
                    }, 300);
                    
                    $('.content').addClass('lose');
                    
                    app.score = parseInt(app.score) - parseInt(app.scoreMinus);
                    app.answerTracking.minus++;
                    $('.score strong').html(app.score);
                }
                
                clearInterval(timer);
                
                $('.answers .button').data('clicked', true);
                $('.answers .button').unbind('click');
                
                setTimeout(function() {
                    if(question === 4) {
                        $.ajax({
                            type: 'post',
                            url: app.baseUrl + '/app/update_score/',
                            data: app.answerTracking,
                            success: function(data) {
                                window.location = app.baseUrl + 'app/step04';
                                //console.log(data);
                            }
                        });
                    }
                    else {
                        app.trivia(question + 1);
                    }
                }, 3000);
            }
        });
    },
    
    /* Handler / Triggers */
    handler: function() {
        // Facebook Connect
        $('.fb-connect').click(function(e) {
            e.preventDefault();
            
            var next_url = $(this).attr('href');
            
            FB.login(function(response) {
                app.fbSession(function() {
                    window.location = next_url;
                });
            }, { scope: app.fbScope }
            );
        });
        
        // Share Score
        $('.share .twitter').click(function() {
            var text = escape('Ya llevo ' + app.score + ' puntos en el Music Challenge de @radiozero977 #zerochallenge');
            var url = escape(app.fbAppUrl);
            
            window.open('https://twitter.com/intent/tweet?text=' + text + '&tw_p=tweetbutton&url=' + url, 'Tweet', 'width=800, height=600, resizable=0, scrollbars=0, location=0');
        });
        
        $('.share .facebook').click(function() {
            FB.ui({
                method: 'feed',
                //redirect_uri: '',
                link: app.fbAppUrl,
                picture: app.baseUrl + '/assets/img/fbpic.png',
                name: 'Music Challenge de Radio Zero',
                caption: '¿Cuánto sabes de música?',
                description: 'Demuestra cuánto sabes de música en el Music Challenge Radio Zero y participa por uno de los iPod Shuffle y entradas a conciertos!'
            }, function(response) {
                console.log('postid: ' + response['post_id']);
            });
        });
    },
    
    /* User Interface Shits */
    ui: function() {
        // Prevent Default on Anchor Links
        $('body').on('click', 'a[href="#"]', function(e) {
            e.preventDefault();
        });
        
        $('.score strong').html(app.score);
        $('.panel .nr em').html(app.questionCount);
    },
    
    /* Preload images and files */
    preload: function() {
        var images = [
            app.baseUrl + 'assets/img/box.png',
            app.baseUrl + 'assets/img/button.png',
            app.baseUrl + 'assets/img/button_h.png',
            app.baseUrl + 'assets/img/correct.png',
            app.baseUrl + 'assets/img/incorrect.png',
            app.baseUrl + 'assets/img/good.png',
            app.baseUrl + 'assets/img/win.png',
            app.baseUrl + 'assets/img/lose.png',
            app.baseUrl + 'assets/img/mask.png',
            app.baseUrl + 'assets/img/panel.png',
            app.baseUrl + 'assets/img/score.png',
            app.baseUrl + 'assets/img/shine.png',
            app.baseUrl + 'assets/img/three.png',
            app.baseUrl + 'assets/img/two.png',
            app.baseUrl + 'assets/img/one.png'
        ];
        
        var misc = [
            app.baseUrl + 'media/success.mp3',
            app.baseUrl + 'media/error.mp3',
            app.baseUrl + 'media/end.mp3'
        ];
        
        // Preload Images
        $.each(images, function(i, item) {
            $('<img src="' + item + '" />');
        });
        
        // Preload Misc Files
        $.each(misc, function(i, item) {
            $.ajax({
                url: item
            });
        });
    },
    
    /* Initialization */
    init: function(args) {
        // Setup
        app.baseUrl = args.baseUrl;
        
        app.fbAppId = args.fbAppId;
        app.fbScope = args.fbScope;
        app.fbAppUrl = args.fbAppUrl;
        
        app.scoreRegular = args.scoreRegular;
        app.scorePlus = args.scorePlus;
        app.scoreMinus = args.scoreMinus;
        
        app.score = args.score;
        
        FB.init({ 
            appId: app.fbAppId,
            cookie: true,
            status: true,
            xfbml: true,
            oauth: true,
            channelUrl : app.baseUrl + 'app/fb_channel/'
        });
        
        // Preload
        app.preload();
        
        // UI
        app.ui();
        
        // Hanlder
        app.handler();
    }
}