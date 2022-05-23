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
if ( ! class_exists( 'vcZimAppStepper' ) ) {

    class vcZimAppStepper extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_app_stepper', array( $this, 'render_shortcode_two' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('App Stepper', 'sodawebmedia'),
                'base'          => 'vc_zim_app_stepper',
                'description'  	=> __( '', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Title', 'sodawebmedia' ),
                        'param_name'    => 'stepper-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add section title', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Text', 'sodawebmedia' ),
                        'param_name'    => 'stepper-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the text parraph', 'sodawebmedia' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 1 title', 'sodawebmedia' ),
                        'param_name'    => 'step-1-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Title of step 1 card' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 1 text', 'sodawebmedia' ),
                        'param_name'    => 'step-1-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Text of step 1 card' ),
                    ),


                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Step 1 image", 'sodawebmedia' ),
                        "param_name" => "step-1-image",
                        "description" => __( "Add image of step 1", 'sodawebmedia' ),                                                
                    ),   

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 2 title', 'sodawebmedia' ),
                        'param_name'    => 'step-2-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Title of step 2 card' ),
                    ),   

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 2 text', 'sodawebmedia' ),
                        'param_name'    => 'step-2-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Text of step 2 card' ),
                    ), 
                    
                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Step 2 image", 'sodawebmedia' ),
                        "param_name" => "step-2-image",
                        "description" => __( "Add image of step 2", 'sodawebmedia' ),                                                
                    ),    

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 3 title', 'sodawebmedia' ),
                        'param_name'    => 'step-3-title',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Title of step 3 card' ),
                    ),   

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Step 3 text', 'sodawebmedia' ),
                        'param_name'    => 'step-3-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Text of step 3 card' ),
                    ), 
                    
                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Step 3 image", 'sodawebmedia' ),
                        "param_name" => "step-3-image",
                        "description" => __( "Add image of step 3", 'sodawebmedia' ),                                                
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
                'stepper-title'      => '',
                'stepper-text' => '',
                'step-1-title'             => '',
                'step-1-text'             => '',
                'step-1-image'             => '',
                'step-2-title'             => '',
                'step-2-text'             => '',
                'step-2-image'             => '',
                'step-3-title'             => '',
                'step-3-text'             => '',
                'step-3-image'             => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $stepper_title       = esc_attr($atts['stepper-title']);
            $stepper_text = esc_attr($atts['stepper-text']);

            $step_1_title = esc_attr($atts['step-1-title']);
            $step_1_text = esc_attr($atts['step-1-text']);
            
            $step_2_title = esc_attr($atts['step-2-title']);
            $step_2_text = esc_attr($atts['step-2-text']);

            $step_3_title = esc_attr($atts['step-3-title']);
            $step_3_text = esc_attr($atts['step-3-text']);

            $img_card     =  esc_attr($atts['step-1-image']);
            $img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            $img_card_step_2     =  esc_attr($atts['step-2-image']);
            $img_2 = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card_step_2,
				'thumb_size' => 'full',
			) );

            $img_card_step_3     =  esc_attr($atts['step-3-image']);
            $img_3 = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card_step_3,
				'thumb_size' => 'full',
			) );

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']); 
            
            $output = '
            <div class="c-download-slider">
            <div class="c-download-slider__wrapper">
                <div class="c-download-slider__texts">
                    <p class="c-download-slider__title c-title"><span class="c-title__title">'.$stepper_title.'</span></p>
                    <p class="c-download-slider__subtitle">'.$stepper_text.' </p>
                    <div class="c-download-slider__step-carousel">
                        <div class="owl-carousel owl-theme js-steps-carousel">
                            <div class="item">
                                <div class="c-step-card">
                                    <div class="c-step-card__wrapper">
                                        <p class="c-step-card__title">Step 1</p>
                                        <div class="c-step-card__card">
                                            <p class="c-step-card__card-title">'.$step_1_title.'</p>
                                            <p class="c-step-card__card-txt">'.$step_1_text.' </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="c-step-card">
                                    <div class="c-step-card__wrapper">
                                        <p class="c-step-card__title">Step 2</p>
                                        <div class="c-step-card__card">
                                            <p class="c-step-card__card-title">'.$step_2_title.'</p>
                                            <p class="c-step-card__card-txt">'.$step_2_text.'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="c-step-card">
                                    <div class="c-step-card__wrapper">
                                        <p class="c-step-card__title">Step 3</p>
                                        <div class="c-step-card__card">
                                            <p class="c-step-card__card-title">'.$step_3_title.'</p>
                                            <p class="c-step-card__card-txt">'.$step_3_text.'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="c-download-slider__images">
                    <div class="c-images-carousel">
                        <div class="owl-carousel owl-theme js-steps-carousel">
                            <div class="item">
                                <figure>
                                '.$img['thumbnail'].'
                                </figure>
                            </div>
                            <div class="item">
                                <figure>
                                '.$img_2['thumbnail'].'
                                </figure>
                            </div>
                            <div class="item">
                                <figure>
                                '.$img_3['thumbnail'].'
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
            ';

            return $output;

        }

    }

    new vcZimAppStepper();

}
?>