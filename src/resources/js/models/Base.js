export default class Base {
    constructor (data = {}) {
        return this.create(data, this.schema)
    }

    get schema () {
        return {}
    }

    create (data = {}, schema = {}) {
        schema = this.normalizeChema(schema)

        Object.keys(schema).forEach(key => {
            const types = [
                'number',
                'string',
                'boolean',
                'object'
            ]

            const type = typeof data[key]

            ! [undefined, null].includes(data[key]) && types.includes(type)
                ? this[key] = data[key]
                : this[key] = schema[key]
        })

        return this
    }

    normalizeChema (schema = {}) {
        let normalizedChema = {}

        Object.keys(schema).forEach(key => {
            if (typeof schema[key] == 'object' && (schema[key].key || schema[key].default)) {
                normalizedChema[schema[key].key || key] = schema[key].default || undefined
            } else {
                normalizedChema[key] = schema[key]
            }
        })

        return normalizedChema
    }
}
