<script>
export default {
  mounted () {
    document.getElementById('app').style.backgroundImage = 'url(images/background.jpg)'
    document.getElementById('app').style.backgroundSize = 'cover'
    document.getElementById('app').style.backgroundRepeat = 'no-repeat'
    document.getElementById('app').style.backgroundPosition = 'center center'
  }
}
</script>

<template>
  <div class="layout">
    <main id="content" role="main">
      <router-view />
    </main>
  </div>
</template>
