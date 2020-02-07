<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">О нас</h1>
            </div>
        </div>

        <div class="container">
            <ul>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, distinctio error dolore libero autem vel odio explicabo. Dolorum reprehenderit sit incidunt ipsam! Nemo hic consequatur sint quidem culpa temporibus libero.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, distinctio error dolore libero autem vel odio explicabo. Dolorum reprehenderit sit incidunt ipsam! Nemo hic consequatur sint quidem culpa temporibus libero.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, distinctio error dolore libero autem vel odio explicabo. Dolorum reprehenderit sit incidunt ipsam! Nemo hic consequatur sint quidem culpa temporibus libero.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, distinctio error dolore libero autem vel odio explicabo. Dolorum reprehenderit sit incidunt ipsam! Nemo hic consequatur sint quidem culpa temporibus libero.</li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AboutPage',

        beforeRouteEnter (to, from, next) {
            document.title = `${process.env.MIX_APP_NAME} - О нас`
            next()
        }
    }
</script>
