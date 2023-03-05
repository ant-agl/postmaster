document.addEventListener("DOMContentLoaded", () => {

    //SPINNER
    const spinner = document.querySelector('#spinner');
    const spinnerBtn = document.querySelector('.spinner__btn-wrap');
    const animTimeout = 6000;

    const queryString = window.location.search;
    const lang = spinnerBtn.querySelector('.spinner__btn').getAttribute('data-lang')
    const bonusModal = document.querySelector('.modal');
    const modalBtn = document.querySelector('.get_bonus');

    spinnerBtn.addEventListener('click', (e) => {    
        spinner.classList.add('spinning');
        
        setTimeout(() => { 
            spinnerBtn.classList.add('spinned');
            bonusModal.classList.add('modal-dispalayed');
            setTimeout(() => {
                bonusModal.classList.add('modal-visible');
                modalBtn.setAttribute('href', `https://foggystarproject.com/${lang}/signup${queryString}`);
            }, 200);
            spinnerBtn.querySelector('.spinner__btn').classList.add('spinned');
            spinnerBtn.querySelector('.spinner__btn').innerHTML = 'Get<br>Bonus';
            spinnerBtn.querySelector('.spinner__btn').classList.add('active');
            spinnerBtn.querySelector('.spinner__btn').setAttribute('href', `https://foggystarproject.com/${lang}/signup${queryString}`);
        }, animTimeout);
    });
});

// Copy text
function copyToClipboard() {
    const str = document.getElementById('item-to-copy').innerText;
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    
    var bonusCont = document.querySelector(".bonus_cont");
    bonusCont.classList.add("active");

    setTimeout( function() { 
        bonusCont.classList.remove("active");
    } , 800)
}