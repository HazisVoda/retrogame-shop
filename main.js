const showMenu = (toggleId, navId) =>{
    const toggle = document.getElementById(toggleId),
          nav = document.getElementById(navId)
 
    toggle.addEventListener('click', () =>{
        // Add show-menu class to nav menu
        nav.classList.toggle('show-menu')
 
        // Add show-icon to show and hide the menu icon
        toggle.classList.toggle('show-icon')
    })
}
 
showMenu('nav-toggle','nav-menu')

let next = document.getElementById('next');
let prev = document.getElementById('prev');
let carousel = document.getElementById('.carousel');
let items = document.getElementById('.carousel .item');
let countItem = items.length;
let active = 1;
let other_1 = null;
let other_2 = null;
next.onclick = () => {
    carousel.classList.remove('prev');
    carousel.classList.add('next');
    active = active + 1 >= countItem ? 0 : active + 1;
    other_1 = active - 1 < 0 ? countItem - 1 : active - 1;
    other_2 = active + 1 >= countItem ? 0 : active + 1;
    changeSlider();
}
prev.onclick = () => {
    carousel.classList.remove('next');
    carousel.classList.add('prev');
    active = active - 1 < 0 ? countItem - 1 : active - 1;
    other_1 = active + 1 >= countItem ? 0 : active + 1;
    other_2 = other_1 + 1 >= countItem ? 0 : other_1 + 1;
    changeSlider();
}
const changeSlider = () => {
    let itemOldActive = document.querySelector('.carousel .item.active');
    if(itemOldActive) itemOldActive.classList.remove('active');

    let itemOldOther_1 = document.querySelector('.carousel .item.other-1');
    if(itemOldOther_1) itemOldOther_1.classList.remove('other-1');

    let itemOldOther_2 = document.querySelector('.carousel .item.other-2');
    if(itemOldOther_2) itemOldOther_2.classList.remove('other-2');

    items.forEach(e => {
        e.querySelector('.image img').style.animation = 'none';
        e.querySelector('.image figcaption').style.animation = 'none';
        void e.offsetWidth;
        e.querySelector('.image img').style.animation = '';
        e.querySelector('.image figcaption').style.animation = '';
    })

    items(active).classList.add('active');
    items(other_1).classList.add('other-1');
    items(other_2).classList.add('other-2');
}