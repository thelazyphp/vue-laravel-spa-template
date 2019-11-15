import Base from './Base'

export default class User extends Base {
    get schema () {
        return {
            id: undefined,
            name: undefined,
            email: undefined
        }
    }
}
