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
    
    jQuery('.js-trusted-carousel').owlCarousel({
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
    
    //plans carousel
    
    jQuery('.js-plans-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots: true,
        responsive:{
            0:{
                items:4
            },
            600:{
                items:4
            },
            1000:{
                items:5
            }
        }
    })

    //history carousel about us
     jQuery('.js-history-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    dots: false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
})
    
    //plans tabs
    const openArea = function(areaButton,areaTab,inner,innerClass) {
        for(let i = 0; i < areaButton.length; i++) {
            areaButton[i].onclick=function(e) {
                e.preventDefault();
                let dataTabB = areaButton[i].getAttribute('data-tab');
    
                if(inner == true) {
                    let buttonText = areaButton[i].innerHTML;
                    innerClass.innerHTML = buttonText;
                }
    
                for(let k = 0; k < areaTab.length; k++) {
                    let dataTabO = areaTab[k].getAttribute('data-tab');
    
                    if(dataTabB == dataTabO) {
                        for(z = 0; z < areaTab.length; z++) {
                            areaTab[z].classList.remove('visible');
                            areaButton[z].classList.remove('active');
                        }
                        areaTab[k].classList.add('visible');
                        areaButton[i].classList.add('active');
                    }
                }
            }
        }
    }
    
    const areaButtonArea = document.getElementsByClassName('js-area-button');
    const areaTabArea = document.getElementsByClassName('js-area-tab');
    const regionalButtons = document.getElementsByClassName('js-regional-button');
    const regionalTabs = document.getElementsByClassName('js-regional-tab');
    const regionalInnerClass = document.getElementsByClassName('js-regional-name')[0];
    const localButtons = document.getElementsByClassName('js-local-button');
    const localTabs = document.getElementsByClassName('js-local-tab');
    const innerLocal = document.getElementsByClassName('js-local-name')[0];
    
    //open modal
    const modal = document.getElementsByClassName('c-modal')[0];
    setTimeout(function(){
        modal.classList.add('visible');
    },1500)

    //close modal
    document.addEventListener(
        "click",
        function(event) {
          // If user either clicks X button OR clicks outside the modal window, then close modal by calling closeModal()
          if (
            !event.target.closest(".c-modal__content")
          ) {
            closeModal()
            console.log('close');
          }else {
              
            console.log('not close');
          }
        },
        false
      )
      
      function closeModal() {
        document.querySelector(".c-modal").classList.remove('visible');
      }

    openArea(areaButtonArea, areaTabArea, false);
    openArea(regionalButtons, regionalTabs, true, regionalInnerClass);
    openArea(localButtons, localTabs, true, innerLocal);

    function faqs(caller,opened,block,rotateIcon) {
        for(let i = 0; i < block.length; i++) {
            caller[i].onclick=function() {
                let dataOpen = opened[i].getAttribute('data-open');
                if(dataOpen == 'false') {
                    block[i].classList.add('selected');
                    opened[i].setAttribute('data-open', 'true');

                    rotateIcon[i].classList.add('rotate-icon');
                }else {
                    block[i].classList.remove('selected');

                    opened[i].setAttribute('data-open', 'false');

                    rotateIcon[i].classList.remove('rotate-icon');
                }
            }
        }
    }

    const caller = document.getElementsByClassName('js-open-faq');
    const block = document.getElementsByClassName('js-support-block');
    const opened = document.getElementsByClassName('js-faq-answer');
    const rotateIcon = document.getElementsByClassName('js-faq-icon');
    faqs(caller,opened,block,rotateIcon);

    const faqsOpener = document.getElementsByClassName('js-open-faq-tab');
    const faqTab = document.getElementsByClassName('js-faqs-tab');
    const innerClass = document.getElementsByClassName('js-faqs-title')[0];
    openArea(faqsOpener,faqTab,true,innerClass);
}