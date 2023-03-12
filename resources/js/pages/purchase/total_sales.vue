<template>
    <div id="sales-wrapper">
        <!-- <div class="text-left mb-10 d-flex align-center" id="total-header"> -->
            <h3 class="ma-0 text-center mb-10">Sales Report</h3>
        <div class="mb-5">
            <v-btn  elevation="6" @click="printGraph()"><v-icon>mdi-printer</v-icon> &nbsp Print</v-btn>
        </div>
        <v-card class="pa-5 mb-5">
            <v-layout>
                <v-flex>
                    <v-layout>
                    <v-flex>
                        <div v-if="showMonth=='week'">
                        <v-btn x-small icon @click="decrement">
                            <v-icon icon>
                            mdi-menu-left
                            </v-icon>
                        </v-btn>
                        <span>{{start_weeks}} to {{end_weeks}}</span>
                        <v-btn x-small icon @click="increment">
                            <v-icon  icon >
                            mdi-menu-right
                            </v-icon>
                        </v-btn>
                        </div>
                        <div v-else-if="showMonth=='year'">
                        <v-btn x-small icon @click="decrement">
                            <v-icon icon>
                            mdi-menu-left
                            </v-icon>
                        </v-btn>
                        <span>{{current_year}}</span>
                        <v-btn x-small icon @click="increment">
                            <v-icon  icon >
                            mdi-menu-right
                            </v-icon>
                        </v-btn>
                        </div>
                        <div style="height:24px" v-else></div>
                    </v-flex>
                    <v-flex class="d-flex flex-row-reverse my-auto">
                        <v-btn x-small @click="calendar('week')">weekly</v-btn>
                        <v-btn class="mx-2" x-small @click="calendar('year')">yearly</v-btn>
                        <v-menu
                        v-model="menu"
                        left
                        :close-on-content-click="false"
                        :nudge-width="200"
                        offset-y
                        >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            @click="showMonth='range'"
                            x-small
                            v-bind="attrs"
                            v-on="on"
                            >
                            range
                            </v-btn>
                        </template>
                        <v-card>
                            <v-list>
                            <div width="150px" class="mx-2">
                                <div>
                                <span>
                                    Start
                                </span>
                                </div>
                                <v-menu
                                ref="menu"
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                                >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                    v-model="range_start"
                                    placeholder="from"
                                    readonly
                                    hide-details="auto"
                                    outlined
                                    dense
                                    append-icon="mdi-calendar-outline"
                                    v-bind="attrs"
                                    v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    color="primary"
                                    v-model="range_start"
                                    no-title
                                    scrollable
                                    @input="menu2 = false"
                                >
                                </v-date-picker>
                                </v-menu>
                            </div>
                            <div width="150px" class="mx-2">
                                <div>
                                <span>
                                    End
                                </span>
                                </div>
                                <v-menu
                                ref="menu"
                                v-model="menu3"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                                >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                    v-model="range_end"
                                    placeholder="from"
                                    readonly
                                    hide-details="auto"
                                    outlined
                                    dense
                                    append-icon="mdi-calendar-outline"
                                    v-bind="attrs"
                                    v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    color="primary"
                                    v-model="range_end"
                                    :min="minimum_range"
                                    no-title
                                    scrollable
                                    @input="menu3 = false"
                                >
                                </v-date-picker>
                                </v-menu>
                            </div>
                            </v-list>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn x-small text @click="menu = false">
                                Cancel
                            </v-btn>
                            <v-btn
                                x-small
                                color="primary"
                                text
                                @click="calendar('range')"
                            >
                                Save
                            </v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-menu>
                    </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
            
        </v-card>
        <v-card class="pa-5" >
          <v-card-title class="pa-0" id="report-graphs">Graph Total Sales by <strong> &nbsp;  {{checkType()}} &nbsp; ({{getFilter()}})</strong> <h4 class="mb-0 ml-auto">Totals : ₱ {{ report_total }}</h4></v-card-title>
          <hr >
          <linechart :chartData="totalSales"></linechart>
        </v-card>
        <div class="d-flex mt-5" id="graphs">
           <v-card class="pa-5">
             <v-card-title class="pa-0 bold d-flex justify-content-between">Most order Food   <h4 class="mb-0">Total Price: ₱ {{most_order_food != false ? most_order_food.total : 0}}</h4></v-card-title>
              <hr class="mb-10">
             <div class="d-flex" id="most-order-wrapper" v-if="most_order_food != false">
               <v-img  class="ma-auto" id="v-image-wrapper"
                    max-height="800"
                    max-width="600"
                    :src="most_order_food.image">
               </v-img>
               <h4>Name: {{most_order_food.product_name}}</h4>
             </div>
             <div class="d-flex" id="most-order-wrapper" v-else>
               <v-img  class="ma-auto" id="v-image-wrapper"
                    max-height="800"
                    max-width="600"
                    :src="noimage">
               </v-img>
               <h4>Name: N/A</h4>
             </div>
           </v-card>
           <v-card class="pa-5">
              <v-card-title class="pa-0">Top {{mostorder.datasets[0].data.length}} most order Food</v-card-title>
              <hr>
              <template v-if="mostorder.labels.length">
                <pie :chartData="mostorder"></pie>
              </template>
              <template v-else>
                  <div>
                      <v-img  class="ma-auto mt-10"
                          max-height="500"
                          max-width="400"
                           src="/empty1.png">
                    </v-img>
                  </div>
              </template>
           </v-card>
        </div>
    </div>
    <!-- </div> -->
