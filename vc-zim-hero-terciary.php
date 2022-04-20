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
if ( ! class_exists( 'vcZimHeroTerciary' ) ) {

    class vcZimHeroTerciary extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_hero_terciary', array( $this, 'render_shortcode_two' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Terciary hero', 'sodawebmedia'),
                'base'          => 'vc_zim_hero_terciary',
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
                        'heading'       => __( 'Subtitle', 'sodawebmedia' ),
                        'param_name'    => 'card-subtitle',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add Author Quote.', 'sodawebmedia' ),
                    ),                     

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Mobile image link', 'sodawebmedia' ),
                        'param_name'    => 'mobile-image-link',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Get the mobile image LINK from the wordpress media library', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Desktop image link', 'sodawebmedia' ),
                        'param_name'    => 'desktop-image-link',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Get the dekstop image LINK from the wordpress media library', 'sodawebmedia' ),
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
                'mobile-image-link' => '',
                'desktop-image-link' => '',
                'element_id'        => '',
                'extra_class'        => ''
            ), $atts));


            //Content 
            $content            = wpb_js_remove_wpautop($content, true);

            $card_title       = esc_attr($atts['card-title']);
            $highlighted_title = esc_attr($atts['highlighted-title']);
            $card_subtitle = esc_attr($atts['card-subtitle']);
            $mobile_image_link = esc_attr($atts['mobile-image-link']);
            $desktop_image_link = esc_attr($atts['desktop-image-link']);


            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']); 
            
            $output = '
            <div class="c-half-hero">
                <div class="c-half-hero__titles">
                    <p class="c-half-hero__title">'.$card_title.' 
                        <span>'.$highlighted_title.'</span>
                    </p>
                        <p class="c-half-hero__subtitle">
                        '.$card_subtitle.'
                        </p>
                </div>
                <div class="c-half-hero__image">
                    <picture>
                        <source srcset="'.$mobile_image_link.'" media="(max-width: 390px)">
                        <source srcset="'.$desktop_image_link.'" alt="Group of happy friends">
                        <img src="'.$desktop_image_link.'" alt="Group of happy friends">
                    </picture> 
                </div>
            </div>
            ';

            return $output;

        }

    }

    new vcZimHeroTerciary();

}
?>