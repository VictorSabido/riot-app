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
            if(this.summoner == '') {
                this.showAlert()
            } else {
                window.location.href = '/summoner/' + this.summoner;
            }
        },
        focusInput: function() {
            this.$refs.summoner.focus(); // Focus input when click arround section
        },
        showAlert: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Summoner name missing',
                icon: 'error',
                confirmButtonText: 'Okey'
            })
        }
    }
})
