const topNavbar = document.querySelector('.top-navbar');
let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
  if (window.scrollY > lastScrollY) {
    // Scrolling down, hide the top navbar
    topNavbar.style.transform = 'translateY(-100%)';
  } else {
    // Scrolling up, show the top navbar
    topNavbar.style.transform = 'translateY(0)';
  }
  lastScrollY = window.scrollY;
});
