<template>
  <v-app id="inspire">
    <v-app-bar id="header-app"
      app
      color="#FFCC6C"
      flat
    >
      <v-container class="py-0 fill-height ma-0" >
        <v-img
            max-height="110"
            max-width="110"
            src="/adminlogo.png"
            class="ml-8"
          ></v-img>
          <h3 class="ml-10" id="role-wrapper">{{roles[this.role - 1]}}</h3>
        <v-spacer></v-spacer>
        <!-- <div> -->
        <div v-if="role == 1">
          <v-btn
            v-for="link in links"
            :key="link.id"
            @click="goTO(link.route)"
            text
          >
            {{ link.text }}
          </v-btn>
         
        </div>
        <v-btn text @click="logout">
            log-out
          </v-btn>
      </v-container>
    </v-app-bar>

    <v-main class="grey lighten-3" id="main_admin" >
       <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      role:'',
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
      ]
    }),
    mounted(){
      this.role = localStorage.getItem('role');
      if(this.role == 2){
         this.$router.push({ name: 'add_order'})
      }
      else if(this.role == 3){
        this.$router.push({ name: 'orders'})
      }
      else{
        this.$router.push({ name: 'total_sales'})
      }
      // this.checkuser();
    },
    methods:{
      goTO(link){
        this.$router.push({ name: link})
      },
      logout(){
        localStorage.removeItem('role');
        localStorage.removeItem('token');
        this.$router.push({ name: 'login'})
      },
      // checkuser(){
      //   this.axios.get('/admin/admin').then((response) => {
      //     console.log(response,'admin');
      //   })
      // }
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
</style>