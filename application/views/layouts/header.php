<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->
        
        <title><?php echo $page_title; ?> — <?php echo $site_name; ?></title>
        
        <meta name="author" content="Plumón Digital" />
        
        <!-- styles -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Marvel:400,700" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Pacifico" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" />
        <!-- /styles -->
        
        <!-- scripts -->
        <script src="<?php echo base_url(); ?>assets/js/jquery-latest.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.textfit.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/soundmanager2.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
        <script>
            $(document).ready(function() {
                app.init({
                    baseUrl: '<?php echo base_url(); ?>'
                });
            });
        </script>
        
        <!--[if lt IE 9 ]>
            <script src="<?php echo base_url(); ?>assets/js/html5.js"></script>
        <![endif]-->
        <!-- /scripts -->
    </head>
    
    <body>
        <div class="container">
            