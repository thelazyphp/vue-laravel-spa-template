<template>
  <div class="layout">
    <div class="sidebar">
      <TheSidebar />
    </div>
    <main class="content">
      <slot />
    </main>
  </div>
</template>

<script>
import TheSidebar from '../components/TheSidebar'

export default {
  components: {
    TheSidebar
  }
}
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1031;
  margin-left: -250px;
  width: 250px;
  height: 100vh;
  overflow-y: auto;
  color: #fff;
  background-color: #343a40;
}

.sidebar.toggled {
  margin-left: 0;
}

@media (min-width: 768px) {
  .sidebar {
    margin-left: 0;
  }

  .content {
    margin-left: 250px;
  }
}

.sidebar {
  transition: margin .25s ease-in-out;
}
</style>
