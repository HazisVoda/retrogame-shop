const sliderTabs = document.querySelectorAll('.slider-tab');
const sliderIndicator = document.querySelector('.slider-indicator');
const sliderControls = document.querySelectorAll('.controls');

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

const updateIndicator = (tab, index) => {
    sliderIndicator.style.transform = `translateX(${tab.offsetLeft - 20}px)`;
    sliderIndicator.style.width = `${tab.getBoundingClientRect().width}px`;

    const scrollLeft = sliderTabs[index].offsetLeft - sliderControls.offsetWidth / 2 + sliderTabs[index].offsetWidth / 2;
}

const swiper = new Swiper(".slider", {
    effect: "fade",
    speed: 1300,
    autoplay: { delay: 6500 },
    navigation: {
        prevEl: "#slide-prev",
        nextEl: "#slide-next"
    },
    on: {
        //Pagination update on slide change
        slideChange: () => {
            const currentTabIndex = [...sliderTabs].indexOf(sliderTabs[swiper.activeIndex]);
            updateIndicator(sliderTabs[swiper.activeIndex], currentTabIndex);
    }
}
  });

sliderTabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
        swiper.slideTo(index);
        updateIndicator(tab, index);
    })
});

updateIndicator(sliderTabs[0], 0);
window.addEventListener('resize', () => updateIndicator(sliderTabs[swiper.activeIndex], 0));