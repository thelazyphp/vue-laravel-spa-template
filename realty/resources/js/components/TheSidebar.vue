<template>
  <ul class="nav flex-column">
    <li v-for="link in links" :key="link.path" class="nav-item">
      <router-link :to="link.path" :exact="link.exact" class="nav-link" active-class="active" exact-active-class="active">
        <span><i class="mr-3" :class="link.icon"></i>{{ link.text }}</span>
        <span v-if="!!link.badge" class="badge badge-pill" :class="`badge-${link.badgeType}`">{{ link.badge }}</span>
      </router-link>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      links: [
        {
          path: '/catalog',
          text: 'Каталог',
          exact: false,
          icon: 'far fa-folder',
          badge: null,
          badgeType: 'light'
        },
        {
          path: '/profile',
          text: 'Профиль',
          exact: false,
          icon: 'far fa-user',
          badge: null,
          badgeType: 'light'
        },
        {
          path: '/employees',
          text: 'Сотрудники',
          exact: false,
          icon: 'fas fa-arrow-down',
          badge: null,
          badgeType: 'light'
        }
      ]
    }
  }
}
</script>

<style scoped>
.nav-link {
  display: flex;
  padding: 0.75rem;
  color: #fff;
  align-items: center;
  justify-content: space-between;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.075);
}

.nav-link.active {
  background-color: rgba(255, 255, 255, 0.175);
}
</style>
