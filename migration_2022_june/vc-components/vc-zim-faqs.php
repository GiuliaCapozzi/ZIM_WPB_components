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
if ( ! class_exists( 'vcZimFaqs' ) ) {

    class vcZimFaqs extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_faqs', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('ZIM FAQs', 'sodawebmedia'),
                'base'          => 'vc_zim_faqs',
                'description'  	=> __( 'Acordeon for ZIM Frequent Answered Questions viz.', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    

                    array(
                        'type' => 'param_group',
                        'param_name' => 'category',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'name' => 'Category name',
                                'heading' => __('Category name', 'category-name'),
                                'param_name' => 'category-name',
                            ),
                            array(
                                'type' => 'param_group',
                                'param_name' => 'questions',
                                'params' => array(
                                    array(
                                        'type' => 'textfield',
                                        'name' => 'question',
                                        'heading' => __('Question', 'question'),
                                        'param_name' => 'question',
                                    ),
                                    array(
                                        'type' => 'textfield',
                                        'name' => 'answer',
                                        'heading' => __('Answer', 'answer'),
                                        'param_name' => 'answer',
                                    )
                                )
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
                'category'   => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));     

            $all_values = vc_param_group_parse_atts($atts['category']);
            $new_all_value = array();
            foreach($all_values as $data){
                $new_line = $data;
                $new_line['category-name'] = isset($new_line['category-name']) ? $new_line['category-name'] : '';

                $new_all_value[] = $new_line;
            }

            
            $template = '<div class="c-faqs">
            <nav class="c-faqs__nav">';
            $count = 0;
            foreach($new_all_value as $accordion){
                 $data_tab = str_replace(' ','_',$accordion['category-name']);
                 $selected_class = ($count == 0) ? 'active' : '';
                 $template .= 
                    '<button class="'.$selected_class.' c-support-card js-open-faq-tab" data-tab="'.$data_tab.'">
                        <p class="c-support-card__text">
                        '.$accordion['category-name'].'
                        </p>
                    </button>';
             $count++;
            }

            $template .= '</nav>
                <section class="c-faqs__tabs">';
                
                $count_p = 0;
                foreach($all_values as $params) {
                    $q = vc_param_group_parse_atts($params['questions']);
                    $c_n = $params['category-name'];
                    $data_tab = str_replace(' ','_',$c_n);
                    $visible_class = ($count_p == 0) ? 'visible' : '';

                    $template .= '<div class="c-faqs__tab js-faqs-tab '.$visible_class.'" data-tab="'.$data_tab.'">
                    <h3 class="c-faqs__tabs-title c-title">
                        <span class="c-title__title js-faqs-title">'.$c_n.'</span>
                    </h3>';
                    foreach($q as $quest) {
                        $template .= '<div class="c-support-block js-support-block">
                            <div class="c-support-block__wrapper">
                                <button class="c-support-block__text js-open-faq js-chevron-button">
                                    <span> '.$quest['question'].' </span>
                                    <span class="c-support-block__icon js-faq-icon">
                                        <svg width="14" height="20" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.19961 0L0.849609 2.35L8.48294 10L0.849609 17.65L3.19961 20L13.1996 10L3.19961 0Z" fill="black"/>
                                        </svg>
                                    </span>
                                </button>
                            
                            </div>
                            <div class="c-support-block__answer js-faq-answer" data-open="false">
                                '.$quest['answer'].'
                            </div>
                        </div>';
                    }
                    $template .= '</div>';

                    $count_p++;
                }
                    
            $template .= '</section>';

            return $template;             

        }

    }

    new vcZimFaqs();

}
?>