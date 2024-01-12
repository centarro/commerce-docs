---
title: Addresses
taxonomy:
    category: docs
---

The [Address module] provides functionality for storing, validating, and displaying international postal addresses. On eCommerce sites in general, when going through checkout customers are often annoyed by US-centric address forms. When interviewed about cart abandonment, this topic is a common complaint. In Drupal Commerce, the *Address* module solves this problem by providing country-specific address forms to customers along with the capability to display the addresses properly for shipping or billing purposes.

**[Address formats](#address-formats)**

- Learn about the structure of addresses and address formats.
- Create a custom address field by repurposing an unused one.

**[Countries and subdivisions](#contries-and-subdivisons)**

- Modify the list of available countries and set the default country for customers.
- Add or modify subdivisions for a country.

**[Address entry](#address-entry)**

- Override address field settings to control whether certain fields are always *hidden*, *optional*, or *required*.
- Customize address form fields and their labels.
- Set initial values for address fields.
- TBD: use an external service for postal code validation.

**[Address display](#address-display)**

- Use the *Default* address formatter for country-specific address formats or extend it to modify its functionality.
- Use the *Plain* address formatter with custom theming to precisely control the display of addresses.
- Override the default language used for formatting addresses on multilingual sites.

**[Zones and territories](#zones-and-territories)**

- TBD

## Address Formats

The *Address* module provides a custom *address* field that can have the following properties:

- Given name (First name)
- Additional name (Middle name / Patronymic)
- Family name (Last name)
- Organization
- Address line 1
- Address line 2
- Postal code
- Sorting code
- Dependent Locality (Neighborhood / Suburb)
- Locality (City)
- Administrative area (State / Province)
- Country (limited to pre-defined list of *available* countries)

### What is an *address format*?
An address format is the definition of how a country's addresses should be rendered for display and how the form for entering an address for that country should be rendered and validated. Address formats are defined on a per-country basis. They make it possible to dynamically alter the form used to enter addresses based on the selected country. Without address formats, an address form might look something like this:

![Badly implemented address form](../../images/address-format-5.png)

With address formats, we can have forms that dynamically change based on the selected country. Here, changing the selected country from *Armenia* to *United States* results in a change to the *Postal code*, *City*, and *Province* fields:

![Country-specific address formatting](../../images/address-format-3.png)

Each country has a different *address format* that tells us:

* Which fields are used in which order (Is there a state field? Does the zip code come before the city? After the state?)
* Which fields are required
* Which fields need to be uppercased for the actual mailing to facilitate automated sorting of mail
* The labels for the administrative area (state, province, parish, etc.), and the postal code (Postal code or ZIP code)
* Validation rules for postal codes, usually in the form of a regular expression.

In countries using a non-latin script (such as China, Taiwan, Korea), the order of fields varies based on the language/script used. Addresses written in latin script follow the minor-to-major order (start with the street, end with the country) while addresses written in the chinese script follow the major-to-minor order (start with the country, end with the street).

All this needs to be taken into account when generating and validating an address form. Selecting a different country requires re-rendering the address form using a different format.

#### Address format repository
The Commerce Guys *Addressing* library provides an address format repository with formats for over 200 countries. The formats are generated from [Google's Address Data Service]. For example, here are the instantiated address formats for *Armenia* (country code *AM*) and the *United States* (country code *US*).

```php
            'AM' => [
                'format' => "%givenName %familyName\n%organization\n%addressLine1\n%addressLine2\n%postalCode\n%locality\n%administrativeArea",
                'postal_code_pattern' => '(?:37)?\d{4}',
                'subdivision_depth' => 1,
            ],
            'US' => [
                'format' => "%givenName %familyName\n%organization\n%addressLine1\n%addressLine2\n%locality, %administrativeArea %postalCode",
                'required_fields' => [
                    'addressLine1', 'locality', 'administrativeArea', 'postalCode',
                ],
                'uppercase_fields' => [
                    'locality', 'administrativeArea',
                ],
                'administrative_area_type' => 'state',
                'postal_code_type' => 'zip',
                'postal_code_pattern' => '(\d{5})(?:[ \-](\d{4}))?',
                'subdivision_depth' => 1,
            ],
```

You can see how the `format` string matches up with the forms displayed in the above image. The postal code, locality, and administrative area (province) fields appear on separate lines for Armenia. For the United States, locality, administrative area (state), and postal code appear inline on a single line.

### What about address field X? Can I add another property to an address field?
No, not without great difficulty. But you can *repurpose* an unused property for a custom one. For example, suppose you need a *Building name* field. In this example, we'll assume that we don't need to use the *Additional name* (middle name) field. Let's look at how we can repurpose this unused field for a custom, *Building name* field.

For any countries that include *Company* (Organization) in their address format, we want *Building name* to be included on the subsequent line.

![Buidling name field](../../images/address-format-2.png)

First, we'll implement an *EventSubscriber* for the `AddressEvents::ADDRESS_FORMAT` event, to customize the address formats for all countries. This example assumes that we have already created a custom module, named *mymodule*. Here is our event subscriber class, in which we insert the *additionalName* field after *organization* in our address format:

```php
<?php

namespace Drupal\mymodule\EventSubscriber;

use Drupal\address\Event\AddressEvents;
use Drupal\address\Event\AddressFormatEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Repurposes additional name field as building name.
 */
class BuildingNameEventSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events[AddressEvents::ADDRESS_FORMAT][] = ['onAddressFormat'];
    return $events;
  }

  public function onAddressFormat(AddressFormatEvent $event) {
    $definition = $event->getDefinition();

    // Place %additionalName after %organization in the format.
    $format = $definition['format'];
    $format = str_replace('%additionalName', '', $format);
    $format = str_replace('%organization', "%organization\n%additionalName", $format);
    $definition['format'] = $format;

    $event->setDefinition($definition);
  }

}
```

Next, we need to add our custom event subscriber to the module's services YAML file, `mymodule.services.yml`:
```YAML
services:
  mymodule.subscriber:
    class: Drupal\mymodule\EventSubscriber\BuildingNameEventSubscriber
    tags:
      - {name: event_subscriber}
```

After rebuilding caches, the *Middle name* field now appears immediately after the *Company* field on both the address form and the displayed address:

![Repurpose middle name field](../../images/address-format-1.png)

Next, we need to relabel *Middle name* as *Building name* and change its textfield size to match the *Company* textfield. We'll do this by modifying the `additional_name` address element field in an `#after_build` callback function.

```php
<?php

use Drupal\Core\Form\FormStateInterface;

function mymodule_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  if (($form_id == 'profile_customer_edit_form') || ($form_id == 'profile_customer_add_form')) {
    $form['address']['widget'][0]['address']['#after_build'][] = 'mymodule_customize_address';
  }
}

function mymodule_customize_address($element, $form_state) {
  $element['additional_name']['#title'] = t('Building name');
  $element['additional_name']['#size'] = 60;

  return $element;
}
```

## Contries and subdivisons

The Address module provides a custom *Country* field type along with a custom form element, default formatter, and default widget. Country data is stored internally using standard 2-letter codes. Country codes are limited to the list of available countries.

### What is the list of available countries and how can I change it?
The *Commerce Guys Addressing* library provides a list of countries with translations for over 250 locales. This library is a requirement for the Address module, which is part of why we strongly recommend using Composer to manage your Drupal Commerce project. The dataset is stored locally in JSON format. For the actual list, see the *CountryRepository* class: `commerceguys/addressing/src/Country/CountryRepository.php`.

You can alter the list of *available* countries for a select list by subscribing to the `AddressEvents::AVAILABLE_COUNTRIES` event. Here is a simple example of an event subscriber for the available countries event. It reduces the set of available countries to just 5: Australia, Brazil, Canada, Japan, and the United Kingdom.

```php
<?php

namespace Drupal\mymodule\EventSubscriber;

use Drupal\address\Event\AddressEvents;
use Drupal\address\Event\AvailableCountriesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LimitCountriesEventSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events[AddressEvents::AVAILABLE_COUNTRIES][] = ['onAvailableCountries'];
    return $events;
  }

  public function onAvailableCountries(AvailableCountriesEvent $event) {
    $countries = ['AU' => 'AU', 'BR' => 'BR', 'CA' => 'CA', 'GB' => 'GB', 'JP' => 'JP'];
    $event->setAvailableCountries($countries);
  }

}
```

Don't forget to include this event subscriber in your custom module's `services.yml` file and rebuild caches.

#### How do I set the default country for customers?
*Default country* is a field setting for *Address* fields. To set the default country for customers, you need to configure the *Address* field for the *Customer* profile type. This administration page is located at `/admin/config/people/profile-types/manage/customer/fields/profile.customer.address`. Select the default country from the list of options:

![Admin ui for default country](../../images/address-countries-1.png)

### Country subdivisions
A country can have several levels of subdivisions that are used for addressing. In the United States that would be the state. In Brazil it would be the state and the municipality. In China it would be the province, the prefecture-level city, and the county. The *Address* module provides user-friendly address forms that provide dropdowns for these subdivisions, thus speeding up the data entry process and reducing mistakes.

Subdivision data is provided by the Commerce Guys addressing library. Subdivisions are hierarchcial, with up to 3 levels: Administrative area -> Locality -> Dependent locality. For each level, there is a set list of options that is used to populate form select lists (dropdown menus). For example, both Australia and the United States have a single level of subdivisions, called *States*. If *United States* is selected as the country, then its 50 states and additional territories appear as *State* options. If *Australia* is selected, then its 8 state/territory options are displayed:

![Australian subdivisions](../../images/address-countries-2.png)

South Korea is an example of a country with an additional level of subdivisions. Once an administrative area, labeled *Do si*, is selected, a list of locality (City) options appears:

![South Korean subdivisions](../../images/address-countries-3.png)

The dataset is stored locally in JSON format. To view the actual data, see `commerceguys/addressing/resources/subdivision`.


#### Why is country X missing subdivisions?
The Commerce Guys addressing data set only includes subdivisions that are required for addressing.

#### How do I add or modify subdivisions for a country?
You can modify the subdivision data returned for a specific country by subscribing to the `AddressEvents::SUBDIVISIONS` event. The Address module includes an example *subdivisions* event subscriber in its test code. See `address/tests/modules/address_test/src/EventSubscriber/GreatBritainEventSubscriber.php`. In this example code, a county field and a predefined list of counties are added for the United Kingdom (country code *GB*).

When *United Kingdom* is selected as the country, a *County* select field appears with the list of options we've defined:

![Custom subdivisions example](../../images/address-countries-4.png)

To start, we subscribe to the `AddressEvents::SUBDIVISIONS` event and implement its callback method: `onSubdivisions()`. We only include the Welsh countries here, for simplicity; a real subscriber would include the full list, sourced from the [CLDR "Territory Subdivisions" listing].

```php
  public static function getSubscribedEvents() {
    $events[AddressEvents::SUBDIVISIONS][] = ['onSubdivisions'];
    return $events;
  }

  public function onSubdivisions(SubdivisionsEvent $event) {
    // For administrative areas $parents is an array with just the country code.
    // Otherwise it also contains the parent subdivision codes. For example,
    // if we were defining cities in California, $parents would be ['US', 'CA'].
    $parents = $event->getParents();
    if ($event->getParents() != ['GB']) {
      return;
    }

    $definitions = [
      'country_code' => $parents[0],
      'parents' => $parents,
      'subdivisions' => [
        // Key by the subdivision code, which is the value that's displayed on
        // the formatted address. Could be an abbreviation (e.g 'CA' for
        // California) or a full name like below.
        // If it's an abbreviation, define a 'name' in the subarray, to be used
        // in the address widget dropdown.
        'Anglesey' => [],
        // You can optionally define an ISO 3166-2 code for each subdivision.
        'Blaenau Gwent' => [
          'iso_code' => 'GB-BGW',
        ],
        'Bridgend' => [],
        'Caerphilly' => [],
        'Cardiff' => [],
        'Carmarthenshire' => [],
        'Ceredigion' => [],
        'Conwy' => [],
        'Denbighshire' => [],
        'Flintshire' => [],
        'Gwynedd' => [],
        'Merthyr Tydfil' => [],
        'Monmouthshire' => [],
        'Neath Port Talbot' => [],
        'Newport' => [],
        'Pembrokeshire' => [],
        'Powys' => [],
        'Rhondda Cynon Taf' => [],
        'Swansea' => [],
        'Tarfaen' => [],
        'Vale of Glamorgan' => [],
        'Wrexham' => [],
      ],
    ];
    $event->setDefinitions($definitions);
  }

```

Next, we need to customize the *address format* defined for the United Kingdom by subscribing to the `AddressEvents::ADDRESS_FORMAT` event. We'll add that into the `getSubscribedEvents()` method:

  ```php
    public static function getSubscribedEvents() {
      $events[AddressEvents::ADDRESS_FORMAT][] = ['onAddressFormat'];
      $events[AddressEvents::SUBDIVISIONS][] = ['onSubdivisions'];
      return $events;
    }

    public function onAddressFormat(AddressFormatEvent $event) {
      $definition = $event->getDefinition();
      if ($definition['country_code'] == 'GB') {
        $definition['format'] = $definition['format'] . "\n%administrativeArea";
        $definition['administrative_area_type'] = AdministrativeAreaType::COUNTY;
        $definition['subdivision_depth'] = 1;
        $event->setDefinition($definition);
      }
    }
  ```

Don't forget to include the event subscriber in your custom module's `services.yml` file and rebuild caches!

#### How can I alter a subdivision list *without* affecting the stored address formats
In the previous example, we created new address format data. In some cases, you might want to just make a minor modification to existing data. Or perhaps you'd like to have different subdivision lists in different contexts. For example, suppose you'd like to have an address field that only displays a subset of *States* for addresses in the United States. In this example, we'll limit the options for *States* in the United States to just the Mid-Atlantic states:  New York, New Jersey, Pennsylvania, Delaware, Maryland, Washington, D.C., Virginia, and West Virginia.

![Modified state options for US](../../images/address-countries-5.png)

The default address widget uses the custom *address* form element. As a result, you cannot use `hook_form_alter()` or `hook_field_widget_form_alter()` directly. Instead, we'll use `hook_form_alter()` in combination with a `#pre_render` callback, in a custom module named *mymodule*.

!!! Example
    ```php
    function mymodule_form_alter(&$form, FormStateInterface $form_state, $form_id) {
    if (($form_id == 'profile_customer_edit_form') || ($form_id == 'profile_customer_add_form')) {
        $form['address']['widget'][0]['address']['#pre_render'][] = 'mymodule_prerender';
    }

    function mymodule_prerender($element) {
    if ($element['country_code']['#default_value'] == 'US') {
        $include_states = ['', 'NY', 'NJ', 'PA', 'DE', 'MD', 'DC', 'VA', 'WV'];
        $options = array_intersect_key($element['administrative_area']['#options'], array_flip($include_states));
        $element['administrative_area']['#options'] = $options;
    }
    return $element;
    }
    ```

## Address entry

This documentation page describes how you can customize address fields as they appear on forms.

* [Setting field overrides](#setting-field-overrides) describes how to control which address field properties appear on forms and whether they are required.
* [Altering address field properties on forms](#altering-address-field-properties-on-forms) describes how to change field property labels and make other alterations.
* [Setting initial values for address field properties](#setting-initial-values-for-address-field-properties) describes how to set default values for address field properties.

### Setting field overrides
For each address field property (First name, middle name, last name, etc.), you can specify an *override* setting. By default, the data in the Commerce Guys *Addressing* library is used to determine how field properties should be used for a specific country.

The options are:

| Setting  | Behavior |
| ---------| -------- |
| Hidden   | Field property will not be used. |
| Optional | A field property that is normally required will be optional. |
| Required | The field property will be required. This will only apply if the address format uses the field property. |

![Field overrides for locked fields](../../images/address-entry-3.png)

Administrative users can configure field override settings for an address field by navigating to a content entity type's *Manage fields* administrative page and then clicking on the address field's *Edit* operations link. Here, an address field named *Test address field* has been added to the *Basic page* content type. This page is located at `/admin/structure/types/manage/page/fields`:

![Field overrides for locked fields](../../images/address-entry-4.png)

#### Field overrides for locked address fields
If your address field is locked, you won't be able to use the address field's *Manage fields* administrative page to set field overrides. However, you can implement `hook_form_alter()` to programmatically set override values.

For example, suppose you want to set overrides for the address field that appears on *Customer* profiles. Here we set override values for the organization, locality, and postal code field properties.

```php
<?php

use Drupal\Core\Form\FormStateInterface;

function mymodule_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  if (($form_id == 'profile_customer_edit_form') || ($form_id == 'profile_customer_add_form')) {
    $form['address']['widget'][0]['address']['#field_overrides'] = [
      'organization' => 'required',
      'locality' => 'optional',
      'postalCode' => 'hidden',
    ];
  }
}
```

### Altering address field properties on forms
The default address widget uses the custom *address* form element. As a result, you cannot use `hook_form_alter()` or `hook_field_widget_form_alter()` directly. Instead, you need to add an `#after_build` callback to the address form element and then alter it from there.

!!! example "Customize the *Company* field property"
    In this example, we'll make the following customizations to the *Company* field property:
    * Change the "Company" label to "Organization".
    * Change the textfield size from 60 to 30.
    * Make the field property required.

    ```php
      <?php

      use Drupal\Core\Form\FormStateInterface;

      function mymodule_form_alter(&$form, FormStateInterface $form_state, $form_id) {
        if (($form_id == 'profile_customer_edit_form') || ($form_id == 'profile_customer_add_form')) {
          $form['address']['widget'][0]['address']['#after_build'][] = 'mymodule_customize_address';
        }
      }

      function mymodule_customize_address($element, $form_state) {
        $element['organization']['#title'] = t('Organization');
        $element['organization']['#size'] = 30;
        $element['organization']['#required'] = TRUE;
        return $element;
      }
    ```

![Customized company field](../../images/address-entry-1.png)

!!! example "Customize the *Street address* field property"
    In this example, we'll change the default "Street address" label with separate labels for each of the two street address field properties. We can use the same `mymodule_form_alter()` from the first example with a new `mymodule_customize_address()`:"

    ```php
      function mymodule_customize_address($element, $form_state) {
        dpm($element['#field_overrides']);
      $element['address_line1']['#title'] = t('Address line 1');
      $element['address_line2']['#title'] = t('Address line 2');
      $element['address_line2']['#title_display'] = 'before';
    ```

![Customized street address](../../images/address-entry-2.png)

### Setting initial values for address field properties
**Note: onInitialValues is deprectated and no longer fires since version 1.5** 
@see the [AddressEvents](https://git.drupalcode.org/project/address/blob/8.x-1.x/src/Event/AddressEvents.php) documentation. 

The *Address* module provides an event that can be used to set initial values for address field properties. In this example, we'll create an event subscriber to set the default country to *Australia*, the suburb to *New South Wales*, and the city to *Sydney*.

![Australia initial values](../../images/address-entry-5.png)

Here is our complete event subscriber class (missing documentation):

```php
<?php

namespace Drupal\mymodule\EventSubscriber;

use Drupal\address\Event\AddressEvents;
use Drupal\address\Event\InitialValuesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AustraliaDefaultEventSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events[AddressEvents::INITIAL_VALUES][] = ['onInitialValues'];
    return $events;
  }

  public function onInitialValues(InitialValuesEvent $event) {
    $initial_values = [
      'country_code' => 'AU',
      'administrative_area' => 'NSW',
      'locality' => 'Sydney',
    ];
    $event->setInitialValues($initial_values);
  }

}
```

Don't forget to add the event subscriber to your custom module's `services.yml` file and rebuild caches:

```php
services:
  mymodule.australia_subscriber:
    class: Drupal\mymodule\EventSubscriber\AustraliaDefaultEventSubscriber
    tags:
      - {name: event_subscriber}
```



## Address display


This documentation page describes how to customize how addresses are displayed. The Address module includes two formatters for displaying addresses: *Default* and *Plain*. If you require customizations that are not possible with either of these formatters, you can create your own custom field formatter plugin.

* Use the [*Default* address formatter](#the-default-address-formatter) to display addresses in country-specific formats.
* Use the [*Plain* address formatter](#the-plain-address-formatter) to provide custom theming for addresses.
* Use the [Postal Label Formatter Service](#postal-label-formatter-service) to use standards required for automated mail sorting.
* [Multilingual Considerations](#multilingual-considerations) decribes how to override the language used to format addresses.

### The *Default* address formatter
The Default formatter uses a repository of address format data (provided by the Commerce Guys Addressing Library) to format the display of an address. Google's [Address Data Service](https://chromium-i18n.appspot.com/ssl-address) is the data source for these address formats. The displayed format will closely match the ordering used on forms for entering the addresses. (*The Country field always appears first on forms, even if it is displayed last in the correctly formatted adddress.*)

![Default address formatter display](../../images/address-display-1.png)

If you want to customize how addresses are displayed by the *default* address formatter, you can create a custom event subscriber to alter the address formats provided by the `AddressFormatRepository`. The [Address formats documentation](#address-formats) provides an explanation of address formats and example event subscriber code.

#### How do I hide the *Country* for domestic addresses?
The *Default* address formatter will display the country for all addresses, regardless of whether the address is international or domestic. One way to alter this behavior is to create a custom address field formatter plugin that *extends* the Default address formatter. In this example, we'll create a custom formatter that will display the *Country* for addresses in all countries *except* the United States.

This example assumes that we have already created a custom module, named mymodule. Well create a custom formatter plugin named `AddressHideUSFormatter` like this:

```php
<?php

namespace Drupal\mymodule\Plugin\Field\FieldFormatter;

use Drupal\address\Plugin\Field\FieldFormatter\AddressDefaultFormatter;

/**
 * Plugin implementation of the 'address_us_default' formatter.
 *
 * @FieldFormatter(
 *   id = "address_us_default",
 *   label = @Translation("Hide US"),
 *   field_types = {
 *     "address",
 *   },
 * )
 */
class AddressHideUSFormatter extends AddressDefaultFormatter {

}

```
We give the field formatter plugin a unique id: *address_us_default* and descriptive label in the annotation. If you rebuild caches, you will see this new field formatter as an option for the Display of any address fields:

![Hide US custom formatter](../../images/address-display-3.png)

At this point, our custom formatter works exactly the same as the *Default* formatter. To customize its behavior, we'll override the `postRender()` method, since that is the method in which the *country* field gets added to the address format string. This is the relevant code:

```php
    if (Locale::matchCandidates($address_format->getLocale(), $locale)) {
      $format_string = '%country' . "\n" . $address_format->getLocalFormat();
    }
    else {
      $format_string = $address_format->getFormat() . "\n" . '%country';
    }
```

All we'll do is add an extra condition, to modify the functionality for *US* addresses:
```php
    if ($address_format->getCountryCode() == 'US') {
      $format_string = $address_format->getFormat();
    }
```

To make this work for domestic addresses in other countries, just change the *country code* in the added condition. Our full `postRender()` method now looks like this:

```php
  public static function postRender($content, array $element) {
    /** @var \CommerceGuys\Addressing\AddressFormat\AddressFormat $address_format */
    $address_format = $element['#address_format'];
    $locale = $element['#locale'];
    // Add the country to the bottom or the top of the format string,
    // depending on whether the format is minor-to-major or major-to-minor.
    if ($address_format->getCountryCode() == 'US') {
      $format_string = $address_format->getFormat();
    }
    elseif (Locale::matchCandidates($address_format->getLocale(), $locale)) {
      $format_string = '%country' . "\n" . $address_format->getLocalFormat();
    }
    else {
      $format_string = $address_format->getFormat() . "\n" . '%country';
    }

    $replacements = [];
    foreach (Element::getVisibleChildren($element) as $key) {
      $child = $element[$key];
      if (isset($child['#placeholder'])) {
        $replacements[$child['#placeholder']] = $child['#value'] ? $child['#markup'] : '';
      }
    }
    $content = self::replacePlaceholders($format_string, $replacements);
    $content = nl2br($content, FALSE);

    return $content;
  }
```

Lastly, we need to add two additional *use* statements for the `postRender()` method:
```php
use CommerceGuys\Addressing\Locale;
use Drupal\Core\Render\Element;
```

Now the *Hide US* address formatter will only show countries for non-US addresses.

### The *Plain* address formatter
The Plain formatter also relies on the addressing library data but only to get the subdivision code and name values, since the *Plain* formatter doesn't actually format the addresses; instead, the *Plain* formatter uses the `address-plain.html.twig` twig template for the actual address formatting.

![Default address formatter display](../../images/address-display-2.png)

As you can see, this default twig template does not do a very good job of properly formatting this particular address. The *City* field isn't even displayed! In most cases, you will want to use the *Default*formatter so that you can rely on the data in the Commerce Guys Addressing Library. However, if you want to precisely control the layout of your addresses, you can create your own twig template for the *Plain* address formatter as part of a custom theme. Theming is an advanced topic beyond the scope of this Drupal Commerce documentation guide. For an overview, please see the [Theming Drupal Guide] on Drupal.org.

#### Available theme variables
- **given_name**: Given name.
- **additional_name**: Additional name.
- **family_name**: Family name.
- **organization**: Organization.
- **address_line1**: First address line.
- **address_line2**: Second address line.
- **postal_code**: Postal code.
- **sorting_code**: Sorting code.
- **country.code**: Country code.
- **country.name**: Country name.

**Subdivision field variables**

For address fields that can be defined as part of a subdivision (administrative area, locality, and dependent locality), the theming is a little trickier. If one of these fields is *not* defined in the country's subdivision data, then you should use its basic variable:

- **dependent_locality**: The dependent locality.
- **locality**: The locality.
- **administrative_area**: The administrative area.

For the example above, to make the missing *City* field appear, we'd need to use `{{ locality }}` instead of `{{ locality.code }}` in our twig template.

If a subdivision was entered, the array will always have a code. If it's a predefined subdivision, it will also have a name. The code is always preferred. These subdivision variables are:

- **dependent_locality.code**: Dependent locality code.
- **dependent_locality.name**: Dependent locality name.
- **locality.code**: Locality code.
- **locality.name**: Locality name.
- **administrative_area.code**: Administrative area code.
- **administrative_area.name**: Administrative area name.


### Postal Label Formatter Service
In the [*Default* address formatter section](#the-default-address-formatter), we created a relatively simple custom address field formatter to remove the *country* field from domestic address displays. If you need a custom formatter with more complex functionality, perhaps for postal/shipping labels, you might want to take a look at the `PostalLabelFormatter` service, provided by the Commerce Guys Addressing library and included in the *Address* module.

The `PostalLabelFormatter` service is an address formatter that uses standards required for automated mail sorting. It takes care of uppercasing fields where required by the format and differentiating between domestic and international mail. In the case of domestic mail, the country name is not displayed at all. In the case of international mail, the postal code is prefixed with the destination's postal code prefix, and the country name is added to the address in both the current locale and English.

For a usage example of this service, see the `AddressToGeo` class in the [Geolocation Address Link module].

### Multilingual considerations
Some countries use separate address formats for the local language vs. other languages. For example, China uses major-to-minor ordering when the address is entered in Chinese, and minor-to-major when the address is entered in other languages. This means that the address must remember which language it was entered in, to ensure consistent formatting later on.

It is possible to override the used language via field settings, in case the language is always known (e.g. a field storing the "english address" on a chinese article). This option will appear on the *Settings* administrative page for the address field, on multilingual sites. Use this setting to ensure that entered addresses are always formatted in the same language.

![Language override setting](../../images/address-format-4.png)

## Zones and territories

!!! note 
    We need help filling out this section! Feel free to follow the *edit this page* link and contribute.


## Links and resources:
* For more information on the Commerce Guys Addressing library and its data model, see its [README file](https://github.com/commerceguys/addressing/blob/master/README.md)
* [Wikipedia page on Administrative division (country subdivision)](https://en.wikipedia.org/wiki/Administrative_division)
* [Drupal 8 documentation: Create a custom field formatter](https://www.drupal.org/docs/8/creating-custom-modules/create-a-custom-field-formatter)
* Discussion and example of a custom address formatter: [Pretty field formatter](https://www.drupal.org/project/address/issues/2912629)

[Google's Address Data Service]: https://chromium-i18n.appspot.com/ssl-address
[Address module]: https://www.drupal.org/project/address
[CLDR "Territory Subdivisions" listing]: http://cldr.unicode.org/index/downloads
[Theming Drupal Guide]: https://www.drupal.org/docs/8/theming
[Geolocation Address Link module]: https://www.drupal.org/project/geolocation_address_link