</template>
<script>
import linechart from '../../charts/line.vue'
import pie from '../../charts/pie.vue'
import moment from "moment";
export default {
  components: {
    linechart,
    pie,
  },
  computed: {
    start_weeks () {
      return moment(this.start_week).add('d', 1).format('MMMM-DD-YYYY')
    },
    end_weeks () {
      return moment(this.end_week).add('d', 1).format('MMMM-DD-YYYY')
    }
  },

  data() {
    return {
     noimage: 'data:image/jpeg;base64,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',
      most_order_food :[],
      menu:false,
      menu2:false,
      total:0,
      menu3:false,
      showMonth:'week',
      current_year: moment().format('YYYY'),
      current_date : moment().format('YYYY-MM-DD'),
      start_week: moment().startOf('week').format('MM-DD-YYYY'),
      end_week: moment().endOf('week').format('MM-DD-YYYY'),

      range_start: moment(this.current_date).format('YYYY-MM-DD'),
      range_end: moment(this.range_start).add('d', 1).format('YYYY-MM-DD'),
      minimum_range: moment(this.range_start).add('d',1).format('YYYY-MM-DD'),

      search: '',
      report_total: 0,
      items:[],
      headers: [
        { text: 'Time of order', align: 'center',value: 'order_time' },
          { text: 'Order Quantity', align: 'center',value: 'quantity' },
        {
          text: 'Customer name',
          align: 'center',
          sortable: false,
          value: 'customer_name',
        },
        { text: 'Product order', align: 'center',value: 'product_order' },
        { text: 'Product price', align: 'center',value: 'price' },
        { text: 'Total', align: 'center',value: 'totals' },
      ],
      totalSales: {
        labels: [],
        datasets: [
          {
            label: 'Sales Report',
            backgroundColor: '#f87979',
            data: []
          }
        ]
      },

      mostorder: {
        labels: [],
        datasets: [
          {
            label: 'Sales Report',
            backgroundColor: '#f87979',
            data: []
          }
        ]
      },

    };
  },
  watch: {
    current_date: {
      handler(val) {
        this.current_year = moment(val).format('YYYY')
      },
    },
    range_start: {
      handler(val) {
        this.range_end = moment(val).add('d', 1).format('YYYY-MM-DD')
        this.minimum_range = moment(val).add('d', 1).format('YYYY-MM-DD')
      }
    }
  },
  mounted() {
    this.initialize()
    // console.log(this.current_date.locale('fr'))
  },
  methods: {
    initialize() {
      this.calendar('week')
      this.range_end = moment(this.range_start).add('d', 1).format('YYYY-MM-DD')
    },
    // printGraph(){

    // },
    getTime(date) {
        return moment(date).format('YYYY MMM DD hh:mm:ss')
    },
    checkType() {
       if(this.showMonth=='year'){
        return 'Yearly'
       }
       else if(this.showMonth == 'week'){
        return 'Weekly';
       }
       else{
        return 'Daily';
       }
    },
    getFilter() {
       if(this.showMonth=='year'){
        return this.current_year
       }
       else if(this.showMonth == 'week'){
        return this.start_weeks +' to '+ this.end_weeks
       }
       else{
        return this.range_start +' to '+ this.range_end
       }
    },
    // printGraph() {
   
    //   var windowContent = '<!DOCTYPE html>';
    //   windowContent += '<html>'
    //   windowContent += '<head><title>Print canvas</title></head>';
    //   windowContent += '<body>'
    //   windowContent += '<img src="' + dataUrl + '">';
    //   windowContent += '</body>';
    //   windowContent += '</html>';
    //   var printWin = window.open('','','width=340,height=260');
    //   printWin.document.open();
    //   printWin.document.write(windowContent);
    //   printWin.document.close();
    //   printWin.focus();
    //   printWin.print();
    //   printWin.close();
    // },
    printGraph() {

      // Get HTML to print from element
     var dataUrl = document.getElementById('line-chart').toDataURL(); //attempt to save base64 string to server using this var  
     var divToPrint = document.querySelector('#report-graphs');

      // Get all stylesheets HTML
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }
      console.log(stylesHtml);
      const WinPrint = window.open('');

      WinPrint.document.write(stylesHtml);
      WinPrint.document.write(divToPrint.outerHTML);
      WinPrint.document.write('<img src="' + dataUrl + '">');

      WinPrint.document.addEventListener('load', function() {
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close(); 
    }, true);
      
    },
    getSum(item) {
       return item.total;
    },
    checkReport(item) {
        this.axios.post('/admin/order/orderreport/'+item.request_view,item).then((response) => {
          console.log(response,'response')
            if(response.data.total.length == 0){
                this.totalSales['labels'] = []
                this.totalSales['datasets'][0]['data'] = []
                this.mostorder['labels'] = []
                this.mostorder['datasets'][0]['data'] = []
                this.mostorder['datasets'][0]['backgroundColor'] = []
                return
            }
            this.totalSales['labels'] = response.data.total.label;
            this.totalSales['datasets'][0]['data'] = response.data.total.data;
            
            this.mostorder['labels'] = response.data.top_product_order.label;
            this.mostorder['datasets'][0]['data'] = response.data.top_product_order.data;
            this.mostorder['datasets'][0]['backgroundColor'] = response.data.top_product_order.backgroundColor;
            this.most_order_food =  response.data.most_order == null ? false : response.data.most_order;
            this.report_total =  response.data.total_order;
            
        })
    },
    calendar(item) {
      if(item=='week') {
        this.showMonth='week'
        let itemWeek= {
          request_view: 'week',
          date_request: {
            start: moment(this.start_week).add('d', 1).format('YYYY-MM-DD'),
            end :  moment(this.end_week).add('d', 1).format('YYYY-MM-DD'),
          },
        };
        this.checkReport(itemWeek)
      }
      else if(item=='year') {
        this.showMonth='year'
        let itemMonth= {
          request_view: 'year',
          month_request: this.current_date,
        };
        this.checkReport(itemMonth)
      }
      else {
        let itemMonth= {
          request_view: 'range',
          date_request: {
            start: this.range_start,
            end :  this.range_end,
          },
        };
        this.checkReport(itemMonth)
        this.menu=false
      }
    },
    increment() {
      if(this.showMonth=='week'){
        this.current_date  = moment(this.end_week, 'MM-DD-YYYY').add('1', 'weeks');
        console.log(this.current_date,'current_date');

        this.start_week= moment(this.current_date).startOf('week').format('MM-DD-YYYY');
        this.end_week= moment(this.current_date).endOf('week').format('MM-DD-YYYY');
        this.calendar(this.showMonth)
      }
      else if(this.showMonth=='year') {
        let addMonth = moment(this.current_date, "YYYY-MM-DD").add(1, 'years').format('YYYY-MM-DD');
        this.current_date = addMonth;
        this.calendar(this.showMonth)
      }
      else {
        alert('error')
      }
    },
    decrement() {
      if(this.showMonth=='week'){
        this.current_date = moment(this.start_week, 'MM-DD-YYYY').subtract('1', 'weeks');
        // this.current_date = moment(this.current_date, 'YYYY-MM-DD').subtract('1', 'weeks');
        this.start_week= moment(this.current_date).startOf('week').format('MM-DD-YYYY');
        this.end_week= moment(this.current_date).endOf('week').format('MM-DD-YYYY');
      }
      else if (this.showMonth=='year') {
        let subtractMonth = moment(this.current_date, "YYYY-MM-DD").subtract(1, 'years').format('YYYY-MM-DD');
        this.current_date = subtractMonth;
      }
      this.calendar(this.showMonth)
    }
  },
};
</script>

<style>
#header-text{
    width: 100%;
    text-align: center;
    padding: 10px;
}
/* #table-wrapper{
    max-height: 500px;
    overflow: auto;
} */
#graphs{
  gap: 10px;
}
#graphs .v-card{
  width: 100%;
}
#most-order-wrapper{
    flex-direction: column;
    align-items: center;
    gap: 22px;
}

#v-image-wrapper{
  border-radius:20px;
  height: 250px;
  width: 320px;
}

</style>
