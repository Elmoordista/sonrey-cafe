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
                >
                  <v-list-item-content>
                    <v-list-item-title>
                     {{ link.text }}
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
    items: [
      {
        text: "Purchase history",
        route: "history",
      },
      {
        text: "Total Sales",
        route: "total_sales",
      },
    ],
  }),

  methods: {
    gotO(link){
      this.$router.push({ name: link})
    }
  },
  watch: {
    dialog(val) {
      this.getAdminInfo();
    },
  },
  mounted(){

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

