<?php 
/**
 * Blockquote
 *
 * Create a Blockquote in WPBakery
 *
 * @category   Wordpress
 * @since      Class available since Release 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}
if ( ! class_exists( 'vcZimHeroSecondary' ) ) {

    class vcZimHeroSecondary extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_hero_secondary', array( $this, 'render_shortcode_two' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Secondary hero', 'sodawebmedia'),
                'base'          => 'vc_zim_hero_secondary',
                'description'  	=> __( '', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title', 'sodawebmedia' ),
                        'param_name'    => 'card-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add title for the hero Quote.', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Higlighted title', 'sodawebmedia' ),
                        'param_name'    => 'highlighted-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Get the url of the image you want to use as background from your WORDPRESS Media Library' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Apple app link', 'sodawebmedia' ),
                        'param_name'    => 'ios-url',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Link to the IOS app download' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Android app link', 'sodawebmedia' ),
                        'param_name'    => 'android-url',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Link to the Android app download' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Subtitle', 'sodawebmedia' ),
                        'param_name'    => 'card-subtitle',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add Author Quote.', 'sodawebmedia' ),
                    ),

                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Mobile image", 'sodawebmedia' ),
                        "param_name" => "mobile-image",
                        "description" => __( "Add mobile image for hero", 'sodawebmedia' ),                                                
                    ),  

                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Desktop image", 'sodawebmedia' ),
                        "param_name" => "desktop-image",
                        "description" => __( "Add desktop image for hero", 'sodawebmedia' ),                                                
                    ),  

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Anchor', 'sodawebmedia' ),
                        'param_name'    => 'anchor',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add link anchor', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Element ID', 'sodawebmedia' ),
                        'param_name'    => 'element_id',
                        'value'             => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Enter element ID (Note: make sure it is unique and valid).', 'sodawebmedia' ),
                        'group'         => __( 'Extra', 'sodawebmedia'),
                    ),

                    array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Extra class name', 'sodawebmedia' ),
                        'param_name'    => 'extra_class',
                        'value'             => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'sodawebmedia' ),
                        'group'         => __( 'Extra', 'sodawebmedia'),
                    ),               
                ),
            ));             

        }

        public function render_shortcode_two( $atts, $content, $tag ) {
            $atts = (shortcode_atts(array(
                'card-title'      => '',
                'highlighted-title'             => '',
                'card-subtitle'       => '',
                'background-image-url' => '',
                'desktop-image' => '',
                'mobile-image' => '',
                'ios-url' => '',
                'android-url' => '',
                'anchor' => '',
                'element_id'        => '',
                'extra_class'        => ''
            ), $atts));


            //Content 
            $content            = wpb_js_remove_wpautop($content, true);

            $card_title       = esc_attr($atts['card-title']);
            $highlighted_title = esc_attr($atts['highlighted-title']);
            $card_subtitle = esc_attr($atts['card-subtitle']);
            $background_image = esc_attr($atts['background-image-url']);
            $ios_url = esc_attr($atts['ios-url']);
            $android_url = esc_attr($atts['android-url']);
            $anchor = esc_attr($atts['anchor']);

            $apple_logo     =  esc_attr($atts['apple-logo-image']);
            $apple_logo_img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            $android_logo     =  esc_attr($atts['android-logo-image']);
            $android_logo_img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            $img_card     =  esc_attr($atts['card-image']);
            $img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            $mobile_img     =  esc_attr($atts['mobile-image']);
            $mobile_img_thumb = wpb_getImageBySize( array(
				'attach_id' => (int) $mobile_img,
				'thumb_size' => 'full',
			) );

            $desktop_img     =  esc_attr($atts['desktop-image']);
            $desktop_img_thumb = wpb_getImageBySize( array(
				'attach_id' => (int) $desktop_img,
				'thumb_size' => 'full',
			) );

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']); 
            
            $output = '
                <div class="c-plans-hero">
                    <div class="c-plans-hero__wrapper">
                        <div class="c-plans-hero__titles">
                            <h1 class="c-plans-hero__titles-title">
                                '.$card_title.' <span> '.$highlighted_title.' </span>
                            </h1>
                            <div class="c-plans-hero__img c-plans-hero__img--mobile">
                                '.$desktop_img_thumb['thumbnail'].'
                            </div>  
                            <p class="c-plans-hero__text">
                                '.$card_subtitle.'
                            </p>
                            <div class="c-plans-hero__download">
                                <a href="'.$ios_url.'"><img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/apple-button.png" alt=""></a>
                                <a href="'.$android_url.'"><img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/playstore-button.png" alt=""></a>
                            </div>
                        </div>
                        <div class="c-plans-hero__desk-img">
                            '.$desktop_img_thumb['thumbnail'].'
                        </div>                        
                    </div>
                <div class="c-hero__button">
                    <a href="'.$anchor.'" class="c-button c-button__transparent c-button__transparent--purple">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.2735L11.06 3.3335L8 6.38683L4.94 3.3335L4 4.2735L8 8.2735L12 4.2735Z" fill="#3F0A72"/>
                            <path d="M12 8.66705L11.06 7.72705L8 10.7804L4.94 7.72705L4 8.66705L8 12.6671L12 8.66705Z" fill="#3F0A72"/>
                            </svg>
                            Scroll down
                    </a>
                </div>
                </div>
            ';

            return $output;

        }

    }

    new vcZimHeroSecondary();

}
?>