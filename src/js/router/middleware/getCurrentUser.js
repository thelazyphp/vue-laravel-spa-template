import i18n from '../../lang'
import store from '../../store'

export default function (to, from, next) {
    if (store.getters['auth/check'] && ! store.state.users.current.id) {
        store.dispatch('users/getCurrent')
            .then(() => next())
            .catch(error => {
                console.log(error)
                store.commit('alert/SHOW', {
                    type: 'danger',
                    message: i18n.t('errors.get_current_user_error')
                })

                store.commit('auth/REMOVE_AUTH_DATA')
                next({ name: 'login' })
            })
    } else {
        next()
    }
}
