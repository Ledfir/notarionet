/*
 *
 * Estos componentes estan presentes solo en el dashboard
 *
 */

const page="./admin/";

function plugin (Vue) {
	//Componentes llamados
	Vue.component('admin-header', require(page+'shared/header.vue').default);
	Vue.component('admin-menu', require(page+'shared/menu.vue').default);
	Vue.component('admin-footer', require(page+'shared/footer.vue').default);
	Vue.component('vSwitch', require(page+'shared/Switch.vue').default);
	Vue.component('messages', require(page+'shared/messages.vue').default);
	Vue.component('input-form', require(page+'shared/input.vue').default);
	Vue.component('select-form', require(page+'shared/select.vue').default);
	Vue.component('checkbox-form', require(page+'shared/checkbox.vue').default);
	Vue.component('switch-form', require(page+'shared/checkbox-switch.vue').default);
	Vue.component('file-form', require(page+'shared/file.vue').default);
	Vue.component('text-form', require(page+'shared/text.vue').default);
	Vue.component('login', require(page+'login.vue').default);
}

// Install by default if using the script tag
if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(plugin)
}

export default plugin