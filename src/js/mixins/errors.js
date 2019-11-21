export default {
    data () {
        return {
            errors: {}
        }
    },

    methods: {
        setErrors (errors = {}) {
            Object.keys(errors).forEach(key => {
                this.errors[key] = Array.isArray(errors[key])
                    ? errors[key][0]
                    : errors[key]
            })
        },

        clearErrors () {
            Object.keys(this.errors).forEach(key => {
                this.errors[key] = ''
            })
        }
    }
}
