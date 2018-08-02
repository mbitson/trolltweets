<script>
    import {Line} from 'vue-chartjs'
    require('chartjs-plugin-annotation');

    export default {
        extends: Line,
        props: ['chartData'],
        mounted () {
            let timeFormat = 'DD/MM/YYYY';
            let data = {
                datasets: this.chartData
            };
            this.renderChart(data, {
                responsive: true,
                maintainAspectRatio: true,
                tooltips: {
                    mode: 'x'
                },
                scales:     {
                    xAxes: [{
                        type:       "time",
                        time:       {
                            parser: timeFormat,
                            tooltipFormat: 'MMMM, YYYY'
                        },
                        scaleLabel: {
                            display:     true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display:     true,
                            labelString: 'value'
                        }
                    }]
                },
                annotation: {
                    events: ['click', 'dblclick', 'mouseover', 'mouseout'],
                    annotations: this.annotations
                }
            })
        },
        computed: {
            annotations(){
                return this.$store.getters['annotations/getAnnotations']();
            }
        }
    }
</script>