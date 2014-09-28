<?php
/*
Plugin Name: HighCycle
Plugin URI: http://jacksonwhelan.com/plugins/highcycle/
Description: HighCycle adds Highslide for image enlargements, and creates slideshows of attached images using the Cycle plugin for jQuery.
Author: Jackson
Version: 1.3.3
Author URI: http://jacksonwhelan.com
*/

$HighCycle = new HighCycle;

class HighCycle {

	function install() {
    }
    
    function uninstall() {
    }
    
    function HighCycle() {
    	add_action( 'init', array( &$this, 'register_scripts' ) );
    	add_action( 'wp_footer', array( &$this, 'print_scripts') );
    	add_action( 'wp_print_styles', array( &$this, 'add_stylesheets') );
		add_filter( 'attachment_fields_to_edit', array( &$this, 'image_attachment_fields_to_edit' ), 9, 2 );
		add_filter( 'attachment_fields_to_save', array( &$this, 'image_attachment_fields_to_save' ), 9, 2 );    	
		add_filter( 'post_gallery', array( &$this, 'gallery_sc' ), 10, 2 );
		add_filter( 'the_content', array( &$this, 'content_filter' ), 99 );
		add_shortcode( 'slideshow', array( &$this, 'highcycle_show' ) );
    }
 
	function register_scripts() {
		wp_register_script('highslide', plugins_url( 'highcycle/highslide/highslide-full.min.js' ), array('jquery'), '1.0', true);
		wp_register_script('cycle', plugins_url( 'highcycle/jquery.cycle/jquery.cycle.all.min.js' ), array('jquery'), '1.0', true);
		wp_register_script('highcycle', plugins_url( 'highcycle/highcycle.js' ), array('jquery', 'highslide', 'cycle' ), '1.0', true);
	}
	 
	function print_scripts() {
		global $add_hs_scripts, $hs_localize;
	 
		if ( ! $add_hs_scripts )
			return;
	 	
	 	$data = array( 
			'graphicsurl' => plugins_url( 'highcycle/highslide/graphics/' ),
			'width' => 1024,
			'height' => 768,
			'pause' => 0,
			'speed' => 2500,
			'delay' => 1000,
		);
		
		if( is_array( $hs_localize ) ) {
			$data = wp_parse_args( $hs_localize, $data );		
		}
		
		wp_localize_script( 'highcycle', 'high_cycle', $data );
		
		wp_print_scripts( array( 'highslide', 'cycle', 'highcycle' ) );
	}
	
	function add_stylesheets() {
        wp_register_style( 'highslide-css', plugins_url( 'highcycle/highslide/highslide.css' ) );
        wp_register_style( 'high-cycle-css', plugins_url( 'highcycle/highcycle.css' ) );	
		wp_enqueue_style( 'highslide-css' );
        wp_enqueue_style( 'high-cycle-css' );
	}
	
	function image_attachment_fields_to_edit($form_fields, $post) {
		if(get_post_meta($post->ID, "_jw_hc_ss_img", true) == 'yes') {
			$selected = " selected='selected'";
		} else {
			$selected = "";
		}
		$form_fields["jw_hc_ss_img"]["label"] = __("Show in Slideshow?");  
		$form_fields["jw_hc_ss_img"]["input"] = "html";  
		$form_fields["jw_hc_ss_img"]["html"] = "<select name='attachments[{$post->ID}][jw_hc_ss_img]' id='attachments[{$post->ID}][jw_hc_ss_img]'> 
		<option value='no'>No</option> 
		<option value='yes'".$selected.">Yes</option> 
		</select>"; 
		if(get_post_meta($post->ID, "_jw_hc_ss_href", true) != '') {
			$value = get_post_meta($post->ID, "_jw_hc_ss_href", true);
		} else {
			$value = "";
		}
		$form_fields["jw_hc_ss_href"]["label"] = __("Slideshow Link");  
		$form_fields["jw_hc_ss_href"]["input"] = "html";  
		$form_fields["jw_hc_ss_href"]["html"] = "<input name='attachments[{$post->ID}][jw_hc_ss_href]' id='attachments[{$post->ID}][jw_hc_ss_href]' value='$value' type='text' />";  
		return $form_fields;
	}
	
	function image_attachment_fields_to_save($post, $attachment) {
		if( isset( $attachment['jw_hc_ss_img'] ) ) {
			update_post_meta( $post['ID'], '_jw_hc_ss_img', $attachment['jw_hc_ss_img'] );
		}
		if( isset( $attachment['jw_hc_ss_href'] ) && $attachment['jw_hc_ss_href'] != '' ) {
			update_post_meta( $post['ID'], '_jw_hc_ss_href', $attachment['jw_hc_ss_href'] );
		}
		return $post;
	}
	
