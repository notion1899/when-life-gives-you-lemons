
<!--Header--->
<?php
	/**This is the header for the theme Send them lemons**/

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<link href="https://fonts.googleapis.com/css?family=Lemonada:700|Open+Sans" rel="stylesheet">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <link rel="icon" href="./images/favicon.png" />

        <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/style.css" />
	
<?php wp_head(); ?>
</head>

<body>
	<?php include_once("analyticstracking.php") ?>
        <div id="headerContainer">



            <header id="header" class="flexContainer">


                <div id="logoWrapper" class="flexContainer">
                    <img id="logoImage" src="<?php echo get_stylesheet_directory_uri();?>/assets/lemonLogo.png" />


                    <span id="logoText">Send Them Lemons</span>


                </div>

                <div id="navContainer" class="flexContainer">


                    <nav>
                        <a href="http://sendthemlemons.com">Home</a>
                    </nav>

                    <nav>
                        <a href="http://sendthemlemons.com/?post_type=product&p=13&preview=true">Buy</a>
                    </nav>


                    <nav>
                        <a href="#faq">FAQ</a>
                    </nav>

                    <div>


                    </div>
                </div>
            </header>

        </div>
