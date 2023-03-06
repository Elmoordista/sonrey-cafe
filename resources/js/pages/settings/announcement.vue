<template>
    <div>
        <div class="d-flex justify-content-between align-center">
            <h3 class="mb-0">Announcement</h3>
            <v-btn color="#ffcc6c" fab @click="drawer = true"><v-icon>mdi-plus</v-icon></v-btn>
        </div>
        <hr>
        <v-data-table class="bg-transparent"
            :headers="headers"
            :items="items"
            :items-per-page="10"
        >
         <template v-slot:item.image="{ item }">
            <div>
                <v-img class="text-center"
                    height="150"
                    width="200"
                    :src="item.image"
                >
                </v-img>
            </div>
        </template>
        <template v-slot:item.action="{ item }">
            <div>
            <v-icon @click="editAnnouncement(item)">mdi-pencil</v-icon>
            |
            <v-icon @click="deleteAnnouncement(item)">mdi-delete</v-icon>
            </div>
        </template>
        </v-data-table>

  
            <v-navigation-drawer
                color="#FFF5BF"
                v-model="drawer"
                temporary
                right
                fixed
                @input="check($event)"
                width="30%"
                hide-overlay>
                <h4 class="mb-0 pa-4">Add Announcement</h4>
                <hr class="ma-0 mb-2"/>
                <div class="pa-2">
                <label>Tittle *</label>
                <v-text-field
                    placeholder="Tittle"
                    hide-details
                    v-model="payload.tittle"
                    class="mb-3"
                    clearable
                    solo
                    dense
                    background-color="white"
                ></v-text-field>
                <label>Link *</label>
                <v-text-field
                    placeholder="Link"
                    hide-details
                    clearable
                      class="mb-3"
                    solo
                    dense
                    v-model="payload.link"
                    background-color="white"
                ></v-text-field>

                <div class="add-image-wrapper" @click="getImage">
                    <h4 v-if="payload.image == ''">Click to add new Photo</h4>
                    <v-img v-else
                    :src="payload.image" id="selected-image"
                    >
                    </v-img>
                 </div>
                <input type="file" multiple ref="file_input" class="d-none" accept="image/*" @change="getFiles">

                  <v-flex xs12>
                <div
                    class="d-flex mb-2 justify-space-between w-100 flex-column"
                >
                    <v-btn
                    color="#ffcc6c"
                    block @click="saveAnnouncement" :loading="loading">
                    Save
                    </v-btn>
                    <v-btn
                    class="mt-1"
                    color="#ffcc6c"
                    block
                    @click="drawer = false"
                    >
                    Close
                    </v-btn>
                </div>
                </v-flex>
                </div>
            </v-navigation-drawer>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        drawer : false,
        isHideDrawer : false,
        loading : false,
        payload : {
            image:'',
            tittle:'',
            link:'',
        },
        headers: [
          {
            text: 'Image',
            value: 'image',
            width:"20%"
          },
          { text: 'Tittle', value: 'tittle' },
          { text: 'Link', value: 'link' },
          { text: 'Actions',width:"150", value: 'action' },
        ],
        items: [
        ],
      }
    },
    mounted(){
        this.getDatas();
    },
    methods:{
        getImage(){
            this.$refs.file_input.click()
        },
        editAnnouncement(item){
            this.payload = Object.assign(this.payload, item)
            this.drawer = true;
        },
        deleteAnnouncement(item){
            let notifier = this.$awn;
            let onOk = () => {this.deleteConfirm(item)};
            notifier.confirm(
                'Are you you want to delete this announcement?',
                onOk,
                false,
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
            thiss.axios.delete(`/admin/announcement/${item.id}`)
            .then((response) => {
                thiss.getDatas()
                thiss.reset()
                thiss.drawer = false;
                thiss.$awn.success('Deleted successfully')
                })
            .catch((error) => {
            }),
            false,
            )            
        },
        check(bool){
            if(bool == false){
                this.payload = {
                    image:'',
                    tittle:'',
                    link:'',
                };
            }
        },
        getDatas(){
            this.axios.get('/admin/announcement').then((response) => {
                this.items = response.data;
            })
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
            }
        },
        saveAnnouncement(){
            this.loading = true;
            var thiss = this;
            var edit = false;
            if(this.payload.id){
                edit = true;
            }
            this.axios.post('/admin/announcement',this.payload).then((response) => {
                this.loading = false;
                this.drawer = false;
                this.reset();
                this.getDatas();
                if(edit){
                    return this.$awn.success('Update announcement successfully');
                }
                this.$awn.success('New announcement added successfully');
            })
            .catch(function (error)
            {
                thiss.loading = false;
                Object.values(error.response.data.errors).forEach(element => {
                    thiss.$awn.warning(element[0]);
                });
            });
        },
        reset(){
            this.payload = {
                image:'',
                tittle:'',
                link:'',
            };
        }
    }
  }
</script>