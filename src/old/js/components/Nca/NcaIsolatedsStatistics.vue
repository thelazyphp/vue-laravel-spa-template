<template>
    <div class="nca-isolateds-statistics-wrapper">
        <canvas id="total-chart"></canvas>
        <canvas id="total-by-rooms-count-chart"></canvas>
        <canvas id="price-per-sqm-chart"></canvas>
        <canvas id="price-per-sqm-by-rooms-count-chart"></canvas>
    </div>
</template>

<script>
    import Chart from 'chart.js'
    import Http from '../../utils/Http'

    export default {
        name: 'NcaIsolatedsStatistics',

        mounted () {
            new Http().get('/nca/isolateds/statistics')
                .then(({ data }) => {
                    let labels = []

                    for (let label in data) {
                        labels.push(label)
                    }

                    let totals = []
                    let pTotals = []
                    let sTotals = []
                    let nbTotals = []
                    let avgPricesPerSqm = []

                    let totals1Room = []
                    let totals2Room = []
                    let totals3Room = []
                    let totals4Room = []
                    let avgPricesPerSqm1Room = []
                    let avgPricesPerSqm2Room = []
                    let avgPricesPerSqm3Room = []
                    let avgPricesPerSqm4Room = []

                    Object.keys(data).forEach(key => {
                        totals.push(data[key].total)
                        pTotals.push(data[key].P_total)
                        sTotals.push(data[key].S_total)
                        nbTotals.push(data[key].NB_total)
                        avgPricesPerSqm.push(data[key].avg_price_per_sqm)

                        totals1Room.push(data[key].total_1_room)
                        totals2Room.push(data[key].total_2_room)
                        totals3Room.push(data[key].total_3_room)
                        totals4Room.push(data[key].total_4_room)
                        avgPricesPerSqm1Room.push(data[key].avg_price_per_sqm_1_room)
                        avgPricesPerSqm2Room.push(data[key].avg_price_per_sqm_2_room)
                        avgPricesPerSqm3Room.push(data[key].avg_price_per_sqm_3_room)
                        avgPricesPerSqm4Room.push(data[key].avg_price_per_sqm_4_room)
                    })

                    const ctx1 = document.getElementById('total-chart').getContext('2d')
                    const totalChart = new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                data: totals,
                                label: this.$t('nca.statistics.chart_labels.isolateds'),
                                borderColor: 'rgba(0, 123, 255, 1)',
                                backgroundColor: 'rgba(0, 123, 255, 0.025)'
                            },
                            {
                                data: pTotals,
                                label: this.$t('nca.statistics.chart_labels.parts'),
                                borderColor: 'rgba(40, 167, 69, 1)',
                                backgroundColor: 'rgba(40, 167, 69, 0.025)'
                            },
                            {
                                data: sTotals,
                                label: this.$t('nca.statistics.chart_labels.shares'),
                                borderColor: 'rgba(23, 162, 184, 1)',
                                backgroundColor: 'rgba(23, 162, 184, 0.025)'
                            },
                            {
                                data: nbTotals,
                                label: this.$t('nca.statistics.chart_labels.new_buildings'),
                                borderColor: 'rgba(255, 193, 7, 1)',
                                backgroundColor: 'rgba(255, 193, 7, 0.025)'
                            }]
                        },
                        options: {
                            responsive: true,
				            title: {
				            	display: true,
				            	text: this.$t('nca.statistics.chart_titles.sold_total')
				            },
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.month')
						            }
                                }],
                                yAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.count')
						            }
                                }]
                            }
                        }
                    })

                    const ctx2 = document.getElementById('total-by-rooms-count-chart').getContext('2d')
                    const totalByRoomsCountChart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                            {
                                data: totals1Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_1_room'),
                                borderColor: 'rgba(0, 123, 255, 1)',
                                backgroundColor: 'rgba(0, 123, 255, 0.025)'
                            },
                            {
                                data: totals2Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_2_room'),
                                borderColor: 'rgba(40, 167, 69, 1)',
                                backgroundColor: 'rgba(40, 167, 69, 0.025)'
                            },
                            {
                                data: totals3Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_3_room'),
                                borderColor: 'rgba(23, 162, 184, 1)',
                                backgroundColor: 'rgba(23, 162, 184, 0.025)'
                            },
                            {
                                data: totals4Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_4_room'),
                                borderColor: 'rgba(255, 193, 7, 1)',
                                backgroundColor: 'rgba(255, 193, 7, 0.025)'
                            }]
                        },
                        options: {
                            responsive: true,
				            title: {
				            	display: true,
				            	text: this.$t('nca.statistics.chart_titles.sold_total_by_rooms_count')
				            },
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.month')
						            }
                                }],
                                yAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.count')
						            }
                                }]
                            }
                        }
                    })

                    const ctx3 = document.getElementById('price-per-sqm-chart').getContext('2d')
                    const pricePerSqmChart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                data: avgPricesPerSqm,
                                label: this.$t('nca.statistics.chart_labels.isolateds'),
                                borderColor: 'rgba(0, 123, 255, 1)',
                                backgroundColor: 'rgba(0, 123, 255, 0.025)'
                            }]
                        },
                        options: {
                            responsive: true,
				            title: {
				            	display: true,
				            	text: this.$t('nca.statistics.chart_titles.avg_price_per_sqm')
				            },
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.month')
						            }
                                }],
                                yAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.price')
						            }
                                }]
                            }
                        }
                    })

                    const ctx4 = document.getElementById('price-per-sqm-by-rooms-count-chart').getContext('2d')
                    const pricePerSqmByRoomsCountChart = new Chart(ctx4, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                            {
                                data: avgPricesPerSqm1Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_1_room'),
                                borderColor: 'rgba(0, 123, 255, 1)',
                                backgroundColor: 'rgba(0, 123, 255, 0.025)'
                            },
                            {
                                data: avgPricesPerSqm2Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_2_room'),
                                borderColor: 'rgba(40, 167, 69, 1)',
                                backgroundColor: 'rgba(40, 167, 69, 0.025)'
                            },
                            {
                                data: avgPricesPerSqm3Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_3_room'),
                                borderColor: 'rgba(23, 162, 184, 1)',
                                backgroundColor: 'rgba(23, 162, 184, 0.025)'
                            },
                            {
                                data: avgPricesPerSqm4Room,
                                label: this.$t('nca.statistics.chart_labels.isolateds_4_room'),
                                borderColor: 'rgba(255, 193, 7, 1)',
                                backgroundColor: 'rgba(255, 193, 7, 0.025)'
                            }]
                        },
                        options: {
                            responsive: true,
				            title: {
				            	display: true,
				            	text: this.$t('nca.statistics.chart_titles.avg_price_per_sqm_by_rooms_count')
				            },
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.month')
						            }
                                }],
                                yAxes: [{
                                    scaleLabel: {
							            display: true,
							            labelString: this.$t('nca.statistics.chart_axes.price')
						            }
                                }]
                            }
                        }
                    })
                })
        }
    }
</script>
