window.onload=function() {
    const plansTab = document.getElementsByClassName('js-open-table');
    const plansTables = document.getElementsByClassName('js-table');

    for(let i = 0; i < plansTab.length; i++) {
        plansTab[i].onclick=function(e) {
            e.preventDefault();
            for(let j = 0; j < plansTables.length; j++) {
                plansTables[j].classList.remove('is-visible');
                plansTab[j].classList.remove('is-selected');
            }
            plansTables[i].classList.add('is-visible');
            plansTab[i].classList.add('is-selected');
        }
    } 
    
    jQuery('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots: true,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })
    jQuery('.partners-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots: false,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:6
            },
            1000:{
                items:6
            }
        }
    })
    jQuery('.partners-carousel .owl-dots').remove();
}