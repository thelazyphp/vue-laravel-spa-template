<script>
export default {
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<template>
  <div class="card shadow border-0">
    <div class="card-body">
      <div class="media mb-3">
        <a class="mr-3" target="_blank" :href="group.url">
          <img class="group-image" alt="" :src="group.image">
        </a>
        <div class="media-body">
          <div class="d-flex flex-column">
            <a target="_blank" :href="group.url">{{ group.name }}</a>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="custom-control custom-switch">
          <input :id="`get-group-posts-${group.id}`" v-model="group.get_posts" type="checkbox" class="custom-control-input" @change="$emit('update', group)">
          <label class="custom-control-label" :for="`get-group-posts-${group.id}`">Получать посты</label>
        </div>
      </div>
      <div class="form-group">
        <div class="row no-gutters align-items-center">
          <div class="col-auto">
            <label class="mb-0 mr-2" :for="`group-posts-limit-${group.id}`">Лимит постов</label>
          </div>
          <div class="col">
            <select :id="`group-posts-limit-${group.id}`" v-model="group.posts_limit" class="custom-select custom-select-sm"  @change="$emit('update', group)">
              <option v-for="option in ['100', '200', '300', '400', '500']" :key="option" :value="option">{{ option }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.group-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>
