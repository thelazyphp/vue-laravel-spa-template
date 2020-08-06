<template>
  <div :style="style" class="user-avatar-upload">
    <input ref="file" type="file" class="d-none" @change="$emit('upload', $event)">
    <a href="" title="Загрузить аватар" @click.prevent="$refs.file.click()">
      <UserAvatar :size="size" :image="image" />
      <div v-if="uploading" class="indicator">
        <div class="spinner-border" role="status"></div>
      </div>
      <div v-else :style="iconStyle" class="uploading-icon">
        <i class="fas fa-cloud-upload-alt"></i>
      </div>
    </a>
  </div>
</template>

<script>
import UserAvatar from './UserAvatar'

export default {
  components: {
    UserAvatar
  },

  props: {
    size: {
      type: Number,
      required: true
    },

    image: {
      type: String,
      required: true
    },

    uploading: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    style () {
      return {
        width: `${this.size}px`,
        height: `${this.size}px`
      }
    },

    iconStyle () {
      return {
        height: `${this.size / 2}px`,
        borderBottomLeftRadius: `${this.size}px`,
        borderBottomRightRadius: `${this.size}px`
      }
    }
  }
}
</script>

<style scoped>
.user-avatar-upload {
  position: relative;
  display: inline-block;
}

.indicator {
  position: absolute;
  display: flex;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 999;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  color: #fff;
  background-color: rgba(0, 0, 0, .5);
}

.uploading-icon {
  position: absolute;
  display: flex;
  width: 100%;
  left: 0;
  bottom: 0;
  z-index: 999;
  align-items: center;
  justify-content: center;
  color: #fff;
  background-color: rgba(0, 0, 0, .125);
}

.user-avatar-upload:hover .uploading-icon {
  background-color: rgba(0, 0, 0, .5);
}
</style>
