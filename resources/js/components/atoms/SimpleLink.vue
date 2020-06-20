<template>
  <a :href="url">{{humanText}}</a>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: false
        }
    },
    computed: {
        humanText() {
            return this.text || this.url;
        }
    }
}
</script>

<style>

</style>