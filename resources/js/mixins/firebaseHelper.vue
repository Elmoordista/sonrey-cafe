<script>
import { getMessaging, onMessage, getToken } from 'firebase/messaging'
import { messaging } from '../plugins/firebase'
export default {
  methods: {
    async getDeviceToken () {
      const token = await getToken(messaging, {
        vapidKey: 'BLmcPdKYUAajYCxQC1DPNvfpewEqKm2vmnv9NL-T8n37GOscYjCzh1txMWfQ_HAi0p0wIcPqbG7w3H_QIaGMMbw'
      })
      if (token) {
        this.initialize(token)
      }
    },
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
