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
if ( ! class_exists( 'vcZimRepeater' ) ) {

    class vcZimRepeater extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_repeater', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('ZIM title text carousel', 'sodawebmedia'),
                'base'          => 'vc_zim_repeater',
                'description'  	=> __( 'Repeater carousel for text content', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    

                    array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Section title', 'sodawebmedia' ),
                        'param_name'    => 'section-title',
                        'value'             => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Title of the carousel section.', 'sodawebmedia' ),
                    ), 
                    
                    array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Section subtitle', 'sodawebmedia' ),
                        'param_name'    => 'section-subtitle',
                        'value'             => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Subitle of the carousel section.', 'sodawebmedia' ),
                    ), 

                    array(
                        'type' => 'param_group',
                        'param_name' => 'values',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'name' => 'Year',
                                'heading' => __('Heading', 'rrf-maxima'),
                                'param_name' => 'year',
                            ),
                            array(
                                'type' => 'textarea',
                                'name' => 'Content',
                                'heading' => __('Content', 'rrf-maxima'),
                                'param_name' => 'content',
                            )
                        )
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
                'values'   => '',
                'section-title'   => '',
                'section-subtitle'   => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));     
            
            $section_title = esc_attr($atts['section-title']);
            $section_subtitle = esc_attr($atts['section-subtitle']);
            $select_color = esc_attr($atts['select-color']);

            $values = vc_param_group_parse_atts($atts['values']);
            $new_accordion_value = array();
            foreach($values as $data){
                $new_line = $data;
                $new_line['label'] = isset($new_line['label']) ? $new_line['label'] : '';
                $new_line['excerpt'] = isset($new_line['excerpt']) ? $new_line['excerpt'] : '';

                $new_accordion_value[] = $new_line;
            }
            $count = 0;
            $output = '<div class="c-story-carousel js-local-tab visible" data-tab="europe">
                        <div class="c-title">
                            <p class="c-title__title">'.$section_title.'</p>
                            <p class="c-title__subtitle">'.$section_subtitle.'  </p>
                        </div>
                        <div class="c-story-carousel__carousel">
                            <div class="owl-carousel owl-theme js-history-carousel">';
                    foreach($new_accordion_value as $accordion){
                        $class = ($count == 1) ? 'active' : '';
                        $output .= '<div class="item">
                                <div class="c-story-card '.$class.'">
                                    <p class="c-story-card__title">'.$accordion['year'].'</p>
                                    <p class="c-story-card__text">
                                        '.$accordion['content'].'
                                    </p>
                                </div>
                            </div>';
                        $count++;
                    }
                    $output .= ' </div>
                        </div>
                    </div>';


            return $output;             

        }

    }

    new vcZimRepeater();

}
?>