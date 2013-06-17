var app = {
    /* Variables */
    baseUrl: '/',
    
    triviaData: '',
    questionCount: 1,
    score: 0,
    
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
                    
                    app.score = app.score - 7;
                    $('.score strong').html(app.score);
                    
                    setTimeout(function() {
                        if(question === 4) {
                            window.location = app.baseUrl + 'app/step04';
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
                    
                    app.score = app.score + 12;
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
                    
                    app.score = app.score - 7;
                    $('.score strong').html(app.score);
                }
                
                clearInterval(timer);
                
                $('.answers .button').data('clicked', true);
                $('.answers .button').unbind('click');
                
                setTimeout(function() {
                    if(question === 4) {
                        window.location = app.baseUrl + 'app/step04';
                    }
                    else {
                        app.trivia(question + 1);
                    }
                }, 3000);
            }
        });
    },
    
    ui: function() {
        $('a[href="#"]').click(function(e) {
            e.preventDefault();
        });
        
        $('.score strong').html(app.score);
        $('.panel .nr em').html(app.questionCount);
    },
    
    /* Initialization */
    init: function(args) {
        app.baseUrl = args.baseUrl;
        
        app.ui();
        
        
    }
}