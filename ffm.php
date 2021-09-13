<?php
/*
Plugin Name: Featured Foto Mod
Plugin URI: https://github.com/seumsims/ffm
Description: 
Version: 1.00
Author: Seum
Author URI: https://github.com/seumsims/
License: MIT License
License URI: https://www.mit.edu/~amini/LICENSE.md
*/
add_action('wp_head', function() {
    if ( !is_user_logged_in() ) {
    _e("SALAMA");
}
});