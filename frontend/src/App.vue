<template>
  <div id="app" :class="{'full-page-center': $route.name === 'Login'}">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
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