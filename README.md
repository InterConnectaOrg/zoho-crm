## Requirements

* Get yourself a [Zoho CRM account](https://www.zoho.com/crm/).
* [Register your application](https://www.zoho.com/crm/developer/docs/php-sdk/clientapp.html)
* PHP >= 7.2
* Laravel >= 5.8

## Installation

Add Zoho CRM to your composer file via the `composer require` command:

```bash
$ composer require interconnecta/zoho-crm
```

Or add it to `composer.json` manually:

```json
"require": {
    "interconnecta/zoho-crm": "0.1.*"
}
```

Zoho CRM's service providers will be automatically registered using Laravel's auto-discovery feature.

## Configuration

The defaults configuration settings are set in `config/zohocrm.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:

```bash
$ php artisan zohocrm:install
```

You'll need to add the following variables to your .env file. Use the credentials previously obtained registering your application.

```php
ZOHOCRM_CLIENT_ID=
ZOHOCRM_CLIENT_SECRET=
ZOHOCRM_REDIRECT_URI=
ZOHOCRM_CURRENT_USER_EMAIL=
ZOHOCRM_SANDBOX=
```

Then, follow the next steps:
1. Go to [Zoho CRM Developer Console](https://accounts.zoho.com/developerconsole).
2. Under the Client previously registered, click the vertical three points then `Self Client`.
3. Enter `aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL`, then click `View Code`.
4. Copy the generated code.

Finally, run the following command:

```bash
$ php artisan zohocrm:setup
```

Enter the previously generated code.

**Zoho CRM is ready to use.**

## Roadmap

You can find the latest development roadmap for this package [here](docs/roadmap.md). Feel free to open an [issue](https://github.com/InterConnectaOrg/zoho-crm/issues) if you have a feature request.

## License

[MIT License](https://opensource.org/licenses/MIT). Copyright (c) 2012-2019, InterConnecta

## Support

Contact:<br>
[interconnecta.com](https://interconnecta.com)<br>
it@interconnecta.com<br>
+1-646-760-4090, ext. 205
