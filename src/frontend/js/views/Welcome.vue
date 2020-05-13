<template>
    <div class="container">
        <div class="text-center">
            <h1 class="font-weight-light">Недвижимость</h1>
            <p class="lead">Каталог объявлений о продаже и аренде недвижимости</p>

            <p><router-link
                role="button"
                class="btn btn-lg btn-primary"
                :to="{ name: 'signIn' }">Войти</router-link></p>

            <p>или <router-link :to="{ name: 'signUp' }">зарегистрироваться</router-link></p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Welcome',

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Добро пожаловать!`
        },
    }
</script>
