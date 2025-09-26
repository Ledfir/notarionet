<template lang="html">
  <div class="new-contract-page">
    <b-form class="c-f-g-2-wrp sm" @submit="onSubmit">
      <h5 class="main-page-title">{{ $t("new_document.title") }}</h5>
        <div class="box-step-title" style="color: #7e7e7e;">
          <h2 class="step-title"> {{ form.contractdata.title}}</h2>
        </div>

      
      <hr class="c-hr" />

      <!-- Paso 1 -->
      <div class="box-step" v-if="step == 1">
        <div class="box-step-title">
          <h2 class="step-title"><span>1</span> {{ $t("new_document.select_type") }}</h2>
        </div>

        <b-form-group class="cus-f-group-2">
          <b-form-select required v-model="form.type_contracts_id" size="sm" v-if="$i18n.locale == 'es'">
            <b-form-select-option :value="null" disabled>Selecciona una opción</b-form-select-option>
            <b-form-select-option :value="1" >Selecciona un formato pre-cargado de nuestro catalogo</b-form-select-option>
            <b-form-select-option :value="4" >Firma una imagen o documento</b-form-select-option>
            <b-form-select-option :value="5" >Firma una imagen o documento con mas personas</b-form-select-option>
            <b-form-select-option :value="3" >Llena un formato y firmalo</b-form-select-option>
            <!-- <b-form-select-option :value="2" >Crea un contrato desde 0</b-form-select-option> -->


            <!-- <b-form-select-option :value="1" >Selecciona de nuestro catalogo</b-form-select-option>
            <b-form-select-option :value="2" >Crea un documento a la medida</b-form-select-option>
            <b-form-select-option :value="3" >Solo firmar y certificar</b-form-select-option>
            <b-form-select-option :value="4" >Certificar elemento grafico digital</b-form-select-option> -->
          </b-form-select>
          <b-form-select required v-model="form.type_contracts_id" size="sm" v-else>
            <b-form-select-option :value="null" disabled>Select an option</b-form-select-option>
            <b-form-select-option :value="1" >Select a pre-loaded format from our catalogue</b-form-select-option>
            <b-form-select-option :value="4" >Sign an image or document</b-form-select-option>
            <b-form-select-option :value="5" >Sign an image or document with more people</b-form-select-option>
            <b-form-select-option :value="3" >Fill out a form and sign it</b-form-select-option>
            <!-- <b-form-select-option :value="2" >Crea un contrato desde 0</b-form-select-option> -->


            <!-- <b-form-select-option :value="1" >Selecciona de nuestro catalogo</b-form-select-option>
            <b-form-select-option :value="2" >Crea un documento a la medida</b-form-select-option>
            <b-form-select-option :value="3" >Solo firmar y certificar</b-form-select-option>
            <b-form-select-option :value="4" >Certificar elemento grafico digital</b-form-select-option> -->
          </b-form-select>
        </b-form-group>

        <b-form-group class="cus-f-group-2" v-if="form.type_contracts_id == 1">
          <b-form-select required v-model="form.contracts_id" size="sm">
            <b-form-select-option :value="null" disabled>{{ $t("new_document.select_option") }}</b-form-select-option>
            <b-form-select-option :value="cont.id" v-for="(cont,indx) in contracts" :key="indx">{{cont.title}}</b-form-select-option>
          </b-form-select>
        </b-form-group>

        <div class="row" v-if="form.type_contracts_id == 1">
          <div class="col-12 box-inf-1">
            <label class="label">{{ $t("new_document.document_type") }}:</label>
            <h6>{{form.contractdata.document_type}}</h6>
          </div>

          <div class="col-12 box-inf-1">
            <label class="label">{{ $t("new_document.description") }}:</label>
            <div v-html="form.contractdata.description"></div>
          </div>
        </div>
        <div class="row" v-if="form.type_contracts_id == 4 || form.type_contracts_id == 5">
          <div class="col-12 box-inf-1">
            {{ $t("new_document.description_con") }}
            </div>
        </div>
       

        <div class="d-block pt-4">
          <!-- <b-button type="submit" class="btn-s1 bg-blue">Siguiente</b-button> -->
          <a v-if="form.contracts_id != 888888888 " @click="submitStep(2)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
          <a v-else @click="submitStep(3)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
        
        </div>
      </div>
      <!--  -->

      <!-- Paso 2 -->
      <!-- <div class="box-step" v-if="step == 2">
        <div class="box-step-title">
          <h2 class="step-title"><span>x</span> Datos del contratante</h2>
        </div>

        <div class="row pb-2">
          <div class="col-12 box-inf-1">
            <label class="label">Tipo de contrato:</label>
            <h6 v-if="form.type_contracts_id == 2">Crea un documento a la medida</h6>
            <h6 v-else-if="form.type_contracts_id == 3">Solo firmar y certificar</h6>
            <h6 v-else-if="form.type_contracts_id == 4 || form.type_contracts_id == 5">Firmar elemento grafico digital</h6>

            
          </div>
        </div>

        <b-form class="form-search-contact" @submit="onSubmitSearch">
          <b-form-input type="text" v-model="formSearch.keyword" required placeholder="Buscar contacto..." />
          <b-button type="submit"></b-button>
        </b-form>

        <div class="box-contacts">
          <b-form-group class="radio-contact-group" v-slot="{ ariaDescribedby }" v-for="(c, cInx) in contacts" :key="'cInx-'+cInx">
            <b-form-radio v-model="form.users_id" required :aria-describedby="ariaDescribedby" name="radio-contact" :value="c.id">
              <div class="col-12 col-contact">
                <div class="wrp">
                  <div class="col col-photo">
                    <div class="photo">
                      <img src="public/images/shared/empty.png" v-bind:style="{ backgroundImage: 'url('+c.imageUrl+')' }">
                    </div>
                  </div>

                  <div class="col col-info">
                    <h6 class="name">{{ c.name }}</h6>
                    <div class="row">
                      <div class="col-lg-6 box">
                        <label class="label">Tel:</label>
                        <h6>{{ c.phone }}</h6>
                      </div>

                      <div class="col-lg-6 box">
                        <label class="label">Email:</label>
                        <h6>{{ c.email }}</h6>
                      </div>

                      <div class="col-lg-12 box">
                        <label class="label">Calle y número exterior:</label>
                        <h6>{{c.address.street}}</h6>
                      </div>

                      <div class="col-lg-6 box">
                        <label class="label">Colonia:</label>
                        <h6>{{c.address.neighborhood}}</h6>
                      </div>

                      <div class="col-lg-6 box">
                        <label class="label">Código postal:</label>
                        <h6>{{c.address.zipcode}}</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-form-radio>
          </b-form-group>
        </div>
        
        <br><br>
        <div class="d-block pt-4">
          <a class="btn btn-s1 bg-blue" @click="step = 1">Anterior</a>
          <a @click="submitStep(2)" class="btn btn-s1 bg-blue">Siguiente</a>
        </div>
      </div> -->
      <!--  -->

      <!-- Paso 3 -->
      <div class="box-step" v-if="step == 3">
        <div class="box-step-title">
          <h2 class="step-title"><span>2</span> {{ $t("new_document.counterpart_data") }}</h2>
           <!-- <h2 class="step-title"><span>3</span> Datos del contraparte</h2> -->
        </div>

        <div class="row pb-2">
          <div class="col-12 box-inf-1" v-if="$i18n.locale == 'es'">
            <label class="label">Document type:</label>
            <h6 v-if="form.type_contracts_id == 2">Create a custom document</h6>
            <h6 v-else-if="form.type_contracts_id == 3">Create a custom document</h6>
            <h6 v-else-if="form.type_contracts_id == 4">Sign an image or document</h6>
            <h6 v-else-if="form.type_contracts_id == 5">Sign an image or document with more people</h6>

          </div>
          <div class="col-12 box-inf-1" v-else>
            <label class="label">Tipo de documento:</label>
            <h6 v-if="form.type_contracts_id == 2">Crea un documento a la medida</h6>
            <h6 v-else-if="form.type_contracts_id == 3">Llena un formato y firmalo</h6>
            <h6 v-else-if="form.type_contracts_id == 4">Firma una imagen o documento</h6>
            <h6 v-else-if="form.type_contracts_id == 5">Firma una imagen o documento con mas personas</h6>

          </div>
          
        </div>

        <p>{{ $t("new_document.select_contacto") }}</p>
        <b-form class="form-search-contact" @submit="onSubmitSearch">
          <b-form-input type="text" v-model="formSearch.keyword" required :placeholder="$t('new_document.search_contact')" />
          <b-button type="submit"></b-button>
        </b-form>

        <div class="box-contacts" v-if="contacts.length > 0">
          <b-form-checkbox class="mb-3" v-model="form.no_signature_creator" name="usertype" value="1" unchecked-value="0" style="font-size: 18px;">{{ $t('new_document.i_manager') }} </b-form-checkbox>

          <b-form-group class="radio-contact-group" v-slot="{ ariaDescribedby }" v-for="(c, cInx) in contacts" :key="'cInx-'+cInx">
            <b-form-radio v-model="form.user_contra_id" required :aria-describedby="ariaDescribedby" name="radio-contact" :value="cInx" >
              <div class="col-12 col-contact" v-bind:class="{ 'active' : c.active }">
                <div class="wrp">
                  <div class="col col-photo" style="max-width:18% !important">
                    <div class="photo">
                      <img src="public/images/shared/empty.png" v-bind:style="{ backgroundImage: 'url('+c.imgURL+')' }">
                    </div>
                  </div>

                  <div class="col col-info">
                    <h6 class="name">{{ c.name }}</h6>
                    <div class="row">
                      <div class="col-lg-12 box">
                        <label class="label">{{ $t("new_document.phone") }}:</label>
                        <h6>{{ c.phone }}</h6>
                      </div>

                      <div class="col-lg-12 box">
                        <label class="label">{{ $t("new_document.email") }}:</label>
                        <h6>{{ c.email }}</h6>
                      </div>

                      <div class="col-lg-12 box" v-if="c.address != null && c.address != 'null'">
                        <label class="label">{{ $t("new_document.address") }}:</label>
                        <h6><span v-if="c.address != null && c.address != 'null'">{{c.address.street}},</span> {{c.address.neighborhood}}, {{c.address.zipcode}}</h6>
                      </div>

                      <!-- <div class="col-lg-6 box" v-if="c.address != null && c.address != 'null'">
                        <label class="label">Colonia:</label>
                        <h6>{{c.address.neighborhood}}</h6>
                      </div>

                      <div class="col-lg-6 box" v-if="c.address != null && c.address != 'null'">
                        <label class="label">Código postal:</label>
                        <h6>{{c.address.zipcode}}</h6>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>


            </b-form-radio>
          </b-form-group>
        </div>
        <div class="box-contacts"  v-else>
          <br>
            <p style="text-align: center;">{{ $t("new_document.no_contacts") }}</p>
            <br><br>
        </div>
          
        <div class="col-12 text-left">
          <a class="btn btn-sm btn-s1 bg-blue" @click="$refs['modal-new-contact'].show();"><i class="fas fa-user-plus"></i>  {{ $t("new_document.add_contact") }}</a>
        </div>

        <div class="d-block pt-4">
          <a class="btn btn-s1 bg-blue" @click="step = 1">{{ $t("new_document.previous") }}</a>
          <a @click="submitStep(3)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
        </div>
      </div>
      <!--  -->

      <!-- Paso 4 -->
      <div class="box-step" v-show="step == 4">
        <div class="box-step-title">
          <!-- <h2 class="step-title"><span>4</span> Claúsulas</h2> -->
           <h2 class="step-title" v-if="form.contracts_id != 0 && form.contracts_id != 999999999 && form.contracts_id != 888888888  && form.contracts_id != 888899999"><span>3</span> {{ $t("new_document.add_info_complete") }}</h2>
           <h2 class="step-title" v-else-if="form.contracts_id == 0 || form.contracts_id == 999999999"><span>3</span> {{ $t("new_document.document_body") }}</h2>
           <h2 class="step-title" v-else-if="form.contracts_id == 888888888"><span>2</span> {{ $t("new_document.document_body") }}</h2>

           <h2 class="step-title" v-else-if="form.contracts_id == 888899999"><span>3</span> {{ $t("new_document.document_body") }}</h2>
        </div>

        <div v-if="form.contracts_id == 0 || form.contracts_id == 999999999">
          
          <div class="tableclass" v-if="form.contracts_id == 0">
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Nombre parte 1</div>
                    <div class="cellsubtable">[parte1_nombre]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Nombre contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_nombre]</div>
                  </div>
                  
              </div>

              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Email parte 1</div>
                    <div class="cellsubtable">[parte1_email]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Email contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_email]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Telefono parte 1</div>
                    <div class="cellsubtable">[parte1_telefono]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Telefono contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_telefono]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">RFC parte 1</div>
                    <div class="cellsubtable">[parte1_rfc]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">RFC contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_rfc]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">CURP parte 1</div>
                    <div class="cellsubtable">[parte1_curp]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">CURP contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_curp]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Calle parte 1</div>
                    <div class="cellsubtable">[parte1_calle]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Calle contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_calle]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Numero exterior parte 1</div>
                    <div class="cellsubtable">[parte1_numext]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Numero exterior contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_numext]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Numero interior parte 1</div>
                    <div class="cellsubtable">[parte1_numint]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Numero interior contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_numint]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Colonia parte 1</div>
                    <div class="cellsubtable">[parte1_colonia]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Colonia contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_colonia]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Codigo postal parte 1</div>
                    <div class="cellsubtable">[parte1_cp]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Codigo postal contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_cp]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Ciudad parte 1</div>
                    <div class="cellsubtable">[parte1_ciudad]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Ciudad contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_ciudad]</div>
                  </div>
              </div>
              <div class="rowtable">
                  <div class="celltable">
                    <div class="cellsubtableth">Estado parte 1</div>
                    <div class="cellsubtable">[parte1_estado]</div>
                  </div>
                  <div class="celltable" v-for="(contra,indx) in form.contacts" :key="indx">
                    <div class="cellsubtableth">Estado contraparte {{indx + 1}}</div>
                    <div class="cellsubtable">[contraparte{{indx + 1}}_estado]</div>
                  </div>
              </div>
            
          </div>
          <br>
          <vue-editor id="editor2"  v-model="form.body" :editorToolbar="customToolbar"></vue-editor>
        </div>
        <div v-else-if="form.contracts_id == 888888888 || form.contracts_id == 888899999">
            <p style="color: red;"> {{ $t("new_document.i_understand") }}</p><br>
          <input type="file" id="imagecertificate" name="imagecertificate" accept=".pdf,image/*" @change="changeImage">
        </div>
        <div v-else >
          <div v-for="(clau,indxc) in form.contractdata.clauses.fields_header" :key="indxc" >
            <!-- <p style="text-align: center;">{{clau.title}}</p> -->
            <!-- <div v-html="clau.description"></div> -->
            <b-form-group class="" :label="clau.title +''" v-if="clau.type == 'campo'">
              <b-form-input type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>
            <b-form-group class="" :label="clau.title +''" v-else>
              <b-form-textarea type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>
            
          </div>
          <hr>
          <div v-for="(clau,indxc) in form.contractdata.clauses.fields_medio" :key="indxc" >
            <!-- <p style="text-align: center;">{{clau.title}}</p> -->
            <!-- <div v-html="clau.description"></div> -->
            <b-form-group class="" :label="clau.title +'*'" v-if="clau.type == 'campo'">
              <b-form-input type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>
            <b-form-group class="" :label="clau.title +'*'" v-else>
              <b-form-textarea type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>
            
          </div>
          <hr>
          <div v-for="(clau,indxc) in form.contractdata.clauses.fields_inferior" :key="indxc" >
            <!-- <p style="text-align: center;">{{clau.title}}</p> -->
            <!-- <div v-html="clau.description"></div> -->
            <b-form-group class="" :label="clau.title +'*'" v-if="clau.type == 'campo'">
              <b-form-input type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>
            <b-form-group class="" :label="clau.title +'*'" v-else>
              <b-form-textarea type="text" v-model="clau.response" size="sm" required placeholder="" />
            </b-form-group>

            <hr>
          </div>
        </div>
        <div class="d-block pt-4">
          <a class="btn btn-s1 bg-blue" v-if="form.contracts_id != 888888888 && form.contractdata.plain_receipt == 0" @click="step = 3">{{ $t("new_document.previous") }}</a>
          <a class="btn btn-s1 bg-blue" v-else @click="step = 1">{{ $t("new_document.previous") }}</a>

          <a v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999" @click="submitStep(4)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>

          <a v-else @click="submitStep(5)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
          <!-- <a v-else @click="submitStep(7)" class="btn btn-s1 bg-blue">Certificar de forma digital</a> -->
        </div>
      </div>
      <!--  -->

      <!-- Paso 5 -->
      <div class="box-step" v-show="step == 5">
        <div class="box-step-title">
          <h2 class="step-title"><span>4</span> {{ $t("new_document.reference_images") }}</h2>
        </div>

        <div class="col-12 text-left">
          <a class="btn btn-sm btn-s1 bg-blue" @click="openModalImage"><i class="fas fa-file-plus"></i> {{ $t("new_document.reference_images") }}</a>
          <br><br>

          <div v-for="(imagerow,indx) in form.images" :key="indx" class="row" style="border: 1px solid;padding-bottom: 10px;padding-top: 10px;border-bottom: 0;">
              <div class="col-10">
                <input  @change="onFileChange(indx, $event)" type="file" :id="'image'+indx" :name="'image'+indx" accept="image/*">
                <br><br>
                <label>{{ $t("new_document.images_comments") }}:</label>
                <input class="form-control" type="text" v-model="imagerow.comments">
              </div>
              
              <div class="col-2" style="display: flex;align-items: center;"> <a class="btn bg-blue" style="background-color: black;color: white;" @click="deleteImage(indx)">{{ $t("new_document.images_delete") }}</a></div>
          </div>
        </div>

        <div >
          <div >

              
          </div>
        </div>

        <div class="d-block pt-4">
          <a class="btn btn-s1 bg-blue" @click="step = 4">{{ $t("new_document.previous") }}</a>
          <a v-if="form.contracts_id == 0 || form.contracts_id == 999999999" @click="submitStep(6)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
          <a v-else @click="submitStep(5)" class="btn btn-s1 bg-blue">{{ $t("new_document.next") }}</a>
          
        </div>
      </div>

      <!-- Paso 6 -->
      <div class="box-step" v-if="step == 6">
        <div class="box-step-title">
          <h2 class="step-title" v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999"><span>5</span> {{ $t("new_document.review") }}</h2>
          <h2 class="step-title" v-else-if="form.contracts_id == 888888888"><span>3</span> {{ $t("new_document.review") }}</h2>
          <h2 class="step-title" v-else-if="form.contracts_id == 888899999"><span>4</span> {{ $t("new_document.review") }}</h2>
        </div>

        <div class="box-pdf-preview" v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999">
          <div class="box">
            <div v-html="form.contractdata.header_format_preview"></div><br>
            <div v-html="form.contractdata.medio_format_preview"></div><br>
            <div v-for="(clau,indxc) in form.contractdata.clauses" :key="indxc">
                <!-- <h6>{{clau.title}}</h6> -->
                <div v-html="clau.descriptionview"></div>
            </div><br>
            <div v-html="form.contractdata.inferior_format_preview"></div>

            <div v-if="form.images.length > 0">
              <div v-for="(im,indximg) in form.images" :key="indximg">
                  <img :src="im.image" width="25%"><br>{{ im.comments }}
              </div>

            </div>
          </div>
        </div>
        <div class="box-pdf-preview" v-else style="text-align:center; height: 110vh;" >

          
          
          <div v-if="filetype == 'image'">
            <p style="text-align:center">{{ $t("new_document.legal_support") }}</p>
            <br><br>
            <div style="display: inline-block;"></div>
            <div style="width:50%;display: inline-block;">
              <img :src="url_imagecertificate" style="width: 100%;">
            </div>
          </div>
          <div v-else-if="filetype == 'pdf'">
            <!-- <p style="text-align:center">RESPALDO JURIDICO: El archivo mostrado a continuación es certificado por sello de tiempo y certificado digital de NOTARIONET y cumple con las normas NOM151 SCFI 2016 de la Secretaría de economía, el servicio de emisión de certificados digitales DOF 20/06/2011, el servicio de emisión de sellos digitales de tiempo de DOF 14/12/2011 y el servicio de digitalización de documentos en soporte físico como tercero legalmente autorizado TLA DOF 03/12/2019</p>
            <br><br> -->
            <div style="display: inline-block;"></div>
            <div>
              <embed :src="url_imagecertificate+'#toolbar=0'" width="500" height="450">
            </div>
          </div>
         
          
          
        </div>
        

        <div class="d-block pt-4">
          <a class="btn btn-s1 bg-blue" @click="step = 5" v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999">{{ $t("new_document.previous") }}</a>
          <a class="btn btn-s1 bg-blue" @click="step = 4" v-else>Anterior</a>
          <a @click="submitStep(6)" class="btn btn-s1 bg-blue" v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999">{{ $t("new_document.next") }}</a>
          <a v-else @click="openSignatureModalSD" class="btn btn-s1 bg-blue">
            <span v-if="form.no_signature_creator == '1'">{{ $t("new_document.finish") }} </span>
              <span v-else>{{ $t("new_document.finish") }}</span>
            </a>
        </div>
      </div>


      <!-- Paso 7 -->
      <div class="box-step" v-if="step == 7">
        <div class="box-step-title">
          <h2 class="step-title"><span>6</span> {{ $t("new_document.finished_document") }}</h2>
        </div>

        <div class="d-block py-5">
          <div class="d-block box-finished">
            <i class="fal fa-check-circle icon"></i>
            <h6 class="subtitle">{{ $t("new_document.completed_successfully") }}</h6>
          </div>

          <div class="d-block pt-4 text-center">
            <!-- <a @click="submitStep(7)" class="btn btn-s1 bg-blue">Firmar</a> -->

            <a @click="openSignatureModalSD" class="btn btn-s1 bg-blue">
              <span v-if="form.no_signature_creator == '1'">{{ $t("new_document.finish") }} </span>
              <span v-else>{{ $t("new_document.sign") }}</span>
              
              
            </a>
            <!-- <b-button type="submit" class="btn btn-s1 bg-blue"><i class="fas fa-badge-check"></i> Firmar de forma digital</b-button> -->
          </div>
        </div>
      </div>

      <!-- Paso 8 -->
      <div class="box-step" v-if="step == 8">
        <div class="box-step-title">
          <!-- <h2 class="step-title"><span>7</span> Contrato firmado</h2> -->
          <h2 class="step-title" v-if="form.contracts_id != 888888888 && form.contracts_id != 888899999"><span>7</span> {{ $t("new_document.signed_document") }}</h2>
          <h2 class="step-title" v-else><span>3</span> {{ $t("new_document.signed_media") }}</h2>
          
        </div>

        <div class="d-block mb-4 box-finished">
          <!-- <i class="fal fa-check-circle icon"></i> -->
          <div class="d-block">
            <success-component></success-component>
          </div>
          <div v-if="form.contracts_id == 888888888 || form.contracts_id == 888899999">
            <h6 class="subtitle">{{ $t("new_document.signed_media") }} <br>{{ $t("new_document.go_to_documents") }}</h6>
          </div>
          <div v-else>
            <h6 class="subtitle">{{form.contractdata.title}}</h6>
            <h6 class="subtitle">{{ $t("new_document.signed_document_success") }}</h6>
            <h6 class="subtitle-2">{{ $t("new_document.will_send") }}</h6>
          </div>
          <br><br>
          <div>
            <p style="text-align: center;color:red" v-if="form.contracts_id != 888888888">{{ $t("new_document.no_valid") }}</p>
            <embed :src="order.documentUrl+'#toolbar=0'" width="500" height="450">
          </div>
           
          
        </div>

        <!-- <div class="col-12 box-inf-1">
          <label class="label">Sello de tempo:</label>
          <h6 class="text-break">{{order.stamp}}</h6>
          <h6 class="text-break">{{order.created}}</h6>
        </div>

        <div class="col-12 box-inf-1">
          <label class="label">Certificado digital:</label>
          <h6 class="text-break">{{order.certificate}}</h6>
          <h6 class="text-break">{{order.created}}</h6>
        </div> -->

        <!-- <div class="d-block pt-4 text-center">
          <a :href="order.documentUrl" target="_blank" type="button" class="btn btn-s1 bg-blue"><i class="fas fa-file-pdf"></i> Descargar en PDF</a>
        </div> -->
        
      </div>

    </b-form>

     <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
      <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
      {{modal.msg}}
      <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
      <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">Aceptar</b-button>
      </div>
  </sweet-modal>

  <!-- Modal nuevo contacto -->
  <b-modal modal-class="modal-content-s1" ref="modal-new-contact" title="Agregar contacto" no-close-on-backdrop no-close-on-esc centered hide-footer>
    <b-form @submit="onSubmitNew">

      <b-form-group class="cus-f-group-2" :label="$t('new_document.email') +'*'">
        <b-form-input type="email" v-model="formNew.email" size="sm" required placeholder="" />
       <!--  <small class="note txt-orange"><i class="fas fa-bell icon"></i> Se enviará un mensaje al email seleccionado para que complete el proceso de registro.</small> -->
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('new_document.full_name') +'*'">
        <b-form-input type="text" v-model="formNew.name" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('new_document.phone') +'*'">
        <b-form-input type="text" v-model="formNew.phone" size="sm" required placeholder="" maxlength="10" minlength="10" />
      </b-form-group>
      <b-button type="submit" class="btn-s1 bg-blue">{{$t('new_document.add_contact')}}</b-button>

    </b-form>
  </b-modal>

  <b-modal modal-class="modal-content-s1" ref="modal-new-image" :title="$t('new_document.add_image')" no-close-on-backdrop no-close-on-esc centered hide-footer>
  

     

      <b-form-group class="cus-f-group-2" :label="$t('new_document.images_comments')">
        <b-form-textarea type="email" v-model="formImage.comments" size="sm" required placeholder="" />
       <!--  <small class="note txt-orange"><i class="fas fa-bell icon"></i> Se enviará un mensaje al email seleccionado para que complete el proceso de registro.</small> -->
      </b-form-group>

      <b-form-group class="cus-f-group-2 col-12" :label="$t('new_document.image')">
          <b-form-file plain id="formimage" name="rfc_company" accept="image/*" v-model="formImage.image"></b-form-file>
      </b-form-group>

      <!-- <b-button @click="onSubmitImage" class="btn-s1 bg-blue">Agregar contacto</b-button> -->

  
  </b-modal>

  <sweet-modal ref="modal_signature_seguridata">
    <p>$t("new_document.the_sign")</p>
      <div id="content-wrapper" role="main">
            <div id="content" class="canvasDivContainer">
                <div class="signCaptureCanvas"></div>
            </div>
      </div>
      <hr>
      <div>
        <p>Firma de referencia</p>
        <img :src="$root.user.signatureUrl" style="300px">
      </div>
  </sweet-modal>

  </div>
