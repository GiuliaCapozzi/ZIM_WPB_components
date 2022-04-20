<?php
//incluir functions.js

function functions_js(){

	wp_enqueue_script('functions.js',

					get_stylesheet_directory_uri().'/js/functions.js',
					array(),
					'1.0.0'
					);

}

add_action('wp_enqueue_scripts', 'functions_js');

//incluir owl-carousel.js

function owl_js(){

	wp_enqueue_script('owl_js',
					  get_stylesheet_directory_uri().'/js/owl.carousel.min.js',
					  array('jquery'),
					  '1.0.0',
					  true
					);

}

add_action('wp_enqueue_scripts', 'owl_js');

//owl carousel enqueue scripts and styles:

function css_owl(){

	wp_enqueue_script('css_owl',
	get_stylesheet_directory_uri().'/css/owl.carousel.min.css',
	array(),
	'1.0.0',
	true	
		);
}

add_action('wp_enqueue_scripts', 'css_owl');

/* Custom functions code goes here. */
add_shortcode('get_info', 'obtener_info');

function obtener_info($atts) {
    $a = shortcode_atts( array(
        'country' => 'DEU',
        'country_text' => 'Germany'
    ), $atts );

    $url = 'https://zimconnections-api.live/db/plans/'.$a['country'];

    $response = wp_remote_get($url);

    if(is_wp_error($response)) {
        error_log("Error:".$response->get_error_message());

        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);
    //var_dump($data);
    
    $duration_array = array();

    for($i = 0; $i < count($data); $i++) {
        if(!in_array($data[$i]->duration,$duration_array)) {
            array_push($duration_array,(int)$data[$i]->duration);
        }
    }

    $index = 0;
    $str = '<p class="c-nav__title">'.$a['country_text'].'</p>
    <nav class="c-nav">';
    foreach($duration_array as $duration) {
        $day = ($duration == 1) ? 'day' : 'days';
        $data_open = $day.$duration;
        $is_selected = ($index == 0) ? 'is-selected' : '';

        $str .= '<button class="'.$is_selected.' c-nav__button js-open-table" data-open="'.$data_open.'">'.$duration.' '.$day.'</button>';

        $index++;
    }
    $str .= '</nav>';

    for($i = 0; $i < count($duration_array); $i++) {
        $day = ($duration_array[$i] == 1) ? 'day' : 'days';
        $data_open = $day.$duration_array[$i];
        $is_visible = ($i == 0) ? 'is-visible' : '';
        $str .= '<div class="c-table-container '.$is_visible.' js-table '.$data_open.'">            
                <table class="c-table">
                <thead>
                    <th>Name</th>
                    <th>Data</th>
                    <th>Price</th>
                </thead>';
        for($j = 0; $j < count($data); $j++) {
            if($data[$j]->duration == $duration_array[$i]) {
                $str .= '<tbody>
                            <tr>
                              <td>'.$data[$j]->name.'</td>
                              <td>'.$data[$j]->data.' '.$data[$j]->data_unit.'</td>
                              <td>                              
                                <select>
                                    <option>'.$data[$j]->price_gbp.' GBP</option>
                                    <option>'.$data[$j]->price_usd.' USD</option>
                                    <option>'.$data[$j]->price_eur.' €</option>
                                </select>
                              </td>
                            </tr>
                        </tbody>';
            }
        }
        $str .= '</table>
                </div>';
    }

    return $str;   

}

add_shortcode('get_plans', 'obtener_planes');

function obtener_planes() {
    $url = 'https://zimconnections-api.live/truphone/products/FRA';
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI2MWM0ODk2MDU0NjQ3OWJmNmVlOGEzZDAiLCJpYXQiOjE2NDMwMjMzOTJ9.kJG4DksJ0ju9_HAddmBsx-pZlPcW0K9uAFUqike8_Tg';

    $response = wp_safe_remote_get( 
        $url, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

     $template = "<table>
                    <thead>
                      <th>ID</th>
                      <th>name</th>
                      <th>price</th>
                      <th>duration</th>
                    </thead>
                     {data}
                </table>";
    
    if($data) {
        $str = '<tbody>';
        foreach($data as $plan) {
            $str .= '<tr>';
            $str .= "<td>
                       $plan->id
                    </td>";
            $str.= "<td>
                ".$plan->name."
            </td>";
            $str.= "<td>
                ".$plan->price." ".$plan->price_currency."
            </td>";
            $str.= "<td>
                ".$plan->duration." ".$plan->duration_unit."
            </td>";
            $str .= '</tr>';
        }
        $str .= '</tbody>';
    }

    $html = str_replace('{data}', $str, $template);

    return $html; 

    //var_dump($data);

}

add_shortcode('var_dump', 'get_dump');
function get_dump() {
    
    $url = 'https://zimconnections-api.live/truphone/products/DEU';
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI2MWM0ODk2MDU0NjQ3OWJmNmVlOGEzZDAiLCJpYXQiOjE2NDMwMjMzOTJ9.kJG4DksJ0ju9_HAddmBsx-pZlPcW0K9uAFUqike8_Tg';

    $response = wp_safe_remote_get( 
        $url, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);
    

    var_dump($data);

}

require_once( 'vc-components/vc-soda-blockquote.php' ); 
require_once( 'vc-components/vc-zim-image-card.php' ); 
require_once( 'vc-components/vc-zim-download-app.php' ); 
require_once( 'vc-components/vc-zim-hero.php' ); 
require_once( 'vc-components/vc-zim-app-stepper.php' );
require_once( 'vc-components/vc-zim-title.php' ); 
require_once( 'vc-components/vc-zim-opinion.php' ); 
require_once( 'vc-components/vc-zim-download-app.php' ); 
require_once( 'vc-components/vc-zim-hero-secondary.php' ); 
require_once( 'vc-components/vc-zim-accepted-payments.php' ); 
require_once( 'vc-components/vc-zim-hero-terciary.php' ); 
require_once( 'vc-components/vc-zim-team-card.php' ); 
require_once( 'vc-components/vc-zim-offices.php' ); 