<template>
	<main role="main">
	<div :class="{'logging-in login-form-fall':inPetition,'page-body login-page':2>1}">
	<div class="login-container">

		<div class="login-header login-caret" style="background-color: white;">

			<div class="login-content">

				<a class="logo">
					<img :src="logo()" width="200" alt="" />
				</a>
				<p class="description">Usuario, ingresa para entrar al area de administracion!</p>

				<!-- progress bar indicator -->
				<div class="login-progressbar-indicator">
					<h3>{{percent}}%</h3>
					<span>Ingresando...</span>
				</div>
			</div>

		</div>

		<div class="login-progressbar">
			<div :style="'width:'+percent+'%'"></div>
		</div>

		<div class="login-form">

			<div class="login-content">

				<div class="form-login-error" style="display:block;" v-show="$root.error">
					<h3>Error</h3>
					<p v-html="$root.error"></p>
				</div>

				<form method="post" @submit.prevent="$root.handleLogin">

					<div class="form-group">

						<div class="input-group">
							<div class="input-group-addon">
								<i class="entypo-user"></i>
							</div>

							<input type="text" class="form-control" placeholder="Email" v-model="$root.login.email">
						</div>

					</div>

					<div class="form-group">

						<div class="input-group">
							<div class="input-group-addon">
								<i class="entypo-key"></i>
							</div>

							<input type="password" class="form-control" placeholder="Password" v-model="$root.login.password">
						</div>

					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block btn-login">
							<i class="entypo-loginentypo-login"></i>
							Ingresar
						</button>
					</div>

				</form>


				<div class="login-bottom-links">

					<!-- <a class="link">Olvide mi contraseña</a> -->


				</div>

			</div>

		</div>

	</div>
	</div>
	</main>
</template>

<script type="text/javascript">
	export default {
		data:function(){
        	return {
        		percent:0,
        		error:false,
				inPetition:false,
        	}
        },

        methods:{
			logo(){
				return window.tools.url('public/images/logo.png');
			}
        },

		mounted() {
            if(this.$root.logged == true){
                this.$router.push('/home');
            }
        }
    }
</script>
