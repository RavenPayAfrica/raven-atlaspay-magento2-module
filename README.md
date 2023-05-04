[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

## AtlasPay Magento 2 Module

AtlasPay payment gateway Magento2 extension

## Install

* Go to Magento2 root folder

* Enter following command to install module:

```bash
composer require raven/getravenbank-magento2-module
```

* Wait while dependencies are updated.

* Enter following commands to enable module:

```bash
php bin/magento module:enable Raven_AtlasPay --clear-static-content
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

## Configuration

To configure the plugin in *Magento Admin* , go to __Stores > Configuration__ from the left hand menu, then click __Payment Methods__ from the list of options. You will see __AtlasPay__ as part of the available Payment Methods. Click on it to configure the payment gateway.

* __Enabled__ - Select _Yes_ to enable AtlasPay Payment Gateway.
* __Title__ - allows you to determine what your customers will see this payment option as on the checkout page.
* __Integration Type__ - allows you to select the type of checkout experience you want on your website. Select _Inline(Popup)_ if you want your customers to checkout while still on your website, and _Redirect_ to be redirected to the payment gateway's checkout
* __Test Mode__ - Check to enable test mode. Test mode enables you to test payments before going live. If you ready to start receving real payment on your site, kindly uncheck this.
* __Test Public Key__ - Enter your Test Secret Key here. Get your API keys from your [AtlasPay account under Settings > Developer/API](https://atlas.getravenbank.com)
* __Live Public Key__ - Enter your Live Public Key here. Get your API keys from your [AtlasPay account under Settings > Developer/API](https://atlas.getravenbank.com) 
* Click on __Save Config__ for the changes you made to be effected.

![Magento Settings](https://i.imgur.com/nUExran.png)

## Known Errors


* Enable and configure `AtlasPay` in *Magento Admin* under `Stores/Configuration/Payment` Methods

[ico-version]: https://img.shields.io/packagist/v/pstk/getravenbank-magento2-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pstk/getravenbank-magento2-module.svg?style=flat-square
<!-- 
[link-packagist]: https://packagist.org/packages/raven/getravenbank-magento2-module
[link-downloads]: https://packagist.org/packages/raven/getravenbank-magento2-module -->


<!-- ## Running the magento2 on docker
Contained within this repo, is a dockerfile and a docker-compose file to quickly spin up a magento2 and mysql container with the getravenbank plugin installed.

### Prerequisites
- Install [Docker](https://www.docker.com/)

### Quick Steps
- Create a `.env` file off the `.env.sample` in the root directory. Replace the `*******` with the right values
- Run `docker-compose up` from the root directory to build and start the mysql and magento2 containers.
- Visit `localhost:8000` on your browser to access the magento store. For the admin backend, visit `localhost:8000/<MAGENTO_BACKEND_FRONTNAME>` where `MAGENTO_BACKEND_FRONTNAME` is the value you specified in your `.env` file
- Run `docker-compose down` from the root directory to stop the containers. -->


## Documentation

* [AtlasPay Documentation](https://raven-atlas.readme.io/docs)
<!-- * [AtlasPay Helpdesk](https://getravenbank.com/help) -->

## Support

For bug reports and feature requests directly related to this plugin, please use the [issue tracker](https://github.com/RavenPayAfrica/raven-atlaspay-magento2-module/issues). 

For general support or questions about your AtlasPay account, you can reach out by sending a message from [our website](https://getravenbank.com/contact).

<!-- ## Community

If you are a developer, please join our Developer Community on [Slack](https://slack.getravenbank.com). -->

## Contributing to the Magento 2 plugin

If you have a patch or have stumbled upon an issue with the Magento 2 plugin, you can contribute this back to the code. Please read our [contributor guidelines](https://github.com/RavenPayAfrica/raven-atlaspay-magento2-module/blob/master/CONTRIBUTING.md) for more information how you can do this.

