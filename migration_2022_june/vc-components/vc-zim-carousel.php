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
if ( ! class_exists( 'vcZimCarousel' ) ) {

    class vcZimCarousel extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_carousel', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('ZIM Trusted by Carousel', 'sodawebmedia'),
                'base'          => 'vc_zim_carousel',
                'description'  	=> __( 'Images carousel', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    
                      

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title', 'sodawebmedia' ),
                        'param_name'    => 'carousel-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the title of the carousel.', 'sodawebmedia' ),
                    ), 
                    
                    array(
                        "type"        => "attach_images",
                        "heading"     => esc_html__( "Add images", "appcastle-core" ),
                        "description" => esc_html__( "Add image", "appcastle-core" ),
                        "param_name"  => "carousel-images",
                        "value"       => "",
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
                'carousel-title'   => '',
                'carousel-images' => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $carousel_title  = esc_html($atts['carousel-title']);
            $image_ids = explode(',',$atts['carousel-images']);

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            


            $output = '<div class="c-trusted-carousel">
                            <div class="c-trusted-carousel__title">
                                <p>'.$carousel_title.'</p>
                            </div>
                            <div class="c-trusted-carousel__carousel">
                                <div class="owl-carousel owl-theme js-trusted-carousel">
            ';
            foreach( $image_ids as $image_id ){
                $images = wp_get_attachment_image_src( $image_id, 'company_logo' );
                $output .= '
                <div class="item">
                    <img src="'.$images[0].'" class="c-trusted-carousel__item" alt="'.$atts['title'].'">
                </div>';
                $images++;
            }
            $output .='</div>
            </div>
            </div>';

            return $output;             

        }

    }

    new vcZimCarousel();

}
?>