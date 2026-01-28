<template>
    <div class="topic-field">
        <NcTextField
            v-model="topic"
            label="MQTT Topic"
            placeholder="e.g., home/living-room/temperature"
            @blur="validateTopic"
            @update:modelValue="validateTopic" />
        <span v-if="error" class="error">{{ error }}</span>
    </div>
</template>

<script>
import NcTextField from '@nextcloud/vue/components/NcTextField'

export default {
    name: 'TopicField',
    components: { NcTextField },
    props: {
        modelValue: String,
    },
    data() {
        return {
            topic: '',
            error: '',
        };
    },
    watch: {
        modelValue(newVal) {
            this.topic = newVal || '';
            this.validateTopic();
        },
    },
    methods: {
        validateTopic() {
            if (!this.topic.trim()) {
                this.error = 'Topic cannot be empty';
                return;
            }
            if (!/^[a-zA-Z0-9/_+#-]+$/.test(this.topic)) {
                this.error = 'Invalid characters in topic';
                return;
            }
            if (this.topic.startsWith('/') || this.topic.endsWith('/')) {
                this.error = 'Topic cannot start or end with /';
                return;
            }
            this.error = '';
            this.$emit('update:modelValue', this.topic);
        },
    },
    mounted() {
        this.topic = this.modelValue || '';
    },
};
</script>

<style scoped>
.topic-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

label {
    font-weight: 500;
}

input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input:focus {
    outline: none;
    border-color: #0066cc;
}

.error {
    color: #d32f2f;
    font-size: 0.875rem;
}
</style>