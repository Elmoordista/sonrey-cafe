<template id="form_login">
  <v-form ref="loginform" id="login_form">
    <div class="login-container">
      <!-- <div class="logo">
        <v-img src="https://wilcity.com/wp-content/uploads/2018/12/sample-logo-design-png-3-2.png" id="imglogo"></v-img>
      </div> -->
      <div class="login">
        <v-content>
          <v-container>
            <v-layout row class="text-lg-center">
              <v-flex>
                <v-container class="cont _cont">
                  <v-card flat>
                    <v-card-title primary-title>
                      <h4>Login</h4>
                    </v-card-title>
                    <v-form id="v_form">
                      <v-text-field
                        name="Username"
                        label="Username"
                        :rules="checkEmail"
                        v-model="data.email"
                        outlined
                        append-icon="mdi-email"
                        rounded
                      ></v-text-field>
                      <v-text-field
                        name="Password"
                        label="Password"
                        :type="viewpass?'password':'text'"
                        :rules="checkPass"
                        v-model="data.password"
                        outlined
                        :append-icon="viewpass?'mdi-eye':'mdi-eye-off'"
                        @click:append="viewpass =! viewpass"
                        rounded
                      ></v-text-field>
                      <v-card-actions>
                        <v-btn primary large block @click="_submit" 
                        rounded
                          >Login</v-btn
                        >
                      </v-card-actions>
                    </v-form>
                  </v-card>
                </v-container>
              </v-flex>
            </v-layout>
          </v-container>
          <v-snackbar v-model="snackbar">
            {{ text }}
            <template v-slot:action="{ attrs }">
              <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                Close
              </v-btn>
            </template>
          </v-snackbar>
        </v-content>
      </div>
    </div>
  </v-form>
</template>
<script>
export default {
  //   components: {
  //     Filters,
  //   },
  data() {
    return {
      data: {
        email: "",
        password: "",
      },
      snackbar: false,
      viewpass: true,
      text: "",
      checkEmail: [
        (v) => !!v || "Email required",
        // (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ],
      checkPass: [
        (v) => !!v || "Password required",
        // (v) => (v && v.length <= 10) || "Name must be less than 10 characters",
      ],
    };
  },
  methods: {
    _submit() {
      const thiss = this;
      axios
        .post("/api/checklogin", {
          email: this.data.email,
          password: this.data.password,
        })
        .then(function (response) {
          if (response.data.message == "success") {
            localStorage.setItem("token", response.data[0].token);
            thiss.$router.go({ name: "dashboard" });
            thiss.text = "Successfully login";
          } else if (response.data.message == "Unauthorised") {
            thiss.snackbar = true;
            thiss.text = "Unauthorised admin acount";
          } else {
            thiss.snackbar = true;
            thiss.text = "Invalid Email/Password";
          }
          // localStorage.setItem("token", response.data.token);
          // thiss.$router.push({ name: "dashboard" });
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>
<style scoped>
.login .cont {
  width: 500px;
  margin: auto;
  box-shadow: 0 3px 5px -1px rgba(100, 100, 0, 0.2),
    0 5px 8px 0 rgba(100, 100, 0, 0.14), 0 1px 14px 0 rgba(100, 100, 0, 0.12) !important;
}

#login_form {
  width: 100%;
  height: 100%;
  background: linear-gradient(-45deg, #393838 45%, #bc3636 40% 100%);
  position: absolute
}
#login_form .login-container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  gap: 30px;
  padding-top: 8%;
}
#imglogo {
  width: 70%!important;
  margin: auto;
}
.theme--light.v-application {
  background-color: orange !important;
}
#v_form {
  padding: 0 16px;
}
._cont {
  border-radius: 18px;
  padding: 0;
} 
._cont .v-card {
  border-radius: 12px;
}
.flex{
  width: 450px !important;
}
.flex ._cont{
  width: 100%;
}
.v-card--flat{
  padding: 10px;
}
</style>
