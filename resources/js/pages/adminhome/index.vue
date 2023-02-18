<template>
  <v-app id="inspire">
    <v-app-bar
      app
      color="#FFCC6C"
      flat
    >
      <v-container class="py-0 fill-height ma-0">
        <v-img
            max-height="130"
            max-width="130"
            src="/adminlogo.png"
          ></v-img>
        <v-spacer></v-spacer>
        <div>
          <v-btn
            v-for="link in links"
            :key="link.id"
            @click="goTO(link.route)"
            text
          >
            {{ link.text }}
          </v-btn>
        </div>

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
      links: [
        {
        text: "Category  &  Roles",
          route: "settings",
        },
        {
        text: "Edit / Add items",
          route: "items",
        },
        {
          text: "Purchase History",
          route: "purchase",
        },
        {
          text: "Account Management",
          route: "accounts",
        },
      ],
    }),

    methods:{
      goTO(link){
      this.$router.push({ name: link})
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
</style>