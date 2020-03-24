export default async ({ to, from, next }) => {
	if (!to.params.record) {
		if (to.query.code && to.query.location) {
			await axios
				.post(ZohoCRM.apiPath + "/api/oauthredirect", { code: to.query.code })
				.then(({ data }) => {
					next({
						name: to.name,
						params: { record: data }
					})
				})
				.catch(err => {
					console.error("Catched Err in Middleware:", err);
				});
		} else {
			// Passing params to avoid warning in console: 
			// https://github.com/vuejs/vue-router/issues/724
			next({ name: 'not-found', params: { '0': to.path } })
		}
	} else {
		next()
	}
}