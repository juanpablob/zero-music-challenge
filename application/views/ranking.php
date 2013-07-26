<section class="ranking">
    <h1 class="logo2"><span>97.7 FM - Zero Music Challenge</span></h1>
    
    <div class="background">
        <div class="mask">
            <div class="content">
                <strong class="big">Ranking Top 7 Semanal</strong>
                
                <div class="box">
                    <table cellpadding="2px">
                        <tr>
                            <th colspan="2">Nombre</th>
                            <th>Puntaje</th>
                        </tr>
                        
                        <?php foreach($top_users as $user) : ?>
                        <tr>
                            <td><?php echo $user->firstname; ?> <?php echo $user->lastname; ?></td>
                            <td class="stars">
                                <img src="<?php echo site_url('assets/tmp/star.png'); ?>" />
                                <img src="<?php echo site_url('assets/tmp/star.png'); ?>" />
                                <img src="<?php echo site_url('assets/tmp/star.png'); ?>" />
                                <img src="<?php echo site_url('assets/tmp/star.png'); ?>" />
                                <img src="<?php echo site_url('assets/tmp/star.png'); ?>" />
                            </td>
                            <td style="text-align: right;"><?php echo $user->score; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <a href="<?php echo site_url(array('app', 'step03')); ?>" class="button play">¡Seguir Jugando!</a>
            </div>
        </div>
        
        <?php include('elements/footer_nav.php'); ?>
    </div>
</section>