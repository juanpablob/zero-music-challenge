<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->
        
        <title><?php echo $page_title; ?> — <?php echo $site_name; ?></title>
        
        <meta name="author" content="@plumon, @pascaleglm, @juanpablob" />
        <meta name="robots" content="nofollow, noindex" />
        
        <!-- styles -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Marvel:400,700" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" />
        <!-- /styles -->
        
        <!-- scripts -->
        <script src="<?php echo base_url(); ?>assets/js/jquery-latest.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.textfit.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/soundmanager2.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/yeipib.js"></script>
        <script src="//connect.facebook.net/es_LA/all.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
        
        <script>
            $(document).ready(function() {
                app.init({
                    baseUrl: '<?php echo base_url(); ?>'
                    <?php if(isset($facebook_config)) : ?>
                    ,fbAppId: '<?php echo $facebook_config['appId']; ?>'
                    ,fbScope: '<?php echo $facebook_config['permissions']; ?>'
                    ,fbAppUrl: '<?php echo $facebook_config['app_url']; ?>'
                    <?php endif; ?>
                    <?php if(isset($score_scale)) : ?>
                    ,scoreRegular: '<?php echo $score_scale['score_regular']; ?>'
                    ,scorePlus: '<?php echo $score_scale['score_plus']; ?>'
                    ,scoreMinus: '<?php echo $score_scale['score_minus']; ?>'
                    <?php endif; ?>
                    <?php if(isset($user_info)) : ?>
                    ,score: <?php echo $user_info->score; ?>
                    <?php endif; ?>
                });
                
                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', 'UA-42081627-1']);
                _gaq.push(['_setDomainName', 'plmn.co']);
                _gaq.push(['_trackPageview']);
                
                (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();
            });
        </script>
        
        <!--[if lt IE 9 ]>
            <script src="<?php echo base_url(); ?>assets/js/html5.js"></script>
        <![endif]-->
        <!-- /scripts -->
    </head>
    
    <body>
        <div class="container">
            