<template>
  <v-container fluid>
      <div class="h1 text-center mb-10">
        Add Employees Account 
      </div>

      <div>
         <v-row>
          <v-col lg="6" md="6" sm="12">
            <div class="text-center mb-10">
              <div class="image-holder">
               <div>
                  <h5>Profile Image:</h5>
                  <v-img class="text-center" v-if="payload.image == ''"
                      max-height="200"
                      max-width="200"
                      src="data:image/jpeg;base64,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"
                    >
                  </v-img>
                  <v-img class="text-center" v-else
                      max-height="200"
                      max-width="200"
                      :src="payload.image"
                    >
                  </v-img>
                  <input type="file" multiple ref="file_input" class="d-none" accept="image/*" @change="getFiles">
              </div>
               <v-btn depressed @click="getImage">
                insert image
              </v-btn>
              </div>
            </div>
          <h4 >Email:</h4>
           <v-text-field class=""
            filled
            placeholder="email"
            solo
            clearable
            hide-details
            append-icon="mdi-email"
            v-model="payload.email"
            background-color="white"
          ></v-text-field>
           <h4 class="mt-5">Birthdate:</h4>
              <v-menu
              v-model="menu"
              :close-on-content-click="true"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="payload.birth_date"
                  append-icon="mdi-calendar"
                  readonly
    
                  v-bind="attrs"
                  v-on="on"
                  hide-details
                  background-color="white"
                  solo
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="payload.birth_date"
              ></v-date-picker>
            </v-menu>
           <h4 class="mt-5">Contact Number:</h4>
          <v-text-field
          class="text-center "
            filled
            solo
            hide-details
            placeholder="Contact Number"
            background-color="white"
            clearable
            append-icon="mdi-phone"
            v-model="payload.number"
          ></v-text-field>
          </v-col>
         <v-col lg="6" md="6" sm="12">
          <h4 class="mt-5">Name: </h4>
           <v-text-field class=""
            filled
            hide-details
            placeholder="name"
            solo
            clearable
            v-model="payload.name"
            background-color="white"
          ></v-text-field>
           <!-- <h4 >Role: </h4>
          <v-text-field
          class="text-center "
            filled
            dense
            placeholder="role"
            solo
            background-color="white"
            clearable
            v-model="payload.role"
          ></v-text-field> -->
           <h4 class="mt-5">Username:</h4>
          <v-text-field
          class="text-center"
            filled
            solo
            hide-details
            placeholder="username"
            background-color="white"
            clearable
            v-model="payload.username"
          ></v-text-field>
           <h4 class="mt-5">Password:</h4>
          <v-text-field
          class="text-center "
            filled
            solo
            hide-details
            placeholder="password"
            background-color="white"
            
            :type="!showpassword1 ? 'password' : 'text'"
            v-model="payload.password"
            @click:append="showpassword1=!showpassword1"
            :append-icon="!showpassword1? 'mdi-eye' : 'mdi-eye-off' "
          ></v-text-field>
           <h4 class="mt-5">Confirm Password:</h4>
          <v-text-field
            class="text-center "
            filled
            solo
            hide-details
            placeholder="confirmpassword"
            background-color="white"
            
            :type="!showpassword2 ? 'password' : 'text'"
            @click:append="showpassword2=!showpassword2"
            :append-icon="!showpassword2 ? 'mdi-eye' : 'mdi-eye-off' "
            v-model="payload.confirmpassword"
          ></v-text-field>
          </v-col>
        </v-row>
        <div >
          <v-btn dark class="float-right" @click="saveuser">
            Save Profile
          </v-btn>
            <v-btn dark class="float-right mr-10" @click="gotO('user_account')">
            Cancel
          </v-btn>
        </div>
      </div>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        search: '',
        menu: false,
        showpassword1: false,
        showpassword2: false,
        payload: {
            email: '',
            birth_date:  (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            number: '',
            name: '',
            role: '',
            image: '',
            username: '',
            password: '',
            confirmpassword: '',
        },
      }
    },
    mounted(){
      this.getUser();
    },
    methods:{
      gotO(link){
        this.$router.push({ name: link})
      },
      getUser(){
          this.axios.get('/admin/client/'+`${this.$route.params.id}`).then((response) => {
            this.payload = response.data;
            this.payload.password = '';
            this.payload.confirmpassword = '';
          })
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
                    this.payload.image = e.target.result;
                    // this.submit(data);
                  };
                  reader.readAsDataURL(dataFile);

            },this);
            // }
        }
      },
      // saveuser(){
      //   delete this.payload.confirmpassword
      //   this.axios.post('/admin/client',this.payload).then((response) => {
      //      this.$router.push({ name: 'user_account'})
      //   })
      // }
      saveuser(){
        var thiss = this;
          if(this.payload.password != this.payload.confirmpassword){
            return this.$awn.warning('Password and Confirm password not match');
          }
        delete this.payload.confirmpassword
        this.axios.post('/admin/client',this.payload).then((response) => {
           this.$awn.success('Account update successfully');
           this.gotO('user_account')
        })
        .catch(function (error)
        {
          Object.values(error.response.data.errors).forEach(element => {
               thiss.$awn.warning(element[0]);
          });
        });
      }
      
    }
  }
</script>


<style scoped>
.image-holder{
  display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 11px;

}
</style>