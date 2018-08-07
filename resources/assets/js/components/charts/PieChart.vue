<script>
    import {Pie} from 'vue-chartjs'

    export default {
        extends: Pie,
        props: ['chartData'],
        mounted () {
            this.renderChart({
                labels: this.chartData.labels,
                datasets: this.chartData.datasets,
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    labels: {
                        fontColor: "rgba(255,255,255,0.9)",
                        fontSize: 16
                    }
                }
            })
        }
    }
</script>