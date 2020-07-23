<script>
import TheNavbar from '../components/TheNavbar'
import TheSidebar from '../components/TheSidebar'

export default {
  components: {
    TheNavbar,
    TheSidebar
  },

  data () {
    return {
      sidebarToggled: false
    }
  },

  mounted () {
    window.onresize = (event) => {
      if (document.documentElement.clientWidth < 768) {
        this.toggleSidebar(false)
      }
    }
  },

  methods: {
    toggleSidebar (force) {
      this.sidebarToggled = !this.sidebarToggled
      document.getElementById('sidebar').classList.toggle('toggled', force)
    }
  }
}
</script>

<template>
  <div>
    <header>
			<TheNavbar :sidebar-toggled="sidebarToggled" class="fixed-top" @toggle-sidebar="toggleSidebar" />
		</header>
		<aside id="sidebar">
      <TheSidebar @close-sidebar="toggleSidebar(false)" />
    </aside>
    <main id="content" role="main">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
#sidebar {
  position: fixed;
  top: 54.22px;
  left: 0;
  z-index: 999;
  width: 250px;
  height: calc(100vh - 54.22px);
  overflow-y: auto;
  margin-left: -250px;
  color: #fff;
  background-color: #003a52;
}

#sidebar.toggled {
  margin-left: 0;
}

#content {
  padding-top: 54.22px;
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
