import Vue from 'vue'
import Userlist from './Userlist.vue'

Vue.config.productionTip = false

new Vue({
    render: h => h(Userlist),
}).$mount('#userlist')
