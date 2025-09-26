<template lang="html">
  <div id="user-access-page" class="placed-backg">

    <div class="container">
      <div class="row mx-0 flex-center-xy page-size">

        <div class="form-container w-100">
            <div class="box-color"><i><img src="/images/redesign/logo-notarynet.webp" alt="NotaryNet" class="img-fluid" style="width: 88px;"></i></div>

          <b-form class="form" @submit.prevent="register">

            <h1 class="mb-3">{{ $t('register.register') }}</h1>

            <div class="row">
              <div class="col-12">
                <h4 class="pt-2 mb-2 f-w-600 text-center txt-black">{{ $t('register.basic_info') }}</h4>
              </div>

              <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.fullname')+' *'">
                <b-form-input type="text" v-model="form.name" required :placeholder="$t('register.fullname')"></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.phone')+' *'">
                <b-form-input type="text" minlength="10" maxlength="10" v-model="form.phone" required :placeholder="$t('register.write_phone')"></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-2 mt-3 col-lg-6" :label="$t('register.email')+' *'">
                <b-form-input type="email" name="emailregister" v-model="form.email" required :placeholder="$t('register.write_email')" autocomplete="off" onfocus="this.removeAttribute('readonly');"></b-form-input>
              </b-form-group>

              <div class="col-lg-6"></div>

              <div class="col-12">
                <h4 class="pt-2 mb-2 f-w-600 text-center txt-black" v-if="!id">{{$t('register.to_whom')}}</h4>
                <h4 class="pt-2 mb-2 f-w-600 text-center txt-black" v-else>{{$t('register.to_whose')}}</h4>


              </div>
              <b-form-group label="" v-slot="{ ariaDescribedby }" class="col-12" style="text-align: center;" >
                <b-form-radio-group
                  id="radio-group-2"
                  v-model="form.who_make_contracts"
                  :aria-describedby="ariaDescribedby"
                  name="radio-sub-component"
                >
                <b-form-radio v-model="form.who_make_contracts" name="some-radios" value="Persona fisica">{{$t('register.physical_p')}}</b-form-radio>
                <b-form-radio v-model="form.who_make_contracts" name="some-radios" value="Persona moral">{{$t('register.moral_p')}}</b-form-radio>
                </b-form-radio-group>


              </b-form-group>

              <div class="col-12">
                  <b-form-group class="cus-f-group-2 col-12" :label="$t('register.businessname')" v-show="form.who_make_contracts == 'Persona moral'">
                        <b-form-input type="text" v-model="form.business_name" :placeholder="$t('register.write_businessname')"></b-form-input>
                  </b-form-group>
              </div>

                <div class="col-12" v-show="form.who_make_contracts == 'Persona moral'">

                    <h4 class="pt-2 mb-2 f-w-600 text-center txt-black">{{$t('register.location_company')}}</h4>
                  </div>



                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.street')" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.street_company" placeholder=""></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" :label="$t('register.num_ext')+ ' *'" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.num_ext_company" placeholder=""></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" :label="$t('register.num_int')" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.num_int_company" :placeholder="$t('register.write_num_int')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.neighborhood')+ ' *'" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.neighborhood_company" :placeholder="$t('register.write_neighborhood')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.zip_code')+ ' *'" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.zipcode_company" :placeholder="$t('register.write_zip_code')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.state')+ ' *'" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-select  v-model="form.state_id_company">
                      <b-form-select-option :value="null">{{ $t('register.select_option') }}</b-form-select-option>
                      <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.town')+ ' *'" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-select  v-model="form.town_id_company">
                      <b-form-select-option :value="null">{{ $t('register.select_option') }}</b-form-select-option>
                      <b-form-select-option :value="t.id" v-for="(t, srInx) in townstwo" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>

                  <!-- <div class="col-12" v-show="form.who_make_contracts == 'Persona moral'">
                    <h4 class="pt-2 mb-2 f-w-600 text-center txt-black">UBICACIÓN DE REPRESENTANTE O APODERADO</h4>
                  </div>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Calle" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.street"  placeholder="Escribe la calle y numero"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" label="Numero exterior *" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.num_ext"  placeholder="Escribe la calle y numero"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" label="Numero interior" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.num_int" placeholder="Escribe el número interior"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Colonia *" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.neighborhood"  placeholder="Escribe la colonia"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Código postal *" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-input type="text" v-model="form.zipcode"  placeholder="Escribe el código postal" minlength="5" maxlength="5"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Estado *" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-select  v-model="form.state_id">
                      <b-form-select-option :value="null">Selecciona una opción</b-form-select-option>
                      <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Ciudad *" v-show="form.who_make_contracts == 'Persona moral'">
                    <b-form-select  v-model="form.town_id">
                      <b-form-select-option :value="null">Selecciona una opción</b-form-select-option>
                      <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group> -->


                  <div class="col-12" v-show="form.who_make_contracts == 'Persona fisica'">
                    <h4 class="pt-2 mb-2 f-w-600 text-center txt-black">{{ $t('register.location') }}</h4>
                  </div>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.street')" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-input type="text" v-model="form.street" :placeholder="$t('register.write_street')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" :label="$t('register.num_ext')+ ' *'" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-input type="text" v-model="form.num_ext" :placeholder="$t('register.write_num_ext')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-3" :label="$t('register.num_int')" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-input type="text" v-model="form.num_int" :placeholder="$t('register.write_num_int')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.neighborhood')+ ' *'" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-input type="text" v-model="form.neighborhood" :placeholder="$t('register.write_neighborhood')"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.zip_code')+ ' *'" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-input type="text" v-model="form.zipcode" :placeholder="$t('register.write_zip_code')" minlength="5" maxlength="5"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.state')+ ' *'" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-select v-model="form.state_id">
                      <b-form-select-option :value="null">{{$t('register.select_option')}}</b-form-select-option>
                      <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" :label="$t('register.town')+ ' *'" v-show="form.who_make_contracts == 'Persona fisica'">
                    <b-form-select v-model="form.town_id">
                      <b-form-select-option :value="null">{{$t('register.select_option')}}</b-form-select-option>
                      <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>



              <div class="col-lg-6"></div>

              <div class="col-12">
                <h4 class="pt-2 mb-2 f-w-600 text-center txt-black">{{$t('register.access_sistem')}}</h4>
              </div>
             <!--  <b-form-group class="cus-f-group-2 col-lg-6" label="Contraseña *">
                <b-form-input type="password" v-model="form.password" required placeholder="Escribe tu Contraseña"></b-form-input>
              </b-form-group> -->

              <b-form-group
                :label="$t('register.pass')+' *'"
                label-for="il-1"
                class="cus-f-group-2 col-lg-6">
                <div class="eye-box" style="position:relative;">
                    <b-form-input
                              id="il-1"
                              size="sm"
                              v-model="form.password"
                              type="password"
                              required
                              :placeholder="$t('register.write_pass')"
                              minlength="6"
                              name="passsregister"
                    ></b-form-input>
                    <i style="background-color: #fff;cursor: pointer; padding: 6px 10px; position: absolute;right: 2px;top: 2px; z-index: 4" class="fas ic-eye" v-bind:class="{ 'fa-eye' : showpass, 'fa-eye-slash' : !showpass }"  @click="showpass = !showpass"></i>
                </div>
              </b-form-group>

