<?php

class td_social_icons {
    static $td_social_icons_array = array(
        'behance' => 'Behance',
        'blogger' => 'Blogger',
        'delicious' => 'Delicious',
        'deviantart' => 'Deviantart',
        'digg' => 'Digg',
        'dribbble' => 'Dribbble',
        'evernote' => 'Evernote',
        'facebook' => 'Facebook',
        'flickr' => 'Flickr',
        'forrst' => 'Forrst',
        'googleplus' => 'Google+',
        'grooveshark' => 'Grooveshark',
        'instagram' => 'Instagram',
        'lastfm' => 'Lastfm',
        'linkedin' => 'Linkedin',
        'mail-1' => 'Mail',
        'myspace' => 'Myspace',
        'path' => 'Path',
        'paypal' => 'Paypal',
        'pinterest' => 'Pinterest',
        'reddit' => 'Reddit',
        'rss' => 'RSS',
        'share' => 'Share',
        'skype' => 'Skype',
        'soundcloud' => 'Soundcloud',
        'spotify' => 'Spotify',
        'stackoverflow' => 'Stackoverflow',
        'steam' => 'Steam',
        'stumbleupon' => 'StumbleUpon',
        'tumblr' => 'Tumblr',
        'twitter' => 'Twitter',
        'vimeo' => 'Vimeo',
        'vk' => 'VKontakte',
        'windows' => 'Windows',
        'wordpress' => 'WordPress',
        'yahoo' => 'Yahoo',
        'youtube' => 'Youtube'
    );




    //Unixdev MOD: circle background
    static function get_icon($url, $icon_id, $open_in_new_window = false, $show_icon_id = false, $circle_background = false) {
        if ($open_in_new_window !== false) {
            $td_a_target = ' target="_blank"';
        } else {
            $td_a_target = '';
        }

        // append mailto: the email only if we have an @ and we don't have the mailto: already in place
        if (
            $icon_id == 'mail-1'
            and strpos($url, '@') !== false
            and strpos(strtolower($url), 'mailto:') === false
        ) {
            $url = 'mailto:' . $url;
        }

        //Unixdev MOD
        //if the $show_icon_id parameter is set to true also add the social network name
        $buffy = '';
        if ( true === $circle_background ) {
            $buffy .= '<a' . $td_a_target . ' href="' . $url . '" title="' . self::$td_social_icons_array[$icon_id] . '" class="ud-icon-stack ud-social-circle-button td-social-icon-wrap">';
            $buffy .= '<i class="ud-icon ud-icon-stack-child ud-icon-circle"></i>';
            $buffy .= '<i class="td-icon-font ud-icon ud-icon-stack-child td-icon-' . $icon_id . '"></i>';
        }else {
            $buffy .= '<span class="td-social-icon-wrap">';
            $buffy .= '<a' . $td_a_target . ' href="' . $url . '" title="' . self::$td_social_icons_array[$icon_id] . '">';
            $buffy .= '<i class="td-icon-font td-icon-' . $icon_id . '"></i>';
            $buffy .= '</span>';
        }
        if($show_icon_id === true) {
            $buffy .= '<span class="td-social-name">' . self::$td_social_icons_array[$icon_id] . '</span>';
        }
        $buffy .=   '</a>';

        return $buffy;
        //-------------------


    }
    //--------end Unixdev MOD

}
