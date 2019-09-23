# InterConnecta - Zoho CRM SDK Wrapper
1. Install the wrapper
    ```
    composer require interconnecta/zoho-crm
    ```
2. Publish the config files:
    ```
    php artisan zohocrm:install
    ```
3. Copy this config keys in the `.env` file:
    ```
    # ZohoCRM API Client Keys
    ZOHOCRM_CLIENT_ID=
    ZOHOCRM_CLIENT_SECRET=
    ZOHOCRM_REDIRECT_URI=
    ZOHOCRM_CURRENT_USER_EMAIL=
    ZOHOCRM_SANDBOX=

    # ZohoCRM Default values - Optional Keys to edit
    ZOHOCRM_ACCOUNTS_URL=https://accounts.zoho.com/
    ZOHOCRM_API_BASE_URL=www.zohoapis.com
    ZOHOCRM_API_VERSION=v2
    ZOHOCRM_ACCESS_TYPE=offline
    ZOHOCRM_PERSISTENCE_HANDLER_CLASS=ZohoOAuthPersistenceHandler
    ```
4. Go to the [Zoho CRM Developer Console](https://accounts.zoho.com/developerconsole) and copy the Client Configuration Keys
5. Generate a grant token, use the standar (or custom) scope:
    ```
    aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL
    ```
6. Execute the setup command and put the **grant token**:
    ```
    php artisan zohocrm:setup
    ```