<!--
              <b-form-group class="cus-f-group-2 col-lg-6" label="Confirmar tu contraseña *">
                <b-form-input type="password" v-model="form.password_confirmation" required placeholder="Confirma tu contraseña"></b-form-input>
              </b-form-group> -->

               <b-form-group
               :label="$t('register.confim_pass')+' *'"
                label-for="il-2"
                class="cus-f-group-2 col-lg-6">
                <div class="eye-box" style="position:relative;">
                    <b-form-input
                              id="il-2"
                              size="sm"
                              v-model="form.password_confirmation"
                              type="password"
                              required
                              :placeholder="$t('register.confirm_pass')"
                              minlength="6"
                              name="passscomfirmregister"
                    ></b-form-input>
                    <i style="background-color: #fff;cursor: pointer; padding: 6px 10px; position: absolute;right: 2px;top: 2px; z-index: 4" class="fas ic-eye" v-bind:class="{ 'fa-eye' : showpass2, 'fa-eye-slash' : !showpass2 }"  @click="showpass2 = !showpass2"></i>
                </div>
              </b-form-group>

              <!-- <div class="col-12 mt-5 mb-2" v-show="form.who_make_contracts == 'Persona moral'">
                <h4 class="f-w-600 text-center txt-black">DOCUMENTACIÓN DE LA EMPRESA</h4>
              </div>

              <div class="col-lg-6" v-show="form.who_make_contracts == 'Persona moral'">
                <div class="row">
                  <b-form-group class="cus-f-group-2 col-12" label="Razon social">
                    <b-form-input type="text" v-model="form.business_name" placeholder="Escribe tu Razon social"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-12" label="Sube tu acta constitutiva">
                    <b-form-file plain name="constitutive_act" accept=".pdf" v-model="form.constitutive_act"></b-form-file>
                  </b-form-group>
                </div>
              </div>

              <div class="col-lg-6" v-show="form.who_make_contracts == 'Persona moral'">
                <div class="row">
                  <b-form-group class="cus-f-group-2 col-12" label="RFC">
                    <b-form-input type="text" v-model="form.rfc_company" placeholder="Escribe tu RFC"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-12" label="Sube tu constancia del RFC">
                    <b-form-file plain name="rfc_company" accept="application/pdf" v-model="form.rfc_file_company"></b-form-file>
                  </b-form-group>
                </div>
              </div> -->





              <!-- <div class="col-12 mt-5 mb-2">
                <h4 class="f-w-600 text-center txt-black">DOCUMENTACIÓN</h4>
              </div>

              <div class="col-lg-6">
                <div class="row">
                  <b-form-group class="cus-f-group-2 col-12" label="RFC">
                    <b-form-input type="text" v-model="form.rfc" placeholder="Escribe tu RFC"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-12" label="Sube tu constancia del RFC">
                    <b-form-file plain name="rfc" accept="application/pdf" v-model="form.rfc_file" id="i_rfc_file"></b-form-file>
                  </b-form-group>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="row">
                  <b-form-group class="cus-f-group-2 col-12" label="CURP">
                    <b-form-input type="text" v-model="form.curp" placeholder="Escribe tu CURP"></b-form-input>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-12" label="Sube un pdf de tu CURP">
                    <b-form-file plain name="curp" accept="application/pdf" v-model="form.curp_file" id="i_curp_file"></b-form-file>
                  </b-form-group>
                </div>
              </div>

              <div class="col-12 pt-3">

                <div class="row">
                  <b-form-group class="cus-f-group-2 col-lg-6" label="Frente INE: Subir PDF ó foto">
                    <b-form-file plain accept="application/pdf, image/gif, image/jpeg, image/png"  name="inefront" v-model="form.ine_frente"></b-form-file>
                  </b-form-group>

                  <b-form-group class="cus-f-group-2 col-lg-6" label="Reverso INE: Subir PDF ó foto">
                    <b-form-file plain accept="application/pdf, image/gif, image/jpeg, image/png"  name="ineback" v-model="form.ine_reverso"></b-form-file>
                  </b-form-group>


                </div>
              </div> -->
            </div>

            <!-- Foto -->
            <div class="row justify-content-between">
              <div class="col-sm-6 col-lg-5 mb-3" style="display:none;">
                <b-form-group class="cus-f-group-2 mb-1" label="Fotografía de rostro *"></b-form-group>

                <div class="box-image-uploader">
                  <img class="empty" src="images/shared/empty.png">

                  <b-form-group class="box-btn-photo" v-show="!imagePhoto">
                    <label class="fake-label" for="img-photo-i1">
                      <div class="inside">
                        <h5>AGREGA UNA FOTO</h5>
                      </div>
                    </label>
                    <input id="img-photo-i1" type="file" accept="image/*" name="image" @change="onFileChange('imagePhoto', $event)">
                  </b-form-group>

                  <div class="placed-backg box-img-fake" v-bind:style="{ backgroundImage: 'url('+imagePhoto+')' }" v-show="imagePhoto">
                    <div class="inside">
                      <button class="btn btn-s1 bg-blue" @click="removeImage('imagePhoto')">Quitar foto</button>
                    </div>
                  </div>
                </div>
              </div>

              <!--  -->

              <!-- Firma -->
              <!-- <div class="col-sm-6 col-lg-5"> -->
              <div class="col-12 mt-4 text-center">
                <b-form-group class="cus-f-group-2 mb-3" :label="$t('register.the_sing')"></b-form-group>
              </div>

              <div class="col-sm-11 col-md-8 col-lg-5 mx-auto">
                <div class="box-image-signature">
                  <img class="empty" src="images/shared/empty.png">

                  <div class="box-btn-signature" @click="openSignatureModal" v-if="!imageSignature">
                    <div class="inside">
                      <h5>{{$t('register.add_signature')}}</h5>
                    </div>
                  </div>

                  <div class="box-fake-signature" v-else>
                    <div class="fake-image" v-bind:style="{ backgroundImage: 'url('+imageSignature+')' }"></div>
                    <div class="box-button">
                      <button class="btn btn-s1 bg-blue" @click="removeImage('imageSignature')">{{$t('register.quit_signature')}}</button>
                    </div>
                  </div>
                </div>
              </div>
              <!--  -->

              <div class="col-12 mt-4 text-center">
                <b-form-checkbox class="mb-3" v-model="form.tos" name="usertype" value="1" unchecked-value="" required>
                  {{$t('register.terms')}} <strong class="txt-black f-w-500">NOTARIONET</strong>
                </b-form-checkbox>

                <p class="mb-2">
                  <router-link to="/login">{{$t('register.already_account')}}</router-link>
                </p>
              </div>
            </div>

            <div class="col-12 mb-4 form-errors" v-if="formErrors.length">
              <hr />
              <ul>
                <li class="text-danger" v-for="formError in formErrors">
                  <small>{{ formError }}</small>
                </li>
              </ul>
            </div>

            <div class="col-12 my-2 text-center">
              <b-button type="submit" class="btn-s1 bg-blue" variant="primary" v-if="$route.params.id" style="background-color: #00bd00;background-color: #00bd00;border-color: #00bd00;"> {{$t('register.acreate_account_sing')}}</b-button>
              <b-button type="submit" class="btn-s1 bg-blue" variant="primary" v-else>{{$t('register.create_account')}}</b-button>
            </div>
          </b-form>
        </div>
        <button @click="bottomFunction()" id="btn-go-bottom" class="t-150" title="Go to bottom"><i class="fal fa-arrow-to-bottom"></i></button>
      </div>
    </div>

    <!-- Modal firma -->
    <b-modal modal-class="modal-signature" ref="modal-signature" centered hide-footer title="" @hidden="showSignaturePad = false">
      <!-- <div class="box-content">
        <p style="text-align: center;">Firma igual que en tu INE</p>
        <div class="box-signature" v-if="showSignaturePad">
          <VueSignaturePad class="signature-pad" ref="signaturePad" />

          <div class="box-buttons">
            <button class="btn btn-s1 bg-blue" @click="saveSignature">Guardar</button>
            <button class="btn btn-s1 bg-blue" @click="resetSignature">Limpiar</button>
          </div>
        </div>
      </div> -->

    </b-modal>
    <!--  -->

    <sweet-modal ref="modalfirma">
      <div id="content-wrapper" role="main">
            <div id="content" class="canvasDivContainer">
                <!--    Se define un div donde se crearán los canvas y otros botones de control
                        En este ejemplo se crearán 2 div a los cuales se agrega la class signCaptureCanvas
                -->
                <div class="signCaptureCanvas"></div>

            </div>
        </div>
    </sweet-modal>

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
      formErrors: [],

      form: {
        name: '',
        lastname: '',
        email: '',
        phone:null,
        password: '',
        password_confirmation: '',
        rfc:null,
        curp:null,
        street:null,
        num_ext:null,
        num_int:null,
        neighborhood:null,
        zipcode:null,
        state_id:null,
        town_id:null,

        street_company:null,
        num_ext_company:null,
        num_int_company:null,
        neighborhood_company:null,
        zipcode_company:null,
        state_id_company:null,
        town_id_company:null,

        clave_ine:null,

        who_make_contracts:null,

        rfc_company:null,
        business_name:null,
        points:null,
        imageSignature:false,

      },

      modal:{
        msg:'',
        icon:'',
        block:false,
      },

      imagePhoto: false,
      imageSignature: false,
      showSignaturePad: false,

      states: [],
      towns: [],
      townstwo:[],
       showpass:false,
      showpass2:false,
      id:null
    }
  },
   watch:{
    'form.state_id':function (val) {
        this.getTowns();
    },
    'form.state_id_company':function (val) {
        this.getTownsTwo();

    },


    'showpass':function (val) {
          if (val == true) {
              $("#il-1").prop('type','text');
          }
          else if (val == false) {
            $("#il-1").prop('type','password');
          }
      },
    'showpass2':function (val) {
          if (val == true) {
              $("#il-2").prop('type','text');
          }
          else if (val == false) {
            $("#il-2").prop('type','password');
          }
      },
  },
  methods: {
    resetSignature() {
      this.$refs.signaturePad.clearSignature();
    },
    saveSignature() {
      const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
      // console.log(isEmpty);
      // console.log(data);
      this.imageSignature = data;
      this.$refs['modal-signature'].hide();
    },

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
          this.imagePhoto = e.target.result;
        }
      };

      reader.readAsDataURL(files[0]);
    },

    openSignatureModal(){
      //this.$refs['modal-signature'].show();
      this.$refs.modalfirma.open();


      $(".canvasDivContainer").empty();
        $(".canvasDivContainer").html('<div class="signCaptureCanvas"></div>');
        var self = this;
        var canvasWidth = 450;
        var canvasHeight = 250;
        if(this.$i18n.locale == 'en'){
          var firmaText = "Sign here";
          var limpiarText = "Clean";
          var getText = "Confirm signature";
        }
        else{
          var firmaText = "Firme Aquí";
          var limpiarText = "Limpiar";
          var getText = "Confirmar firma";
        }

        var clickX_simple = [];
        var clickY_simple = [];
        var time_simple = [];
        var clickDrag_simple = [];
        var canvas_simple;
        var sigPad = [];


        var canvasDiv = null;
        var button_clear = null;
        var button_getdata = null;
        /*  Función que busca los elementos div que cuenten con la class signCaptureCanvas,
            para cada div encontrado creará un canvas, un botón para borrar y otro para obtener los datos
        */

        var canvasDivList = $(".signCaptureCanvas");
        $.each(canvasDivList, function (index, value) {

            canvasDiv = value;
            clickX_simple[index] = new Array();
            clickY_simple[index] = new Array();
            time_simple[index] = new Array();
            clickDrag_simple[index] = new Array();
            time_simple[index] = new Array();
            sigPad[index] = new Array();

            // Creación de canvas

            var idSignCaptureCanvas = 'idSignCaptureCanvas' + index;
            canvas_simple = document.createElement('canvas');
            canvas_simple.setAttribute('width', canvasWidth);
            canvas_simple.setAttribute('height', canvasHeight);
            canvas_simple.setAttribute('id', idSignCaptureCanvas);
            canvas_simple.setAttribute('class', 'drawnCanvas');
            canvas_simple.setAttribute('index', index);
            canvasDiv.appendChild(canvas_simple);

              // Creación de botón para mostrar datos capturados en consola
              button_getdata = document.createElement('button');
            button_getdata.setAttribute('class', 'btn-s1 bg-blue');
            button_getdata.setAttribute('type', 'button');
            button_getdata.setAttribute('index', index);
            button_getdata.setAttribute('id', 'idGetButton' + index);
            $(button_getdata).html(getText);
            $(button_getdata).insertAfter(canvasDiv);


            // Creación de botón para limpiar texto

            button_clear = document.createElement('button');
            button_clear.setAttribute('class', 'btn-s1 btn-secondary');
            button_clear.setAttribute('type', 'button');
            button_clear.setAttribute('index', index);
            button_clear.setAttribute('id', 'idCleanButton' + index);
            $(button_clear).html(limpiarText);
            $(button_clear).insertAfter(canvasDiv);

            if (typeof G_vmlCanvasManager != 'undefined') {
                canvas_simple = G_vmlCanvasManager.initElement(canvas_simple);
            }

            /*  Instancia de la librería SignaturePad, en él se define el elemento canvas
                y otras propiedades como texto de marca de agua, fuente, grosor del trazo etc.
            */

            sigPad[index] = new SignaturePad( {
                canvas: document.getElementById(idSignCaptureCanvas),   // Elemento canvas
                textFont: 'normal 15px monospace',                      // Fuente
                textStrokeColor: 'transparent',                         // Color de contorno del texto
                textFillColor: '#000',                                  // Color del texto de marca de agua
                brushSize: 2,                                           // Grosor del trazo
                splashText: firmaText,                                  // Texto de la marca de agua
                pointBlackPercent: 0.005,                               // Porcentaje de trazo minímo para aprovar la firma
                canvasWhitePercent: 0.80,                               // Procentaje del lienzo en blanco que debe haber para aprovar la firma
                dialog: {
                    displayDialog: 'N',// Desplegar dialog en caso de que se detecte que el trazo no concuerda con la configuración
                    //Mensaje de cuerpo del dialogo
                    bodyText: '¡Ups! se ha detectado que la firma puede no ser correcta, por favor a continuación confirma si la firma mostrada en la parte superior parece ser correcta:',
                    btnDecline: 'Declinar y repetir firma',//Texto de boton para declinar
                    btnConfirm: 'Confirmar y enviar firma',//Texto de boton para confirmar
                    btnConfirmFunction: function () { //function para actuar dependiendo si el usuario presiona el boton confirmar
                        console.log("btnConfirm pressed");
                        console.log(sigPad[index].getDataInJSON());
                    },
                    btnDeclineFunction: function () {//function para actuar dependiendo si el usuario presiona el boton declinar
                        var signBackground = document.getElementsByClassName('signature_background')[0];
                        signBackground.firstChild.remove();
                        signBackground.remove();

                        document.querySelector('#idCleanButton0').click();
                    }
                }
            });

            sigPad[index].setSize( canvasWidth, canvasHeight );

            /*  Asignación de funciones a los botones creados previamente.
            */

            document.querySelector( '#idCleanButton' + index ).onclick = function () {
                // Función para borrar el trazo del canvas
                sigPad[index].clear();
            }

            document.querySelector( '#idGetButton' + index ).onclick = function () {
              /*var statuss = sigPad[index].verify();
                if (statuss['status'] == 'Rejected') {
                    $(".signature_background").css("display", "none");
                    alert('La firma no es valida');
                }
                else{*/
                  self.$refs.modalfirma.close();
                  self.form.points = sigPad[index].getDataInJSON();
                  self.imageSignature = sigPad[index].toDataURL();
                //}


                //sigPad[index].sendDialog();

                //console.log(sigPad[index].getData());


            }

        });


      setTimeout(()=>{ this.showSignaturePad = true; }, 1000);
    },

    removeImage: function (target) {
      if(target == 'imagePhoto'){
        this.imagePhoto = false;
      }
      if(target == 'imageSignature'){
        this.imageSignature = false;
      }
    },

    register(evt){
      this.modal.icon = "";
      this.modal.msg = 'Guardando tu información y creando una cuenta para firmar. Esto tomara un par de minutos.';
      this.modal.block = true;
      this.$refs.modal.open();

      this.formErrors = [];
      if(this.form.password.length < 6){
          this.formErrors.push("La contraseña debe tener por lo menos 6 caracteres");
          this.modal.block = false;
          this.modal.icon = 'error';
          this.modal.msg = 'La contraseña debe tener por lo menos 6 caracteres';

      } else {
        if(this.form.password !== this.form.password_confirmation){
            this.formErrors.push("Los campos contraseña y confirmación de contraseña deben coincidir");
            this.modal.block = false;
            this.modal.icon = 'error';
            this.modal.msg = 'Los campos contraseña y confirmación de contraseña deben coincidir';

        } else {

          if (this.form.points == null && this.imageSignature == false) {
            this.modal.block = false;
            this.modal.icon = 'warning';
            this.modal.msg = 'La firma de es obligatoria';
          }
          else{
            var formData = new FormData();
            formData.append("name", this.form.name);
            formData.append("lastname", this.form.lastname);
            formData.append("email", this.form.email);
            formData.append("phone", this.form.phone);
            formData.append("password", this.form.password);
            formData.append("rfc", this.form.rfc);
            formData.append("curp", this.form.curp);
            formData.append("street", this.form.street);
            formData.append("num_ext", this.form.num_ext);
            formData.append("num_int", this.form.num_int);
            formData.append("neighborhood", this.form.neighborhood);
            formData.append("zipcode", this.form.zipcode);
            formData.append("state_id", this.form.state_id);
            formData.append("town_id", this.form.town_id);

            formData.append("street_company", this.form.street_company);
            formData.append("num_ext_company", this.form.num_ext_company);
            formData.append("num_int_company", this.form.num_int_company);
            formData.append("neighborhood_company", this.form.neighborhood_company);
            formData.append("zipcode_company", this.form.zipcode_company);
            formData.append("state_id_company", this.form.state_id_company);
            formData.append("town_id_company", this.form.town_id_company);


            /*formData.append("clave_ine", this.form.clave_ine);

            var checkrfcfile = document.getElementById('i_rfc_file');
            var rfcfiledata = checkrfcfile.files[0];
            if (rfcfiledata) {
                formData.append("rfc_file", jQuery('input[name="rfc"]')[0].files[0]);
            }

            var checkcurpfile = document.getElementById('i_curp_file');
            var rfccurpdata = checkcurpfile.files[0];
            if (rfccurpdata) {
              formData.append("curp_file", jQuery('input[name="curp"]')[0].files[0]);
            }

            formData.append("inefront", jQuery('input[name="inefront"]')[0].files[0]);
            formData.append("ineback", jQuery('input[name="ineback"]')[0].files[0]);*/

            formData.append("image", jQuery('input[name="image"]')[0].files[0]);
            formData.append("business_name", this.form.business_name);
            /*formData.append("rfc_company", this.form.rfc_company);

            formData.append("rfc_file_company", jQuery('input[name="rfc_company"]')[0].files[0]);
            formData.append("constitutive_act", jQuery('input[name="constitutive_act"]')[0].files[0]);*/
            formData.append("points", this.form.points);
            formData.append("signature", this.imageSignature);

            formData.append("users_id", this.id);



            axios.post(tools.url('/api/user/register'),formData).then((response)=>{
                /*this.$parent.user = response.data;
                this.$parent.logged = true;*/
                this.login();
            }).catch((error)=>{
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = error.response.data.msg;
            });
          }
        }
      }
    },

    handleErrors:function(errors){
        var err="";

        if (errors.response) {
            if(errors.response.data.errors){
                jQuery.each(errors.response.data.errors,(k,v)=>{
                    err+="*"+v[0]+"\n";
                });
            }
        }
        else{
            console.log(errors);
            err="Error desconocido.";
        }

        this.modal.icon = "error";
        this.modal.msg = err;
        this.modal.block = false;
        this.$refs.modal.open();

    },
    getStates(){
      axios.get(tools.url('/api/states')).then((response)=>{
        this.states = response.data;
      }).catch((error)=>{
         console.log(error);
      });
    },

    getTowns(){
      if(this.form.state_id){
        axios.get(tools.url('/api/towns/' + this.form.state_id)).then((response)=>{
          this.towns = response.data;
        }).catch((error)=>{
          console.log(error);
        });
      }
    },
    getTownsTwo(){
      if(this.form.state_id_company){
        axios.get(tools.url('/api/towns/' + this.form.state_id_company)).then((response)=>{
          this.townstwo = response.data;
        }).catch((error)=>{
          console.log(error);
        });
      }
    },
    login(){
      axios.get(tools.url('/sanctum/csrf-cookie')).then(() => {
          axios.post(tools.url("/api/login"),{email:this.form.email,password:this.form.password}).then((response)=>{
                 this.$root.user = response.data;
                 this.$root.logged = true;

                 this.$root.auth();
                 this.$refs.modal.close();
                 this.$router.push('/usuario/contratos');
                   }).catch((error)=>{
                 console.log(error.response.data.error);
          });
       });
    },
    bottomFunction() {
        window.scrollTo(0,document.body.scrollHeight);
    }
  },

  mounted()
  {
    this.getStates();
    if(this.$root.logged){
        this.$router.push("/usuario");
    }
    this.form.password = null;
    this.form.email = null;
    if(this.$route.params.id){
      this.id = this.$root._getURLID(this.$route.params.id);
      this.$root.title_url = this.$root._getURLNameTitle(this.$route.params.id);
    }

  },

}
</script>
<style lang="css">
    .box-color img{
        align-content: center;
        text-align: center;
        background: #fff !important;
    }
    #user-access-page > .container > .row .form-container .box-color i{
        align-content: center;
        text-align-last: center;
        border: 1px solid #004aad;
        background: #ffffff;
    }
.mainContetDiv {
   position: relative;
   height: 90%;
   width: 90%;
   align-self: center;
}

.canvasDivContainer {
	display: block;
	padding: 12pt;
}

.drawnCanvas {
	border: 1px solid #000000;
	background-color: #FFFFFF;
	cursor: pointer;
}

.buttonContainer {
	float: left;
	margin-top: 30px;
	width: 100%;
	margin-left: 40%;
}

.divChartContainer {
	margin-top: 20px;
	width: 200px;
	height: 150px;
}

.cleanButton {
	margin-top: 20px;
}
.btn-s1{
    border-radius: 20px !important;
}

@media screen and (max-width: 575px) {
  .sweet-modal .sweet-content {
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  .sweet-modal .sweet-content .btn-s1 {
    width: 50% !important;
    min-width: auto !important;
  }
}
</style>
