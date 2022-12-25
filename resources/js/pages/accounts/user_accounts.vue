<template>
  <v-card>
    <v-card-title>
      Users Account
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="desserts"
      :search="search"
    ></v-data-table>
  </v-card>
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          { text: 'Email', value: 'email' },
          { text: 'Name', value: 'name' },
          { text: 'Number', value: 'number' },
          { text: 'Username', value: 'username' },
          { text: 'Address', value: 'address' },
        ],
        desserts: [
          {
            email: 'Test@email.com',
            name: 'Juan A. Carlo',
            number: '0965123252',
            username: 'username',
            address: 'test address',
          },
          {
            email: 'Test@email2.com',
            name: 'pedro A. Carlo',
            number: '0965123252',
            username: 'username2',
            address: 'test address2',
          },
          {
            email: 'Test@email.com',
            name: 'Coco A. Carlo',
            number: '096512325215',
            username: 'username3',
            address: 'test address3',
          },
        ],
      }
    },
  }
</script>