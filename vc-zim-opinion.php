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
if ( ! class_exists( 'vcZimOpinion' ) ) {

    class vcZimOpinion extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_opinion', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Opinion Block', 'sodawebmedia'),
                'base'          => 'vc_zim_opinion',
                'description'  	=> __( 'Individual title block', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    
                      

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Opinion text', 'sodawebmedia' ),
                        'param_name'    => 'opinion-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the text of the opinion.', 'sodawebmedia' ),
                    ),   

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Author name', 'sodawebmedia' ),
                        'param_name'    => 'author-name',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the name of the opinion\'s author.', 'sodawebmedia' ),
                    ), 

                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Author image", 'sodawebmedia' ),
                        "param_name" => "author-photo",
                        "description" => __( "Add picture of author", 'sodawebmedia' ),                                                
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
                        'heading'       => __( 'Modifier class name', 'sodawebmedia' ),
                        'param_name'    => 'extra_class',
                        'value'             => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Style the zim logo element differently - c-opinion--logo-left, c-opinion--logo-right, c-opinion--logo-center.', 'sodawebmedia' ),
                        'group'         => __( 'Extra', 'sodawebmedia'),
                    ),               
                ),
            ));             

        }

        public function render_shortcode( $atts, $content, $tag ) {
            $atts = (shortcode_atts(array(
                'opinion-text'   => '',
                'author-name' => '',
                'author-photo' => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $opinion_text  = esc_attr($atts['opinion-text']);
            $author_name = esc_attr($atts['author-name']);
            $author_photo = esc_attr($atts['author-photo']);
            $author_photo_img = wpb_getImageBySize( array(
				'attach_id' => (int) $author_photo,
				'thumb_size' => 'full',
			) );

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            


            $output = ' 
                <div class="c-opinion '.$extra_class.'">
                    <div class="c-opinion__wrapper">
                        <div class="c-opinion__logo">
                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/zim-tiny-logo.png" alt="">
                        </div>
                        <div class="c-opinion__text">
                            “ '.$opinion_text.'”
                        </div>
                        <div class="c-opinion__author">   
                            '.$author_photo_img['thumbnail'].'
                            <p>'.$author_name.'</p>
                        </div>
                    </div>
                </div>
            ';

            return $output;             

        }

    }

    new vcZimOpinion();

}
?>