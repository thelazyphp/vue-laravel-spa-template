<script>
export default {
  data () {
    return {
      loading: false
    }
  },

  computed: {
    items () {
      return this.$store.state.catalog.items
    }
  },

  async created () {
    await this.fetchItems()
  },

  methods: {
    async fetchItems (page = null) {
      if (!!page) {
        this.$store.commit(
          'catalog/setPage', page
        )
      }

      this.loading = true

      try {
        await this.$store.dispatch('catalog/fetch')
      } catch (error) {
        //

        console.log(error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<template>
  <div class="container">
    <h1 class="my-5">Каталог</h1>
    <template v-if="loading">
      <div class="mb-3 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="card mb-3">
        <div class="card-body p-0 table-responsive">
          <div class="card-header d-flex bg-white" style="padding: 0.75rem">
            <form class="row flex-fill no-gutters" @submit.prevent>
              <div class="col-auto">
                <button type="submit" class="btn mr-2 btn-sm btn-primary">Найти</button>
              </div>
              <div class="col">
                <input type="search" class="form-control form-control-sm" placeholder="Поиск">
              </div>
            </form>
            <button type="button" class="btn ml-2 btn-sm btn-primary dropdown-toggle" title="Фильтры"><i class="fas fa-filter"></i></button>
          </div>
          <table class="table">
            <thead class="bg-light">
              <tr>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0"></th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Дата</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Комнат</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Этаж</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Этажность</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Год постройки</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Пл. общ.</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Пл. жил.</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Пл. кухни</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Стоимость, USD</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td class="text-nowrap align-middle">
                  <span v-if="item.images.length" :style="{
                    display: 'inline-block',
                    width: '50px',
                    height: '50px',
                    borderRadius: '50%',
                    backgroundImage: `url(${item.images[0].thumb || item.images[0].src})`,
                    backgroundSize: 'cover',
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: 'center center' }"></span>
                </td>
                <td class="text-nowrap align-middle">{{ new Date(item.published_at || item.updated_at).toLocaleDateString() }}</td>
                <td class="text-nowrap align-middle">{{ item.rooms }}</td>
                <td class="text-nowrap align-middle">{{ item.floor }}</td>
                <td class="text-nowrap align-middle">{{ item.floors }}</td>
                <td class="text-nowrap align-middle">{{ item.year_built }}</td>
                <td class="text-nowrap align-middle">{{ item.size.total }}</td>
                <td class="text-nowrap align-middle">{{ item.size.living }}</td>
                <td class="text-nowrap align-middle">{{ item.size.kitchen }}</td>
                <td class="text-nowrap align-middle">{{ item.price.amount }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>
