// Fixation automatique de la barre de navigation lors du défilement vers le bas, et affichage lors du défilement vers le haut.
document.addEventListener("DOMContentLoaded", function(){
    let headerElm = document.querySelector('header');
    if(headerElm){
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            let scrollTop = window.scrollY;
            if(scrollTop < lastScrollTop && scrollTop > 100 && window.screen.width > 700) {
                headerElm.classList.add('navbar-fixed');
            }
            else {
                headerElm.classList.remove('navbar-fixed');
            }
            lastScrollTop = scrollTop;
        });
    }
});

function contact_mail_open()
{
    const sujectValue = document.getElementById("subject")?.value;
    const messageValue = document.getElementById("message")?.value;

    if(typeof sujectValue == 'string' && typeof messageValue == 'string'){
        const aElmt = document.createElement("A");
        aElmt.setAttribute('target', '_blank');
        aElmt.href = `mailto: contact@takea.vet?subject=${sujectValue}&body=${messageValue}`;
        aElmt.click();
    }
}