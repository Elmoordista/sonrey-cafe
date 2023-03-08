<template>
    <div id="left-side-bar" color="#FFCC6C">
         <div class="d-flex justify-content-between align-center px-10 py-2">
            <h3 class="mb-0">Products : {{items.length ? items.length : 0}}</h3>
            <div class="d-flex" id="_products">
              <v-text-field
                label="Product Name"
                filled
                hide-details
                v-model="search.name"
                clearable
                solo
                rounded
                @input="searchProduct"
                dense
                background-color="white"
              ></v-text-field>
              <v-select
                item-text="name"
                item-value="id"
                :items="options"
                label="Category"
                dense
                solo
                rounded
                hide-details
                clearable
                v-model="search.cat_id"
                @change="searchProduct"
                ></v-select>
              <v-btn dark @click="dialog = true"> Add</v-btn>
            </div>
        </div>
        <hr class="ma-0">
        <v-row>
          <v-col cols="12 px-6 py-5 d-flex " id="left-list-wrapper">
             <div class="item-wrapper d-flex flex-column"  v-for="data in items" :key="data.id">
                <div class="border border-dark" id="list-item" >
                  <v-img v-if="data.image"
                    max-height="160"
                    max-width="140"
                    :src="data.image"
                    id="v-image"
                  >
                  </v-img>
                  <v-img v-else
                    max-height="160"
                    max-width="160"
                    :src="noimage"
                  >
                  </v-img>
                  <div class="d-flex product-info mt-3">
                    <h5>{{data.product_name}}</h5>
                    <h5>₱ {{data.price}}</h5>
                  </div>
                </div>
                  <v-btn dark @click="editItem(data)">
                    Edit
                  </v-btn>
                  <v-btn wh @click="deleteItem(data)">
                    Delete
                  </v-btn>
                
              </div>
               <v-btn
                class="mx-2" id="btn-add"
                dark
                color="white"
                 @click="dialog = true"
              >
                <v-icon dark color="#000" class="elevation-10" id="icon-add" size="50">
                  mdi-plus
                </v-icon>
              </v-btn>
          </v-col>
        </v-row>
        <!-- modal -->
          <v-dialog class="pa-30"
            v-model="dialog"
            persistent
            max-width="400px"
          >
            <v-card id="modal-wrapper" class="pa-5">
              <v-card-title class="pa-0">Add item</v-card-title>
              <hr>
                <div class="text-center">
                 <div class="item-wrapper d-flex flex-column">
                      <v-icon dark color="#000" size="25" id="closemodal" @click="closeDialog()">
                        mdi-close
                      </v-icon>
                    <div class="add-image-wrapper" @click="getImage">
                          <h4 v-if="payload.image == ''">Click to add new Photo</h4>
                         <v-img v-else
                          :src="payload.image" id="selected-image"
                        >
                        </v-img>
                    </div>
                    <input type="file" multiple ref="file_input" class="d-none" accept="image/*" @change="getFiles">

                    <v-text-field
                      label="Product name"
                      filled
                      hide-details
                      v-model="payload.product_name"
                      clearable
                      background-color="white"
                    ></v-text-field>
                    <v-text-field
                    class="text-center"
                      label="Product Price"
                      filled
                      background-color="white"
                      v-model="payload.price"
                      clearable
                      @keydown="isNumber($event)"
                      hide-details
                    ></v-text-field>
                     <v-select
                    item-text="name"
                    item-value="id"
                    :items="options"
                    label="Category"
                    dense
                    solo
                    hide-details
                    v-model="payload.category_id"
                    ></v-select>
                     <v-select
                    item-text="name"
                    item-value="id"
                    :items="statuses"
                    label="Status"
                    dense
                    solo
                    hide-details
                    v-model="payload.status"
                    ></v-select>
                       <v-btn dark @click="saveProduct" :loading="loading" height="50" class="mt-5">
                        Save new Item
                      </v-btn>
                  </div>
                </div>
            </v-card>
          </v-dialog>
    </div>
</template>

<script>
export default {
  data: () => ({
    dialog:false,
    drawer: null,
    loading: false,
    items: [],
    search: {
      name:'',
      cat_id:'',
    },
    noimage: 'data:image/jpeg;base64,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',
    options: [],
    statuses: [
        {
          id:1,
          name:'Available',
        },
        {
          id:0,
          name:'Not available',
        },
    ],
    payload: {
      image:'',
      product_name:'',
      price:'',
      category_id: '',
      status:1,
    },
  }),
  mounted(){
    this.getProduct();
    this.getCategory();
  },
  methods: {
    gotO(link){
      this.$router.push({ name: link})
    },
    isNumber(e){
      const keysAllowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', 'Backspace'];
      const keyPressed = e.key;

      if (!keysAllowed.includes(keyPressed)) {
        e.preventDefault()
      }
    },
    getImage(){
      this.$refs.file_input.click()
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
      this.axios.get('admin/product').then((response) => {
        this.items = response.data;
      })
    },
    deleteItem(data){
        let notifier = this.$awn;
        let onOk = () => {this.deleteConfirm(data)};
        let onCancel = () => {};
        notifier.confirm(
          'Are you you want to delete this product?',
          onOk,
          onCancel,
          {
            labels: {
              confirm: 'Delete confirmation'
            }
          }
        )
    },
    deleteConfirm(data){
        let thiss = this;
        let name = data.product_name;
        this.$awn.asyncBlock(
          thiss.axios.delete('admin/product/'+data.id).then((response) => {
              this.getProduct();
          }), 'Product '+name+' Deleted'
        )
    },
     searchProduct(){
      this.axios.post('/admin/product/searchproduct',this.search).then((response) => {
       this.items = response.data;
      })
    },
    editItem(data){
      this.dialog = true;
      this.payload = Object.assign(this.payload, data)
      // this.payload.product_name = data.product_name;
      // this.payload.price = data.price;
      // this.payload.id = data.id;
      // this.payload.image = data.image;
    },
    closeDialog(){
      this.dialog = false;
      this.payload.product_name = '';
      this.payload.price = '';
      this.payload.id = '';
      this.payload.image = '';
      this.payload.category_id = '';
    },
    saveProduct(){
      this.loading = true;
      var thiss = this;
      var edit = false;
      if(this.payload.id){
        edit = true;
      }
      this.axios.post('admin/product',this.payload).then((response) => {
         this.loading = false;
         this.closeDialog();
         this.getProduct();
         if(edit){
          return this.$awn.success('Update product successfully');
         }
        this.$awn.success('New category added successfully');
      })
      .catch(function (error)
      {
        thiss.loading = false;
        Object.values(error.response.data.errors).forEach(element => {
            thiss.$awn.warning(element[0]);
        });
      });
    }
  },
  watch: {
    // dialog(val) {
    //   this.getAdminInfo();
    // },
  },
};
</script>

<style scoped>

  #_products{
    width: 50%;
    align-items: center;
    gap: 10px;
  }
  #icon-add{
    font-size: 50px;
    color: #fff !important;
    caret-color: rgb(0, 0, 0);
    border-radius: 44px;
    background-color: #000;
    width: 170px;
    height: 170px;
    border-radius: 90px;
  }
  #btn-add{
    background-color: transparent !important;
    background: transparent;
    box-shadow: none;
  }
</style>

