<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button
            :text="getBackButtonText()"
            default-href="/"
          ></ion-back-button>
        </ion-buttons>
        <ion-button color="primary" slot="end" @click="submit">
          Save
        </ion-button>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-grid>
        <ion-row>
          <ion-col size="12">
            <ion-item>
              <ion-input
                v-model="title"
                placeholder="News Title here"
              ></ion-input>
            </ion-item>
          </ion-col>
        </ion-row>
        <ion-row>
          <ion-col size="12">
            <editor-content :editor="editor" />
          </ion-col>
        </ion-row>
      </ion-grid>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import {
  IonBackButton,
  IonInput,
  IonButtons,
  IonContent,
  IonHeader,
  IonPage,
  IonToolbar,
} from "@ionic/vue";
import { personCircle } from "ionicons/icons";
import { defineComponent } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import { loadingController } from "@ionic/vue";
import { inject } from "vue";
export default defineComponent({
  name: "Home",
  data() {
    return {
      personCircle,
      editor: null,
      loading: null,
      description: null,
      title: null,
      axios: inject("axios"),
      getBackButtonText: () => {
        const win = window as any;
        const mode = win && win.Ionic && win.Ionic.mode;
        return mode === "ios" ? "Inbox" : "";
      },
    };
  },

  setup() {
    // const route = useRoute();
    // const message = getMessage(parseInt(route.params.id as string, 10));
    // return { message }
  },
  methods: {
    async presentLoading() {
      this.loading = await loadingController.create({
        cssClass: "my-custom-class",
        message: "Please wait...",
      });

      await this.loading.present();
    },
    submit() {
      if (this.title && this.editor.getHTML() !== '<p></p>') {
        this.presentLoading().then(() => {
          this.axios
            .post("http://127.0.0.1:8000/api/news", {
              title: this.title,
              content: this.editor.getHTML(),
            })
            .then( response => {
              loadingController.dismiss();
              if(response.data.message === 'success'){
                this.$swal("News Successfully inserted");
              }
            })
            .catch( error => {
              console.log(error);
            });
        });
      } else {
        this.$swal("Plese complete the fields");
      }
    },
  },
  components: {
    IonBackButton,
    IonButtons,
    IonContent,
    IonHeader,
    IonPage,
    IonToolbar,
    IonInput,
    EditorContent,
  },
  mounted() {
    this.editor = new Editor({
      content: "Type Content of news here 🎉",
      extensions: [StarterKit],
    });
  },

  beforeUnmount() {
    this.editor.destroy();
  },
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