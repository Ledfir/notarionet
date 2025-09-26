<template lang="html">
  <div id="contact-page">

    <section class="container main-section">
      <div class="row align-items-center">
        <div class="col-12 col-title">
          <h1 class="title-s-1">{{ $t("contact.contact") }}</h1>
        </div>

        <div class="col-lg-6 col-info">
          <h5 class="title"> {{ $t("contact.qustions") }}<br />{{ $t("contact.send_ms") }}</h5>

          <h5 class="mt-3 subtitle" hidden>{{ $t("contact.address") }}</h5>
          <p hidden>
            Av. Juárez 624, Adolfo L. Mateos.<br />
            C.P. 77667<br />
            Cozumel, Quintana Roo
          </p>

          <h5 class="mt-3 subtitle">{{ $t("contact.phone") }}</h5>
          <p>
            Tel. <a href="tel:5219871119588">+52 1 987 869 8658</a>
          </p>

          <h5 class="mt-3 subtitle">{{ $t("contact.email") }}</h5>
          <p>
            <a href="mailto:hola@notarionet.com">hola@notarionet.com</a>
          </p>

          <h5 class="mt-3 subtitle">{{ $t("contact.follow") }}</h5>
          <p>
            <a class="btn-network mr-1" href="#"><i class="fab fa-facebook-square"></i></a>
            <a class="btn-network ml-1" href="#"><i class="fab fa-twitter-square"></i></a>
          </p>
        </div>

        <div class="col-lg-6 col-form">
          <b-form @submit="onSubmit">
            <div class="row">
              <b-form-group class="cus-f-group-1 col-12" :label="$t('contact.name_input')">
                <b-form-input type="text" v-model="form.name" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('contact.email_input')">
                <b-form-input type="email" v-model="form.email" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('contact.phone')">
                <b-form-input type="text" v-model="form.phone" minlength="10" maxlength="10" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('contact.msg')">
                <b-form-textarea
                  v-model="form.msg"
                  placeholder=""
                  rows="5"
                  max-rows="5"
                ></b-form-textarea>
              </b-form-group>

              <div class="col-12">
                <b-button type="submit" class="btn-s1 bg-blue w-100 rounded-pill">{{ $t("contact.send") }}</b-button>
              </div>
            </div>
          </b-form>
        </div>
      </div>
    </section>
    <div class="section-map" hidden>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3736.9855963665327!2d-86.9471302252993!3d20.506816705501706!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f4e59cd7dde8c2f%3A0x5fa0094f44efc9fa!2sAv%20Lic%20Benito%20Ju%C3%A1rez%20624%2C%20Adolfo%20L%C3%B3pez%20Mateos%2C%2077667%20Cozumel%2C%20Q.R.!5e0!3m2!1ses-419!2smx!4v1757034946319!5m2!1ses-419!2smx" style="width: 100%; border: 0;" height="400" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      form: {
        name: null,
        email: null,
        phone: null,
        msg: null,
      }
    }
  },

  methods: {
    makeToast(variant = null, msg, title) {
      this.$bvToast.toast(msg, {
        title: title,
        variant: variant,
        solid: true,
        toaster: 'b-toaster-bottom-center',
        appendToast: true
      })
    },

    onSubmit(event) {
      event.preventDefault();

      var params = this.form;
      var apiURL = tools.url("/api/contactform");

      axios.post( apiURL,params )
      .then( (response) => {
        // alert(response.data.response);
        if(response.data.response == 'Gracias por enviarnos su mensaje, nos pondremos en contacto con usted lo antes posible.'){
            if(this.$i18n.locale == 'en'){
              this.makeToast('success', 'Thank you for sending us your message, we will contact you as soon as possible.', 'Message sent');
            }
            else{
              this.makeToast('success', response.data.response, 'Mensaje enviado');
            }
        }
        else{
          this.makeToast('success', response.data.response, 'Mensaje enviado');
        }

        // alert(response.data.response);

        for (let prop in this.form) {
          this.form[prop] = '';
        }
      })
      .catch( (error) => {
        // console.log(error.response.data);
        let errors = error.response.data;

        for (let prop in errors) {
          // console.log(errors[prop]);
          this.makeToast('danger', errors[prop], '¡Error!');
        }
      });
    },
  }
}
</script>

<style>
    .section-map{
        height: 400px;
        background-color: #f0f0f0;
    }
    #contact-page .main-section .col-info{
        text-align: center !important;
    }
</style>
