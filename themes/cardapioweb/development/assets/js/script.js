/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal(
    {
        distance: '90px',
        duration: 3000,
    }
);

sr.reveal('.home__data', {origin: 'top', delay: 300});
sr.reveal('.home__img', {origin: 'bottom', delay: 400});
sr.reveal('.home__footer', {origin: 'bottom', delay: 600});