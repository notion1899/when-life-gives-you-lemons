<!--header.php--> <!DOCTYPE html> <index>
    <head>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <link href="https://fonts.googleapis.com/css?family=Lemonada:700|Open+Sans" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="./images/favicon.png" />
        <link rel="stylesheet" type="text/css" href="global.css" />
        <link rel="stylesheet" type="text/css" href="index.css" />
        <link rel="stylesheet" type="text/css "href="queries.css" />
	<?php wp_head(); ?>
    </head>
    <body>
        <div id="headerContainer">
            <header>
	<?php wp_nav_menu( array(
            'theme_location' => 'primary', </header>
            'container' => false, </div>
            'menu_class' => 'menu'
      ) ); ?>
</header>

<div>
