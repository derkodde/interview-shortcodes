<?php

add_action( 'init', 'isshort_mce_buttons' );
function isshort_mce_Buttons() {
    add_filter( "mce_external_plugins", "isshort_mce_add_buttons" );
    add_filter( 'mce_buttons', 'isshort_mce_register_buttons' );
}
function isshort_mce_add_buttons( $plugin_array ) {
    $plugin_array['isshort'] = plugin_dir_url( __FILE__ ) . '/mce-button.js' ;
    return $plugin_array;
}
function isshort_mce_register_buttons( $buttons ) {
    array_push( $buttons, 'cap', 'question','answer' );

    return $buttons;
}

 ?>
