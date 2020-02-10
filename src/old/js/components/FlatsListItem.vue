<template>
    <div>
        <article class="card card-body shadow">
            <div class="media">
                <img
                    v-show="showImage"
                    class="align-self-center rounded mr-3"
                    width="250px"
                    alt=""
                    :src="flat.images[0] || 'images/image_not_found.jpg'"
                    :class="{ 'clickable': flat.images[0] }"
                    :data-toggle="flat.images[0] ? 'modal' : ''"
                    :data-target="flat.images[0] ? `#images-modal-${flat.id}` : ''"
                    :title="flat.images[0] ? $t('flats_list_item.titles.show_images') : $t('flats_list_item.titles.images_not_found')"
                    @error="onImageError"
                    @click="loadModalImages = true"
                >
                <div class="media-body">
                    <a
                        class="lead"
                        target="_blank"
                        :href="flat.url"
                        :title="$t('flats_list_item.titles.show_at_site')"
                    >
                        {{ flat.title || flat.url }}
                    </a>
                    <div class="my-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-1">
                                {{ $t('flats_list_item.props.id') }}:
                                <span class="badge badge-pill badge-light">{{ flat.id }}</span>
                            </li>
                            <li
                                v-if="flat.published_at"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.published_at') }}:
                                <span class="badge badge-pill badge-light">{{ flat.published_at }}</span>
                            </li>
                            <li
                                v-else
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.updated_at') }}:
                                <span class="badge badge-pill badge-light">{{ flat.updated_at }}</span>
                            </li>
                            <li
                                v-if="flat.rooms_count"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.rooms_count') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'rooms_count', value: flat.rooms_count })"
                                >
                                    {{ flat.rooms_count }}
                                </a>
                            </li>
                            <li
                                v-if="flat.floor"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.floor') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'floor', value: flat.floor })"
                                >
                                    {{ flat.floor }}
                                </a>
                                <template v-if="flat.floors_total">
                                    /
                                    <a
                                        href="javascript:void(0)"
                                        class="badge badge-pill badge-light"
                                        :title="$t('flats_list_item.titles.find_similar')"
                                        @click="$emit('findSimilar', { prop: 'floors_total', value: flat.floors_total })"
                                    >
                                        {{ flat.floors_total }}
                                    </a>
                                </template>
                            </li>
                            <li
                                v-if="flat.construction_year"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.construction_year') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'construction_year', value: flat.construction_year })"
                                >
                                    {{ flat.construction_year }}
                                </a>
                            </li>
                            <li
                                v-if="flat.size"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.size') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'size', value: flat.size })"
                                >
                                    {{ flat.size }}
                                </a>
                                /
                                <template v-if="flat.living_size">
                                    <a
                                        href="javascript:void(0)"
                                        class="badge badge-pill badge-light"
                                        :title="$t('flats_list_item.titles.find_similar')"
                                        @click="$emit('findSimilar', { prop: 'living_size', value: flat.living_size })"
                                    >
                                        {{ flat.living_size }}
                                    </a>
                                </template>
                                <template v-else>-</template>
                                /
                                <template v-if="flat.kitchen_size">
                                    <a
                                        href="javascript:void(0)"
                                        class="badge badge-pill badge-light"
                                        :title="$t('flats_list_item.titles.find_similar')"
                                        @click="$emit('findSimilar', { prop: 'kitchen_size', value: flat.kitchen_size })"
                                    >
                                        {{ flat.kitchen_size }}
                                    </a>
                                </template>
                                <template v-else>-</template>
                            </li>
                            <li
                                v-if="flat.price"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.price') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'price', value: flat.price })"
                                >
                                    {{ flat.price }}
                                    <template v-if="flat.price_currency">{{ flat.price_currency }}</template>
                                </a>
                                <i
                                    v-if="priceRisen"
                                    class="fas fa-long-arrow-alt-up text-danger"
                                    :title="priceRisenTitle"
                                >
                                </i>
                                <i
                                    v-else-if="priceFallen"
                                    class="fas fa-long-arrow-alt-down text-success"
                                    :title="priceFallenTitle"
                                >
                                </i>
                            </li>
                            <li
                                v-if="flat.walls_type"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.walls_type') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'walls_type', value: flat.walls_type })"
                                >
                                    {{ flat.walls_type }}
                                </a>
                            </li>
                            <li
                                v-if="flat.balcony_type"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.balcony_type') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'balcony_type', value: flat.balcony_type })"
                                >
                                    {{ flat.balcony_type }}
                                </a>
                            </li>
                            <li
                                v-if="flat.bathroom_type"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.bathroom_type') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'bathroom_type', value: flat.bathroom_type })"
                                >
                                    {{ flat.bathroom_type }}
                                </a>
                            </li>
                            <li
                                v-if="flat.locality"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.locality') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'locality', value: flat.locality })"
                                >
                                    {{ flat.locality }}
                                </a>
                            </li>
                            <li
                                v-if="flat.street"
                                class="list-group-item p-1"
                            >
                                {{ $t('flats_list_item.props.address') }}:
                                <a
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'street', value: flat.street })"
                                >
                                    {{ flat.street }}
                                </a>
                                <a
                                    v-if="flat.house"
                                    href="javascript:void(0)"
                                    class="badge badge-pill badge-light"
                                    :title="$t('flats_list_item.titles.find_similar')"
                                    @click="$emit('findSimilar', { prop: 'house', value: flat.house })"
                                >
                                    {{ flat.house }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <a
                                href="javascript:void(0)"
                                class="mr-2"
                                :title="$t('flats_list_item.titles.open')"
                            >
                                <i class="fas fa-external-link-square-alt fa-lg"></i>
                            </a>
                            <a
                                v-if="bookmarked"
                                href="javascript:void(0)"
                                :title="$t('flats_list_item.titles.remove_from_bookmarks')"
                            >
                                <i class="fas fa-bookmark fa-lg"></i>
                            </a>
                            <a
                                v-else
                                href="javascript:void(0)"
                                :title="$t('flats_list_item.titles.add_to_bookmarks')"
                            >
                                <i class="far fa-bookmark fa-lg"></i>
                            </a>
                        </div>
                        <a
                            href="javascript:void(0)"
                            :title="$t('flats_list_item.titles.bind')"
                        >
                            <i class="fas fa-binoculars fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </article>
        <AppImagesModal
            :id="`images-modal-${flat.id}`"
            :images="flat.images"
            :loadModalImages="loadModalImages"
        />
    </div>
</template>

<script>
    import AppImagesModal from './AppImagesModal'

    export default {
        name: 'FlatsListItem',

        components: {
            AppImagesModal
        },

        props: {
            flat: {
                type: Object,
                required: true
            },

            showImage: {
                type: Boolean,
                default: true
            }
        },

        data () {
            return {
                loadModalImages: false,
                bookmarked: false,
                oldPrice: null,
                priceRisen: false,
                priceFallen: false
            }
        },

        computed: {
            priceRisenTitle () {
                let title = `${this.$t('flats_list_item.price_risen')}`

                if (this.oldPrice) {
                    title += `. ${this.$t('flats_list_item.old_price')}: ${this.oldPrice}`

                    if (this.flat.price_currency) {
                        title += ` ${this.flat.price_currency}`
                    }
                }

                return title
            },

            priceFallenTitle () {
                let title = `${this.$t('flats_list_item.price_fallen')}`

                if (this.oldPrice) {
                    title += `. ${this.$t('flats_list_item.old_price')}: ${this.oldPrice}`

                    if (this.flat.price_currency) {
                        title += ` ${this.flat.price_currency}`
                    }
                }

                return title
            }
        },

        methods: {
            onImageError (event) {
                event.target.classList.remove('clickable')
                event.target.removeAttribute('data-toggle')
                event.target.removeAttribute('data-target')
                event.target.src = 'images/image_not_found.jpg'
                event.target.setAttribute('title', $t('flats_list_item.titles.images_not_found'))
            }
        }
    }
</script>

<style scoped>
    .close {
        outline: none !important;
    }

    .clickable {
        cursor: pointer !important;
    }
</style>