	function gallery_sc($attr) {
		global $post, $add_hs_scripts;
		
		$add_hs_scripts = true;
		
		static $instance = 0;
		$instance++;
	
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}
	
		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail'
		), $attr));
	
		$id = intval($id);
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	
		if ( empty($attachments) )
			return '';
	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}
	
		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	
		$selector = "gallery-{$instance}";
	
		$output = "<!-- highcycle_gallery_shortcode() ".$size." -->
			<div id='$selector' class='gallery galleryid-{$id}'>";
	
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = wp_get_attachment_link($id, $size, false, false);
			//$link = str_replace('<a', '<a class="highslide" onclick="return hs.expand(this)" ', $link);
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	
		return $output;
	}
		
	function content_filter($content) {
		global $post, $add_hs_scripts;
		
		$add_hs_scripts = true;
		
		$pattern[0] = "/(<a)([^\>]*?) href=('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)>(.*?)<\/a>/i";
		$replacement[0]	= '$1 href=$3$4$5$6$2$7>$8</a>';
		$pattern[1] = "/(<a href=)('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)(>)(.*?)(<\/a>)/i";
		$replacement[1]	= '$1$2$3$4$5 class="highslide" onclick="return hs.expand(this)" $6$7$8$9';
		$pattern[2] = "/(<a href=)('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\") rel=('|\")lightbox([^\>]*?)('|\")([^\>]*?) rel=('|\")(lightbox|nolightbox)([^\>]*?)('|\")([^\>]*?)(>)(.*?)(<\/a>)/i";
		$replacement[2]	= '$1$2$3$4$5$9 rel=$10$11$12$13$14$15$16$17';
		$pattern[3] = "/(<a href=)('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)(>)(.*?) title=('|\")(.*?)('|\")(.*?)(<\/a>)/i";
		$replacement[3]	= '$1$2$3$4$5$6 title=$9$10$11$7$8 title=$9$10$11$12$13';
		$pattern[4]	= "/(<a href=)('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?) title=([^\>]*?) title=([^\>]*?)(>)(.*?)(<\/a>)/i";
		$replacement[4]	= '$1$2$3$4$5$6 title=$7$9$10$11';

		$content = preg_replace($pattern, $replacement, $content);
		return $content;
		
	}
	
	function highcycle_show( $atts ) {
		
		global $post, $add_hs_scripts, $hs_localize, $wptouch_pro, $facebook_app;
		$add_hs_scripts = true;
		
		extract( shortcode_atts( array(
			'id' => $post->ID,
			'size' => 'large',
			'show' => 'selected',
			'showthumbs' => false,
			'linkto' => 'large',
			'pager' => false,
			'per_page' => 12,
			'speed' => 2000,
			'pause' => 0,
			'delay' => 1000
		), $atts ) );
		
		if( ( is_object( $wptouch_pro ) && $wptouch_pro->showing_mobile_theme ) || $facebook_app) {
			$size = 'medium';
			$linkto = 'none';
		}
		
		$hs_localize['speed'] = $speed;
		$hs_localize['pause'] = $pause;
		$hs_localize['delay'] = $delay;
		 
		$count = 1;
		if($show == 'all') {
			$attachments =& get_children( 'post_type=attachment&post_mime_type=image&orderby=menu_order&order=ASC&post_parent='.$post->ID );
		} else {
			$attachments =& get_children( 'post_type=attachment&post_mime_type=image&orderby=menu_order&order=ASC&meta_key=_jw_hc_ss_img&meta_value=yes&post_parent='.$post->ID );
		}	
		if($attachments) {
			$out = '<div class="jw-highcycle-wrap"><div id="jw-highcycle-'.$post->ID.'" class="jw-highcycle">';
		} else {
			$out = null;
			return($out);
			exit;
		}
		$slides = '';
		$thumbs = '';
		foreach($attachments as $attachment => $attachment_array ) {
			if($showthumbs) {
			$thumbimagearray = wp_get_attachment_image_src($attachment, 'thumbnail', false);
			$thumbimageURI = $thumbimagearray[0];
			}
			$slideimagearray = wp_get_attachment_image_src($attachment, $size, false);
			$slideimageURI = $slideimagearray[0];
			$fullimagearray = wp_get_attachment_image_src($attachment, 'full', false);
			$fullimageURI = $fullimagearray[0];
			$imageID = get_post($attachment);
			$imageTitle = $imageID->post_title;
			$imageCaption = $imageID->post_excerpt;
			$imageDescription = $imageID->post_content;
			if($imageCaption != '') {
				$caption = '<div class="slide-cap">'.$imageCaption.'</div>';
			} else {
				$caption = '';
			}
			if($linkto == 'none') { 
				$out.= '<div class="slide" id="i'.$count.'"><img src="'.$slideimageURI.'" width="'.$slideimagearray[1].'" height="'.$slideimagearray[2].'" />'.$caption.'</div>';
			} elseif($linkto == 'large' || get_post_meta($imageID->ID, "_jw_hc_ss_href", true) == '') {
				$out.= '<div class="slide" id="i'.$count.'"><a href="'.$fullimageURI.'" class="highslide" onclick="return hs.expand(this)"><img src="'.$slideimageURI.'" width="'.$slideimagearray[1].'" height="'.$slideimagearray[2].'" /></a>'.$caption.'</div>';
			} elseif($linkto == 'url') { 
				$out.= '<div class="slide" id="i'.$count.'"><a href="'.get_post_meta($imageID->ID, "_jw_hc_ss_href", true).'"><img src="'.$slideimageURI.'" width="'.$slideimagearray[1].'" height="'.$slideimagearray[2].'" /></a>'.$caption.'</div>';
			} if($showthumbs) {
			$thumbs.= '			
			<li><a href="#" id="goto'.$count.'" class="slides'.$post->ID.'"><img src="'.$thumbimageURI.'" width="200" height="100" /></a></li>';
			}
			$count++;

		}
		$out.= '</div>';

		if($pager) {
			$out.= '<div class="jw-highcycle-pager"><a href="#" class="jw-hc-prev">Previous</a> <span class="jw-hc-pages"> </span> <a href="#" class="jw-hc-prev">Next</a></div>';
		}

		$out.= '</div>';
		
		if($showthumbs) {
			$out.= '<div class="jw-highcycle-thumbs"><ul class="jw-highcycle-thumb">'.$thumbs.'</ul></div>';
		}
		return $out;
	}

}

register_activation_hook( __FILE__, array( 'HighCycle', 'install' ) );
register_deactivation_hook( __FILE__, array( 'HighCycle', 'uninstall' ) );

?>