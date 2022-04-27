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
if ( ! class_exists( 'vcZimMOdal' ) ) {

    class vcZimMOdal extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_modal', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Modal zim', 'sodawebmedia'),
                'base'          => 'vc_zim_modal',
                'description'  	=> __( '', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(
    
                    array(
                        "type" => "textarea_html",
                        "holder" => "div",
                        "class" => "",                     
                        "heading" => __( "Blockquote Content", 'sodawebmedia' ),
                        "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
                        "value" => __( "<p>I am test text block. Click edit button to change this text.</p>", 'sodawebmedia' ),
                        "description" => __( "Enter content.", 'sodawebmedia' )
                    ),    

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title', 'sodawebmedia' ),
                        'param_name'    => 'modal-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the modal title', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Subtitle', 'sodawebmedia' ),
                        'param_name'    => 'modal-subtitle',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the modal subtitle', 'sodawebmedia' ),
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
                'blockquote_cite'   => '',
                'modal-title'      => '',
                'modal-subtitle'             => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $content            = wpb_js_remove_wpautop($content, true);
            $modal_title      = esc_attr($atts['modal-title']);
            $modal_subtitle = esc_attr($atts['modal-subtitle']);


            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);

            $output .= '<div class="c-modal">
                <div class="c-modal__fade">
                    <div class="c-modal__content">
                        <span class="c-modal__circle c-modal__circle--left-top">
                            <svg width="147" height="140" viewBox="0 0 147 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M146.595 -0.000274658C140.969 78.0529 77.4857 139.585 0 139.585L0 -0.000274658H146.595Z" fill="#ECE7F3"/>
                                </svg>
                                
                        </span>
                        <span class="c-modal__circle c-modal__circle--right-top"><svg width="136" height="151" viewBox="0 0 136 151" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M136 150.459C59.9517 144.685 0 79.5284 0 0H136V150.459Z" fill="#ECE7F3"/>
                        </svg></span>
                        <span class="c-modal__circle c-modal__circle--right-bottom">
                            <svg width="147" height="140" viewBox="0 0 147 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M-0.00088501 140C5.62488 61.9471 69.1081 0.415039 146.594 0.415039L146.594 140L-0.00088501 140Z" fill="#ECE7F3"/>
                                </svg>
                                
                        </span>
                        <span class="c-modal__circle c-modal__circle--left-bottom">
                            <svg width="136" height="151" viewBox="0 0 136 151" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.124817C76.0483 5.89888 136 71.0556 136 150.584H0V0.124817Z" fill="#ECE7F3"/>
                                </svg>
                                
                        </span>
                        
                        <p class="c-modal__title">
                            '.$modal_title.'
                        </p>
                        <p class="c-modal__subtitle">
                            '.$modal_subtitle.' 
                        </p>
                        <div class="c-modal__form">
                            '.$content.'
                        </div>
                    </div>
                </div>
            </div>';

            return $output;             

        }

    }

    new vcZimMOdal();

}
?>