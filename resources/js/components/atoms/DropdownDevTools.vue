<template>
  <dropdown title="Tools">
    <simple-link v-for="(url, index) in urls" :key="index" :url="url.url" :text="url.text"></simple-link>
  </dropdown>
</template>
<script>
export default {
  props: {
    urls: {
      type: Array,
      required: true
    }
  }
}
</script>