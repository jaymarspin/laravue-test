<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-title slot="start">News List</ion-title>
        <ion-button slot="end" :routerLink="'/addnews'" >Add News</ion-button>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true">
      <ion-refresher slot="fixed" @ionRefresh="refresh($event)">
        <ion-refresher-content></ion-refresher-content>
      </ion-refresher>
      
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Inbox</ion-title>
          
        </ion-toolbar>
      </ion-header>
      
      <ion-list>
        <MessageListItem v-for="message in messagess" :key="message.id" :message="message" />
      </ion-list>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonContent, IonButton , IonHeader, IonList, IonPage, IonRefresher, IonRefresherContent, IonTitle, IonToolbar } from '@ionic/vue';
import MessageListItem from '@/components/MessageListItem.vue';
import { defineComponent } from 'vue';
import { getMessages } from '@/data/messages';
import { loadingController } from "@ionic/vue";
import { inject } from "vue";
export default defineComponent({
  name: 'Home',
  data() {
    
    return {
      messagess: [],
      loading: null,
      axios: inject("axios"),
      messages: getMessages()
    }
  },
  methods: {
    
    async presentLoading() {
      this.loading = await loadingController.create({
        cssClass: "my-custom-class",
        message: "Please wait...",
      });

      await this.loading.present();
    },


    getData(){
  //     fromName: 'Joanne Pollan',
  //   subject: 'Updated invitation: Swim lessons',
  //   date: 'Yesterday',
  //   id: 4
  // },
      this.presentLoading().then(() => {
          this.axios
            .get("http://127.0.0.1:8000/api/news")
            .then( (response) => {
              console.log(response.data);
              for (const value of response.data) {
                console.log(value)
                const data = {
                  id: value.id,
                  title: value.title,
                  content: value.content,
                  date: value.created_at
                }
                this.messagess.push(data)
                console.log(this.messagess)
              }
              loadingController.dismiss()
            })
            .catch( (error) => {
              console.log(error);
            });
        });
    },
    refresh: (ev: CustomEvent) => {
      setTimeout(() => {
        ev.detail.complete();
      }, 3000);
    },
  },
  components: {
    IonContent,
    IonHeader,
    IonList,
    IonPage,
    IonRefresher,
    IonRefresherContent,
    IonTitle,
    IonToolbar,
    IonButton,
    MessageListItem
  },
  mounted() {
    this.getData()
  }
});

function getData() {
  throw new Error('Function not implemented.');
}
</script>