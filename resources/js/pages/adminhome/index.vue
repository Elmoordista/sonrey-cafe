<template>
  <v-app id="inspire">
    <v-app-bar id="header-app"
      app
      color="#FFCC6C"
      flat
    >
      <v-container class="py-0 fill-height ma-0" >
        <!-- <v-img
            max-height="110"
            max-width="110"
            src="/adminlogo.png"
            class="ml-8"
          ></v-img> -->
           <v-img v-if="!logo"
              max-height="110"
              max-width="110"
              class="ml-8"
              src="/adminlogo.png" id="site-logo"
            >
          </v-img>
          <v-img v-else
              max-height="110"
              max-width="110"
              class="ml-8"
              :src="logo"
              id="site-logo"
            >
          </v-img>
          <h3 class="ml-10" id="role-wrapper">{{roles[this.role - 1]}}</h3>
        <v-spacer></v-spacer>
        <!-- <div> -->
        <div v-if="role == 1" class="mr-10">
          <v-btn
            v-for="link in links"
            :key="link.id"
            @click="goTO(link.route)"
            text
          >
            {{ link.text }}
          </v-btn>
         
        </div>
          <!-- <v-btn text @click="logout">
            log-out
          </v-btn> -->
          
          <v-menu bottom>
            <template v-slot:activator="{ attrs, on }">
              <v-btn icon v-bind="attrs"   v-on="on" rounded><v-icon color="#000" size="30">mdi-account</v-icon></v-btn>
            </template>

            <v-list>
              <v-list-item link v-if="role == 1">
                <v-list-item-title @click="dialog = true">Info</v-list-item-title>
              </v-list-item>
              <v-list-item link>
                <v-list-item-title @click="logout">Logout</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
      </v-container>
    </v-app-bar>
    
     <v-dialog class="pa-30"
            v-model="dialog"
            persistent
            max-width="400px"
          >
            <v-card id="modal-wrapper" class="pa-5">
              <v-card-title class="pa-0">Add admin info</v-card-title>
              <hr>
                <div class="text-center">
                 <div class="item-wrapper d-flex flex-column">
                      <v-icon dark color="#000" size="25" id="closemodal" @click="closeDialog()">
                        mdi-close
                      </v-icon>
                    <div class="add-image-wrapper-logo" @click="getImage">
                         <v-img v-if="!payload.logo"
                            src="/adminlogo.png" id="selected-image"
                          >
                        </v-img>
                         <v-img v-else
                            :src="payload.logo" id="selected-image"
                          >
                        </v-img>
                    </div>
                    <input type="file" multiple ref="file_input" class="d-none" accept="image/*" @change="getFiles">

                    <v-text-field
                      label="Gcash name"
                      filled
                      hide-details
                      v-model="payload.gcash_name"
                      clearable
                      background-color="white"
                    ></v-text-field>
                    <v-text-field
                      label="Gcash number"
                      filled
                      hide-details
                      v-model="payload.gcash_number"
                      clearable
                      @keydown="isNumber($event)"
                      background-color="white"
                    ></v-text-field>
                    <v-text-field
                    class="text-center"
                      label="Contact number"
                      filled
                      background-color="white"
                      v-model="payload.contact_number"
                      clearable
                      @keydown="isNumber($event)"
                      hide-details
                    ></v-text-field>
                       <v-btn dark  :loading="loading" @click="saveInfo" height="50" class="mt-5">
                        Save info
                      </v-btn>
                  </div>
                </div>
            </v-card>
          </v-dialog>

    <v-main class="grey lighten-3" id="main_admin" >
       <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      role:'',
      items: [
        { title: 'info' },
        { title: 'logout' },
      ],
      links: [
        {
        text: "Category",
          route: "category",
        },
        {
        text: "Edit / Add items",
          route: "items",
        },
        {
          text: "Purchase History",
          route: "history",
        },
        {
          text: "Account Management",
          route: "employee_account",
        },
      ],
      roles :[
        'Admin',
        'Cashier',
        'Kitchen',
      ],
      payload :{
        logo:'/adminlogo.png',
        gcash_name: '',
        contact_number : '',
        contact_number : '',
      },
      logo : '',
      
      dialog :false,
      loading :false,
      curentRoles : ''
    }),
    mounted(){
      this.role = localStorage.getItem('role');
      this.getInfo();
      
    },
    methods:{
      closeDialog(){
        this.dialog = false;
        this.payload = {
          logo:'/adminlogo.png',
          gcash_number : '',
          contact_number : '',
        };
      },
      getImage(){
        this.$refs.file_input.click()
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
                    this.payload.logo = e.target.result;
                    // this.submit(data);
                  };
                  reader.readAsDataURL(dataFile);

            },this);
            // }
        }
      },
      goTO(link){
        this.$router.push({ name: link})
      },
      isNumber(e){
        const keysAllowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', 'Backspace'];
        const keyPressed = e.key;
        if (!keysAllowed.includes(keyPressed)) {
          e.preventDefault()
        }
      },
      logout(){
        localStorage.removeItem('role');
        localStorage.removeItem('token');
        this.$router.push({ name: 'login'})
      },
      saveInfo(){
        this.loading = true;
        var thiss = this;
        var bol = this.payload.id
        this.axios.post('/admin/admininfo', this.payload).then((response) => {
          this.loading = false;
          this.dialog = false;
          this.getInfo();
          if(bol){
            return this.$awn.success('Admin info updated successfully');
          }
          this.$awn.success('Admin info added successfully');
        }).catch(function (error)
        {
            thiss.loading = false;
          Object.values(error.response.data.errors).forEach(element => {
               thiss.$awn.warning(element[0]);
          });
        });
      },
      getInfo(){
        this.axios.get('/admin/admininfo').then((response) => {
          this.payload = Object.assign(this.payload,response.data);
          this.logo = this.payload.logo;
        }).catch(function (error)
        {
        });
      }
    }
  }
</script>
<style scoped>
#inspire header{
      height: 130px !important;
}
#main_admin{
  padding: 130px 0px 0px !important;
}
#role-wrapper{
    font-weight: 700;
    font-family: auto;
}
#header-app{
      border-bottom: solid 2px #000 !important;
}
.add-image-wrapper-logo h4{
  color: #fff;
}
#selected-image{
 border-radius: 130px !important;
}
.add-image-wrapper-logo {
    border: solid 2px;
    border-radius: 130px;
    height: 250px;
    width: 247px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #676767;
}
#site-logo{
    border-radius: 50px;
    height: 150px;
}
</style>