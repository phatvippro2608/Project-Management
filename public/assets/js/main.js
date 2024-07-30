(function () {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim()
        if (all) {
            return [...document.querySelectorAll(el)]
        } else {
            return document.querySelector(el)
        }
    }

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        if (all) {
            select(el, all).forEach(e => e.addEventListener(type, listener))
        } else {
            select(el, all).addEventListener(type, listener)
        }
    }

    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener)
    }

    /**
     * Sidebar toggle
     */
    if (select('.toggle-sidebar-btn')) {
        on('click', '.toggle-sidebar-btn', function (e) {
            select('body').classList.toggle('toggle-sidebar')
        })
    }


    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select('#navbar .scrollto', true)
    const navbarlinksActive = () => {
        let position = window.scrollY + 200
        navbarlinks.forEach(navbarlink => {
            if (!navbarlink.hash) return
            let section = select(navbarlink.hash)
            if (!section) return
            if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                navbarlink.classList.add('active')
            } else {
                navbarlink.classList.remove('active')
            }
        })
    }
    window.addEventListener('load', navbarlinksActive)
    onscroll(document, navbarlinksActive)

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select('#header')
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add('header-scrolled')
            } else {
                selectHeader.classList.remove('header-scrolled')
            }
        }
        window.addEventListener('load', headerScrolled)
        onscroll(document, headerScrolled)
    }

    /**
     * Back to top button
     */
    let backtotop = select('.back-to-top')
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active')
            } else {
                backtotop.classList.remove('active')
            }
        }
        window.addEventListener('load', toggleBacktotop)
        onscroll(document, toggleBacktotop)
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    const nav_items = document.querySelectorAll('.nav-item');

    nav_items.forEach(item => {
        link = item.querySelector('.nav-link');
        if (link.href && link.href === window.location.href) {
            link.classList.remove('collapsed');
        } else {
            link.classList.add('collapsed');
        }
        nav_content = item.querySelector('.nav-content')
        if (nav_content) {
            sublinks = nav_content.querySelectorAll('.nav-sub-link')
            sublinks.forEach(sublink => {
                link.classList.add('collapsed');
                sublink.classList.remove('active')
                nav_content.classList.remove('show')
            })
            sublinks.forEach(sublink => {
                if (sublink.href && sublink.href === window.location.href) {
                    link.classList.remove('collapsed');
                    sublink.classList.add('active')
                    nav_content.classList.add('show')
                }
            })
        }
    })
});

const btnPhoto = document.querySelector('.btn_photo')
const overlays = document.querySelectorAll('.overlay-upload');
const fileInput = document.getElementById('fileInput');
const profileImage = document.getElementById('profileImage');

overlays.forEach(overlay => {
    overlay.addEventListener('click', function () {
        fileInput.click();
    });
});



