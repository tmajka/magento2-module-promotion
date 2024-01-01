<h1 align="center">TMajka_Promotion</h1> 

<div align="center">
  <p>The module responsible for managing promotions by RestApi.</p>
  <img src="https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>


## Installation details

```
composer require tmajka/magento2-module-promotion
bin/magento module:enable TMajka_Promotion
bin/magento setup:upgrade
```

## CLI command to demo data

#### Deploy sample data for the promotion module
```
bin/magento promotions:sampledata:deploy
```
#### Remove data for the promotion module
```
bin/magento promotions:sampledata:remove
```

## License

[MIT](https://opensource.org/licenses/MIT)

