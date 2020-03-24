// Pages
import SetupPage from "~/pages/Setup"
import NotFound from "~/pages/NotFound"

// Middlewares
import checkToken from "~/middleware/check-token";

export default [
	{
		path: '/',
		redirect: '/setup'
	},

	{
		path: '/setup',
		name: 'setup',
		component: SetupPage
	},

	{
		path: '/oauthredirect',
		name: 'oauthredirect',
		component: SetupPage,
		props: true,
		meta: {
			middleware: [
				checkToken
			]
		}
	},

	{
		path: '*',
		name: 'not-found',
		component: NotFound
	}
]