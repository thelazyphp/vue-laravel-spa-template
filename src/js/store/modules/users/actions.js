import Http from '../../../utils/Http'

export default {
    getCurrent ({ commit }) {
        return new Promise((resolve, reject) => {
            new Http().get('/users/current')
                .then(response => {
                    commit('SET_CURRENT', response.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    }
}
