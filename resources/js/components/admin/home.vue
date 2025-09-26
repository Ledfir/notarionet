<template>
	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<div class="profile-env">
				<header class="row">
					<div class="col-sm-2"> <a class="profile-picture">
						<img :src="profile()" class="img-responsive img-circle" width="115px"> </a>
					</div>
					<div class="col-sm-7">
						<ul class="profile-info-sections">
							<li> <div class="profile-name"> <strong> <a>{{$root.user.name}}</a> <a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a> </strong> <span>{{roles}}</span> </div> </li>
						</ul>
					</div>
					<div class="col-sm-3">

					</div>
				</header>
				<section class="profile-info-tabs">
					<div class="row">
						<div class="col-sm-offset-2 col-sm-10">
							<ul class="user-details">
								<li><a><i class="fas fa-user"></i> {{$root.user.name}} {{$root.user.lastname}}</a></li>
								<li><a><i class="fas fa-at"></i> {{$root.user.email}}</a> </li>
								<li v-if="$root.user.phone"><a><i class="fas fa-phone"></i> {{$root.user.phone}}</a></li>
							</ul> <!-- tabs for the profile links -->
							<ul class="nav nav-tabs">
								<li><router-link to="/profile/">Editar perfil</router-link></li>
							</ul>
						</div>
					</div>
				</section>

				<!-- <section class="profile-feed">
					<div class="profile-stories">
						<b>Notificaciones:</b>
						<article class="story" v-for="(notification, index) in notifications" :key="index">
							<aside class="user-thumb">
								<router-link v-if="notification.url!=null" :to="notification.url">
									<img :src="'/img/avatar.png'" alt="" class="img-circle" width="44">
								</router-link>
								<a v-else>
									<img :src="'/img/avatar.png'" alt="" class="img-circle" width="44">
								</a>
							</aside>
							<div class="story-content">
								<header>
									<div class="publisher">
										<router-link v-if="notification.url!=null" :to="notification.url">{{$parent.user.name}}</router-link>
										<a v-else>{{$parent.user.name}}</a>
										<em>{{notification.created_at}}</em>
									</div>
									<div class="story-type"> <i :class="notification.icon"></i> </div>
								</header>
								<div class="story-main-content"> <p>{{notification.text}} </p></div>
								<hr>
							</div>
						</article>
					</div>
				</section> -->

			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default {
		data(){
			return {
				user:{
					name:"",
					email:"",
					img:{key:""},
					phone:"",
					celphone:"",
					roles:[]
				},
				notifications:{},
			}
		},
		computed:{
			roles:function(){
				let roles=jQuery.map(this.$root.user.roles,(row)=>{
					return row.name;
				});
				return roles.join(", ");
			}
		},
		methods:{
			profile(){
				return ('https://ui-avatars.com/api/?name='+this.$parent.user.name+'&size=256');
			},
		},
        mounted() {
        	//this.user = this.$parent.user;
        }
    }
</script>
