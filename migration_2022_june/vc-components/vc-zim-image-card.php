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
if ( ! class_exists( 'vcZimImageCard' ) ) {

    class vcZimImageCard extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_soda_imagecard', array( $this, 'render_shortcode_two' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('image card', 'sodawebmedia'),
                'base'          => 'vc_soda_imagecard',
                'description'  	=> __( '', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title', 'sodawebmedia' ),
                        'param_name'    => 'card-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add section title', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Text', 'sodawebmedia' ),
                        'param_name'    => 'card-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the text parraph', 'sodawebmedia' ),
                    ),


                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Image", 'sodawebmedia' ),
                        "param_name" => "card-image",
                        "description" => __( "Add image", 'sodawebmedia' ),                                                
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
                'card-text' => '',
                'card-image'             => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $card_title       = esc_attr($atts['card-title']);
            $card_text = esc_attr($atts['card-text']);
            $img_card     =  esc_attr($atts['card-image']);
            $img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']); 
            
            $output = '
                <div class="c-img-text">
                <div class="c-img-text__wrapper">
                    <div class="c-img-text__titles">
                        <p class="c-img-text__title">'.$card_title.'</p>
                        <div class="c-img-text__mobile-img">
                            '.$img['thumbnail'].'
                        </div>
                        <p class="c-img-text__subtitle">
                                '.$card_text.'
                        </p>

                    </div>
                    <div class="c-img-text__image">
                        '.$img['thumbnail'].'
                    </div>
                </div>
            </div>   
            ';

            return $output;

        }

    }

    new vcZimImageCard();

}
?>