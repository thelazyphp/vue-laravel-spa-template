import Vue from 'vue'

const getItemById = (items, id) => {
    return items.find(item => item.id == id)
}

const getItemIndexById = (items, id) => {
    return items.findIndex(item => item.id == id)
}

export default {
    namespaced: true,

    state: {
        category: 'apartments',
        items: [],
        isLoading: false,
    },

    getters: {
        getItemById (state) {
            return id => {
                return getItemById(state.items, id)
            }
        },

        getItemIndexById (state) {
            return id => {
                return getItemIndexById(state.items, id)
            }
        },
    },

    mutations: {
        setCategory (state, payload) {
            state.category = payload
        },

        setItems (state, payload) {
            state.items = payload
        },

        setIsLoading (state, payload) {
            state.isLoading = payload
        },

        removeItem (state, payload) {
            state.items.splice(
                getItemIndexById(state.items, payload), 1
            )
        },
    },

    actions: {
        fetchItems ({ state, commit }) {
            const endpoint = '/compilations'
            const params = { category: state.category }

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.get(endpoint, { params })
                    .then(response => {
                        commit('setItems', response.data)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },

        create ({ state, commit }, { name, filter }) {
            const endpoint = '/compilations'

            const formData = {
                category: state.category,
                name,
                filter,
            }

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.post(endpoint, formData)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },

        delete ({ commit }, id) {
            const endpoint = `/compilations/${id}`

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.delete(endpoint)
                    .then(response => {
                        commit('removeItem', id)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },
    },
}
