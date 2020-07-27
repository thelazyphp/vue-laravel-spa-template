<script>
import AppService from '../app.service'

export default {
  data () {
    return {
      links: [
        {
          path: '/favorites',
          text: 'Избранные',
          exact: false,
          icon: 'far fa-star',
          badge: null,
          badgeType: 'light'
        },
        {
          path: '/posts',
          text: 'Посты',
          exact: false,
          icon: 'far fa-comments',
          badge: null,
          badgeType: 'light'
        },
        {
          path: '/groups',
          text: 'Группы',
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
        }
      ]
    }
  },

  watch: {
    groupsTotal (value) {
      this.groupsLink.badge = value
    },

    favoritesTotal (value) {
      this.favoritesLink.badge = value
    }
  },

  computed: {
    groupsTotal () {
      return this.$store.state.groups.total
    },

    groupsLink () {
      return this.links.find(link => link.path == '/groups')
    },

    favoritesTotal () {
      return this.$store.state.favorites.total
    },

    favoritesLink () {
      return this.links.find(link => link.path == '/favorites')
    }
  },

  async created () {
    this.groupsLink.badge = await AppService.getGroupsCount()
    this.favoritesLink.badge = await AppService.getFavoritesCount()
  }
}
</script>

<template>
  <ul class="nav flex-column">
    <li v-for="link in links" :key="link.path" class="nav-item" @click="$emit('close-sidebar')">
      <router-link :to="link.path" :exact="link.exact" class="nav-link" active-class="active" exact-active-class="active">
        <span><i class="mr-3" :class="link.icon"></i>{{ link.text }}</span>
        <span v-if="!!link.badge" class="badge badge-pill" :class="`badge-${link.badgeType}`">{{ link.badge }}</span>
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
