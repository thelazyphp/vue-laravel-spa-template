<script>
export default {
  data () {
    return {
      links: [
        {
          path: '/favorites',
          text: 'Избранные',
          exact: false,
          icon: 'far fa-star',
          badge: null
        },
        {
          path: '/posts',
          text: 'Посты',
          exact: false,
          icon: 'far fa-comments',
          badge: null
        },
        {
          path: '/groups',
          text: 'Группы',
          exact: false,
          icon: 'far fa-folder',
          badge: null
        },
        {
          path: '/profile',
          text: 'Профиль',
          exact: false,
          icon: 'far fa-user',
          badge: null
        }
      ]
    }
  }
}
</script>

<template>
  <ul class="nav flex-column">
    <li v-for="link in links" :key="link.path" class="nav-item" @click="$emit('close-sidebar')">
      <router-link :to="link.path" :exact="link.exact" class="nav-link" active-class="active" exact-active-class="active">
        <span><i class="mr-3" :class="link.icon"></i>{{ link.text }}</span>
        <span v-if="!!link.badge" class="badge badge-pill badge-light">{{ link.badge }}</span>
      </router-link>
    </li>
  </ul>
</template>

<style scoped>
.nav-link {
  padding: 0.75rem;
  color: #fff;
  display: flex;
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
