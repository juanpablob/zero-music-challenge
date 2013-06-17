<section class="game">
    <h1 class="logo1"><span>97.7 FM - Zero Music Challenge</span></h1>
    
    <div class="score">
        <span>Tu Puntaje</span>
        <strong>0</strong>
    </div>
    
    <div class="background">
        <div class="mask">
            <div class="content">
                <div class="trivia">
                    <span class="question">¿Qué canción está sonando?</span>
                    
                    <div class="panel">
                        <span class="nr">Pregunta <em>1</em> de 4</span>
                        <span class="time">07</span>
                    </div>
                    
                    <div class="answers clearfix">
                        <a href="#" class="button" data-songid=""></a>
                        <a href="#" class="button" data-songid=""></a>
                        <a href="#" class="button" data-songid=""></a>
                        <a href="#" class="button" data-songid=""></a>
                    </div>
                </div>
                
                <div class="counter">
                    <img src="<?php echo site_url('assets/img/three.png'); ?>" class="c-three" />
                    <img src="<?php echo site_url('assets/img/two.png'); ?>" class="c-two" />
                    <img src="<?php echo site_url('assets/img/one.png'); ?>" class="c-one" />
                </div>
            </div>
        </div>
        
        <?php include('elements/footer_nav.php'); ?>
    </div>
</section>

<script>
    $(document).ready(function() {
        app.gameConstructor();
    })
</script>