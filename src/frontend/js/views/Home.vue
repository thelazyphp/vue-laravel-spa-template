<template>
    <div class="page-wrapper">
        <main
            role="main"
            class="container"
        >
            <section class="py-5 text-center">
                <h1 class="font-weight-light">Недвижимость</h1>
                <p class="lead">Каталог объявлений о продаже и аренде недвижимости</p>

                <template v-if="!$store.getters['auth/isAuth']">
                    <p><router-link
                        to="/sign-in"
                        role="button"
                        class="btn btn-lg btn-primary">Войти</router-link></p>

                    <p>или <router-link to="/sign-up">зарегистрироваться</router-link></p>
                </template>
            </section>

            <section
                v-if="$store.getters['auth/isAuth']"
                class="py-5"
            >
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <router-link
                            to="/catalog/apartments"
                            class="nav-link">Квартиры</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            to="/catalog/lands"
                            class="nav-link">Земельные участки</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            to="/catalog/houses"
                            class="nav-link">Дома, дачи, коттеджи</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            to="/catalog/commercial"
                            class="nav-link">Коммерческая недвижимость</router-link>
                    </li>
                </ul>
            </section>
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
