<?php
/*
Plugin Name: Qualigo Widget
Description: Show Qualigo Banner as Widget
Version: 1.0.0
Author: Qualigo - info@qualigo.com
Author URI: https://qualigo.com
Text Domain: Qualigo-Widget
Domain Path: /l10n
*/

class Qualigo_Widget extends WP_Widget {
	private $var_sTextdomain;
	
    private static $_params = array(
        'qualigo-ds'=> 'Publisher-Id', // __('Publisher-Id')
        'qualigo-subsd' => 'Sub.-ID',
        'qualigo-search' => 'Keyword',
        'qualigo-format' => 'Banner-Format',
        'qualigo-col-headline' => 'Title-Color',
        'qualigo-col-text' => 'Text-Color',
        'qualigo-col-url' => 'Link-Color',
        'qualigo-col-background' => 'Background-Color',
        'qualigo-col-border' => 'Border-Color'
    );
    private static $_format = array(

        "ad_120x600" => "120x600 Skyscraper",
        "ad_125x125" => "125x125 Button",
        "ad_160x300" => "160x300 Wide Skyscraper (half)",
        "ad_160x600" => "160x600 Wide Skyscraper",
        "ad_200x200" => "200x200 Small Square",
        "ad_234x60" => "234x60 Half Banner",
        "ad_250x250" => "250x250 Square",
        "ad_300x250" => "300x250 Medium Rectangle",
        "ad_336x280" => "336x280 Large Rectangle",
        "ad_468x60" => "468x60 Banner",
        "ad_600x505" => "600x505 Banner",
        "ad_728x90" => "728x90 Leaderboard",
        "ad_728x200" => "728x200 Large Leaderboard",
        "ad_728x310" => "728x310 Large Leaderboard",
        "ad_728x595" => "728x595 Banner"
    );


    public function __construct(){
        $this->var_sTextdomain = 'Qualigo-Widget';
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain($this->var_sTextdomain, PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/l10n', dirname(plugin_basename(__FILE__)) . '/l10n');
		}
		
        $widget_options = array(
            'classname' => 'Qualigo_Widget',
            'description' => __('Qualigo Banner as Widget.', $this->var_sTextdomain)
        );
        $control_options = array();
        $this->WP_Widget('Qualigo_Widget', __('Qualigo-Widget', $this->var_sTextdomain), $widget_options, $control_options);
    }

    public function form( $instance ) {

		$instance = wp_parse_args((array) $instance, array(
            'qualigo-search' => 'Werbung',
            'qualigo-format' => 'ad_468x60',
            'qualigo-col-headline' => '#0B0B61',
            'qualigo-col-text' => '#848484',
            'qualigo-col-url' => '#848484',
            'qualigo-col-background' => '#FFFFFF',
            'qualigo-col-border' => '#FF8000'
        ));
?>
            <p>
<?php echo __('IMPORTANT NOTICE: in order to assign banner ads, you have to enter your publisher ID, which you receive after registration at qualigo.com! As a SUB-ID you can choose your own text (without spaces and special characters).', $this->var_sTextdomain); ?>
            </p>
<?php
        $qualigo = array();
        foreach ( self::$_params AS $_field=>$_description) {
            $qualigo[$_field] = (empty($instance[$_field])) ? '' : apply_filters($_field, $instance[$_field]);
            $addclass = "";
			if (substr($_field, 0, 12 ) == "qualigo-col-" ) $addclass = "color-field";
?>
        <p>
            <label for="<?php echo $this->get_field_id($_field); ?>"><?php echo __($_description, $this->var_sTextdomain); ?></label><br>
<?php
			if (  $_field == "qualigo-format") {
?>
			<select id="<?php echo $this->get_field_id($_field); ?>" name="<?php echo $this->get_field_name($_field); ?>">
<?php
        		foreach ( self::$_format AS $_format_id=>$_format_titel) {
?>
				<option value="<?php echo $_format_id; ?>" <?php print (($qualigo[$_field]==$_format_id) ? 'selected=selected' : ''); ?>><?php echo __($_format_titel, $this->var_sTextdomain); ?></option>
<?php
				}
?>
			</select>
<?php
			}
			else {
?>
            <input id="<?php echo $this->get_field_id($_field); ?>" name="<?php echo $this->get_field_name($_field); ?>" type="text" value="<?php echo $qualigo[$_field]; ?>" class="<?php print $addclass; ?>"/>
        </p>
<?php
		    }
?>
<?php
     	}
?>
<script>
    jQuery(document).ready(function($) { 
            jQuery('.color-field').on('focus', function(){
                var parent = jQuery(this).parent();
                jQuery(this).wpColorPicker()
                parent.find('.wp-color-result').click();
            }); 
    });
</script>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array(
            'qualigo-search' => 'Werbung',
            'qualigo-format' => 'ad_468x60',
            'qualigo-col-headline' => '#0B0B61',
            'qualigo-col-text' => '#848484',
            'qualigo-col-url' => '#848484',
            'qualigo-col-background' => '#FFFFFF',
            'qualigo-col-border' => '#FF8000'
        ));
        foreach ( self::$_params AS $_field=>$_description) {
            $instance[$_field] = (string) strip_tags($new_instance[$_field]);
        }
        return $instance;
    }

    public function widget( $args, $instance ) {
        extract($args);
		foreach ( self::$_params AS $_field=>$_description) {
            $qualigo[$_field] = (empty($instance[$_field])) ? '' : apply_filters($_field, $instance[$_field]);
		}
        echo $before_widget;
		$output = '<script type="text/javascript">
        var QualiGOAdOptions = {
        ad_ds : "'.$qualigo['qualigo-ds'].'",
        ad_subds : "'.$qualigo['qualigo-subsd'].'",
        ad_cat : "",
        ad_search : "'.$qualigo['qualigo-search'].'",
        ad_wo : "de",
        ad_m : "de",
        ad_erotic : "0",
        ad_name : "'.$qualigo['qualigo-format'].'",
        ad_target : "0",
        ad_track : "WP02",
        ad_trackingurl : "",
        ad_color_headline : "'.str_replace("#", "", $qualigo['qualigo-col-headline']).'",
        ad_color_text : "'.str_replace("#", "", $qualigo['qualigo-col-text']).'",
        ad_color_url : "'.str_replace("#", "", $qualigo['qualigo-col-url']).'",
        ad_color_background : "'.str_replace("#", "", $qualigo['qualigo-col-background']).'",
        ad_color_border : "'.str_replace("#", "", $qualigo['qualigo-col-border']).'",
        ad_start : 1,
        };
        (function(src,params) {
        var position = document.getElementsByTagName("script");
        position = position[position.length-1];
        qi=document.createElement("script");
        qi.async="async";
        qi.src=src;
        qi.onload = (function() { displaynow(params,position); });
        position.parentNode.insertBefore(qi,position);
        }) ( "//qualigo.com/doks/ad.js", QualiGOAdOptions );
        </script>';

        if ( strlen($qualigo['qualigo-ds']) < 1 || strlen($qualigo['qualigo-search']) < 1 || strlen($qualigo['qualigo-format']) < 1 ) {
			$output = "";
		}
        echo $output;
		echo $after_widget;
    }
    

}

function qualigo_widget_init() {
    register_widget('qualigo_widget');
}
add_action('widgets_init', 'qualigo_widget_init');


