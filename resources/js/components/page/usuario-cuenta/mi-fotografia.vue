<template lang="html">
<div>
  <b-form class="c-f-g-2-wrp sm" @submit.prevent="onSubmit()">
    <h5 class="main-page-title">{{ $t("account.mylogo.mylogo") }}</h5>
    <hr class="c-hr" />
    <p>{{ $t("account.mylogo.subtitle") }}</p>

    <b-form-group class="cus-f-group-2 mb-1 text-center" label="Logo"></b-form-group>

    <div class="row justify-content-center">
      <div class="col-lg-9 mb-4">
        <div class="box-image-uploader">
          <img class="empty" src="/images/shared/empty.png">

          <b-form-group class="box-btn-photo" v-show="!imagePhotoUrl">
            <label class="fake-label" for="img-photo-i1">
              <div class="inside">
                <h5>{{ $t("account.mylogo.extention") }}</h5>
              </div>
            </label>
            <input id="img-photo-i1" name="image" accept=".png,.jpg" type="file" @change="onFileChange('imagePhoto', $event)">
          </b-form-group>

          <div class="placed-backg box-img-fake" v-bind:style="{ backgroundImage: 'url('+imagePhotoUrl+')' }" v-show="imagePhotoUrl">
            <div class="inside">
              <a class="btn btn-s1 bg-blue" @click="removeImage('imagePhoto')">{{ $t("account.mylogo.quit") }}</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 text-center">
        <b-button type="submit" class="btn-s1 bg-blue" v-if="imagePhotoUrl">{{ $t("account.mylogo.save_logo") }}</b-button>
      </div>
    </div>

  </b-form>

  <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
    <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
    {{modal.msg}}
    <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
    <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">OK</b-button>
    </div>
  </sweet-modal>
</div>
</template>

<script>
export default {
  data(){
    return{
      imagePhotoUrl: null,

      modal:{
        msg:'',
        icon:'',
        block:false,
      },
    }
  },

  methods: {
    onFileChange(target, e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length){
        return;
      }

      // ----- Create image ----
      var image = new Image();
      var reader = new FileReader();

      reader.onload = (e) => {
        if(target == 'imagePhoto'){
          this.imagePhotoUrl = e.target.result;
        }
      };

      reader.readAsDataURL(files[0]);
    },

    removeImage: function (target) {
      if(target == 'imagePhoto'){
        this.imagePhotoUrl = false;
      }
      if(target == 'imageSignature'){
        this.imageSignature = false;
      }
    },

    onSubmit(){
         this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        var formData = new FormData();
        formData.append("image", jQuery('input[name="image"]')[0].files[0]);

        axios.post(tools.url('/api/user/image'), formData).then((response)=>{

                this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = response.data.msg;
                this.$root.auth();


        }).catch((error)=>{
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = response.data.msg;

        });
    },

  },
  mounted(){
      this.imagePhotoUrl = this.$root.user.imageUrlLogo;
  }
}
</script>
