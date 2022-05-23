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
if ( ! class_exists( 'vcZimCompatibility' ) ) {

    class vcZimCompatibility extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_compatibility', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('ZIM compatibilities', 'sodawebmedia'),
                'base'          => 'vc_zim_compatibility',
                'description'  	=> __( 'Acordeon for ZIM compatibility viz.', 'sodawebmedia' ),
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
                        'param_name' => 'all-values',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'name' => 'Compatibility',
                                'heading' => __('All devices compatibility', 'rrf-maxima'),
                                'param_name' => 'compatibility',
                            )
                        )
                    ),

                    array(
                        'type' => 'param_group',
                        'param_name' => 'ios-values',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'name' => 'Compatibility',
                                'heading' => __('IOS device compatibility', 'rrf-maxima'),
                                'param_name' => 'compatibility',
                            )
                        )
                    ),
                    

                    array(
                        'type' => 'param_group',
                        'param_name' => 'android-values',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'name' => 'Compatibility',
                                'heading' => __('ANDROID device compatibility', 'rrf-maxima'),
                                'param_name' => 'compatibility',
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
                'all-values'   => '',
                'ios-values'   => '',
                'android-values'   => '',
                'section-title'   => '',
                'section-subtitle'   => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));     
            
            $section_title = esc_attr($atts['section-title']);
            $section_subtitle = esc_attr($atts['section-subtitle']);

            $all_values = vc_param_group_parse_atts($atts['all-values']);
            $new_all_value = array();
            foreach($all_values as $data){
                $new_line = $data;
                $new_line['compatibility'] = isset($new_line['compatibility']) ? $new_line['compatibility'] : '';

                $new_all_value[] = $new_line;
            }

            $ios_values = vc_param_group_parse_atts($atts['ios-values']);
            $new_ios_value = array();
            foreach($ios_values as $data){
                $new_line = $data;
                $new_line['compatibility'] = isset($new_line['compatibility']) ? $new_line['compatibility'] : '';

                $new_ios_value[] = $new_line;
            }

            $android_values = vc_param_group_parse_atts($atts['android-values']);
            $new_android_value = array();
            foreach($android_values as $data){
                $new_line = $data;
                $new_line['compatibility'] = isset($new_line['compatibility']) ? $new_line['compatibility'] : '';

                $new_android_value[] = $new_line;
            }
            $count = 0;
            $template ='
            <div class="c-compatibility">
                <div class="c-compatibility__wrapper">
                    <div class="c-compatibility__texts">
                        <div class="c-title">
                            <p class="c-title__title">
                                '.$section_title.'
                            </p>
                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/compatibilidad.png" alt="" class="c-compatibility__image">
                        </div>
                        <p class="c-compatibility__text">
                           '.$section_subtitle.' 
                        </p>
                    </div>
                    <div class="c-compatibility__acordeon">
                        <div class="c-compatibility__acordeon-header">
                            <div><input type="radio" class="c-compatibility__acordeon-input js-input-acordeon" id="contactChoice1"
                            name="contact" value="email" checked="">
                            <label for="contactChoice1">All</label></div>

                            <div><input type="radio" class="c-compatibility__acordeon-input js-input-acordeon" id="contactChoice2"
                            name="contact" value="phone">
                            <label for="contactChoice2">IOS</label></div>

                            <div><input type="radio" class="c-compatibility__acordeon-input js-input-acordeon" id="contactChoice3"
                            name="contact" value="mail">
                            <label for="contactChoice3">Android</label> </div>                       
                        </div>
                        <div class="c-compatibility__acordeon-body">
                            <div class="c-compatibility__acordeon-tab  js-acordeon-tab active">';
                            foreach($new_all_value as $accordion){
                                $template .= 
                                    '<p>'.$accordion['compatibility'].'</p>
                                ';
                            }
                                
                           $template .= '</div>
                           <div class="c-compatibility__acordeon-tab js-acordeon-tab ">
                                ';
                                
                            foreach($new_ios_value as $accordion){
                                $template .= 
                                    '<p>'.$accordion['compatibility'].'</p>
                                ';
                            }
                            $template.= '
                            </div>
                            <div class="c-compatibility__acordeon-tab js-acordeon-tab ">';                            
                            foreach($new_android_value as $accordion){
                                $template .= 
                                    '<p>'.$accordion['compatibility'].'</p>
                                ';
                            }
                            $template .= '</div>                        
                        </div>
                    </div>
                </div>
            </div>
            ';
            


            return $template;             

        }

    }

    new vcZimCompatibility();

}
?>