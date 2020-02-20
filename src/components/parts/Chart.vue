<script>
import { Bar } from 'vue-chartjs';

export default {
  extends: Bar,
  name: 'chart',
  props: {
    eval_log: ""
  },
  data () {
    return {
      data: {
        labels: ['5 min', '4 min', '3 min', '2 min', '1 min', 'now'],
        datasets: [
          {
            label: '海抜［m］',
            data: '',
            borderColor: '#4B8E8D',
            fill: false,
            type: 'line',
            lineTension: 0.4,
          }
        ]
      },
      options: {
        scales: {
          xAxes: [{
            scaleLabel: {
              display: true,
              labelString: '',
              fontSize: 10
            },
            gridLines: {
                //x軸の網線
                display: false
            },
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              /*stepSize: 10,*/
            },
          }]
        }
      },
      scale: []
    }
  },
  created () {
    this.data.labels = this.create_scale()
    this.data.datasets[0].data = this.eval_log;
  },
  mounted() {
    //データを入れてから生成する
    this.renderChart(this.data, this.options)
  },
  watch: {
    eval_log() {
      this.data.datasets[0].data = this.eval_log;
      this.create_scale();
      this.renderChart(this.data, this.options);//再描画
    }
  },
  methods: {
      create_scale() {
          for(let i=this.eval_log.length; i>0; i--) {
              this.scale.push(i)
          }
          return this.scale;
      }
  }
}
</script>