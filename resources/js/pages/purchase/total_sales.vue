<template>
    <div>
        <!-- <div class="text-left mb-10 d-flex align-center" id="total-header"> -->
            <h3 class="ma-0 text-center mb-10">Total sales</h3>
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
        <linechart></linechart>
    </div>
    <!-- </div> -->
</template>
<script>
import linechart from '../../charts/line.vue'
import moment from "moment";
export default {
  components: {
    linechart
  },
  computed: {
    start_weeks () {
      return moment(this.start_week).format('MMMM-DD-YYYY')
    },
    end_weeks () {
      return moment(this.end_week).format('MMMM-DD-YYYY')
    }
  },

  data() {
    return {
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
    getTime(date) {
        return moment(date).format('YYYY MMM DD hh:mm:ss')
    },
    checkType(type) {
       if(type=='year'){
        return 'Yearly'
       }
       else if(type == 'week'){
        return 'Weekly';
       }
       else{
        return 'Daily';
       }
    },
    print() {

      // Get HTML to print from element
     var divToPrint = document.querySelector('#table-wrapper-data');

      // Get all stylesheets HTML
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }
      console.log(stylesHtml);
      const WinPrint = window.open('');

      WinPrint.document.write(stylesHtml);
      WinPrint.document.write(divToPrint.outerHTML);

      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    },
    getSum(item) {
       return item.total;
    },
    checkReport(item) {
      DataRepository.report(`${item.request_view}`,item).then(({data}) => {
        
        this.items = data.data
        this.total = data.total
      })
    },
    calendar(item) {
      if(item=='week') {
        this.showMonth='week'
        let itemWeek= {
          request_view: 'week',
          date_request: {
            start: moment(this.start_week).format('YYYY-MM-DD'),
            end :  moment(this.end_week).format('YYYY-MM-DD'),
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

</style>
