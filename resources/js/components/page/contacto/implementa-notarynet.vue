<template lang="html">
  <div id="contact-page">

    <section class="container main-section">
      <div class="row">
        <div class="col-12 col-title">
          <h1 class="title-s-1">Diseña e implementa NOTARYNET® en tu negocio</h1>
        </div>

        <div class="col-lg-6 col-info">
          <h5 class="f-w-600 title mb-1">¿Deseas tener tu propia plataforma con tu identidad corporativa y concepto de marca ?</h5>
          <p>
            Te adaptamos NOTARYNET ® a tus necesidades con tus propios contratos y/o documentos pre-cargados.
          </p>

          <h5 class="mt-4 f-w-600 subtitle">Cotiza con precio especial.<br />Incluye:</h5>
          <p style="line-height: 1.8;">
            Directorio de clientes y contactos <span class="dot-li">•</span><br />
            Centro de documentos inteligente <span class="dot-li">•</span><br />
            Legalidad y certidumbre jurídica al 100% <span class="dot-li">•</span><br />
            Respaldo del CAE; Centro de Arbitraje Empresarial <span class="dot-li">•</span><br />
            Asesoria permanente <span class="dot-li">•</span><br />
            Soporte tecnico personalizado <span class="dot-li">•</span>
          </p>
        </div>

        <div class="col-lg-6 col-form">
          <b-form @submit="onSubmit">
            <div class="row">
              <b-form-group class="cus-f-group-1 col-12" label="Nombre completo">
                <b-form-input type="text" v-model="form.name" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" label="Correo electrónico">
                <b-form-input type="email" v-model="form.email" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" label="Teléfono">
                <b-form-input type="text" v-model="form.phone" minlength="10" maxlength="10" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" label="Mensaje">
                <b-form-textarea
                  v-model="form.msg"
                  placeholder=""
                  rows="5"
                  max-rows="5"
                ></b-form-textarea>
              </b-form-group>

              <div class="col-12">
                <b-button type="submit" class="btn-s1 bg-blue w-100">ENVIAR</b-button>
              </div>
            </div>
          </b-form>
        </div>
      </div>
    </section>

  </div>
</template>

<script>
export default {
  data(){
    return{
      form: {
        name: null,
      }
    }
  },

  methods: {
    onSubmit(event) {
      event.preventDefault();
    }
  },
}
</script>
