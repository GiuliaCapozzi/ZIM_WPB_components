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
    //url usa
    $url = 'https://zimconnections-api.live/truphone/products/USA';
    //url europe
    $url_europe = 'https://zimconnections-api.live/truphone/products/EUROPE';
    $url_apac = 'https://zimconnections-api.live/truphone/products/APAC';
    $url_global = 'https://zimconnections-api.live/truphone/products/GLOBAL';

    //local countries
    //EUROPE    
    $url_austria = 'https://zimconnections-api.live/truphone/products/AUT';

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
                                <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                                <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                            <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                            <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                            <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                            <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                            <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                            <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                            <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                            <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                            <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                            <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                                <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                                <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                                <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                                <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                                <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                                <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
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
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Austria</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Belgium</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Croatia</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Cyprus</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Czech Republic</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Germany</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Greece</p>
                                    </div>
                                </a>   
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Hungary</p>
                                    </div>
                                </a>

                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Ireland</p>
                                    </div>
                                </a>   
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Italy</p>
                                    </div>
                                </a>    
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Netherlands</p>
                                    </div>
                                </a>  
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Norway</p>
                                    </div>
                                </a>     
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Poland</p>
                                    </div>
                                </a>       
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Portugal</p>
                                    </div>
                                </a>         
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Sweden</p>
                                    </div>
                                </a>   
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Switzerland</p>
                                    </div>
                                </a>   
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">UK</p>
                                    </div>
                                </a>   
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">France</p>
                                    </div>
                                </a>  
                                 
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Spain</p>
                                    </div>
                                </a> 
                                 
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
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
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Peru</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Brazil</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Argentina</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Aruba</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Uruguay</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Trinidad</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Suriname</p>
                                    </div>
                                </a>        
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">USA</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Jamaica</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
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
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Hong Kong</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Israel</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Japan</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">China</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">India</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Indonesia</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Malaysia</p>
                                    </div>
                                </a>       
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Singapore</p>
                                    </div>
                                </a>        
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Taiwan </p>
                                    </div>
                                </a>          
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Thailand</p>
                                    </div>
                                </a>     
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">South Korea</p>
                                    </div>
                                </a>              
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
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
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
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
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">South Africa</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Kenya</p>
                                    </div>
                                </a>      
                                <a href="#" class="item">
                                    <div class="c-plans-carousel-card">
                                        <figure>
                                            <img src="https://www.zimconnections.com/des/wp-content/uploads/2022/03/plan-card-carousel-1.png" alt=""> 
                                        </figure>
                                        <p class="c-plans-carousel-card__title">Ghana</p>
                                    </div>
                                </a>              
                                
                            </div>
                        </div>
                    </div>     
                </div>        
                <div class="c-plans__local-countries">        
                    <div class="c-title">
                        <h2 class="c-title__title">Plans in <span class="js-local-name">Austria</span></h2>
                    </div>

                    <div class="c-plans__local-country js-local-country-tab" data-country="austria">';
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
                                    <a href="#" class="c-button c-button--sm c-button--primary">Buy</a>
                                    <a href="#" class="c-button c-button--sm c-button--secondary">Add to cart</a>
                                </div>
                            </div>';
                    }
                      
                $template .= '</div>

                </div> 
            </div>
        </div>
    </div>
    ';

    return $template;

}

add_shortcode('var_dump', 'get_dump');
function get_dump() {
    
    $url = 'https://zimconnections-api.live/truphone/products/USA';
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
require_once( 'vc-components/vc-zim-modal.php' ); 
require_once( 'vc-components/vc-zim-carousel.php' ); 
require_once( 'vc-components/vc-partners-carousel.php' ); 
require_once( 'vc-components/vc-zim-story.php' ); 
require_once( 'vc-components/vc-zim-compatibility.php' ); 
require_once( 'vc-components/vc-zim-faqs.php' ); 