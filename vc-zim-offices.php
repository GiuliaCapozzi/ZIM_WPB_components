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
if ( ! class_exists( 'vcZimOffices' ) ) {

    class vcZimOffices extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_offices', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Zim Offices', 'sodawebmedia'),
                'base'          => 'vc_zim_offices',
                'description'  	=> __( 'Address and photo map of ZIM offices', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    
                      

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'UK office address', 'sodawebmedia' ),
                        'param_name'    => 'uk-address',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Address of the UK office.', 'sodawebmedia' ),
                    ),  

                     
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'UK City', 'sodawebmedia' ),
                        'param_name'    => 'uk-city',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Address of the UK city.', 'sodawebmedia' ),
                    ),
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Spain office', 'sodawebmedia' ),
                        'param_name'    => 'spain-address',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Address of the Spain office.', 'sodawebmedia' ),
                    ), 
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Spain city', 'sodawebmedia' ),
                        'param_name'    => 'spain-city',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'City of the Spain office.', 'sodawebmedia' ),
                    ), 
                    
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Mobile image link', 'sodawebmedia' ),
                        'param_name'    => 'mobile-image',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Link of image for mobile. Get the link from the WP media gallery.', 'sodawebmedia' ),
                    ),  

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Desktop image link', 'sodawebmedia' ),
                        'param_name'    => 'desktop-image',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Link of image for desktop. Get the link from the WP media gallery.', 'sodawebmedia' ),
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

        public function render_shortcode( $atts, $content, $tag ) {
            $atts = (shortcode_atts(array(
                'uk-address'   => '',
                'uk-city' => '',
                'spain-address' => '',
                'spain-city' => '',
                'mobile-image' => '',
                'desktop-image' => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $uk_address  = esc_attr($atts['uk-address']);
            $uk_city = esc_attr($atts['uk-city']);
            $spain_address = esc_attr($atts['spain-address']);
            $spain_city = esc_attr($atts['spain-city']);
            
            $img_mobile = esc_attr($atts['mobile-image']);
            $img_desktop = esc_attr($atts['desktop-image']);

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            


            $output = '
            <div class="c-map-info">
                <div class="c-map-info__wrapper container">
                    <div class="c-title">
                        <p class="c-title__title">Our offices</p>
                    </div>
                    <div class="c-map-info__map">
                        <picture>
                            <source srcset="'.$img_mobile.'" media="(max-width: 767px)">
                            <source srcset="'.$img_desktop.'" media="(min-width: 768px)">
                            <img src="'.$img_desktop.'" alt="">
                        </picture>
                    </div>
                    <div class="c-map-info__info">
                        <div class="c-map-info__info-office">
                            <div class="c-title c-title--mobile">
                                <p class="c-title__title">Our offices</p>
                            </div>
                            <p class="c-map-info__info-office-title">
                                Office in the United Kingdom
                            </p>
                            <p class="c-map-info__info-office-address">
                            <span> '.$uk_address.'</span>
                            <span> '.$uk_city.'</span>
                            </p>
                        </div>
                        <div class="c-map-info__info-office">
                            <p class="c-map-info__info-office-title">
                                Office in Spain
                            </p>
                            <p class="c-map-info__info-office-address">
                            <span> '.$spain_address.'</span>
                            <span> '.$spain_city.'</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            ';

            return $output;             

        }

    }

    new vcZimOffices();

}
?>