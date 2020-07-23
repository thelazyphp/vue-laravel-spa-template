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
  <div class="card border-0 shadow">
    <div class="card-body">
      <div class="d-flex mb-3">
        <a :href="group.url" class="mr-3" target="_blank">
          <span :style="{
            display: 'inline-block',
            width: '50px',
            height: '50px',
            borderRadius: '50%',
            backgroundImage: `url(${group.image})`,
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
            backgroundPosition: 'center center' }"></span>
        </a>
        <a :href="group.url" target="_blank">{{ group.name }}</a>
      </div>
      <p v-if="group.latest_post" class="text-truncate">
        <a :href="group.latest_post.user.url" target="_blank">{{ group.latest_post.user.name }}</a>
        <span>разместил(а)</span>
        <a :href="group.latest_post.url" target="_blank">{{ group.latest_post.message }}</a>
      </p>
      <div class="d-flex justify-content-end">
        <a href="" role="button" title="Удалить" @click.prevent="$emit('remove')">
          <i class="far fa-trash-alt"></i>
        </a>
        <a href="" class="ml-2" role="button" title="Редактировать" @click.prevent="$emit('edit')">
          <i class="far fa-edit"></i>
        </a>
      </div>
    </div>
  </div>
</template>
