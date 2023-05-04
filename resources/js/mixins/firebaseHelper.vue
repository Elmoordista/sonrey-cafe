<script>
import { getMessaging, onMessage, getToken } from 'firebase/messaging'
import { messaging } from '../plugins/firebase'
export default {
  methods: {
    // async getDeviceToken () {
    //   try {
    //       const token = await getToken(messaging, {
    //         vapidKey: 'BLmcPdKYUAajYCxQC1DPNvfpewEqKm2vmnv9NL-T8n37GOscYjCzh1txMWfQ_HAi0p0wIcPqbG7w3H_QIaGMMbw'
    //       })
    //       if (token) {
    //         this.initialize(token)
    //       }
    //     } catch (err) {
    //         console.log('An error occurred while retrieving token. ', err);
    //     }
    // },
    activateNotification(){
      const messaging = getMessaging()
      onMessage(messaging, (payload) => {
        // alert('test');
        this.initialize(true)
        // this.saveCoachNotification(payload)
      })
    },
  }
}
</script>
