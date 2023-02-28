<template>
    <div class="d-flex" id="d-order-wrapper">
        <div class="add-order-wrapper">
          <div class="d-flex pa-5 search-bar" >
            <v-text-field label="product name" solo dense hide-details v-model="name_search" @input="searchProduct" clearable></v-text-field>
            <v-select
            item-text="name"
            item-value="id"
            :items="options"
            label="Category"
            dense
            solo
            clearable
            v-model="category_search"
            @change="searchProduct"
            ></v-select>
          </div>
          <v-col cols="12 d-flex " id="left-list-wrapper2">
              <div class="item-wrapper d-flex flex-column"  v-for="data in items" :key="data.id">
                <div class="border border-dark" id="list-item" >
                
                  <v-img v-if="data.image"
                    max-height="160"
                    max-width="140"
                    :src="data.image"
                    id="v-image"
                  >
                    <v-chip v-if="!data.status" color="red">
                       Not available
                    </v-chip>
                  </v-img>
                  <v-img v-else
                    max-height="160"
                    max-width="140"
                    :src="noimage"
                     id="v-image"
                  >
                     <v-chip v-if="!data.status" color="red">
                       Not available
                    </v-chip>
                  </v-img>
                  <div class="d-flex product-info mt-3">
                    <h5>{{data.product_name}}</h5>
                    <h5>₱ {{data.price}}</h5>
                  </div>
                   <v-btn :disabled="data.status ? false : true " :id="!data.status ? 'change-bg' : ''" dark @click="orderItem(data)"  class="mt-2">
                    order
                  </v-btn>
                </div>
              </div>
          </v-col>
        </div>
        <div class="vl"></div>
        <div id="reciep-wrapper">
          <div class="reciep-order">
            <div class="header-reciept text-center">
              <h2 >SONREY CAFE</h2>
              <p>3 Katarungan, Mandaluyong, Kalakhang Maynila</p>
            </div>
            <div class="body-reciept">
              <h4>DATE: {{datenow}}</h4>
              <hr>
              <div class="list-cart">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Item Name</th>
                      <th >Qty</th>
                      <th >Total</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="save_Order == false">
                    <tr  v-for="cartdata in cart_order.cart_detail" :key="cartdata.id">
                      <th>{{cartdata.product.product_name}}</th>
                      <td >{{cartdata.quantity}}</td>
                      <td >{{cartdata.total}}</td>
                      <td class="text-center"> 
                        <div>
                        <v-icon @click="editCartProduct(cartdata)" size="15">mdi-pencil</v-icon>
                          |
                        <v-icon @click="deleteCartProduct(cartdata)" size="15">mdi-delete</v-icon>
                      </div></td>
                    </tr>
                    </template>
                    <template v-else>
                      <tr>
                        <td class="list-empty" colspan="4" style="text-align: center;">
                            <div class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i>
                                 No data
                            </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
              <hr>
              <div class="total-wrapper d-flex">
                  <h4>Total:</h4>
                  <h4>₱ {{totals}}</h4>
              </div>
            </div>
            
          </div>
           <!-- <v-btn dark @click="saveOrder()" class="mt-5"> -->
           <v-btn dark @click="saveOrder()" class="mt-5" :disabled="save_Order">
              Save order
            </v-btn>
        </div>
         <v-dialog class="pa-20"
            v-model="dialog"
            persistent
            max-width="300px"
          >
            <v-card id="modal-wrapper" class="pa-5">
                <div class="text-center">
                 <div class="item-wrapper d-flex flex-column">
                    <div>
                         <v-img v-if="payload.image "
                          max-height="200"
                          max-width="250"
                          :src="payload.image "
                          id="v-image"
                          class="ma-auto v-image-wrapper"
                        >
                        </v-img>
                        <v-img v-else
                          max-height="200"
                          max-width="250"
                          :src="noimage"
                          id="v-image"
                          class="ma-auto v-image-wrapper"
                        >
                        </v-img>
                    </div>
                    <div class="d-flex product-info mt-3">
                      <h4>{{payload.product_name}}</h4>
                      <h4>₱ {{payload.price}}</h4>
                    </div>
                    <div class="d-flex quanity">
                        <h4>Quantity:</h4>
                          <v-icon dark color="#000" size="25"  @click="minusQuant">
                            mdi-minus
                          </v-icon>
                           <v-text-field class="align-center" hide-details solo width="10" v-model="quantity"></v-text-field>
                          <v-icon dark color="#000" size="25"  @click="addQuant">
                            mdi-plus
                          </v-icon>
                    </div>
                      <v-btn dark @click="placeOrder" :loading="laoderorder" v-if="!isEdit">
                       Place Order
                      </v-btn>
                      <v-btn dark @click="updateOrder" :loading="laoderorder" v-else>
                       Update order
                      </v-btn>
                      <v-btn dark @click="cancelOrder">
                       Cancel
                      </v-btn>
                  </div>
                </div>
            </v-card>
          </v-dialog>
         <v-dialog class="pa-20"
            v-model="dialogPrint"
            persistent
            max-width="500px"
          >
            <v-card id="modal-wrapper"  class="pa-5">
                <div id="reciep-wrapper" class="reciep-wrapper-print" style="width:100%">
                <div class="reciep-order" id="reciep-order">
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
                            <th style="text-align:left">Item Name</th>
                            <th style="text-align:left">Qty</th>
                            <th style="text-align:left;width: 20px;">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr  v-for="cartdata in print_order.cart_detail" :key="cartdata.id" >
                            <td style="text-align:left ;padding:5px">{{cartdata.product.product_name}}</td>
                            <td style="text-align:left;padding:5px">{{cartdata.quantity}}</td>
                            <td style="text-align:left;padding:5px">{{cartdata.total}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <hr>
                    <div class="total-wrapper d-flex" style="text-align:end;margin-top:10px">
                        <h4 style="margin-left: auto;">Total: ₱ {{totalprint}}</h4>
                    </div>
                  </div>
                  
                </div>
           <!-- <v-btn dark @click="saveOrder()" class="mt-5"> -->
                <v-btn dark @click="printReciept()" class="mt-5">
                  Print Reciept
                </v-btn>

                <v-btn dark @click="dialogPrint = false, getCart()" class="mt-1">
                  Close
                </v-btn>
            </div>
            </v-card>
          </v-dialog>
    </div>
</template>
<script>
import moment from 'moment';

export default {
  data: () => ({
    dialog:false,
    dialogPrint:false,
    drawer: null,
    loading: false,
    category_search: '',
    name_search: '',
    laoderorder: false,
    datenow: moment().format('DD MMM YYYY hh:mm:ss'),
    isEdit: false,
    save_Order: true,
    totals: 0,
    totalprint: 0,
    quantity: 1,
    items: [],
    cart_order: [],
    print_order: [],
     noimage: 'data:image/jpeg;base64,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',
    options: [],
    payload: []
  }),
  mounted(){
    this.getProduct();
    this.getCategory();
    this.getCart();

  },
  methods: {
    editCartProduct(item){

      this.dialog = true;
      this.isEdit = true
      this.quantity = item.quantity;
      var data = {
        image :  item.product.image,
        quantity :  item.quantity,
        product_name :  item.product.product_name,
        product_id :  item.product_id,
        cart_id :  item.cart_id,
        price :  item.product.price,
        old_total :  item.total,
      }
      this.payload = data;

    },
    deleteCartProduct(item){
      this.axios.post('/admin/cart_detail/deleteproduct',item).then((response) => {
       this.getCart()
      this.$awn.success('Delete order in cart successfully')
      })
    },
    saveOrder(){
        this.$awn.asyncBlock(
         this.axios.post('/admin/order',this.cart_order).then((response) => {
            // this.getCart()
            this.cart_order = [];
            this.save_Order = true;
            this.totals = 0;
            this.dialogPrint = true;
          }),
          'Order added successfully',
        )
    },
    searchProduct(){
      var payload = {
        name: this.name_search,
        cat_id: this.category_search,
      }
      this.axios.post('/admin/product/searchproduct',payload).then((response) => {
       this.items = response.data;
      })
    },
    cancelOrder(){
      this.payload = [];
      this.dialog = false
    },
    placeOrder(){
      // this.laoderorder = true;
      this.payload.total = parseInt(this.payload.price) * this.payload.quantity
      this.axios.post('/admin/cart',this.payload).then((response) => {
        this.dialog = false;
        this.laoderorder = false;
        this.getCart()
        this.$awn.success('Place order successfully')
      })
    },
    updateOrder(){
      this.payload.total = parseInt(this.payload.price) * this.payload.quantity
      this.axios.post('/admin/cart_detail/updatecart', this.payload).then((response) => {
        this.dialog = false;
        this.laoderorder = false;
        this.$awn.success('Update order successfully')
        this.getCart()
      })
    },
    gotO(link){
      this.$router.push({ name: link})
    },
    getImage(){
      this.$refs.file_input.click()
    },
    orderItem(data){
      this.dialog = true
      this.isEdit = false
      this.payload = data;
      this.payload.quantity = 1;
    },
    addQuant(){
      this.quantity = parseInt(this.quantity) + 1;
      this.payload.quantity = this.quantity
    },
    minusQuant(){
      if(this.quantity == 1) return;
      this.quantity = parseInt(this.quantity) - 1;
       this.payload.quantity = this.quantity
    },
    getCategory(){
      this.axios.get('/admin/category').then((response) => {
        response.data.forEach(value => {
            var data = {
              id:value.id,
              name:value.category_name,
            }
            this.options.push(data);
        });
      })
    },
    getCart(){
      this.axios.get('/admin/cart').then((response) => {
        this.cart_order = response.data;
        this.print_order = response.data;
        this.totals = response.data.cart_total ? response.data.cart_total : 0;
        this.totalprint = response.data.cart_total ? response.data.cart_total : 0;
        // if(response.data.length == 0) return;
        this.save_Order = this.totals ? false : true;
      })
    },
    getFiles(event) {
          var input = event.target;
          if (input.files) {
            Object.values(input.files).forEach(function (dataFile) {
              var split = dataFile['type'].split("/");
                  var data = {
                      type : dataFile['type'],
                      size : dataFile['size'],
                      name : dataFile['name'],
                    }
                  var val = true;
                  var reader = new FileReader();
                  reader.onload = (e) => {
                    this.payload.image = e.target.result;
                    // this.submit(data);
                  };
                  reader.readAsDataURL(dataFile);

            },this);
            // }
        }
      },
    getProduct(){
      this.axios.get('/admin/product').then((response) => {
        this.items = response.data;
      })
    },
    deleteItem(data){
      this.axios.delete('/admin/product/'+data.id).then((response) => {
        this.getProduct();
      })
    },
    editItem(data){
      this.dialog = true;
      this.payload.product_name = data.product_name;
      this.payload.price = data.price;
      this.payload.id = data.id;
      this.payload.image = data.image;
    },
    closeDialog(){
      this.dialog = false;
      this.payload.product_name = '';
      this.payload.price = '';
      this.payload.id = '';
      this.payload.image = '';
      this.payload.category_id = '';
    },
    printReciept(){
       // Get HTML to print from element
     var divToPrint = document.querySelector('#reciep-order');

      // Get all stylesheets HTML
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }
      console.log(stylesHtml,'stylesHtml')
      // return;
      
      const WinPrint = window.open('');

      WinPrint.document.write(stylesHtml);
      WinPrint.document.write(divToPrint.outerHTML);

      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    },
  },
};
</script>
<style>
  .v-input__control{
    width: 50px;
  }
  .v-image-wrapper{
        height: 200px !important;
  }
  .red{
    height: 23px !important;
    width: 100%;
    text-align: center;
    justify-content: center;
  }

  #change-bg{
    background-color: gray !important;
  }

</style>

