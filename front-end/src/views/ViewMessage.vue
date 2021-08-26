<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button :text="getBackButtonText()" default-href="/"></ion-back-button>
        </ion-buttons>
        <ion-label v-if="message" style="font-size: 30px">{{ message.title }}</ion-label>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true" v-if="message">
      
      
      <div class="ion-padding">
        <i>{{ message.date }}</i>
        <div style="font-size: 22px" v-html="message.content"></div>
        
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { useRoute } from 'vue-router';
import { IonBackButton, IonLabel,IonButtons, IonContent, IonHeader, IonPage, IonToolbar } from '@ionic/vue';
import { personCircle } from 'ionicons/icons';
 
import { defineComponent } from 'vue';
 
 
export default defineComponent({
  name: 'Home',
  data() {
    return {
      personCircle,
      id: null,
      message: null,
      getBackButtonText: () => {
        const win = window as any;
        const mode = win && win.Ionic && win.Ionic.mode;
        return mode === 'ios' ? 'Inbox' : '';
      }
    }
  },

  methods: {
    getData(){
      const route = useRoute();
      this.id = route.params.id as string
      this.axios
            .get(`http://127.0.0.1:8000/api/news/${this.id}`)
            .then( response => {
              console.log(response)
              const data = {
                  title: response.data.title,
                  content: response.data.content,
                  date: response.data.created_at
                }

                this.message = data
              
            })
            .catch( (error) => {
              console.log(error);
            });
    }
  },
  components: {
    IonBackButton,
    IonButtons,
    IonContent,
    IonHeader,
    IonPage,
    IonToolbar,
    IonLabel,
  },mounted(){
    this.getData();
  }
});
</script>

<style scoped>
ion-item {
  --inner-padding-end: 0;
  --background: transparent;
}

ion-label {
  margin-top: 12px;
  margin-bottom: 12px;
}

ion-item h2 {
  font-weight: 600;
}

ion-item .date {
  float: right;
  align-items: center;
  display: flex;
}

ion-item ion-icon {
  font-size: 42px;
  margin-right: 8px;
}

ion-item ion-note {
  font-size: 15px;
  margin-right: 12px;
  font-weight: normal;
}

h1 {
  margin: 0;
  font-weight: bold;
  font-size: 22px;
}

p {
  line-height: 22px;
}
</style>