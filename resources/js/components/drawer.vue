<template>
  <v-navigation-drawer
    temporary
    right
    fixed
    v-model="drawer"
    width="40%"
    hide-overlay
    @input="trys($event)"
  >
    <p
      class="
        form-title
        pa-2
        title
        font-weight-regular
        text-uppercase
        d-flex
        justify-space-between
      "
    >
      {{ this.$t('announce.addNewNotification') }}
      <v-btn icon small @click="drawer = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </p>
    <v-divider ></v-divider>
    <ValidationObserver ref="form">
      <v-form class="form-box" @submit.prevent="sendForm(false)">
        <v-container grid-list-md>
          <v-layout row wrap class="px-1">

            <v-flex xs12>
              <ValidationProvider slim class="announce-text-field">
                <div class="mb-1 d-flex ">
                  <p
                    class="subtitle-2 font-weight-regular my-auto mr-2"
                  >
                    <span>client:</span>
                  </p>
                  <v-checkbox
                    class="user-checkbox"
                    v-model="selectedClientType"
                    :label="this.$t('announce.allClient')"
                    value="all"
                    @change="getClients($event)"
                    id="all"
                  ></v-checkbox>
                  <v-checkbox
                    class="user-checkbox"
                    v-model="selectedClientType"
                    :label="this.$t('announce.subscriberClient')"
                    value="subscribed"
                    @change="getClients($event)"
                    id="subscriber"
                  ></v-checkbox>
                  <v-checkbox
                    class="user-checkbox"
                    v-model="selectedClientType"
                    :label="this.$t('announce.nonSubscriberClient')"
                    value="not-subscribed"
                    @change="getClients($event)"
                    id="not-subscribed-clients"
                  ></v-checkbox>
                </div>
              </ValidationProvider>
            </v-flex>
            <v-flex xs12>
              <div class="mb-1 d-flex announce-text-field">
                <p
                  class="subtitle-2 font-weight-medium my-auto mr-1"
                >
                  <span>client:</span>
                </p>
                <v-checkbox
                  v-model="taggleAllClient"
                  class="user-checkbox"
                  :label="this.$t('announce.selecAll')"
                ></v-checkbox>
              </div>
            </v-flex>
            <v-flex xs12>
              <ValidationProvider class="announce-text-field">
                <div class="mb-1">
                  <v-autocomplete
                    class="user-option"
                    v-model="payload.clients"
                    :items="clients"
                    :placeholder="this.$t('subscription.selectClient')"
                    dense
                    solo
                    chips
                    clearable
                    small-chips
                    hide-details="auto"
                    multiple
                    prepend-inner-icon="mdi-plus"
                    :item-text="getItemText"
                    item-value="id"
                  >
                    <template v-slot:selection="{ item, index }">
                      <v-chip v-if="index === 0">
                        <span>{{ item.full_name }}</span>
                      </v-chip>
                      <span v-if="index === 1" class="grey--text text-caption">
                        (+{{ payload.clients.length - 1 }} {{ $t('announce.selected')}})
                      </span>
                    </template>
                  </v-autocomplete>
                </div>
              </ValidationProvider>
            </v-flex>
            <v-flex xs12>
              <ValidationProvider
                slim
                name="title"
                rules="required"
                v-slot="{ errors }"
              >
                <div class="mb-1">
                  <v-flex xs12>
                    <p class="subtitle-1 font-weight-medium text-uppercase">
                      {{ $t('blogs.title') }}:
                    </p>
                  </v-flex>
                  <v-text-field
                    v-model="payload.title"
                    :placeholder="$t('blogs.typeTitle')+' ...'"
                    hide-details="auto"
                    type="text"
                    :error-messages="errors"
                    solo
                  ></v-text-field>
                </div>
              </ValidationProvider>
            </v-flex>
            <v-flex xs12>
              <ValidationProvider>
                <div class="mb-1">
                  <v-flex xs12>
                    <p class="subtitle-1 font-weight-medium text-uppercase">
                      Message:
                    </p>
                  </v-flex>
                  <v-textarea
                    v-model="payload.message"
                    name="message"
                    :placeholder="$t('blogs.typeMessage')+' ...'"
                    hide-details="auto"
                    type="text"
                    solo
                  ></v-textarea>
                </div>
              </ValidationProvider>
            </v-flex>
            <v-flex xs12>
              <div
                class="d-flex mb-2 justify-space-between w-100"
              >
                <v-btn
                  @click="sendForm(true)"
                  class="success mt-1"
                 block
                >
                  <v-icon>mdi-send</v-icon>
                  {{ this.$t('announce.send') }}
                </v-btn>
              </div>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
    </ValidationObserver>
  </v-navigation-drawer>
