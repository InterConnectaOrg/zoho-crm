export default async ({ to, from, next }) => {
	if (!to.params.record) {
		if (to.query.code && to.query.location) {
			let body = { code: to.query.code, location: to.query.location }

			await axios
				.post(ZohoCRM.apiPath + "/api/oauthredirect", body)
				.then(({ data }) => {
					next({
						name: to.name,
						params: { record: data.data }
					})
				})
				.catch(err => {
					console.error("In Middleware:", err);
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