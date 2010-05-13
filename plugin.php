<?php
/*
Plugin Name: Include Me
Plugin URI: http://www.satollo.net/plugins/include-me
Description: Directly include HTML or PHP in any post/page to embed advanced functionalities.
Version: 1.0.2
Author: Satollo
Author URI: http://www.satollo.net
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008 Satollo  (email : satollo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


//$includeme_options = get_option('includeme');
define('INCLUDE_ME', '1.0.2');

add_action('admin_menu', 'includeme_admin_menu');
function includeme_admin_menu()
{
    add_options_page('Include Me', 'Include Me', 'manage_options', 'include-me/options.php');
}


add_shortcode('includeme', 'includeme_call');

function includeme_call($attrs, $content=null) {
    global $includeme_options;

    if (isset($attrs['file'])) {
        $file = strip_tags($attrs['file']);
        if ($file[0] != '/') $file = ABSPATH . $file;

        ob_start();
        include($file);
        $buffer = ob_get_contents();
        ob_end_clean();
    }
    else {
        $tmp = '';
        foreach ($attrs as $key=>$value) {
            if ($key == 'src') $value = strip_tags($value);
            $value = str_replace('&amp;', '&', $value);
            if ($key == 'src') $value = strip_tags($value);
            $tmp .= ' ' . $key . '="' . $value . '"';
        }
        $buffer = '<iframe' . $tmp . '></iframe>';
    }
    return $buffer;
}
?>