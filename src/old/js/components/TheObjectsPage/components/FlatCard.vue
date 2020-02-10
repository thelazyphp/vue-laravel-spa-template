<template>
    <div class="card shadow h-100">
        <div
            v-show="showImage"
            ref="image"
            class="card-img-top"
            style="height: 250px"
        >
        </div>

        <div class="card-body">
            <p
                v-if="item.rooms_count"
                class="card-text"
            >
                {{ item.rooms_count }}-комнатная квартира
            </p>

            <p
                v-if="item.price"
                class="card-text text-right"
            >
                {{ item.price }}

                <span
                    v-if="item.price_currency"
                    class="text-danger"
                >
                    {{ item.price_currency }}
                </span>

                <small v-if="item.size">
                    <br>

                    ({{ Math.round(item.price / item.size) }} <span v-if="item.price_currency">{{ item.price_currency }}</span> за м<sup>2</sup>)
                </small>
            </p>
        </div>

        <ul
            class="list-group list-group-flush"
            style="font-size: 13px"
        >
            <li
                v-if="item.floor"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Этаж:
                <span class="badge badge-light badge-pill">
                    {{ item.floor }}
                </span>
            </li>

            <li
                v-if="item.floors_total"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Этажность:
                <span class="badge badge-light badge-pill">
                    {{ item.floors_total }}
                </span>
            </li>

            <li
                v-if="item.construction_year"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Год постройки:
                <span class="badge badge-light badge-pill">
                    {{ item.construction_year }}
                </span>
            </li>

            <li
                v-if="item.size"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Площадь общ.:
                <span class="badge badge-light badge-pill">
                    {{ item.size }}
                </span>
            </li>

            <li
                v-if="item.living_size"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Площадь жил.:
                <span class="badge badge-light badge-pill">
                    {{ item.living_size }}
                </span>
            </li>

            <li
                v-if="item.kitchen_size"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Площадь кухни:
                <span class="badge badge-light badge-pill">
                    {{ item.kitchen_size }}
                </span>
            </li>

            <li
                v-if="item.walls_type"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Стены:
                <span class="badge badge-light badge-pill">
                    {{ item.walls_type }}
                </span>
            </li>

            <li
                v-if="item.balcony_type"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Балкон:
                <span class="badge badge-light badge-pill">
                    {{ item.balcony_type }}
                </span>
            </li>

            <li
                v-if="item.bathroom_type"
                class="list-group-item d-flex justify-content-between align-items-center p-2"
            >
                Сан. узел:
                <span class="badge badge-light badge-pill">
                    {{ item.bathroom_type }}
                </span>
            </li>
        </ul>

        <div class="card-footer">
            <ul class="list-inline mb-0">
                <a
                    href="javascript:void(0)"
                    class="list-inline-item"
                    title="Показать"
                >
                    <i class="fas fa-search"></i>
                </a>

                <a
                    href="javascript:void(0)"
                    class="list-inline-item"
                    title="Добавить в избранное"
                >
                    <i class="far fa-star"></i>
                </a>

                <a
                    class="list-inline-item"
                    target="_blank"
                    title="Посмотреть на сайте"
                    :href="item.url"
                >
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FlatCard',

        props: {
            item: {
                type: Object,
                required: true
            },

            showImage: {
                type: Boolean,
                default: true
            }
        },

        computed: {
            fallbackImage () {
                return 'images/image_not_found.jpg'
            }
        },

        mounted () {
            this.$refs.image.style.backgroundImage = `url("${this.item.images[0] || this.fallbackImage}"), url("${this.fallbackImage}")`
            this.$refs.image.style.backgroundSize = 'cover'
            this.$refs.image.style.backgroundRepeat = 'no-repeat'
            this.$refs.image.style.backgroundPosition = 'center center'
        }
    }
</script>
