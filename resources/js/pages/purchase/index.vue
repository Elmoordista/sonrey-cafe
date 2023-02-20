<template>
    <div id="left-side-bar">
        <v-row>
          <v-col cols="2" id="left-list">
              <v-list color="#FFCC6C">
                <v-list-item
                   v-for="link in items"
                  :key="link.id"
                  @click="gotO(link.route)"
                  link
                  :class="link.route == routes ? 'active-btn':''"
                >
                  <v-list-item-content>
                    <v-list-item-title class="d-flex">
                    <v-icon size="20">{{ link.icon }}</v-icon>
                      <p class="mb-0 ml-5">
                        {{ link.text }}
                      </p>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
          </v-col>
          <v-col class="pa-10" id="router-view">
            <router-view></router-view>
          </v-col>
          <!-- <v-col class="pa-10">
            <v-sheet
              min-height="70vh"
            >
            </v-sheet>
          </v-col> -->
        </v-row>
    </div>
</template>

<script>
export default {

  data: () => ({
    drawer: null,
    payload: null,
    routes: '',
    items: [
      {
        text: "Purchase history",
        route: "history",
        icon: "mdi-cart-plus",
      },
      {
        text: "Total Sales",
        route: "total_sales",
        icon: "mdi-sale",
      },
    ],
  }),

  methods: {
    gotO(link){
      this.routes = link;
      this.$router.push({ name: link})
    }
  },
  watch: {
    dialog(val) {
      this.getAdminInfo();
    },
  },
  mounted(){
   this.routes = this.$route.name;
  }
};
</script>

<style scoped>
#left-side-bar{
    height: 100%;
}
#left-side-bar .row{
    height: 100%;
    margin: 0;
}
#left-list{
    padding: 0;
    max-width: 20%;
}
#left-list .v-sheet {
    height: 100%;
}

#router-view{
   background-color: #FFF5BF;
}

</style>

