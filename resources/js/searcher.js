require('./app');

window.Vue = require('vue');
new Vue({
    el: '#app',
    props: {
        summoner: String,
    },
    methods: {
        sendSummoner: function() {
            this.summoner = document.getElementById('summoner').value; // Google autocomplete
            window.location.href = '/summoner/' + this.summoner;
        },
        focusInput: function() {
            this.$refs.summoner.focus(); // Focus input when click arround section
        }
    },
})