</template>

<script>
import SuccessComponent from '../shared/success-checkmark-component.vue'
export default {
  components: {
    SuccessComponent
  },

  data(){
    return{
      step: 1,

      form: {
        type_contracts_id:null,
        contracts_id:null,
        users_id:null,
        user_contra_id:null,
        password: '',
        contractdata:{
          id:null,
          title:null,
          description:null,
          header_format_preview:null,
          medio_format_preview:null,
          inferior_format_preview:null,
          clauses:{
            fields_header:null,
            fields_medio:null,
            fields_inferior:null,
          }
        },
        contacts:[],
        images:[],
        body:null,

        signaturepoints:[],
        signatureUrl:null,
        no_signature_creator:'0'
      },

      formSearch: {
        keyword: null,
      },

      contracts: [],
      contacts:[],

      contactsTwo: [
      ],
      order:{
        stamp:null,
        certificate:null,
        documentUrl:null
      },
      modal:{
          msg:'',
          icon:'',
          block:false,
        },
      formNew:{
        state_id: null,
        town_id: null,
      },
      formImage:{
        comments:null,
        image:null
      },
      customToolbar: [
					[{ 'font': [] }],
					[{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
					['bold', 'italic', 'underline'],
					[{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
					[{ 'list': 'ordered'}, { 'list': 'bullet' }]//,
					//['image', 'code-block']
				],
        url_imagecertificate:null,
        formsignature:{
            points:[],
            signatureUrl:null
        },
      filetype:null,
    }
  },
  watch:{
    'form.type_contracts_id':function(val){
      if (val != null) {
          if(val == 2){
            this.form.contracts_id = 0;
          }
          else if(val == 3){
              this.form.contracts_id = 999999999;
          }
          else if(val == 4){
              this.form.contracts_id = 888888888;
          }
          else if(val == 5){
              this.form.contracts_id = 888899999;
          }
      }
    },
      'form.contracts_id':function(val){
          if (val != null) {
            var indx = null;
            for (var i = 0; i < this.contracts.length; i++) {
                if(this.contracts[i]['id'] == val){
                    this.form.contractdata = this.contracts[i];
                    break;
                }
            }
          }
          else{
              this.form.contractdata.id = null;
              this.form.contractdata.title = null;
              this.form.contractdata.description = null;
          }
          
      },
      'form.user_contra_id':function(val){
        for (var i = 0; i < this.contacts.length; i++) {
          this.contacts[i]['active'] = false;
        }
          if (val != null) {
              

              if (this.form.contacts.length > 0) {
                  if(!this.form.contacts.includes(this.contacts[val]['id'])){
                      this.form.contacts.push(this.contacts[val]['id'])
                  }
                  else{
                    var newids = [];
                    for (var i = 0; i < this.form.contacts.length; i++) {
                      if(this.form.contacts[i] != this.contacts[val]['id'])
                      {   

                          newids.push(this.form.contacts[i]);
                      }
                    }
                    this.form.contacts = newids;
                  }
               
              }
              else{

                this.form.contacts.push(this.contacts[val]['id']);
              }
          }
      
        for (var i = 0; i < this.contacts.length; i++) {

            if(this.form.contacts.includes(this.contacts[i]['id'])){
              this.contacts[i]['active'] = true;
            }
        }
        

      }
  },
  methods: {
    onSubmitSearch(event) {
      event.preventDefault()
      console.log('xxxx');
    },

    submitStep(nStep){
      if(nStep == 1){ // Evaluar y pasar a sig paso
        this.step = 2;
      }
      if(nStep == 2){ // Evaluar y pasar a sig paso
          if (this.form.contracts_id != null) {
            
            if (this.form.contractdata['plain_receipt'] == 1) {
              this.step = 4;
            }
            else{
              this.step = 3;
            }
        }
        else{
            alert('Selecciona un documento');
            return;
        }
       
      }
      if(nStep == 3){ // Evaluar y pasar a sig paso
        if(this.form.contracts_id == 888888888 ){
          this.step = 4;
        }
        else{
          if (this.form.contacts.length > 0) {
            this.step = 4;
          }
          else{
              alert('Selecciona un contraparte');
              return;
          }
        }
        
        
      }
      if(nStep == 4){ // Evaluar y pasar a sig paso
         
          
          this.step = 5;
      }
      if(nStep == 5){ // Evaluar y pasar a sig paso
        if(this.form.contracts_id == 888888888 ){
            if (typeof jQuery('input[name="imagecertificate"]')[0].files[0] !== 'undefined') {

            }
            else{
              alert('Debe seleccionar un archivo');
              return;
            
            }
        }
        if(this.form.contracts_id == 888899999 ){
            if (typeof jQuery('input[name="imagecertificate"]')[0].files[0] !== 'undefined') {
             
            }
            else{
              alert('Debe seleccionar un archivo');
              return;

            }
        }

        this.getReview();

        if(this.form.contracts_id != 0 && this.form.contracts_id != 999999999 && this.form.contracts_id != 888888888 && this.form.contracts_id != 888899999){
          var clauses = this.form.contractdata.clauses;
          for (var i = 0; i < this.form.contractdata.clauses.length; i++) {
              for (var x = 0; x < this.form.contractdata.clauses[i]['fields'].length; x++) {
                  this.form.contractdata.clauses[i]['descriptionview'] = this.form.contractdata.clauses[i]['descriptionview'].replace('['+this.form.contractdata.clauses[i]['fields'][x]['name']+']',this.form.contractdata.clauses[i]['fields'][x]['response']);
              }
              
          }
          
        }
        else{
           /*  var data_contacts = [];
            for (var i = 0; i < this.contacts.length; i++) {
                for (var x = 0; x < this.form.contacts.length; x++) {
                    if(this.form.contacts[x] == this.contacts[i]['id']){
                      data_contacts.push(this.contacts[i]);
                    }
                  
                }
            }

            this.form.bodyview = this.form.body.replace('[parte1_nombre]',this.form.contractdata.clauses[i]['fields'][x]['response']); */
            
        }
        var self = this;
        setTimeout(function(){
          self.step = 6;
        }, 1000);
        
      }
      if(nStep == 6){ // Evaluar y pasar a sig paso
        
        this.step = 7;
      }

      if(nStep == 7){ // Evaluar y pasar a sig paso
        if(this.form.contracts_id == 888888888 ){
            if (typeof jQuery('input[name="imagecertificate"]')[0].files[0] !== 'undefined') {
              this.saveFormImage();
            }
            else{
              alert('Debe seleccionar un archivo');
              return;
            
            }
        }
        else if(this.form.contracts_id == 888899999 ){
            if (typeof jQuery('input[name="imagecertificate"]')[0].files[0] !== 'undefined') {
              this.saveFormImage();
            }
            else{
              alert('Debe seleccionar un archivo');
              return;

            }
        }
        else{
          this.saveForm();
        }
        
        
        
      }
     
    },

    onSubmit(event) {
      event.preventDefault()
      alert('Finalizado');
    },
    getContracts(){
        axios.get(tools.url("/api/contracts")).then((response)=>{
            this.contracts = response.data;
        }).catch(()=>{});
    },
    getContacts(){
        axios.get(tools.url('/api/contacts')).then((response)=>{
          this.contacts = response.data;
        }).catch((error)=>{
           console.log(error);
        });
    },
    saveForm(){
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
            this.modal.msg = 'Uploading, this process can take around 1-2 minutes.';
        }
        else{
            this.modal.msg = 'Cargando, este proceso puede tomar alrededor de 1 a 2 minutos.';
        }
        
        this.modal.block = true;
        //this.$refs.modal.open();

        
        var formData = new FormData();
        for (let x = 0; x < this.form.images.length; x++) {
          formData.append("image"+x, jQuery('input[name="image'+x+'"]')[0].files[0]);
        
          
        }
        this.form.imagesfiles = formData;
        axios.post(tools.url('/api/saveorder'),this.form).then((response)=>{
            this.modal.block = false;
            this.$refs.modal.close();

            this.order = response.data;
            this.step = 8;
            this.$root.auth();
        }).catch((error)=>{
           this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al guardar la información';
        });
    },
    saveFormImage(){
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
            this.modal.msg = 'Uploading, this process can take around 1-2 minutes.';
        }
        else{
            this.modal.msg = 'Cargando, este proceso puede tomar alrededor de 1 a 2 minutos.';
        }
        this.modal.block = true;
        //this.$refs.modal.open();

        
        var formData = new FormData();
        formData.append('form', JSON.stringify(this.form));
        formData.append("imagecertificate", jQuery('input[name="imagecertificate"]')[0].files[0]);
        
        axios.post(tools.url('/api/saveorderimage'),formData).then((response)=>{
            this.modal.block = false;
            this.$refs.modal.close();

            this.order = response.data;
            this.step = 8;
            this.$root.auth();
        }).catch((error)=>{
           this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al guardar la información';
        });
    },
    onSubmitNew(event) {
        event.preventDefault()
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        axios.post(tools.url('/api/contact'),this.formNew).then((response)=>{
            this.getContacts();
            this.modal.block = false;
            this.modal.icon = "success";
            this.modal.msg = 'Contacto guardado correctamente';
            if(this.$i18n.locale == 'en'){
                this.modal.msg = 'Contact saved successfully';
            }
            else{
                this.modal.msg = 'Contacto guardado correctamente';
            }

            this.formNew = {};
            this.$refs['modal-new-contact'].hide();
            this.getContacts();
            
        }).catch((error)=>{
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = error.response.data.msg;
        });
    },
    openModalImage(){
        var aux = {comments:null,image:null};
        this.form.images.push(aux);

   
    },
    deleteImage(indx){
        var newimages = [];
        for (let x = 0; x < this.form.images.length; x++) {
            if(x != indx){
              newimages.push(this.form.images[x]);
            }
          
        }
        this.form.images = newimages;
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
        //if(target == 'imagePhoto'){
          this.form.images[target].image = e.target.result;
        //}
      };

      reader.readAsDataURL(files[0]);
    },
    getReview(){
      this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();
        axios.post(tools.url('/api/getreview'),this.form).then((response)=>{
            this.form.contractdata.header_format_preview = response.data.header_format_preview;
            this.form.contractdata.medio_format_preview = response.data.medio_format_preview;
            this.form.contractdata.inferior_format_preview = response.data.inferior_format_preview;

            this.modal.block = false;
            this.$refs.modal.close();
        }).catch((error)=>{
          this.modal.block = false;
            this.$refs.modal.close();
        });
    },
    changeImage(event){
    
      const file = event.target.files[0];
      this.url_imagecertificate = URL.createObjectURL(file);
      this.filetype = 'image';
      if (file.type == 'application/pdf') {
          this.filetype = 'pdf';
      }

    },
    openSignatureModalSD(){
      /*
      //si esta activo el check de no firma creador no hacemos la compararcion ni abrimos modal de firma
      if (this.form.no_signature_creator == '0' ) {
          

          this.$refs.modal_signature_seguridata.open();
            $(".canvasDivContainer").empty();
            $(".canvasDivContainer").html('<div class="signCaptureCanvas"></div>');
            var self = this;
            var canvasWidth = 450;
            var canvasHeight = 250;
            var firmaText = "Firme Aquí";
            var limpiarText = "Limpiar";
            var getText = "Confirmar firma";
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
            *-/

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
                *-/

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
                            //console.log("btnConfirm pressed");
                            //console.log(sigPad[index].getDataInJSON());
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
                *-/

                document.querySelector( '#idCleanButton' + index ).onclick = function () {
                    // Función para borrar el trazo del canvas
                    sigPad[index].clear();
                }

                document.querySelector( '#idGetButton' + index ).onclick = function () {
                    /*var statuss = sigPad[index].verify();
                    console.log(statuss);
                    if (statuss['status'] == 'Rejected') {
                      
                      
                      $(".signature_background").css("display", "none");
                      alert('La firma no es valida');
                    }
                    else{*-/
                    
                        // Función para obtener los datos X, Y y Dt
                        self.$refs.modal_signature_seguridata.close();
                      
                        self.formsignature.points = sigPad[index].getDataInJSON();
                        self.compareSiganature();
                        //self.formsignature.signatureUrl = sigPad[index].toDataURL();
                    //}
                    
                }
                
            });
        }
        else{*/
          this.modal.icon = "";
        
          if(this.$i18n.locale == 'en'){
              this.modal.msg = 'Uploading, this process can take around 1-2 minutes.';
          }
          else{
              this.modal.msg = 'Cargando, este proceso puede tomar alrededor de 1 a 2 minutos.';
          }

          this.modal.block = true;
          this.$refs.modal.open();

          this.submitStep(7);
          
        //}
    },
    compareSiganature(){
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();
        axios.post(tools.url('/api/comparesignature'),this.formsignature).then((response)=>{
            if (response.data.status == true) {
              if (response.data.prediction > 50) {
                  //this.modal.block = false;
                  //this.$refs.modal.close();
                  this.submitStep(7);
              }
              else{
                  this.modal.block = false;
                  this.$refs.modal.close();
                  alert('La firma no coincide con la firma registrada anteriormente, intenta nuevamente');
                  this.openSignatureModalSD();
              }
              
            }
            else{
                this.modal.block = false;
                this.$refs.modal.close();
                alert('La firma no coincide con la firma registrada anteriormente, intenta nuevamente');
                this.openSignatureModalSD();
            } 
            
        }).catch((error)=>{
            this.modal.block = false;
            this.$refs.modal.close();
            alert('Error al comparar la firma, no es similar a la firma inicial que realizaste y que nuestro algoritmo toma de referencia.');
            this.openSignatureModalSD();
        });
    }
    
    
  },
  mounted(){
    if(this.$root.logged == false){
         this.$router.push("/login");
     }
      this.getContracts();
      this.getContacts();
  }
}
</script>

<style>
.tableclass {display:block; }
.rowtable { display:block;}
.celltable {display:inline-block;}
.cellsubtableth {
  background-color: #dddddd;
  display:inline-block;
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
.cellsubtable {
  display:inline-block;
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
</style>