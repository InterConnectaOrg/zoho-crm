import Vue from "vue"
import routes from './routes'
import Router from "vue-router";

Vue.use(Router)

window.ZohoCRM.basePath = '/' + window.ZohoCRM.webPath
window.ZohoCRM.apiPath = '/' + window.ZohoCRM.apiPath

let routerBasePath = window.ZohoCRM.webPath + '/'

const router = new Router({
	mode: 'history',
	base: routerBasePath,
	routes
})

/**
 * [nextFactory description]
 * @param  {[type]} context    [description]
 * @param  {[type]} middleware [description]
 * @param  {[type]} index      [description]
 * @return {[type]}            [description]
 */
function nextFactory(context, middleware, index) {
	const subsequentMiddleware = middleware[index]
	if (!subsequentMiddleware) {
		return context.next
	}

	return (...parameters) => {
		context.next(...parameters)
		const nextMiddleware = nextFactory(context, middleware, index + 1)
		subsequentMiddleware({ ...context, next: nextMiddleware })
	}
}

/**
 * [middleware description]
 * @type {[type]}
 */
router.beforeEach((to, from, next) => {
	if (to.meta.middleware) {
		const middleware = Array.isArray(to.meta.middleware)
			? to.meta.middleware
			: [to.meta.middleware];

		const context = {
			from,
			next,
			router,
			to,
		};
		const nextMiddleware = nextFactory(context, middleware, 1);

		return middleware[0]({ ...context, next: nextMiddleware });
	}

	return next();
});

export default router