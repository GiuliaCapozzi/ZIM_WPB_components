window.onload=function() {
    const plansTab = document.getElementsByClassName('js-open-table');
    const plansTables = document.getElementsByClassName('js-table');

    const input = document.getElementsByClassName('js-input-acordeon');
    const tab = document.getElementsByClassName('js-acordeon-tab');

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

    function openTab(input,tab) {
        for(let i = 0; i < input.length; i++) {
            input[i].onchange=function() {
                for(let k = 0; k < tab.length; k++) {
                    tab[k].classList.remove('active');
                }
                tab[i].classList.add('active');
            }
        }
    }
    openTab(input,tab);
    
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