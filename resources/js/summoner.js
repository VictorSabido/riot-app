require('./app');

window.Vue = require('vue');

import TopCard from './components/TopCard.vue';
Vue.component('top-card', TopCard);

var app = new Vue({
    el: '#app',
    methods: {
        updateSummoner: function() {
            let form = document.getElementById('updateSummoner');
            this.$refs.refresh.classList.add('loading-spinner');
            form.submit();
        },
    }
});

// Sticky updateBar
window.onscroll = function() {stickyBar()};
    
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function stickyBar() {
    if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
    } else {
    navbar.classList.remove("sticky");
    }
}

// Toolti
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems);
  });

