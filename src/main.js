import Vue from 'vue'
import App from './App.vue'
Vue.mixin({ methods: { t, n } })

const View = Vue.extend(App)
new View().$mount('#talkmqtt')

console.log('test')

window.OCA.WorkflowEngine.registerOperator({
	id: 'OCA\\TalkMqtt\\Workflow\\MqttOperation',
	color: 'var(--color-success)',
	operation: '',
    options: Tag,
})
