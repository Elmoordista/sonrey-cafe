<template>
  <div>
     <div class="mb-5">
        <v-btn  elevation="6" @click="showPrintOrders()"><v-icon>mdi-printer</v-icon> &nbsp Print</v-btn>
    </div>
    <v-card>
      <v-card-title class="pt-10">
              Order list
              <v-spacer></v-spacer>
              <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                  rounded
                  outlined
                  dense
              ></v-text-field>
              <v-select class="ml-10"
                  :items="options"
                  item-text="name"
                  item-value="id"
                  label="Status"
                  outlined
                  rounded
                  dense
                  hide-details
                  v-model="filter"
                  clearable
                  @change="searchStatus"
              ></v-select>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
      >
        <template v-slot:item.status="{ item }">
          <v-row id="btn-status">
            <v-menu v-for="([text, rounded], index) in btns" :key="text" :rounded="rounded" offset-y>
              <template v-slot:activator="{ attrs, on }">
                <v-btn :color="colors[item.status]" class="white--text" v-bind="attrs" v-on="on" id="btn-wrapper">
                  {{ statuses[item.status].status }}
                  <v-icon>mdi-menu-down</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item v-for="(val, index) in statuses" :key="index" link @click="getItem(val, item)">
                  <v-list-item-title v-text="val.status"></v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-row>
        </template>
        <template v-slot:item.name="{ item }">
          <div>
          {{item.client.name}}
          </div>
        </template>
        <template v-slot:item.created_at="{ item }">
          <div>
          {{changeDate(item.created_at)}}
          </div>
        </template>
        <template v-slot:item.pay="{ item }">
          <div>
          ₱ {{item.pay}}
          </div>
        </template>
        <template v-slot:item.change="{ item }">
          <div>
          ₱ {{item.change}}
          </div>
        </template>
        <template v-slot:item.total="{ item }">
          <div>
          ₱ {{item.total}}
          </div>
        </template>
        <template v-slot:item.action="{ item }">
          <div>
            <v-icon @click="viewData(item)">mdi-eye</v-icon>
            |
            <v-icon @click="getOrderDetail(item)">mdi-printer</v-icon>
          </div>
        </template>
      </v-data-table>
    </v-card>

      <div class="dialog">
        <v-dialog class="pa-20" v-if="items.length"
            v-model="dialogPrint"
            persistent
            max-width="700px"
          >
            <v-card id="modal-wrapper"  class="pa-5">
                <div id="reciep-wrapper" class="reciep-wrapper-print" style="width:100%">
                  <div class="reciep-order _order" id="reciep-order">
                    <div class="header-reciept text-center" style="margin-bottom:20px">
                      <h2 style="text-align:center">SONREY CAFE</h2>
                      <p style="text-align:center">3 Katarungan, Mandaluyong, Kalakhang Maynila</p>
                    </div>
                    <div class="body-reciept">
                      <h4 style="margin-bottom:10px">DATE: {{datenow}}</h4>
                      <hr>
                      <div class="list-cart">
                        <table class="table" style="width:100%">
                          <thead>
                            <tr>
                              <th style="text-align:left">Ref. Order</th>
                              <th style="text-align:left">Customer</th>
                              <th style="text-align:left">Status</th>
                              <th style="text-align:left">Pay</th>
                              <th style="text-align:left">Change</th>
                              <th style="text-align:left">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr  v-for="item in items" :key="item.id" >
                              <td style="text-align:left ;padding:5px">{{item.order_ref}}</td>
                              <td style="text-align:left ;padding:5px">{{item.client.name}}</td>
                              <td style="text-align:left ;padding:5px">{{getStatus(item.status)}}</td>
                              <td style="text-align:left ;padding:5px">₱ {{item.pay}}</td>
                              <td style="text-align:left ;padding:5px">₱ {{item.change}}</td>
                              <td style="text-align:left ;padding:5px">₱ {{item.total}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <hr>
                     
                     <div class="total-wrapper d-flex" style="text-align:end;margin-top:10px">
                          <h4 style="margin-left: auto;">Total: ₱ {{totals}}</h4>
                      </div>
                    </div>
                  </div>
                  <v-btn dark @click="printReciept()" class="mt-5">
                    Print Reciept
                  </v-btn>

                  <v-btn dark @click="dialogPrint = false" class="mt-1">
                    Close
                  </v-btn>
              </div>
            </v-card>
          </v-dialog>
      </div>
      <div class="dialog">
        <v-dialog class="pa-20" v-if="detailOrder.length"
            v-model="dialogPrintDetail"
            persistent
            max-width="700px"
          >
            <v-card id="modal-wrapper"  class="pa-5">
                <div id="reciep-wrapper" class="reciep-wrapper-print _detail" style="width:100%">
                  <div class="reciep-order" id="reciep-order" style="height: 588px;">
                    <div class="header-reciept text-center" style="margin-bottom:20px">
                      <h2 style="text-align:center">SONREY CAFE</h2>
                      <p style="text-align:center">3 Katarungan, Mandaluyong, Kalakhang Maynila</p>
                    </div>
                    <div class="body-reciept">
                      <h4 style="margin-bottom:5px">Order ref: {{orderRef}}</h4>
                      <h4 style="margin-bottom:5px">Order status: {{getStatus(orderStat)}}</h4>
                      <h4 style="margin-bottom:10px">DATE: {{datenow}}</h4>
                      <h4 style="margin-bottom:10px">PAY: ₱ {{pay}}</h4>
                      <h4 style="margin-bottom:10px">CHANGE: ₱ {{change}}</h4>
                      <hr>
                      <div class="list-cart">
                        <table class="table" style="width:100%">
                          <thead>
                            <tr>
                              <th style="text-align:left">Product name</th>
                              <th style="text-align:left">Quantity</th>
                              <th style="text-align:left">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr  v-for="detailsorder in detailOrder" :key="detailsorder.id" >
                              <td style="text-align:left ;padding:5px">{{detailsorder.product.product_name}}</td>
                              <td style="text-align:left ;padding:5px">{{detailsorder.quantity}}</td>
                              <td style="text-align:left ;padding:5px">{{detailsorder.total}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <hr>
                       <div class="total-wrapper d-flex" style="text-align:end;margin-top:10px">
                          <h4 style="margin-left: auto;">Total: ₱ {{totals}}</h4>
                      </div>
                     
                    </div>
                     <!-- <div class="total-wrapper d-flex" style="text-align:end;margin-top:10px">
                          <h4 style="margin-left: auto;">Total: ₱ 10000</h4>
                      </div> -->
                  </div>
                  <v-btn dark @click="printReciept()" class="mt-5">
                    Print Reciept
                  </v-btn>

                  <v-btn dark @click="dialogPrintDetail = false" class="mt-1">
                    Close
                  </v-btn>
              </div>
            </v-card>
          </v-dialog>
      </div>
  </div>
</template>
<script>
import moment from 'moment';

  export default {
    data () {
      return {
        search: '',
        dialogPrint : false,
        dialogPrintDetail : false,
        orderRef : '',
        orderStat: '',
        totals : 0,
        pay : 0,
        change : 0,
        datenow: moment().format('DD MMM YYYY hh:mm:ss'),

        filter: '',
        options: [
            {id:4,name:'All'},
            {id:0,name:'Pending'},
            {id:1,name:'Accepted'},
            {id:2,name:'Cancelled'},
            {id:3,name:'Done'},
        ],
        btns: [
        ['Removed', '0'],
      ],
        noimage: 'data:image/jpeg;base64,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',
        headers: [
          { text: 'Ref. Order', value: 'order_ref'},
          { text: 'Note', value: 'note'},
          { text: 'Customer', value: 'name' },
          { text: 'Pay', value: 'pay' },
          { text: 'Change', value: 'total' },
          { text: 'Total', value: 'total' },
          { text: 'Order Date', value: 'created_at' },
          { text: 'Status', value: 'status'},
          { text: 'Actions', value: 'action' },
        ],
        items: [
        ],
        detailOrder: [
        ],
        colors: ['primary', 'success', 'error', '#555550'],
        statuses: [
          { id: 0, status: 'Pending' },
          { id: 1, status: 'Accept' },
          { id: 2, status: 'Cancelled' },
          { id: 3, status: 'Done' },
        ],

      }
    },
    mounted(){
      this.getOrder();
    },
    methods:{
      getStatus(id){
       var role = this.statuses.filter(x => x.id === id);
      //  return role[0]['id'];
        if(role.length){
          return role[0]['status']; 
        }
      },
      gotO(link){
        this.$router.push({ name: link})
      },
      changeDate(date){
       return moment(date).format('DD MMM YYYY hh:mm:ss')
      },
      showPrintOrders(){
       this.totals = 0;
       this.dialogPrint = true;
       this.items.forEach(element => {
          this.totals = this.totals + element.total
       });
      },
      getItem(item,val){
        var payload ={
          status:item.id,
          id:val.id,
        }
        this.axios.post('/admin/order/update_status',payload).then((response) => {
         this.getOrder();
         this.$awn.success('Order status update successfully')
        })
      },
      getOrderDetail(item){
        this.orderRef  = item.order_ref;
        this.orderStat  = item.status;
        this.totals  = item.total;
        this.pay  = item.pay;
        this.change  = item.change;
        this.axios.get("/admin/cart_detail/"+`${item.id}`).then((response) => {
          this.detailOrder = response.data.order_detail
          this.dialogPrintDetail = true
        })
      },

      searchStatus(){
        if(this.filter==4 || this.filter==null){
          this.getOrder()
          return;
        }
        this.axios.get('/admin/order/'+this.filter).then((response) => {
          this.items = response.data;
        })
      },
      getOrder(){
        this.axios.get('/admin/order').then((response) => {
          this.items = response.data;
        })
      },
      viewData(item){
          this.$router.push({ name: 'order_details' , params: { id: item.id}})
      },
      deleteOrder(item){
          this.axios.delete('/admin/order/'+item.id).then((response) => {
             this.getOrder()
          })
      },
       printReciept(){
       // Get HTML to print from element
        
      var divToPrint ='';
      if(this.dialogPrintDetail == false && this.dialogPrint == true){
        divToPrint = document.querySelector('._order');
      }
      else{
        divToPrint = document.querySelector('._detail');
      }

      // Get all stylesheets HTML
      let stylesHtml = '';
      // for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
      //   stylesHtml += node.outerHTML;
      // }
      // console.log(stylesHtml,'stylesHtml')
      // return;
      
      const WinPrint = window.open('');

      WinPrint.document.write(stylesHtml);
      WinPrint.document.write(divToPrint.outerHTML);

      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
      },
    }
  }
</script>

<style scoped>
#new_user{
  position: absolute;
  right: 0;
  top: -35px;
}
</style>


<style >
#btn-wrapper{
  height: 25px;
  min-width: 140px;
  width:140px;
  padding: 0 16px;
  font-size: 11px;
}
.filter-wrapper{
  display: flex;
  width: 45%;
  gap: 26px;
  align-items: center;
}
#btn-status{
  display: flex;
    align-items: center;
}
.list-cart{
  height: 348px;
  overflow: auto !important;
}
</style>