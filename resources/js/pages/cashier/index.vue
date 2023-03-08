<template>
    <div id="left-side-bar">
        <v-row>
          <v-col id="left-list">
              <v-list color="#FFCC6C">
                <v-list-item
                   v-for="link in items"
                  :key="link.id"
                  @click="gotO(link.route)"
                  active-color="primary"
                  :value="link.route"
                  :class="link.route == routes? 'active-btn':''"
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
  props: {
    source: String,
    dialog:Boolean
  },
  data: () => ({
    drawer: null,
    payload: null,
    routes: '',
    items: [
      // {
      //   text: "Add Employees Account",
      //   route: "new_account",
      // },
      {
        text: "Add order",
        route: "add_order",
        icon: "mdi-cart",
      },
      {
        text: "Order list",
        route: "order_list",
        icon: "mdi-format-list-bulleted",

      },
    ],
  }),
  computed: {
    left_side_bar: {
      get: function () {
        return this.$store.getters.left_sidebar_status;
      },
      set: function (newValue) {
        if (
          this.$route.name.indexOf("ariations") != -1 ||
          this.$route.name.indexOf("izes") != -1 ||
          this.$route.name.indexOf("ategories") != -1 ||
          this.$route.name.indexOf("treams") != -1
        ) {
          this.sublist = true;
        }
      },
    },
  },

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
  },
  created(){
    var role = localStorage.getItem('role');
    if (role != 2) {
      return this.$router.push({ name: role == 1? 'total_sales' : 'orders'});
    }
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
  max-width: 10%;
}
#left-list .v-sheet {
    height: 100%;
}

#router-view{
   background-color: #FFF5BF;
}

</style>

