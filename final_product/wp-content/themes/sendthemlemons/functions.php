<?php

function wp_enqueue_woocommerce_style(){
	wp_register_style( 'sendthemlemons', get_template_directory_uri() . '/woocommerce.css' );

	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'sendthemlemons' );
	}
}



add_action( 'init', 'jk_remove_wc_breadcrumbs' );
function jk_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );


}
function woo_edit(){
remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
remove_action('woocommerce_sidebar','woocommerce_get_sidebar');
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_style' );

remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);

add_action('woocommerce_single_product_summary','woocommerce_template_single_price', 5);
add_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart', 5);
add_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);


}

add_action('template_redirect','woo_edit');

?>
