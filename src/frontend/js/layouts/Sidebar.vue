<template>
    <div
        id="app"
        class="app-wrapper"
    >
        <the-header></the-header>

        <div class="row no-gutters">
            <div class="col-lg-3 col-xl-2 sidebar">
                <div class="sidebar-inner">
                    <router-view name="sidebar"></router-view>
                </div>
            </div>

            <main
                role="main"
                class="col-lg-9 col-xl-10"
            >
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
    import TheHeader from '../components/TheHeader'

    export default {
        name: 'Sidebar',
        components: { TheHeader },
    }
</script>

<style scoped>
    .sidebar {
        padding: 15px;
    }

    @media (min-width: 992px) {
        .sidebar {
            min-height: calc(100vh - 54.22px);
        }
    }

    .sidebar .sidebar-inner {
        background-image: url(/realty/images/waves.png);
        background-repeat: no-repeat;

        /* theme: bordered */
        border-radius: 0.25rem;

        height: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #fff;

        /* theme: shadow-sm */
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
