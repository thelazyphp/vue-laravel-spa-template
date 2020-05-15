<template>
    <div class="main-content">
        <div class="container-fluid">
            <div class="card card-body border-0 shadow-sm">
                <form @submit.prevent="createClient">
                    <div class="form-row">
                        <div class="col-sm-6 col-md-4 form-group">
                            <label for="lName">Фамилия</label>

                            <input
                                id="lName"
                                v-model="form.lName"
                                type="text"
                                autofocus
                                class="form-control">
                        </div>

                        <div class="col-sm-6 col-md-4 form-group">
                            <label for="fName">Имя <span class="text-danger">*</span></label>

                            <input
                                id="fName"
                                v-model="form.fName"
                                type="text"
                                required
                                class="form-control">
                        </div>

                        <div class="col-sm-12 col-md-4 form-group">
                            <label for="mName">Отчество</label>

                            <input
                                id="mName"
                                v-model="form.mName"
                                type="text"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone0">Телефон <span
                            v-if="form.phones.length"
                            class="text-danger">*</span></label>

                        <div
                            v-for="(phone, index) in form.phones"
                            :key="index"
                            class="mb-2 input-group"
                        >
                            <input
                                :id="`phone${index}`"
                                v-model="form.phones[index]"
                                type="text"
                                class="form-control"
                                required
                                placeholder="Введите номер телефона">

                            <div class="input-group-append">
                                <button
                                    type="button"
                                    title="Удалить телефон"
                                    class="btn btn-link text-danger"
                                    @click="form.phones.splice(index, 1)"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-left">
                            <button
                                v-if="form.phones.length < 5"
                                type="button"
                                class="btn btn-danger"
                                @click="form.phones.push(null)"><i class="mr-3 fas fa-plus-circle"></i>Добавить телефон</button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-sm-6 form-group">
                            <label for="email">E-Mail</label>

                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="form-control">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="address">Адрес</label>

                            <input
                                id="address"
                                v-model="form.address"
                                type="text"
                                class="form-control">
                        </div>
                    </div>

                    <div class="text-right">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="$store.state.clients.isLoading"><span
                                v-if="$store.state.clients.isLoading"
                                role="status"
                                aria-hidden="true"
                                class="mr-3 spinner-border spinner-border-sm"></span>Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ClientCreate',

        data () {
            return {
                form: {
                    fName: null,
                    mName: null,
                    lName: null,
                    phones: [],
                    email: null,
                    address: null,
                },
            }
        },

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Добавить клиента`
        },

        methods: {
            createClient () {
                let formData = {
                    f_name: this.form.fName,
                    m_name: this.form.mName,
                    l_name: this.form.lName,
                    email: this.form.email,
                    address: this.form.address,
                }

                if (this.form.phones.length) {
                    formData['phones'] = this.form.phones
                }

                this.$store.dispatch('clients/create', formData)
                    .then(() => this.$router.push({ name: 'clients' })).catch(error => console.log(error))
            },
        },
    }
</script>

<style scoped>
    .main-content {
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>
