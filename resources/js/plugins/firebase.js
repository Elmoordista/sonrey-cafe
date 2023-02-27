import { initializeApp, getApps } from "firebase/app";
import { getMessaging } from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyDrx3kMu9Wr-V-kzSTBheaKVVMqwQddmbw",
    authDomain: "order-no.firebaseapp.com",
    projectId: "order-no",
    storageBucket: "order-no.appspot.com",
    messagingSenderId: "63679759765",
    appId: "1:63679759765:web:df8f49dda395e63dc8229c"
};

const apps = getApps()

const app = !apps.length ? initializeApp(firebaseConfig) : apps[0];

export const messaging =getMessaging(app)
