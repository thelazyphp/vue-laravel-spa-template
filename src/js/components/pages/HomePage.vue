<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Добро пожаловать!</h1>
            </div>
        </div>

        <div class="container">
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. In labore magnam laudantium iste dolores molestias ducimus sed consequatur optio voluptatibus, est doloremque veritatis deleniti amet nihil dolor quaerat, nostrum vitae?</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum nisi, beatae natus nesciunt, cupiditate porro quam autem voluptatum ex distinctio aperiam numquam eveniet neque quasi atque blanditiis quas temporibus? Soluta!</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam explicabo soluta modi vitae molestias voluptatem labore magnam velit laborum deserunt iure quia, ratione dignissimos architecto cum consectetur est iste nam!</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eum ad praesentium ratione commodi non deleniti iure, sequi nemo recusandae illo modi animi ut amet aliquid cum dolorem tempora eveniet veniam.</p>
        </div>
    </div>
</template>

<script>
    import store from '../../store'

    export default {
        name: 'HomePage',

        beforeRouteEnter (to, from, next) {
            if (! store.getters['auth/isAuth']) {
                next('/login')
            } else {
                document.title = `${process.env.MIX_APP_NAME} - Главная`
                next()
            }
        }
    }
</script>
