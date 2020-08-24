
import VueSweetalert2 from 'vue-sweetalert2';
require('./bootstrap');

import 'owl.carousel';
window.Vue = require('vue');

Vue.use(VueSweetalert2);
Vue.config.ignoredElements = ['trix-editor', 'trix-toolbar']

Vue.component('fecha-receta', require('./components/FechaReceta').default);
Vue.component('eliminar-receta', require('./components/EliminarReceta').default);
Vue.component('like-button', require('./components/LikeButton').default);


const app = new Vue({
    el: '#app',
});

//Corousel con owl

jQuery(document).ready(function() {
    jQuery('.owl-carousel').owlCarousel({
        margin:10,
        loop: true,
        autoplay: true,
        autoplayHovePause: true,
        responsive: {
            0 : {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
});


