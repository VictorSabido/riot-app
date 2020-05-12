window.$ = window.jQuery = require('jquery');
import 'materialize-css/dist/js/materialize.min.js'

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);


new Vue({
    el: '#app',
    data () {
      return {
        info: null
      }
    }
  })
