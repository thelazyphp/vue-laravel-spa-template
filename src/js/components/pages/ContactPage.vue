<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Связаться с нами</h1>
            </div>
        </div>

        <div class="container">
            <form>
                <div class="form-group">
                    <label
                        class="sr-only"
                        for="email"
                    >
                        E-Mail
                    </label>

                    <input
                        id="email"
                        class="form-control"
                        type="email"
                        placeholder="E-Mail"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label
                        class="sr-only"
                        for="message"
                    >
                        Сообщение
                    </label>

                    <textarea
                        id="message"
                        class="form-control"
                        rows="3"
                        placeholder="Введите ваше сообщение..."
                        required
                    >
                    </textarea>
                </div>

                <div class="text-right">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ContactPage',

        beforeRouteEnter (to, from, next) {
            document.title = `${process.env.MIX_APP_NAME} - Связаться с нами`
            next()
        }
    }
</script>
