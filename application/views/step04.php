<section class="good">
    <h1 class="logo2"><span>97.7 FM - Zero Music Challenge</span></h1>
    
    <?php
        if($correct_answers === 4) {
            $greeting = 'Excelente!';
            $klass = 'win';
        }
        elseif($correct_answers === 3) {
            $greeting = 'Muy bien!';
            $klass = 'shine2';
        }
        elseif($correct_answers === 2) {
            $greeting = 'Más o menos';
            $klass = 'shine2';
        }
        elseif($correct_answers === 1) {
            $greeting = 'Practica más!';
            $klass = 'shine2';
        }
        elseif($correct_answers === 0) {
            $greeting = 'Muy mal';
            $klass = 'lose';
        }
    ?>
    
    <div class="background">
        <div class="mask">
            <div class="<?php echo $klass; ?>">
                <div class="content">
                    <strong class="big"><?php echo $greeting; ?></strong>
                    
                    <div class="panel">
                        <span class="nr">Respuestas Correctas</span>
                        <span class="time">0<?php echo $correct_answers; ?></span>
                    </div>

                    <div class="clearfix">
                        <div class="final-score">
                            <span>Tu Puntaje</span>
                            <strong><?php echo $user_info->score; ?></strong>
                        </div>
                        
                        <div class="share">
                            <span>Compartir Resultado</span>

                            <a href="#" class="twitter"></a>
                            <a href="#" class="facebook"></a>
                        </div>
                    </div>
                    
                    <a href="<?php echo site_url(array('app', 'step03')); ?>" class="button play" rel="tracking" data-tracking="Seguir Jugando">¡Seguir Jugando!</a>
                    <a href="<?php echo site_url(array('app', 'ranking')); ?>" class="button">Ver Ranking</a>
                </div>
            </div>
        </div>
        
        <?php include('elements/footer_nav.php'); ?>
    </div>
</section>