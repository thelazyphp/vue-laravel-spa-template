import { mapMutations } from 'vuex'

export default {
    methods: {
        ...mapMutations([
            'alert/SHOW',
            'alert/CLOSE'
        ])
    }
}
