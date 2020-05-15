<template>
    <div class="main-content">
        <div class="container-fluid">
            <div class="card card-body border-0 shadow-sm">
                <div class="mb-3 btn-toolbar">
                    <div class="btn-group">
                        <router-link
                            :to="{ name: 'createClient' }"
                            class="btn btn-danger"><i class="mr-3 fas fa-plus-circle"></i>Добавить клиента</router-link>
                    </div>
                </div>

                <div
                    v-if="$store.getters['clients/getItems'].length"
                    class="table-responsive"
                >
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Фамилия</th>
                                <th scope="col">Имя</th>
                                <th scope="col">Отчество</th>
                                <th scope="col">Телефон</th>
                                <th scope="col">E-Mail</th>
                                <th scope="col">Адрес</th>
                                <th scope="col">Действие</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="item in $store.getters['clients/getItems']"
                                :key="item.id"
                            >
                                <td>{{ item.id }}</td>
                                <td>{{ item.l_name }}</td>
                                <td>{{ item.f_name }}</td>
                                <td>{{ item.m_name }}</td>

                                <td>
                                    <div
                                        v-for="(phone, index) in item.phones"
                                        :key="index"
                                    >
                                        <a :href="`tel:${phone}`">{{ phone }}</a>
                                    </div>
                                </td>

                                <td>
                                    <a :href="`mailto:${item.email}`">{{ item.email }}</a>
                                </td>

                                <td>{{ item.address }}</td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-link text-secondary"
                                        title="Удалить"
                                        @click="deleteClient(item.id)"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Clients',

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Клиенты`
            this.$store.dispatch('clients/fetchData')
        },

        methods: {
            deleteClient (id) {
                if (confirm(`Удалить клиента №${id}`)) {
                    this.$store.dispatch('clients/delete', id)
                }
            },
        },
    }
</script>

<style scoped>
    .main-content {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .table th, td {
        white-space: nowrap;
    }

    .table > thead > tr > th {
        border-top: none;
        border-bottom: none;
    }

    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
