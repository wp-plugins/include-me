<?php

/*
  Plugin Name: Include Me
  Plugin URI: http://www.satollo.net/plugins/include-me
  Description: Include external HTML or PHP in any post or page.
  Version: 1.0.6
  Author: Stefano Lissa
  Author URI: http://www.satollo.net
 */

if (is_admin()) {
    add_action('admin_head', 'includeme_admin_head');

    function includeme_admin_head() {
        if (!isset($_GET['page'])) return;
        if (strpos($_GET['page'], 'include-me/') === 0) {
            echo '<link type="text/css" rel="stylesheet" href="' . plugins_url('admin.css', __FILE__) . '">';
        }
    }

    add_action('admin_menu', 'includeme_admin_menu');

    function includeme_admin_menu() {
        add_options_page('Include Me', 'Include Me', 'manage_options', 'include-me/options.php');
    }
}

add_shortcode('includeme', 'includeme_call');

function includeme_call($attrs, $content = null) {

    if (isset($attrs['file'])) {
        $file = strip_tags($attrs['file']);
        if ($file[0] != '/')
            $file = ABSPATH . $file;

        ob_start();
        include($file);
        $buffer = ob_get_clean();
        $options = get_option('includeme', array());
        if (isset($options['shortcode'])) {
            $buffer = do_shortcode($buffer);
        }
    } else {
        $tmp = '';
        foreach ($attrs as $key => $value) {
            if ($key == 'src')
                $value = strip_tags($value);
            $value = str_replace('&amp;', '&', $value);
            if ($key == 'src')
                $value = strip_tags($value);
            $tmp .= ' ' . $key . '="' . $value . '"';
        }
        $buffer = '<iframe' . $tmp . '></iframe>';
    }
    return $buffer;
}

?>