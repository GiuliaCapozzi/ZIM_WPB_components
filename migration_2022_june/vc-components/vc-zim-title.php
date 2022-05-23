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
if ( ! class_exists( 'vcZimTitle' ) ) {

    class vcZimTitle extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_title', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Title Block', 'sodawebmedia'),
                'base'          => 'vc_zim_title',
                'description'  	=> __( 'Individual title block', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    
                      

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title text', 'sodawebmedia' ),
                        'param_name'    => 'title-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the title text.', 'sodawebmedia' ),
                    ),  
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Subtitle text', 'sodawebmedia' ),
                        'param_name'    => 'subtitle-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the title text.', 'sodawebmedia' ),
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
                'title-text'   => '',
                'subtitle-text' => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $title_text  = esc_html($atts['title-text']);
            $subtitle_text  = esc_html($atts['subtitle-text']);

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            


            $output = ' <div class="c-title">
                          <p class="c-title__title">'.$title_text.'</p>';
            if($subtitle_text != '') {
                $output .= '<p class="c-title__subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>';
            }
            $output .= '
            </div>';

            return $output;             

        }

    }

    new vcZimTitle();

}
?>