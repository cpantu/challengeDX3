require('./bootstrap');

window.Vue = require('vue');
import { createApp } from 'vue'
import PropertiesList from './components/index.component';
import PropertyDetail from './components/property.component';

const app = createApp({})



app.component('properties-list', PropertiesList);
app.component('property-detail', PropertyDetail);

app.mount('#app')


// const app = new Vue({
//     el: '#app',
// });
