<template>
  <div>
    <aside id="sidebar"></aside>
    <main id="content" role="main">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
#sidebar {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1031;
  margin-left: -250px;
  width: 250px;
  height: 100vh;
  overflow-y: auto;
  color: #fff;
  background-color: #3c4655;
  background-image: linear-gradient(0deg,#7e8ea1,#3c4655);
}

#sidebar.toggled {
  margin-left: 0;
}

@media (min-width: 768px) {
  #sidebar {
    margin-left: 0;
  }

  #content {
    margin-left: 250px;
  }
}

#sidebar {
  transition: margin .25s ease-in-out;
}
</style>
