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
}