<?php
//incluir functions.js

function functions_js(){

	wp_enqueue_script('functions.min.js',

					get_stylesheet_directory_uri().'/js/functions.min.js',
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

/*individual plan shortcode */

add_shortcode('get_zone_plan', 'get_dump');

function get_dump($att) {
	$att = shortcode_atts(array(
		'zone' => 'DEU'
	),$att);
    
    $url = 'https://zimconnections-api.live/truphone/products/'.$att['zone'];

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
    

    //var_dump($data);

    $template = '';

    $template .= '<div class="c-plans__local-country visible">';
    foreach($data as $plan) {                   
        $template .=  '
            <div class="c-plans-card">
                <div class="c-plans-card__top">
                    <p class="c-plans-card__title">
                        '.$plan->data.' '.$plan->data_unit.'
                    </p>
                    <p class="c-plans-card__logo">
                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                    </p>
                </div>
                <div class="c-plans-card__text">
                    <p class="c-plans-card__duration">'.$plan->duration.' '.$plan->duration_unit.'</p>
                    <p class="c-plans-card__price">'.$plan->price_currency.' '.$plan->price.'</p>
                </div>
                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
            </div>';
    }
    
    $template .= '
</div> <!--fin -->';   

return $template;

}

/* individual plan shortcode */

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
    //url usa
    $url = 'https://zimconnections-api.live/truphone/products/USAPLUS';
    //url europe
    $url_europe = 'https://zimconnections-api.live/truphone/products/EUROPE';
    $url_apac = 'https://zimconnections-api.live/truphone/products/APAC';
    $url_global = 'https://zimconnections-api.live/truphone/products/GLOBAL';

    //local countries
    //EUROPE    
    $url_austria = 'https://zimconnections-api.live/truphone/products/AUT';
    $url_belgium = 'https://zimconnections-api.live/truphone/products/BEL';
    $url_croatia = 'https://zimconnections-api.live/truphone/products/HRV';
    $url_cyprus = 'https://zimconnections-api.live/truphone/products/CYP';
    $url_c_r = 'https://zimconnections-api.live/truphone/products/CZE';
    $url_germay = 'https://zimconnections-api.live/truphone/products/DEU';
    $url_greece = 'https://zimconnections-api.live/truphone/products/GRC';
    $url_hungary = 'https://zimconnections-api.live/truphone/products/HUN';
    $url_ireland = 'https://zimconnections-api.live/truphone/products/IRL';
    $url_germany = 'https://zimconnections-api.live/truphone/products/DEU';
    $url_italy = 'https://zimconnections-api.live/truphone/products/ITA';
    $url_ntr = 'https://zimconnections-api.live/truphone/products/NLD';
    $url_poland = 'https://zimconnections-api.live/truphone/products/POL';
    $url_portugal = 'https://zimconnections-api.live/truphone/products/PRT';
    $url_sweden = 'https://zimconnections-api.live/truphone/products/SWE';
    $url_switz = 'https://zimconnections-api.live/truphone/products/CHE';
    $url_uk = 'https://zimconnections-api.live/truphone/products/GBR';
    $url_fr = 'https://zimconnections-api.live/truphone/products/FRA';
    $url_esp = 'https://zimconnections-api.live/truphone/products/ESP';
    $url_usa = 'https://zimconnections-api.live/truphone/products/USA';
    $hong_k = 'https://zimconnections-api.live/truphone/products/HKG';
    $israel = 'https://zimconnections-api.live/truphone/products/ISR';
    $url_japan = 'https://zimconnections-api.live/truphone/products/JPN';
    $url_australia = 'https://zimconnections-api.live/truphone/products/AUS';
    $url_norway = 'https://zimconnections-api.live/truphone/products/NOR';

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

    $response_europe = wp_safe_remote_get( 
        $url_europe, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_apac = wp_safe_remote_get( 
        $url_apac, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_global = wp_safe_remote_get( 
        $url_global, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    //individual countries
    //EUROPE   

    $response_austria = wp_safe_remote_get( 
        $url_austria, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );
    $response_belgium = wp_safe_remote_get( 
        $url_belgium, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_croatia = wp_safe_remote_get( 
        $url_croatia, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_cyprus = wp_safe_remote_get( 
        $url_cyprus, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_c_r = wp_safe_remote_get( 
        $url_c_r, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_germany = wp_safe_remote_get( 
        $url_germany, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_greece = wp_safe_remote_get( 
        $url_greece, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_hungary = wp_safe_remote_get( 
        $url_hungary, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_ireland = wp_safe_remote_get( 
        $url_ireland, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_italy = wp_safe_remote_get( 
        $url_italy, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_ntr = wp_safe_remote_get( 
        $url_ntr, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_norway = wp_safe_remote_get( 
        $url_norway, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_poland = wp_safe_remote_get( 
        $url_poland, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_portugal = wp_safe_remote_get( 
        $url_portugal, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_sweden = wp_safe_remote_get( 
        $url_sweden, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_switz = wp_safe_remote_get( 
        $url_switz, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_uk = wp_safe_remote_get( 
        $url_uk, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_fr = wp_safe_remote_get( 
        $url_fr, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_esp = wp_safe_remote_get( 
        $url_esp, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_usa = wp_safe_remote_get( 
        $url_usa, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_hong_k = wp_safe_remote_get( 
        $hong_k, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_israel = wp_safe_remote_get( 
        $israel, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_japan = wp_safe_remote_get( 
        $url_japan, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $response_australia = wp_safe_remote_get( 
        $url_australia, 
        array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'      => $token,
            )
        ),
    );

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    //europe
    $body_europe = wp_remote_retrieve_body($response_europe);
    $data_europe = json_decode($body_europe);

    //apac
    $body_apac = wp_remote_retrieve_body($response_apac);
    $data_apac = json_decode($body_apac);

    //global 
    $body_global = wp_remote_retrieve_body($response_global);
    $data_global = json_decode($body_global);

    //individual countries
    //EUROPE
    $body_austria = wp_remote_retrieve_body($response_austria);
    $data_austria = json_decode($body_austria);

    
    $body_belgium = wp_remote_retrieve_body($response_belgium);
    $data_belgium = json_decode($body_belgium);

    $body_croatia = wp_remote_retrieve_body($response_croatia);
    $data_croatia = json_decode($body_croatia);

    $body_cyprus = wp_remote_retrieve_body($response_cyprus);
    $data_cyprus = json_decode($body_cyprus);

    $body_c_r = wp_remote_retrieve_body($response_c_r);
    $data_c_r = json_decode($body_c_r);

    $body_germany = wp_remote_retrieve_body($response_germany);
    $data_germany = json_decode($body_germany);

    $body_greece = wp_remote_retrieve_body($response_greece);
    $data_greece = json_decode($body_greece);

    $body_hungary = wp_remote_retrieve_body($response_hungary);
    $data_hungary = json_decode($body_hungary);

    $body_ireland = wp_remote_retrieve_body($response_ireland);
    $data_ireland = json_decode($body_ireland);

    $body_italy = wp_remote_retrieve_body($response_italy);
    $data_italy = json_decode($body_italy);

    $body_ntr = wp_remote_retrieve_body($response_ntr);
    $data_ntr = json_decode($body_ntr);

    $body_norway = wp_remote_retrieve_body($response_norway);
    $data_norway = json_decode($body_norway);

    $body_poland = wp_remote_retrieve_body($response_poland);
    $data_poland = json_decode($body_poland);

    $body_portugal = wp_remote_retrieve_body($response_portugal);
    $data_portugal = json_decode($body_portugal);

    $body_sweden = wp_remote_retrieve_body($response_sweden);
    $data_sweden = json_decode($body_sweden);

    $body_switz = wp_remote_retrieve_body($response_switz);
    $data_switz = json_decode($body_switz);

    $body_uk = wp_remote_retrieve_body($response_uk);
    $data_uk = json_decode($body_uk);

    $body_fr = wp_remote_retrieve_body($response_fr);
    $data_fr = json_decode($body_fr);

    $body_esp = wp_remote_retrieve_body($response_esp);
    $data_esp = json_decode($body_esp);

    $body_usa = wp_remote_retrieve_body($response_usa);
    $data_usa = json_decode($body_usa);

    $body_hong_k = wp_remote_retrieve_body($response_hong_k);
    $data_hong_k = json_decode($body_hong_k);

    $body_israel = wp_remote_retrieve_body($response_israel);
    $data_israel = json_decode($body_israel);

    $body_japan = wp_remote_retrieve_body($response_japan);
    $data_japan = json_decode($body_japan);

    $body_australia = wp_remote_retrieve_body($response_australia);
    $data_australia = json_decode($body_australia);

    $template = '
    <div class="c-plans">
        <div class="c-title">
            <p class="c-title__title">
                ZIM plans and coverage areas
            </p>
        </div>
        <div class="c-plans__wrapper">
            <nav class="c-plans__nav">
            <a href="#" class="js-area-button active" data-tab="global">Global</a>  
            <a href="#" class="js-area-button" data-tab="regional">Regional</a>  
            <a href="#" class="js-area-button" data-tab="local">Local</a>  
            </nav>
            <div class="c-plans__tabs js-area-tab visible" data-tab="global">
                <div class="c-plans__tabs-wrapper">';    
               
                foreach($data_apac as $plan_apac) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_apac->data.' '.$plan_apac->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_apac->duration.' '.$plan_apac->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_apac->price_currency.' '.$plan_apac->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }                    
            $template .= '<!--  <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                20 GB
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">4 weeks</p>
                            <p class="c-plans-card__price">€ 20</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                20 GB
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">4 weeks</p>
                            <p class="c-plans-card__price">€ 20</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                20 GB
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">4 weeks</p>
                            <p class="c-plans-card__price">€ 20</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                20 GB
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">4 weeks</p>
                            <p class="c-plans-card__price">€ 20</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                20 GB
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">4 weeks</p>
                            <p class="c-plans-card__price">€ 20</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>-->
                </div>
            </div>
            <div class="c-plans__tabs js-area-tab" data-tab="regional">
                <nav class="c-plans__subnav">
                    <a href="#" class="active js-regional-button" data-tab="europe-regional">Europe</a>
                    <a href="#" class="js-regional-button" data-tab="usa-regional">USA</a>
                    <a href="#" class="js-regional-button" data-tab="asia-pacific-regional">Asia Pacific</a>
                </nav>
                <div class="c-title mb-4">
                    <h2 class="c-title__title">Available plans <span class="js-regional-name">Europe</span></h2>
                </div>
                <div class="c-plans__tabs-wrapper js-regional-tab visible" data-tab="europe-regional">';     
                foreach($data_europe as $plan_eu) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_eu->data.' '.$plan_eu->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_eu->duration.' '.$plan_eu->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_eu->price_currency.' '.$plan_eu->price.'</p>
                            </div>
                            
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                $template .=  ' </div>
                <div class="c-plans__tabs-wrapper js-regional-tab" data-tab="usa-regional">';
                foreach($data as $plan) {
                    $template .= '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan->data.' '.$plan->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan->duration.' '.$plan->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan->price_currency.' '.$plan->price.'</p>
                            </div>
                            
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                $template .= '</div>
                <div class="c-plans__tabs-wrapper js-regional-tab" data-tab="asia-pacific-regional">';
                foreach($data_apac as $plan_apac) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_apac->data.' '.$plan_apac->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_apac->duration.' '.$plan_apac->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_apac->price_currency.' '.$plan_apac->price.'</p>
                            </div>
                            
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }

                 $template .= '
                </div>
            </div>
            <div class="c-plans__tabs js-area-tab" data-tab="local">
                <nav class="c-plans__subnav">
                    <a href="#" class="js-local-button active" data-tab="europe">Europe</a>
                    <a href="#" class="js-local-button" data-tab="america">America</a>
                    <a href="#" class="js-local-button" data-tab="asia">Asia</a>
                    <a href="#" class="js-local-button" data-tab="oceania">Oceania</a>
                    <a href="#" class="js-local-button" data-tab="africa">Africa</a>
                </nav>        
                <div class="c-title mb-4">
                    <h2 class="c-title__title">Plans in <span class="js-local-name">Europe</span></h2>
                </div>
                <div class="c-plans__tabs-carousels">
                    <div class="c-plans-carousel js-local-tab visible" data-tab="europe">
                        <div class="c-plans-carousel__carousel">
                            <div class="owl-carousel owl-theme js-plans-carousel">
                                <a href="#plansIn" class="item js-plans-country" data-country="austria" data-name="Austria">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/aut.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Austria</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="belgium" data-name="Belgium">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/bel.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Belgium</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="croatia" data-name="Croatia">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/hrv.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Croatia</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="cyprus" data-name="Cyprus">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/cyp.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Cyprus</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="c_r" data-name="Czech Republic">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/cze.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Czech Republic</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="germany" data-name="Germany">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/deu.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Germany</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" data-country="greece" class="item js-plans-country" data-name="Greece">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/grc.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Greece</p>
                                    </div>
                                </a>   
                                <a href="#plansIn" class="item js-plans-country" data-country="hungary" data-name="Hungary">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/hun.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Hungary</p>
                                    </div>
                                </a>

                                <a href="#plansIn" class="item js-plans-country" data-country="ireland" data-name="Ireland">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/irl.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Ireland</p>
                                    </div>
                                </a>   
                                <a href="#plansIn" class="item js-plans-country" data-country="italy" data-name="Italy">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/ita.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Italy</p>
                                    </div>
                                </a>    
                                <a href="#plansIn" class="item js-plans-country" data-country="ntr" data-name="Netherlands">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/nld.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Netherlands</p>
                                    </div>
                                </a>  
                                <a href="#plansIn" class="item js-plans-country" data-country="norway" data-name="Norway">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/nor.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Norway</p>
                                    </div>
                                </a>     
                                <a href="#plansIn" class="item js-plans-country" data-country="poland" data-name="Poland">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/pol.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Poland</p>
                                    </div>
                                </a>       
                                <a href="#plansIn" class="item js-plans-country" data-country="portugal" data-name="Portugal">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/prt.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Portugal</p>
                                    </div>
                                </a>         
                                <a href="#plansIn" class="item js-plans-country" data-name="Sweden">
                                    <div class="c-plans-carousel-card" data-country="sweden">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/swe.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Sweden</p>
                                    </div>
                                </a>   
                                <a href="#plansIn" class="item js-plans-country" data-country="switz" data-name="Switzerland">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/che.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Switzerland</p>
                                    </div>
                                </a>   
                                <a href="#plansIn" class="item js-plans-country" data-country="uk" data-name="UK">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/gbr.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">UK</p>
                                    </div>
                                </a>   
                                <a href="#plansIn" class="item js-plans-country" data-country="fr" data-name="France">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/fra.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">France</p>
                                    </div>
                                </a>  
                                 
                                <a href="#plansIn" class="item js-plans-country" data-country="esp" data-name="Spain">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/esp.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Spain</p>
                                    </div>
                                </a> 
                                 
                                <a href="#plansIn" class="item js-no-active-card" data-country="albania">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/alb.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Albania</p>
                                    </div>
                                </a>   
                            </div>
                        </div>
                    </div> 
                    <div class="c-plans-carousel js-local-tab" data-tab="america">
                        <div class="c-plans-carousel__carousel">
                            <div class="owl-carousel owl-theme js-plans-carousel">        
                                <a href="#plansIn" class="item js-plans-country" data-name="USA">
                                    <div class="c-plans-carousel-card" data-country="usa">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/usa.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">USA</p>
                                    </div>
                                </a> 
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/per.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Peru</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/br.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Brazil</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/arg.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Argentina</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/abw.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Aruba</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/ury.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Uruguay</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/tto.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Trinidad</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/sur.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Suriname</p>
                                    </div>
                                </a>     
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/jam.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Jamaica</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/aia.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Anguilla</p>
                                    </div>
                                </a>          
                                
                            </div>
                        </div>
                    </div> 
                    <div class="c-plans-carousel js-local-tab" data-tab="asia">
                        <div class="c-plans-carousel__carousel">
                            <div class="owl-carousel owl-theme js-plans-carousel">
                                <a href="#plansIn" class="item js-plans-country" data-name="Hong Kong">
                                    <div class="c-plans-carousel-card" data-country="hong_k">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/hkg.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Hong Kong</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="israel" data-name="Israel">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/isr.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Israel</p>
                                    </div>
                                </a>      
                                <a href="#plansIn" class="item js-plans-country" data-country="japan" data-name="Japan">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/jpn.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Japan</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/chn.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">China</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/ind.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">India</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/idn.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Indonesia</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/mys.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Malaysia</p>
                                    </div>
                                </a>       
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/sgp.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Singapore</p>
                                    </div>
                                </a>        
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/twn.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Taiwan </p>
                                    </div>
                                </a>          
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/tha.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Thailand</p>
                                    </div>
                                </a>     
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/kor.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">South Korea</p>
                                    </div>
                                </a>              
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/tur.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Turkey</p>
                                    </div>
                                </a>      
                                
                            </div>
                        </div>
                    </div>  
                    <div class="c-plans-carousel js-local-tab" data-tab="oceania">
                        <div class="c-plans-carousel__carousel">
                            <div class="owl-carousel owl-theme js-plans-carousel">
                                <a href="#plansIn" class="item js-plans-country" data-country="australia"  data-name="Australia">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/aus.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Australia</p>
                                    </div>
                                </a>               
                                
                            </div>
                        </div>
                    </div>
                    <div class="c-plans-carousel js-local-tab" data-tab="africa">
                        <div class="c-plans-carousel__carousel">
                            <div class="owl-carousel owl-theme js-plans-carousel">
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/zaf.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">South Africa</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/ken.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Kenya</p>
                                    </div>
                                </a>      
                                <a href="#" class="item  js-no-active-card">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://zimconnections-api.live/destinationsImgs/gha.jpg" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Ghana</p>
                                    </div>
                                </a>              
                                
                            </div>
                        </div>
                    </div>     
                </div>        
                <div class="c-plans__local-countries" id="plansIn">        
                    <div class="c-title">
                        <h2 class="c-title__title">Plans in <span class="js-local-name">Austria</span></h2>
                    </div>

                    <div class="c-plans__local-country js-local-country-tab visible" data-country="austria">';
                        foreach($data_austria as $plan_austria) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_austria->data.' '.$plan_austria->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_austria->duration.' '.$plan_austria->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_austria->price_currency.' '.$plan_austria->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin austria-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="belgium">';
                        foreach($data_belgium as $plan_belgium) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_belgium->data.' '.$plan_belgium->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_belgium->duration.' '.$plan_belgium->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_belgium->price_currency.' '.$plan_belgium->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin belgium-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="croatia">';
                        foreach($data_croatia as $plan_croatia) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_croatia->data.' '.$plan_croatia->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_croatia->duration.' '.$plan_croatia->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_croatia->price_currency.' '.$plan_croatia->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin croatia-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="cyprus">';
                        foreach($data_cyprus as $plan_cyprus) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_cyprus->data.' '.$plan_cyprus->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_cyprus->duration.' '.$plan_cyprus->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_cyprus->price_currency.' '.$plan_cyprus->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin cyprus-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="c_r">';
                        foreach($data_c_r as $plan_c_r) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_c_r->data.' '.$plan_c_r->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_c_r->duration.' '.$plan_c_r->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_c_r->price_currency.' '.$plan_c_r->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin c_r-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="germany">';
                        foreach($data_germany as $plan_germany) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_germany->data.' '.$plan_germany->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_germany->duration.' '.$plan_germany->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_germany->price_currency.' '.$plan_germany->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin germany-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="greece">';
                        foreach($data_greece as $plan_greece) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_greece->data.' '.$plan_greece->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_greece->duration.' '.$plan_greece->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_greece->price_currency.' '.$plan_greece->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin greece-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="hungary">';
                        foreach($data_hungary as $plan_hungary) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_hungary->data.' '.$plan_hungary->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_hungary->duration.' '.$plan_hungary->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_hungary->price_currency.' '.$plan_hungary->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin hungary-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="ireland">';
                        foreach($data_ireland as $plan_ireland) {                   
                            $template .=  '
                                <div class="c-plans-card">
                                    <div class="c-plans-card__top">
                                        <p class="c-plans-card__title">
                                            '.$plan_ireland->data.' '.$plan_ireland->data_unit.'
                                        </p>
                                        <p class="c-plans-card__logo">
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                        </p>
                                    </div>
                                    <div class="c-plans-card__text">
                                        <p class="c-plans-card__duration">'.$plan_ireland->duration.' '.$plan_ireland->duration_unit.'</p>
                                        <p class="c-plans-card__price">'.$plan_ireland->price_currency.' '.$plan_ireland->price.'</p>
                                    </div>
                                    <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                                </div>';
                        }
                        
                        $template .= '
                    </div> <!--fin ireland-->

                    <div class="c-plans__local-country js-local-country-tab" data-country="italy">';
                    foreach($data_italy as $plan_italy) {                   
                        $template .=  '
                            <div class="c-plans-card">
                                <div class="c-plans-card__top">
                                    <p class="c-plans-card__title">
                                        '.$plan_italy->data.' '.$plan_italy->data_unit.'
                                    </p>
                                    <p class="c-plans-card__logo">
                                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                    </p>
                                </div>
                                <div class="c-plans-card__text">
                                    <p class="c-plans-card__duration">'.$plan_italy->duration.' '.$plan_italy->duration_unit.'</p>
                                    <p class="c-plans-card__price">'.$plan_italy->price_currency.' '.$plan_italy->price.'</p>
                                </div>
                                
                                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                            </div>';
                    }
                    
                    $template .= '
                </div> <!--fin italy-->

                <div class="c-plans__local-country js-local-country-tab" data-country="ntr">';
                    foreach($data_ntr as $plan_ntr) {                   
                        $template .=  '
                            <div class="c-plans-card">
                                <div class="c-plans-card__top">
                                    <p class="c-plans-card__title">
                                        '.$plan_ntr->data.' '.$plan_ntr->data_unit.'
                                    </p>
                                    <p class="c-plans-card__logo">
                                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                    </p>
                                </div>
                                <div class="c-plans-card__text">
                                    <p class="c-plans-card__duration">'.$plan_ntr->duration.' '.$plan_ntr->duration_unit.'</p>
                                    <p class="c-plans-card__price">'.$plan_ntr->price_currency.' '.$plan_ntr->price.'</p>
                                </div>
                                
                                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                            </div>';
                    }
                    
                    $template .= '
                </div> <!--fin ntr-->

                <div class="c-plans__local-country js-local-country-tab" data-country="norway">';
                foreach($data_norway as $plan_norway) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_norway->data.' '.$plan_norway->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_norway->duration.' '.$plan_norway->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_norway->price_currency.' '.$plan_norway->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                
                $template .= '
            </div> <!--fin norway-->

            <div class="c-plans__local-country js-local-country-tab" data-country="poland">';
                foreach($data_poland as $plan_poland) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_poland->data.' '.$plan_poland->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_poland->duration.' '.$plan_poland->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_poland->price_currency.' '.$plan_poland->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                
                $template .= '
            </div> <!--fin poland-->

            <div class="c-plans__local-country js-local-country-tab" data-country="portugal">';
                foreach($data_portugal as $plan_portugal) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_portugal->data.' '.$plan_portugal->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_portugal->duration.' '.$plan_portugal->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_portugal->price_currency.' '.$plan_portugal->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                
                $template .= '
            </div> <!--fin portugal-->

            <div class="c-plans__local-country js-local-country-tab" data-country="sweden">';
                foreach($data_sweden as $plan_sweden) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_sweden->data.' '.$plan_sweden->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_sweden->duration.' '.$plan_sweden->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_sweden->price_currency.' '.$plan_sweden->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                
                $template .= '
            </div> <!--fin sweden-->

            <div class="c-plans__local-country js-local-country-tab" data-country="switz">';
                foreach($data_switz as $plan_switz) {                   
                    $template .=  '
                        <div class="c-plans-card">
                            <div class="c-plans-card__top">
                                <p class="c-plans-card__title">
                                    '.$plan_switz->data.' '.$plan_switz->data_unit.'
                                </p>
                                <p class="c-plans-card__logo">
                                    <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                                </p>
                            </div>
                            <div class="c-plans-card__text">
                                <p class="c-plans-card__duration">'.$plan_switz->duration.' '.$plan_switz->duration_unit.'</p>
                                <p class="c-plans-card__price">'.$plan_switz->price_currency.' '.$plan_switz->price.'</p>
                            </div>
                            <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                        </div>';
                }
                
                $template .= '
            </div> <!--fin switz-->

            <div class="c-plans__local-country js-local-country-tab" data-country="uk">';
            foreach($data_uk as $plan_uk) {                   
                $template .=  '
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                '.$plan_uk->data.' '.$plan_uk->data_unit.'
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">'.$plan_uk->duration.' '.$plan_uk->duration_unit.'</p>
                            <p class="c-plans-card__price">'.$plan_uk->price_currency.' '.$plan_uk->price.'</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>';
            }
            
            $template .= '
        </div> <!--fin uk-->

        <div class="c-plans__local-country js-local-country-tab" data-country="fr">';
            foreach($data_fr as $plan_fr) {                   
                $template .=  '
                    <div class="c-plans-card">
                        <div class="c-plans-card__top">
                            <p class="c-plans-card__title">
                                '.$plan_fr->data.' '.$plan_fr->data_unit.'
                            </p>
                            <p class="c-plans-card__logo">
                                <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                            </p>
                        </div>
                        <div class="c-plans-card__text">
                            <p class="c-plans-card__duration">'.$plan_fr->duration.' '.$plan_fr->duration_unit.'</p>
                            <p class="c-plans-card__price">'.$plan_fr->price_currency.' '.$plan_fr->price.'</p>
                        </div>
                        <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
                    </div>';
            }
            
            $template .= '
        </div> <!--fin fr-->
        <div class="c-plans__local-country js-local-country-tab" data-country="esp">';
        foreach($data_esp as $plan_esp) {                   
            $template .=  '
                <div class="c-plans-card">
                    <div class="c-plans-card__top">
                        <p class="c-plans-card__title">
                            '.$plan_esp->data.' '.$plan_esp->data_unit.'
                        </p>
                        <p class="c-plans-card__logo">
                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                        </p>
                    </div>
                    <div class="c-plans-card__text">
                        <p class="c-plans-card__duration">'.$plan_esp->duration.' '.$plan_esp->duration_unit.'</p>
                        <p class="c-plans-card__price">'.$plan_esp->price_currency.' '.$plan_esp->price.'</p>
                    </div>
                    
                    <div class="c-plans-card__buttons">
                    <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                    <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                </div>
                </div>';
        }
        
        $template .= '
    </div> <!--fin esp-->

    <div class="c-plans__local-country js-local-country-tab" data-country="usa">';
        foreach($data_usa as $plan_usa) {                   
            $template .=  '
                <div class="c-plans-card">
                    <div class="c-plans-card__top">
                        <p class="c-plans-card__title">
                            '.$plan_usa->data.' '.$plan_usa->data_unit.'
                        </p>
                        <p class="c-plans-card__logo">
                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                        </p>
                    </div>
                    <div class="c-plans-card__text">
                        <p class="c-plans-card__duration">'.$plan_usa->duration.' '.$plan_usa->duration_unit.'</p>
                        <p class="c-plans-card__price">'.$plan_usa->price_currency.' '.$plan_usa->price.'</p>
                    </div>
                    
                    <div class="c-plans-card__buttons">
                    <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                    <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                </div>
                </div>';
        }
        
        $template .= '
    </div> <!--fin usa-->

    <div class="c-plans__local-country js-local-country-tab" data-country="hong_k">';
    foreach($data_hong_k as $plan_hong_k) {                   
        $template .=  '
            <div class="c-plans-card">
                <div class="c-plans-card__top">
                    <p class="c-plans-card__title">
                        '.$plan_hong_k->data.' '.$plan_hong_k->data_unit.'
                    </p>
                    <p class="c-plans-card__logo">
                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                    </p>
                </div>
                <div class="c-plans-card__text">
                    <p class="c-plans-card__duration">'.$plan_hong_k->duration.' '.$plan_hong_k->duration_unit.'</p>
                    <p class="c-plans-card__price">'.$plan_hong_k->price_currency.' '.$plan_hong_k->price.'</p>
                </div>
                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
            </div>';
    }
    
    $template .= '
</div> <!--fin hong_k-->

<div class="c-plans__local-country js-local-country-tab" data-country="israel">';
    foreach($data_israel as $plan_israel) {                   
        $template .=  '
            <div class="c-plans-card">
                <div class="c-plans-card__top">
                    <p class="c-plans-card__title">
                        '.$plan_israel->data.' '.$plan_israel->data_unit.'
                    </p>
                    <p class="c-plans-card__logo">
                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                    </p>
                </div>
                <div class="c-plans-card__text">
                    <p class="c-plans-card__duration">'.$plan_israel->duration.' '.$plan_israel->duration_unit.'</p>
                    <p class="c-plans-card__price">'.$plan_israel->price_currency.' '.$plan_israel->price.'</p>
                </div>
                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
            </div>';
    }
    
    $template .= '
</div> <!--fin israel-->

<div class="c-plans__local-country js-local-country-tab" data-country="japan">';
    foreach($data_japan as $plan_japan) {                   
        $template .=  '
            <div class="c-plans-card">
                <div class="c-plans-card__top">
                    <p class="c-plans-card__title">
                        '.$plan_japan->data.' '.$plan_japan->data_unit.'
                    </p>
                    <p class="c-plans-card__logo">
                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                    </p>
                </div>
                <div class="c-plans-card__text">
                    <p class="c-plans-card__duration">'.$plan_japan->duration.' '.$plan_japan->duration_unit.'</p>
                    <p class="c-plans-card__price">'.$plan_japan->price_currency.' '.$plan_japan->price.'</p>
                </div>
                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
            </div>';
    }
    
    $template .= '
</div> <!--fin japan-->
<div class="c-plans__local-country js-local-country-tab" data-country="australia">';
    foreach($data_australia as $plan_australia) {                   
        $template .=  '
            <div class="c-plans-card">
                <div class="c-plans-card__top">
                    <p class="c-plans-card__title">
                        '.$plan_australia->data.' '.$plan_australia->data_unit.'
                    </p>
                    <p class="c-plans-card__logo">
                        <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/logo-truphone-small.png" alt="">
                    </p>
                </div>
                <div class="c-plans-card__text">
                    <p class="c-plans-card__duration">'.$plan_australia->duration.' '.$plan_australia->duration_unit.'</p>
                    <p class="c-plans-card__price">'.$plan_australia->price_currency.' '.$plan_australia->price.'</p>
                </div>
                <div class="c-plans-card__buttons">
                                <a href="https://apps.apple.com/es/app/zim/id1611244114" target="_blank" class="c-button c-button--sm c-button--primary">Apple Store</a>
                                <a href="https://play.google.com/store/apps/details?id=com.zim_cli" target="_blank" class="c-button c-button--sm c-button--secondary">Play Store</a>
                            </div>
            </div>';
    }
    
    $template .= '
</div> <!--fin australia-->

                </div> 
            </div>
        </div>
    </div>
    ';

    return $template;

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
require_once( 'vc-components/vc-zim-modal.php' ); 
require_once( 'vc-components/vc-zim-carousel.php' ); 
require_once( 'vc-components/vc-partners-carousel.php' ); 
require_once( 'vc-components/vc-zim-story.php' ); 
require_once( 'vc-components/vc-zim-compatibility.php' ); 
require_once( 'vc-components/vc-zim-faqs.php' ); 