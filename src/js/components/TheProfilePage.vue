<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('profile.title') }}</div>

        <div class="card-body text-center text-primary">
            <i class="fas fa-user fa-5x"></i>
        </div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                {{ $t('profile.id') }}:
                <span class="text-primary">{{ currentUser.id }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('profile.role') }}:
                <span class="text-primary">{{ role }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('profile.name') }}:
                <span class="text-primary">{{ currentUser.name }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('profile.email') }}:
                <span class="text-primary">{{ currentUser.email }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            role () {
                return this.$t(`profile.roles.${this.currentUser.role}`)
            }
        }
    }
</script>

<style scoped>

</style>
