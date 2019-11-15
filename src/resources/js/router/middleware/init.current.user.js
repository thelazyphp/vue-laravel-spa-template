import store from '../../store'
import * as AuthService from '../../services/auth.service'

export default function (to, from, next) {
    if (AuthService.getAccessToken() && ! store.state.users.current.id) {
        store.dispatch('users/getCurrent')
            .finally(() => next())
    } else {
        next()
    }
}