</template>
<script>
import moment from "moment";
const is_ricurring = 1;
export default {
  data() {
    return {
      time: false,
      menu: false,
      taggleAllCoach: true,
      taggleAllClient: false,
      selectedClientType: "all",
      selectedCoachType: "all",
      payload: {
        clients: [],
        coaches: [],
        status: "",
        is_sent: 0,
        title: "",
        message: "",
        time: moment().format("HH:mm"),
        date: moment().format("YYYY-MM-DD"),
        type: 0,
        schedule_period: 3,
        cycle_count: 2,
        cycle_type: 3,
      },
      set_time: false,
      panel: 1,
      periodOptions: [
        { id: 1, text: this.$t('announce.daily') },
        { id: 2, text: this.$t('announce.weekly') },
        { id: 3, text: this.$t('announce.monthly') },
        { id: 4, text: this.$t('announce.every3months') },
        { id: 5, text: this.$t('announce.every6months') },
        { id: 6, text: this.$t('announce.everyYear') },
        { id: 7, text: this.$t('announce.custom') },
      ],
      cycleOptions: [
        { id: 1, text: this.$t('announce.daily') },
        { id: 2, text: this.$t('announce.weekly') },
        { id: 3, text: this.$t('announce.monthly') },
      ],
      clients: [],
      coaches: [],
      drawer: false,
      originalPayload: null,
      currentTime :moment().format('YYYY-MM-DD HH:mm')
    };
  },
  props: {
    drawerStatus: {
      type: Boolean,
      default: () => false,
    },

    selectedItem: {
      type:Object,
      default:() => {}
    }
  },

  computed: {
    resetForm2 () {
      return this.$store.getters.resetForm2
    }
  },

  mounted() {
    this.initialize()
  },
  methods: {
    trys(e){
      if(e==false){
        this.$refs.form.reset();
      }
    },
    initialize () {
      // this.selectAllCoach();
      this.selectAllClient();
      this.getClients();
      // this.getCoaches();
    },
    sendForm(is_sent) {
      this.$refs.form.validate().then((result) => {
        if (!result) return;

        this.payload.is_sent = is_sent;
        if(is_sent) {
          if(this.payload.id && this.currentTime<=this.payload) {
            if(this.payload.type==0){
              this.payload.status = 0;
            }
            this.$emit("updateRecord", this.payload);
          }else{
            this.payload.status = 1;
            this.$emit("addRecord", this.payload);
          }
        }else{
          this.payload.status = 0;
          this.$emit("saveRecord", this.payload)
        }
      });
    },
    selectAllCoach() {
      if (this.taggleAllCoach == true) {
        const selectedCoachIds = this.coaches.map(({ id }) => id);
        this.payload.coaches = selectedCoachIds;
      } else {
        this.payload.coaches = [];
      }
    },
    selectAllClient() {
      if (this.taggleAllClient == true) {
        const selectedClientIds = this.clients.map(({ id }) => id);
        this.payload.clients = selectedClientIds;
      } else {
        this.payload.clients = [];
      }
    },
    getClients(type) {
      this.$axios
        .get(`clients?type=${type}&no-paginate=true`)
        .then(({ data }) => {
          this.clients = data.data;
          this.selectAllClient()
        });
    },
    getCoaches(type) {
      this.$axios
        .get(`coaches?type=${type}&no-paginate=true`)
        .then(({ data }) => {
          this.coaches = data.data;
          this.selectAllCoach()
        });
    },
    getItemText(item) {
      return `${item.first_name} ${item.last_name}`;
    },
    reset() {
      this.payload.clients= [],
      this.payload.coaches= [],
      this.payload.status= "",
      this.payload.is_sent= 0,
      this.payload.title= "",
      this.payload.message= "",
      this.payload.time= moment().format("HH:mm"),
      this.payload.date= moment().format("YYYY-MM-DD"),
      this.payload.type= 0,
      this.payload.schedule_period= 3,
      this.payload.cycle_count= 2,
      this.payload.cycle_type= 3,
      this.panel='undefined',

      this.initialize()
      this.$nextTick(() => {
        this.$refs.form.reset();
      });
    },
  },
  watch: {
    resetForm2 (val) {
      if (val) {
        this.$store.commit('resetFormMutation', false)
        this.reset()
      }
    },
    taggleAllCoach: {
      handler(val) {
        // this.selectAllCoach(val);
      },
    },
    taggleAllClient: {
      handler(val) {
        this.selectAllClient(val);
      },
    },
    drawerStatus(val) {
      if (val && !this.originalPayload)
        this.originalPayload = this.cloneVariable(this.payload);
        this.drawer = val;
    },
    drawer(val) {
      if (!val) {
        if (this.payload.id)
          this.payload = this.cloneVariable(this.originalPayload);
          this.$emit("closeDrawer");
      }
    },
    selectedItem: {
      handler(val) {
        this.payload = this.cloneVariable(val);

        const selectedClientIds = this.payload.clients.map(({ id }) => id);
        this.payload.clients = selectedClientIds;

        const selectedCoachesIds = this.payload.coaches.map(({ id }) => id);
        this.payload.coaches = selectedCoachesIds;
      },
      deep: true,
    },
  },
};
</script>

<style >
.submit-btn{
  background-color:rgb(72, 72, 173) !important
}
</style>
