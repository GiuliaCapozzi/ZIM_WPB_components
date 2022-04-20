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
if ( ! class_exists( 'vcZimTeamCard' ) ) {

    class vcZimTeamCard extends WPBakeryShortCode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'vc_zim_team_card', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => __('Team card', 'sodawebmedia'),
                'base'          => 'vc_zim_team_card',
                'description'  	=> __( 'Individual team members card with linkedin link', 'sodawebmedia' ),
                'category'      => __( 'ZIM Modules', 'sodawebmedia'),                
                'params' => array(    
                      

                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Name text', 'sodawebmedia' ),
                        'param_name'    => 'title-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Name and surname of team member.', 'sodawebmedia' ),
                    ),  
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Bio text', 'sodawebmedia' ),
                        'param_name'    => 'bio-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the member bio text.', 'sodawebmedia' ),
                    ), 
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Role', 'sodawebmedia' ),
                        'param_name'    => 'role-text',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the member role in ZIM.', 'sodawebmedia' ),
                    ), 
                    
                    array(
                        'type'          => 'textfield',
                        'holder'        => 'div',
                        'heading'       => __( 'Linkedin link', 'sodawebmedia' ),
                        'param_name'    => 'linkedin',
                        'value'         => __( '', 'sodawebmedia' ),
                        'description'   => __( 'Add the member linkedin link.', 'sodawebmedia' ),
                    ),  
                    
                    array(
                        "type" => "attach_image",
                        "class" => "",
                        "heading" => __( "Team member image", 'sodawebmedia' ),
                        "param_name" => "member-image",
                        "description" => __( "Add member image. It should be a rounded image", 'sodawebmedia' ),                                                
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
                'bio-text' => '',
                'member-image' => '',
                'role-text' => '',
                'linkedin' => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));


            //Content 
            $title_text  = esc_attr($atts['title-text']);
            $bio_text = esc_attr($atts['bio-text']);
            $role_text = esc_attr($atts['role-text']);
            $linkedin = esc_attr($atts['linkedin']);
            
            $img_card     =  esc_attr($atts['member-image']);
            $img = wpb_getImageBySize( array(
				'attach_id' => (int) $img_card,
				'thumb_size' => 'full',
			) );

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            


            $output = '
            <div class="c-team-card">
                '.$img['thumbnail'].'
                <div class="c-team-card__text-container">
                    <p class="c-team-card__name">
                        '.$title_text.'
                    </p>
                    <p class="c-team-card__role">
                        '.$role_text.'
                    </p>
                    <p class="c-team-card__text">
                       '.$bio_text.'
                    </p>
                </div>
                <div class="c-team-card__socials">
                    <a href="'.$linkedin.'" target="_blank">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.66608 0.232422C1.40021 0.232422 0.374023 1.24819 0.374023 2.50121C0.374023 3.75422 1.40021 4.76999 2.66608 4.76999C3.93195 4.76999 4.95814 3.75422 4.95814 2.50121C4.95814 1.24819 3.93195 0.232422 2.66608 0.232422ZM1.62424 2.50121C1.62424 1.93165 2.09069 1.46994 2.66608 1.46994C3.24147 1.46994 3.70792 1.93165 3.70792 2.50121C3.70792 3.07076 3.24147 3.53247 2.66608 3.53247C2.09069 3.53247 1.62424 3.07076 1.62424 2.50121Z" fill="black"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.374023 5.80125C0.374023 5.45952 0.653893 5.18249 0.99913 5.18249H4.33303C4.67827 5.18249 4.95814 5.45952 4.95814 5.80125V16.5264C4.95814 16.8681 4.67827 17.1452 4.33303 17.1452H0.99913C0.653893 17.1452 0.374023 16.8681 0.374023 16.5264V5.80125ZM1.62424 6.42001V15.9077H3.70792V6.42001H1.62424Z" fill="black"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.20835 5.80125C6.20835 5.45952 6.48822 5.18249 6.83346 5.18249H10.1674C10.5126 5.18249 10.7925 5.45952 10.7925 5.80125V6.15933L11.1553 6.00543C11.779 5.74083 12.446 5.57491 13.1206 5.5142C15.4334 5.30608 17.4603 7.10509 17.4603 9.41501V16.5264C17.4603 16.8681 17.1804 17.1452 16.8352 17.1452H13.5013C13.156 17.1452 12.8762 16.8681 12.8762 16.5264V10.7513C12.8762 10.4778 12.7664 10.2155 12.571 10.0221C12.3756 9.82871 12.1106 9.72006 11.8343 9.72006C11.558 9.72006 11.293 9.82871 11.0976 10.0221C10.9022 10.2155 10.7925 10.4778 10.7925 10.7513V16.5264C10.7925 16.8681 10.5126 17.1452 10.1674 17.1452H6.83346C6.48822 17.1452 6.20835 16.8681 6.20835 16.5264V5.80125ZM7.45856 6.42001V15.9077H9.54225V10.7513C9.54225 10.1496 9.78373 9.57253 10.2136 9.14705C10.6434 8.72157 11.2264 8.48254 11.8343 8.48254C12.4422 8.48254 13.0252 8.72157 13.455 9.14705C13.8849 9.57253 14.1264 10.1496 14.1264 10.7513V15.9077H16.2101V9.41501C16.2101 7.84388 14.8257 6.60339 13.2338 6.74664C12.6886 6.79569 12.1497 6.92993 11.6477 7.14288L10.4136 7.66643C10.2205 7.74836 9.99866 7.72876 9.82324 7.61427C9.64782 7.49977 9.54225 7.30569 9.54225 7.0977V6.42001H7.45856Z" fill="black"/>
                        </svg>
                    </a>
                </div>
            </div>   
            ';

            return $output;             

        }

    }

    new vcZimTeamCard();

}
?>