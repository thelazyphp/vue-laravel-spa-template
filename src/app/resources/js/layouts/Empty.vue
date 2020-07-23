<template>
  <main class="d-flex align-items-center justify-content-center" role="main">
    <router-view />
  </main>
</template>

<style scoped>
main {
  padding: 0 15px;
  width: 100%;
  height: 100vh;
  background-image: url("/facebook/images/background.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}
</style>
