<script>
import TopicField from './components/TopicField.vue';

OCA.WorkflowEngine.registerOperator({
	id: 'OCA\\TalkMqtt\\Workflow\\MqttOperation',
	color: 'var(--color-success)',
	operation: '',
	options: TopicField,
})

</script>
