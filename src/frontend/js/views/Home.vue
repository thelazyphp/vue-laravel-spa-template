<template>
    <div class="page-wrapper">
        <main
            class="container"
            role="main"
        >
            <div class="py-5 text-center">
                <h1 class="font-weight-light">Недвижимость</h1>
                <p class="lead">Каталог объявлений о продаже и аренде недвижимости</p>

                <template v-if="!$store.getters['auth/isAuth']">
                    <p><router-link
                        to="/sign-in"
                        class="btn btn-lg btn-primary"
                        role="button">Войти</router-link></p>

                    <p>или <router-link to="/sign-up">зарегистрироваться</router-link></p>
                </template>
            </div>

            <ul
                v-if="$store.getters['auth/isAuth']"
                class="list-group list-group-flush"
            >
                <router-link
                    to="/catalog/apartments"
                    class="list-group-item list-group-item-action">Квартиры</router-link>

                <router-link
                    to="/catalog/lands"
                    class="list-group-item list-group-item-action">Земельные участки</router-link>

                <router-link
                    to="/catalog/houses"
                    class="list-group-item list-group-item-action">Дома, дачи, коттеджи</router-link>

                <router-link
                    to="/catalog/commercial"
                    class="list-group-item list-group-item-action">Коммерческая недвижимость</router-link>
            </ul>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'Home',

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Главная`
        },
    }
</script>
