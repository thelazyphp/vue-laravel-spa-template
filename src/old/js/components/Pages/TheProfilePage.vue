<template>
    <div class="container">
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
                    {{ $t('profile.first_name') }}:
                    <span class="text-primary">{{ currentUser.first_name }}</span>
                </li>
                <li class="list-group-item">
                    {{ $t('profile.last_name') }}:
                    <span class="text-primary">{{ currentUser.last_name }}</span>
                </li>
                <li class="list-group-item">
                    {{ $t('profile.email') }}:
                    <span class="text-primary">{{ currentUser.email }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'TheProfilePage',

        computed: {
            role () {
                return this.$t(`profile.roles.${this.currentUser.role}`)
            }
        }
    }
</script>
