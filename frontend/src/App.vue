<template>
  <div
    id="app"
    :class="{'full-page-center': isFullPageCenter}"
  >
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',

  computed: {
    isFullPageCenter() {
      return  ['Login', 'Register'].includes(this.$route.name);
    }
  }
}
</script>

<style>
.full-page-center {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  width: 100%;
  background-color: #f0f2f5;
}
</style>