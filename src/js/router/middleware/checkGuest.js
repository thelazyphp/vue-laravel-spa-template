import store from '../../store'

export default function (to, from, next) {
    if (to.matched.some(record => record.meta.requiresGuest)) {
        if (store.getters['auth/check']) {
            next({ name: 'home' })
        } else {
            next()
        }
    } else {
        next()
    }
}
