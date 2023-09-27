

Vue.component('ChartLineRealtime', {
    extends: VueChartJs.Line,
    mixins: [VueChartJs.mixins.reactiveProp],
    props: ['chartData'],
    data: function () {
        return {
            options: {
                tooltips: {
                    enabled: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        fontColot: 'black'
                    }
                },
                scales: {
                    xAxes: [
                        {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    ]
                },
                responsive: true,
                maintainAspectRadio: false
            }
        };
    },
    mounted: function () {
        this.renderChart(this.chartData, this.options);
    }
});