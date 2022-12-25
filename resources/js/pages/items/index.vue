<template>
    <div id="left-side-bar" color="#FFCC6C">
        <v-row>
          <v-col cols="12 pa-10 d-flex " id="left-list-wrapper">
              <div class="item-wrapper d-flex flex-column">
                <div class="border border-dark" id="list-item">
                  <v-img
                    max-height="160"
                    max-width="160"
                    src="../images/chicksilog.png"
                  >
                  </v-img>
                  <h4>Chick Silog</h4>
                  <h4>₱ 105.00</h4>
                </div>
                 <v-btn dark>
                    Edit
                  </v-btn>
                 <v-btn wh>
                    Delete
                  </v-btn>
              </div>
              <div class="item-wrapper d-flex flex-column">
                <div class="border border-dark" id="list-item">
                  <v-img
                    max-height="160"
                    max-width="160"
                    src="../images/longsilog.png"
                  >
                  </v-img>
                  <h4>Long Silog</h4>
                  <h4>₱ 85.00</h4>
                </div>
                 <v-btn dark>
                    Edit
                  </v-btn>
                 <v-btn wh>
                    Delete
                  </v-btn>
             </div>
               <v-btn
                class="mx-2" id="btn-add"
                dark
                color="white"
                 @click="dialog = true"
              >
                <v-icon dark color="#000" size="50"  >
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
            <v-card id="modal-wrapper">
                <div class="text-center">
                 <div class="item-wrapper d-flex flex-column">
                    <h4>Add item</h4>
                      <v-icon dark color="#000" size="25" id="closemodal" @click="dialog=false">
                        mdi-close
                      </v-icon>
                    <div class="add-image-wrapper">
                      <h4>Click to add new Photo</h4>
                    </div>
                    <v-text-field
                      label="Item Name"
                      filled
                      rounded
                      hide-details
                      dense
                      clearable
                      background-color="white"
                    ></v-text-field>
                    <v-text-field
                    class="text-center"
                      label="Item Price"
                      filled
                      rounded
                      dense
                      background-color="white"

                      clearable
                      hide-details
                    ></v-text-field>
                       <v-btn dark>
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
    payload: null,
    items: [
      {
        text: "Add Employees Account",
        route: "new_account",
      },
      {
        text: "Employees Accounts",
        route: "employee_account",
      },
      {
        text: "Users Account",
        route: "user_account",
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
    background-color: #FFF5BF;
}
#left-side-bar .row{
    height: 100%;
    margin: 0;
}
#left-list-wrapper{
    padding: 0;
    gap: 10px;
    max-width: 40%;
    max-height: 440px;
}
#left-list .v-sheet {
    height: 100%;
}
#list-item h4{
  margin-top: 10px;
  margin-bottom: 0;
}
#list-item{
    border-radius: 25px;
    height: 270px;
    display: flex;
    width: -moz-fit-content;
    width: fit-content;
    padding: 25px;
    background-color: #fff;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}
.item-wrapper{
  gap: 10px;
}
.item-wrapper button{
  border-radius: 15px;
}
#btn-add{
  height: 100%;
  width: 210px;
  border-radius: 41px;
}
.add-image-wrapper{
    padding: 18px;
    border: solid 2px;
    border-radius: 10px;
    height: 250px;
    margin-bottom: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #676767;
}
.add-image-wrapper h4{
    color: #fff;
}
#modal-wrapper{
  padding: 40px;
   background-color: #FFF5BF;
}
#closemodal{
    position: absolute;
    top: 20px;
    right: 20px;
}
</style>

