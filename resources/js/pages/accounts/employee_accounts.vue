<template>
  <v-container fluid>
      <div class="h1 text-center mb-10">
        Employees Acccount
      </div>
      <template>
        <v-row class="d-flex" style="gap:10px">
          <v-card class="pa-5 d-flex justify-content-between align-items-center">
            <div>
              <p class="ma-0">Role: Admin</p>
              <p class="ma-0">Username: admin@gmail.com</p>
              <p class="ma-0">Password: abc*****</p>
            </div>
            <div>
             <v-btn dark width="150" rounded>
              Modify
            </v-btn>
            <v-btn wh width="150" rounded class="ml-5">
              Delete
            </v-btn>
            </div>
          </v-card>
          <v-card class="pa-5 d-flex justify-content-between align-items-center">
            <div>
              <p class="ma-0">Role: Kitchen</p>
              <p class="ma-0">Username: kitchen@gmail.com</p>
              <p class="ma-0">Password: abc*****</p>
            </div>
            <div>
             <v-btn dark width="150" rounded>
              Modify
            </v-btn>
            <v-btn wh width="150" rounded class="ml-5">
              Delete
            </v-btn>
            </div>
          </v-card>
          <v-card class="pa-5 d-flex justify-content-between align-items-center">
            <div>
              <p class="ma-0">Role: Cashier</p>
              <p class="ma-0">Username: cashier @gmail.com</p>
              <p class="ma-0">Password: abc*****</p>
            </div>
            <div>
             <v-btn dark width="150" rounded>
              Modify
            </v-btn>
            <v-btn wh width="150" rounded class="ml-5">
              Delete
            </v-btn>
            </div>
          </v-card>
        </v-row>
      </template>
    
  </v-container>
</template>