document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.mySwiper', {
        loop: true,
        lazy: true,
        grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });

    new Swiper('.mySwiperOffers', {
        slidesPerView: 4,
        spaceBetween: 10,
        grabCursor: true,
        loop: true,
        lazy: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        slidesPerView: 1,
        breakpoints: {
            1024: { slidesPerView: 4 },
            768: { slidesPerView: 2 },
            480: { slidesPerView: 1 }
        }
    });
});
