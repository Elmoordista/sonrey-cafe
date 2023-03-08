<template>
  <v-container fluid class="position-relative">
      <div class="h1 text-center mb-10">
        Employees Acccount
      </div>
      <v-btn @click="dialog = true"
        class="mx-2"
        fab
        dark
        color="orage"
        id="new_user"
      >
        <v-icon dark>
          mdi-plus
        </v-icon>
      </v-btn>
      <template>
        <v-row class="d-flex" style="gap:10px">
              <v-card class="pa-5 d-flex justify-content-between align-items-center" v-for="account in items" :key="account.id">
                <div>
                  <p class="ma-0">Role: {{getRole(account.role)}}</p>
                  <p class="ma-0">Username: {{account.email}}</p>
                  <p class="ma-0">Password: *******</p>
                </div>
                <div>
                <v-btn dark width="150" rounded @click="modifyAccount(account)">
                  Modify
                </v-btn>
                <v-btn wh width="150" rounded class="ml-5" @click="deleteAccount(account)">
                  Delete
                </v-btn>
                </div>
              </v-card>
        </v-row>

         <v-dialog class="pa-30"
            v-model="dialog"
            persistent
            max-width="450px"
          >
            <v-card id="modal-wrapper">
                <div class="text-center">
                 <div class="item-wrapper d-flex flex-column">
                    <h4 >Add Employee</h4>
                    <hr class="mb-5">
                      <v-icon dark color="#000" size="25" id="closemodal" @click="closeDialog()">
                        mdi-close
                      </v-icon>

                    <v-text-field
                      label="Email"
                      filled
                      rounded
                      hide-details
                      v-model="payload.email"
                      clearable
                      background-color="white"
                      append-icon="mdi-email"
                    ></v-text-field>
                    <v-text-field
                    class="text-center"
                      label="Password"
                      filled
                      rounded
                      :type="!showpassword1 ? 'password' : 'text'"
                      background-color="white"
                      v-model="payload.password"
                      hide-details
                      @click:append="showpassword1 =! showpassword1"
                      :append-icon="!showpassword1 ? 'mdi-eye' : 'mdi-eye-off' "
                    ></v-text-field>
                    <v-text-field
                    class="text-center"
                      label="Confirm Password"
                      filled
                      rounded
                      :type="!showpassword2 ? 'password' : 'text'"
                      background-color="white"
                      v-model="payload.confirmpassword"
                      hide-details
                      @click:append="showpassword2=!showpassword2"
                      :append-icon="!showpassword2 ? 'mdi-eye' : 'mdi-eye-off' "
                    ></v-text-field>
                     <v-select
                    item-text="name"
                    item-value="id"
                    :items="options"
                    label="Role"
                    solo
                    rounded
                    v-model="payload.role"
                    ></v-select>
                       <v-btn dark @click="saveEmployee" :loading="loading">
                        Save
                      </v-btn>
                  </div>
                </div>
            </v-card>
          </v-dialog>
      </template>
    
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        search: '',
        dialog: false,
        loading: false,
        showpassword1: false,
        showpassword2: false,
        noimage: 'data:image/jpeg;base64,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',
        headers: [
          { text: 'Image', value: 'image'  },
          { text: 'Name', value: 'name' },
          { text: 'Email', value: 'email' },
          { text: 'Birth date', value: 'birth_date' },
          { text: 'Number', value: 'number' },
          { text: 'Username', value: 'username' },
          { text: 'Action', value: 'action' },
        ],
        items: [
        ],
        payload:{
          role:1,
        },
        options: [
          {
              id:1,
              name:'Admin',
          },
          {
              id:2,
              name:'Cashier',
          },
          {
              id:3,
              name:'Kitchen',
          },
        ]
      }
    },
    mounted(){
      // this.$awn.success('invalid date combination');
      this.getAdmin();
    },
    methods:{
      gotO(link){
        this.$router.push({ name: link})
      },
      modifyAccount(item){
        this.payload = Object.assign(this.payload, item);
        this.payload.password = '';
        this.payload.confirmpassword = '';
        this.dialog= true;
      },
      deleteAccount(item){
          let notifier = this.$awn;
          let onOk = () => {this.deleteConfirm(item)};
          let onCancel = () => {};
          notifier.confirm(
            'Are you you want to delete this Account?',
            onOk,
            onCancel,
            {
              labels: {
                confirm: 'Delete confirmation'
              }
            }
          )
      },
      deleteConfirm(item){
        let thiss = this;
        this.$awn.asyncBlock(
           thiss.axios.delete(`/admin/admin/${item.id}/${item.role}`)
          .then((response) => {
              thiss.getAdmin()
              thiss.closeDialog()
              thiss.$awn.success('Deleted successfully')
            })
          .catch((error) => {
            return thiss.$awn.alert('Must be have 1 account for the role of ' + this.getRole(error.response.data.message[0].role));
          }),
          false,
        )

      },
      closeDialog(item){
        this.payload = {};
        this.payload.role = 1;
        this.dialog = false;
      },
      getAdmin(){
        this.axios.get('/admin/admin').then((response) => {
          this.items = response.data
        })
      },
      getRole(id){
       var role = this.options.filter(x => x.id === id);
        return role[0]['name'];
      },
      saveEmployee(){
        var thiss = this;
          if(this.payload.password != this.payload.confirmpassword){
            return this.$awn.warning('Password and Confirm password not match');
          }
         this.axios.post('/admin/admin',this.payload)
         .then((response) => {
            this.getAdmin();
            this.closeDialog();
            this.$awn.success('New employee account added');
         })
        .catch(function (error)
        {
          Object.values(error.response.data.errors).forEach(element => {
               thiss.$awn.warning(element[0]);
          });
        });
      },
      editUser(item){
          this.$router.push({ name: 'edit_account' , params: { id: item.id}})
      },
      deleteUser(item){
          this.axios.delete('/admin/client/'+item.id).then((response) => {
             this.getAdmin();
          })
      },
    }
  }
</script>

<style scoped>
#new_user{
      position: absolute;
    top: 0;
    right: 0;
}
</style>