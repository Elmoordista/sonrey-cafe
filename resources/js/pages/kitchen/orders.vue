<template>
    <div>
        <v-card class="pa-5">
            <v-card-title class="pa-0 bold d-flex justify-content-between"> <h3 class="bold mb-0"><v-icon @click="initialize">mdi-refresh</v-icon> Total Orders : {{items.length?items.length:0}}</h3>  
                <v-btn-toggle
                v-model="toggle"
                mandatory
                >
                <v-btn class="mr-5" color="primary" height="36" :value="true"><p class="mb-0" style="color:#fff">Processing</p> <v-badge :content="processCount ? processCount:'0'">
                    <v-icon icon="mdi-vuetify" size="x-large"></v-icon>
                </v-badge></v-btn>
                <v-btn color="black" height="36" :value="false"><p class="mb-0" style="color:#fff">Done</p><v-badge :content="doneCount ? doneCount: '0' ">
                    <v-icon icon="mdi-vuetify" size="x-large"></v-icon>
                </v-badge></v-btn>
                </v-btn-toggle>
            </v-card-title>
            <hr>
            <template v-if="items.length">
                <!-- <template v-if="toggle"> -->
                    <carousel :items="9" :dots="false" id="carousel-wrapper" :nav="false" v-if="processItem.length" :class="toggle ? 'showprocess' : 'hideprocess'">
                        <template slot="prev">
                                <span class="prev"><v-icon size="50">mdi-arrow-left-bold-circle</v-icon></span>
                        </template>

                        <template slot="next" v-if="processItem.length > 10">
                            <span class="next"><v-icon size="50">mdi-arrow-right-bold-circle</v-icon></span>
                        </template>
                        <template v-for="(data , ind) in processItem">
                                <v-card id="card-order" class="mr-5" :key="ind">
                                    <div id="card-number" v-if="data.order_num <= 9"><h2>00{{data.order_num}}</h2></div>
                                    <div id="card-number" v-else-if="data.order_num >= 10"><h2>0{{data.order_num}}</h2></div>
                                    <div id="card-number" v-else><h2>{{data.order_num}}</h2></div>
                                </v-card>
                        </template>
                    </carousel>
                    <carousel :items="10" :dots="false" id="carousel-wrapper" :nav="false" v-if="doneItem.length" :class="!toggle ? 'showdone' : 'hidedone'">
                        <template slot="prev">
                                <span class="prev"><v-icon size="50">mdi-arrow-left-bold-circle</v-icon></span>
                        </template>

                        <template slot="next" v-if="items.length > 10">
                            <span class="next"><v-icon size="50">mdi-arrow-right-bold-circle</v-icon></span>
                        </template>
                        <template v-for="(data , ind) in doneItem">
                                <v-card id="card-order" class="mr-5" :key="ind">
                                    <div id="card-number" v-if="data.order_num <= 9"><h2>00{{data.order_num}}</h2></div>
                                    <div id="card-number" v-else-if="data.order_num >= 10"><h2>0{{data.order_num}}</h2></div>
                                    <div id="card-number" v-else><h2>{{data.order_num}}</h2></div>
                                </v-card>
                        </template>
                    </carousel>
            </template>
            <div v-else class="pa-2 text-center">
                No record
            </div>
        </v-card>

        <div class="order-info mt-10">
            <carousel :items="4" :dots="false" id="carousel-wrapper-order-info" :nav="false" v-if="processItem.length" :class="toggle ? 'showprocess' : 'hideprocess'"> 
                    <template slot="prev">
                            <span class="prev"><v-icon size="50">mdi-arrow-left-bold-circle</v-icon></span>
                    </template>

                    <template slot="next" v-if="items.length > 4">
                        <span class="next"><v-icon size="50">mdi-arrow-right-bold-circle</v-icon></span>
                    </template>
                    <v-card class="mr-5 pa-5" id="order-info-wrapper" v-for="(data , ind) in processItem" :key="ind">
                        <div id="card-number" v-if="(ind + 1) <= 9"><h4 > Order #: 00{{(ind + 1)}} </h4></div>
                        <div id="card-number" v-else-if="(ind + 1) >= 10"><h4 > Order #: 0{{(ind + 1)}} </h4></div>
                        <div id="card-number" v-else><h4 > Order #: 0{{(ind + 1)}} </h4></div>
                        <div id="card-number"><h4>Ref. ID: {{data.order_ref}}</h4></div>
                        <div class="table" id="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Item Qty</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(order_details , keys) in data.order_detail" :key="keys">
                                        <td>{{order_details.product.product_name}}</td>
                                        <td>{{order_details.quantity}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                            <div class="d-flex justify-content-center" id="btn-action">
                                <v-btn
                                    rounded
                                    @click="changeStatus(data)"
                                    color="black"
                                    id="btn-done"
                                    :disabled="ind >= 4 ? true : false"
                                    >
                                    <p>Done</p>
                                </v-btn>
                            </div>
                    </v-card>
            </carousel>

            <carousel :items="4" :dots="false" id="carousel-wrapper-order-info" :nav="false" v-if="doneItem.length" :class="!toggle ? 'showdone' : 'hidedone'"> 
                    <template slot="prev">
                            <span class="prev"><v-icon size="50">mdi-arrow-left-bold-circle</v-icon></span>
                    </template>

                    <template slot="next" v-if="items.length > 4">
                        <span class="next"><v-icon size="50">mdi-arrow-right-bold-circle</v-icon></span>
                    </template>
                    <v-card class="mr-5 pa-5" id="order-info-wrapper" v-for="(data , ind) in doneItem" :key="ind">
                        <div id="card-number" v-if="(ind + 1) <= 9"><h4 > Order #: 00{{(ind + 1)}} </h4></div>
                        <div id="card-number" v-else-if="(ind + 1) >= 10"><h4 > Order #: 0{{(ind + 1)}} </h4></div>
                        <div id="card-number" v-else><h4 > Order #: 0{{(ind + 1)}} </h4></div>
                        <div id="card-number"><h4>Ref. ID: {{data.order_ref}}</h4></div>
                        <div class="table" id="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Item Qty</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(order_details , keys) in data.order_detail" :key="keys">
                                        <td>{{order_details.product.product_name}}</td>
                                        <td>{{order_details.quantity}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                            <div class="d-flex justify-content-center" id="btn-action">
                                <v-btn
                                    disabled
                                    rounded
                                    color="black"
                                    id="btn-done"
                                    >
                                    <p>Done</p>
                                </v-btn>
                            </div>
                    </v-card>
            </carousel>
        </div>
    </div>
</template>

<script>
import carousel from 'vue-owl-carousel'
  export default {
    components: { carousel },
    data () {
      return {
        items: [],
        doneItem: [],
        processItem: [],
        toggle: '',
        processCount: 0,
        doneCount: 0,
      }
    },
    mounted(){
        this.activateNotification();
        this.initialize(false)
    },
    methods:{
        initialize(bool){
            this.resetVal();
            this.axios.get('/admin/order/orders').then((response) => {
                this.items = response.data
                var doneS = this.items.filter(x => x.status === 3)
                var process = this.items.filter(x => x.status === 1)
                this.processItem = Object.assign(this.processItem,response.data.filter(x => x.status === 1))
                this.doneItem = Object.assign(this.doneItem,response.data.filter(x => x.status === 3))
                // this.processItem = this.items.filter(x => x.status === 1)
                this.processCount = process.length > 0 ? process.length : 0;
                this.doneCount = doneS.length > 0 ? doneS.length : 0;
                if(bool){
                    this.$awn.success('New order Recieved')
                }
            })
        },  
        resetVal(){
             this.items = [];
             this.doneItem = [];
             this.processItem = [];
        },
        changeStatus(item){
             var payload = {
                id : item.id,
                status: 3,
             }
             var thiss = this;
             this.$awn.asyncBlock(
                this.axios.post('/admin/order/update_status',payload).then((response) => {
                    thiss.initialize()
                }),
                'Order done',
            )
           
        },
    }
  }
</script>

<style scoped>
#card-order{
    height: 90px;
    width: 120px;
    border: solid 1px #000;
}
#card-order #card-number{
    padding: 22px;
    text-align: center;
    margin: auto;
    height: 100%;
    display: flex;
    margin: auto;
    align-items: center;
    justify-content: center;    
}
#card-order #card-number *{
    font-family: unset;
    font-weight: 700;
    font-size: 40px;
}
#carousel-wrapper{
    position: relative;
    padding: 0px 40px;
}
.next{
    position: absolute;
    z-index: 999;
    top: 25px;
    right: -12px;
    cursor: pointer;
}
.prev{
    position: absolute;
    z-index: 999;
    top: 25px;
    left: -12px;
     cursor: pointer;
}

#carousel-wrapper-order-info span .next{
    position: absolute;
    z-index: 999;
    top: 40%;
    right: -12px;
    cursor: pointer;
}
#carousel-wrapper-order-info span  .prev{
    position: absolute;
    z-index: 999;
    top: 40%;
    left: -12px;
     cursor: pointer;
}
#btn-done p{
    width: 120px;
}
#btn-done p{
    color: #fff;
    margin-bottom: 0;
    font-weight: 700;
    font-size: medium;
}
#order-info-wrapper{
    height: 400px;
    border-radius: 12px;
    position: relative;
}

</style>
<style >
#carousel-wrapper-order-info{
    position: relative;
}
#carousel-wrapper-order-info .owl-carousel  .owl-stage-outer  .owl-stage{
    padding: 5px;
    display: flex;
}
#btn-action{
    position: absolute;
    bottom: 18px;
    left: 0;
    right: 0;
}
#table-wrapper{
    max-height: 244px;
    overflow: auto;
}

.showprocess{
    display: block;
}
.hideprocess{
    display: none;
}

.showdone{
    display: block;
}
.hidedone{
    display: none;
}
</style>