<template>
	<div class="w-full bg-white rounded shadow-lg p-8 m-4 max-w-2xl md:mx-auto">
		<h1 class="text-xl text-center font-semibold mb-6">Zoho OAuth2 Connection</h1>
		<form class="mb-12" @submit.prevent="submit">
			<div class="flex flex-col mb-4">
				<label
					class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
					for="clientid"
				>Client ID</label>
				<input
					class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
					v-model="form.clientid"
					id="clientid"
					name="clientid"
					type="text"
					placeholder="******************"
				/>
				<p
					class="text-gray-600 text-xs italic"
				>Make sure you are using the exact same value Zoho provides.</p>
			</div>
			<div class="flex flex-col mb-4">
				<label
					class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
					for="clientsecret"
				>Client Secret</label>
				<input
					class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
					v-model="form.clientsecret"
					id="clientsecret"
					name="clientsecret"
					type="text"
					placeholder="******************"
				/>
				<p
					class="text-gray-600 text-xs italic"
				>Make sure you are using the exact same value Zoho provides.</p>
			</div>
			<div class="flex flex-col mb-4">
				<label
					class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
					for="redirecturi"
				>Authorized Redirect URI</label>
				<input
					class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
					v-model="form.redirecturi"
					id="redirecturi"
					name="redirecturi"
					type="text"
				/>
				<p
					class="text-gray-600 text-xs italic"
				>Make sure you are using the exact same value Zoho provides.</p>
			</div>
			<div class="flex flex-col mb-4">
				<label
					class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
					for="scope"
				>Scope</label>
				<input
					class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
					v-model="form.scope"
					id="scope"
					name="scope"
					type="text"
				/>
				<p class="text-gray-600 text-xs italic">
					Data that your application wants to access. Refer to
					<a
						class="font-extrabold text-blue-800"
						href="https://www.zoho.com/crm/developer/docs/api/oauth-overview.html"
						target="_blank"
						rel="noopener noreferrer"
					>Scopes</a> for more details.
				</p>
			</div>
			<div class="flex flex-wrap -mx-3 mb-8">
				<div class="w-full md:w-1/2 px-3">
					<label
						class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
						for="email"
					>User Email</label>
					<input
						class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
						v-model="form.email"
						id="email"
						name="email"
						type="email"
					/>
					<!-- <p class="text-red-500 text-xs italic">Please fill out this field.</p> -->
				</div>
				<div class="w-full md:w-1/2 px-3">
					<label
						class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
						for="accesstype"
					>Access Type</label>
					<input
						class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
						v-model="form.accesstype"
						id="accesstype"
						name="accesstype"
						type="text"
					/>
				</div>
			</div>
			<button
				class="block mx-auto bg-blue-500 hover:bg-blue-700 text-white py-2 px-8 rounded"
				type="submit"
			>Save</button>
		</form>
		<div class="text-center" id="success-message" v-if="savedSuccessfully">
			<div
				class="p-2 bg-green-500 text-center items-center text-indigo-100 leading-none lg:rounded-full flex lg:inline-flex"
				role="alert"
			>
				<span
					class="flex rounded-full bg-green-700 uppercase px-2 py-1 text-xs font-bold mr-3"
				>Successful Connection</span>
				<span
					class="font-semibold mr-2 text-left flex-auto"
				>You may find the secret keys in your environment file.</span>
				<!-- <svg
				class="fill-current opacity-75 h-4 w-4"
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 20 20"
			>
				<path
					d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"
				/>
				</svg>-->
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["record"],

	data() {
		return {
			form: {
				scope:
					"aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL",
				accesstype: "online",
				clientid: "",
				clientsecret: "",
				redirecturi: "",
				email: ""
			},
			code: "",
			savedSuccessfully: false
		};
	},

	mounted() {
		this.checkProps();
	},

	computed: {
		authenticationURL() {
			return `https://accounts.zoho.com/oauth/v2/auth?
					scope=${this.form.scope}
					&client_id=${this.form.clientid}
					&response_type=code
					&access_type=${this.form.accesstype}
					&redirect_uri=${this.form.redirecturi}`;
		}
	},

	methods: {
		submit() {
			axios
				// .post(ZohoCRM.basePath + "/api/save", this.form)
				.post(ZohoCRM.apiPath + "/api/save", this.form)
				.then(result => {
					window.location.replace(this.authenticationURL);
				})
				.catch(err => {
					console.log("err :", err);
				});
		},

		checkProps() {
			if (this.record) this.setFormData();
		},

		setFormData() {
			this.form.clientid = this.record.clientid;
			this.form.clientsecret = this.record.clientsecret;
			this.form.redirecturi = this.record.redirecturi;
			this.form.email = this.record.email;

			this.savedSuccessfully = true;
		}
	}
};
</script>

<style>
</style>