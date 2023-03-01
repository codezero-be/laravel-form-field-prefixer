# Laravel Form Field Prefixer

[![GitHub release](https://img.shields.io/github/release/codezero-be/laravel-form-field-prefixer.svg?style=flat-square)](CHANGELOG.md)
[![Laravel](https://img.shields.io/badge/laravel-10-red?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![License](https://img.shields.io/packagist/l/codezero/laravel-form-field-prefixer.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/scrutinizer/build/g/codezero-be/laravel-form-field-prefixer/master?style=flat-square)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/build-status/master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/codezero-be/laravel-form-field-prefixer/master?style=flat-square)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/quality/g/codezero-be/laravel-form-field-prefixer/master?style=flat-square)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/codezero/laravel-form-field-prefixer.svg?style=flat-square)](https://packagist.org/packages/codezero/laravel-form-field-prefixer)

[![ko-fi](https://www.ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/R6R3UQ8V)

#### Reuse form partials with optional prefixes and array keys.

When you have large forms and you want to split them up into reusable partials, you might end up doing some nasty logic inside the partials. To streamline this, I tucked away the logic behind the `FormFieldPrefixer` class.

## ✅ Requirements

- PHP >= 7.1
- Laravel >= 5.6

## 📦 Install

```bash
composer require codezero/laravel-form-field-prefixer
```

> Laravel will automatically register the `FormFieldPrefixer` facade.

## 🛠 Usage

### Basic Usage - Simple Forms

Pass an instance of the `FormFieldPrefixer` to your form partial.

Optionally you may provide an optional prefix to the `make` method:

```blade
@include('forms.address', ['prefixer' => FormFieldPrefixer::make('client')])
```

If you don't provide a prefix, `client_` will be stripped of the example output below.

Inside the partial you can now use `$prefixer` to generate field names dynamically:

#### Labels

Generate a `for` attribute:

```blade
<label {{ $prefixer->for('address') }}>Address:</label>
```

This will compile to:

```blade
<label for="client_address">Address:</label>
```

#### Input Fields

Generate a `name`, `id` and `value` attribute:

You may pass an optional default value that will be filled in when the form loads.

If you submit the form and get redirected back with the input (like when you have validation errors), then the new values will override the default ones and will be filled in behind the scenes, using `Session::getOldInput($field)`.

```blade
<input
    {{ $prefixer->name('address') }}
    {{ $prefixer->id('address') }}
    {{ $prefixer->value('address', 'default value') }}
>
```

This will compile to:

```blade
<input name="client_address" id="client_address" value="default value">
```

#### Select Fields

Generate a `name` and `id` attribute for the select and a `selected` attribute for the selected option:

The `selected` method requires the option's value and accepts an optional default value to determine if the option is the selected one. In the example below `Mr.` is set as the default value.

Like with the input field, old input has precedence over the default value.

```blade
<select {{ $prefixer->name('title') }} {{ $prefixer->id('title') }}>
    @foreach(['Mr.', 'Mrs.'] as $option)
        <option value="{{ $option }}" {{ $prefixer->selected('title', $option, 'Mr.') }}>
            {{ $option }}
        </option>
    @endforeach
</select>
```

This will compile to:

```blade
<select name="client_title" id="client_title">
    <option value="mr" selected="selected">Mr.</option>
    <option value="mrs">Mrs.</option>
</select>
```

#### Validation Errors

Generate a validation key you can pass to `@error`:

```blade
@error($prefixer->validationKey('address'))
    {{ $message }}
@enderror
```

This will compile to:

```blade
@error('client_address')
    {{ $message }}
@enderror
```

### Flat Array Forms

If you require, for example, multiple addresses, you can specify an array key for each partial:

```blade
@foreach(['billing', 'shipping'] as $type)
    @include('forms.address', ['prefixer' => FormFieldPrefixer::make('client')->asArray($type)])
@endforeach
```

The partial will contain the same templates as the previous examples, but this time, notice the difference between the `name`, `id` and `error` key:

```blade
<input name="client_address[billing]" id="client_address_billing" value="">

@error('client_address.billing')
    {{ $message }}
@enderror
```

Alternatively, you can also use the field name as the array key, by not specifying a key in advance:

```blade
@include('forms.address', ['prefixer' => FormFieldPrefixer::make('client')->asArray()])
```

Now the `address` field will look like this:

```blade
<input name="client[address]" id="client_address" value="">

@error('client.address')
    {{ $message }}
@enderror
```

Again, if you did not specify a prefix, `client_` would be stripped off.

### Multi Dimensional Array Forms

You can also use a multi dimensional array notation. For this, you always need to specify a prefix, as that will be the basename of the input. If you don't, the result will be a flat array like in the previous example.

```blade
@foreach(['billing', 'shipping'] as $type)
    @include('forms.address', ['prefixer' => FormFieldPrefixer::make('client')->asMultiDimensionalArray($type)])
@endforeach
```

The partial will contain the same templates as the previous examples, but this time, notice that the input's name is now an array key.

```blade
<input name="client[billing][address]" id="client_billing_address" value="">

@error('client.billing.address')
    {{ $message }}
@enderror
```

### Hybrid Forms with a Touch of VueJS

_**Disclaimer:** As a backend kind of guy, I didn't want to go all-in on JS, but I wanted to add just enough JS to make things work. I'm using VueJS, so this solution will only work with VueJS at this time. Making PHP and VueJS work together nicely with these dynamic forms was much more tricky than I thought (attributes needed much manipulation). But because I didn't want to duplicate all of my forms, I kept banging my head against the keyboard until this package fell out. So far I'm pretty happy with the working result._

This code is supported, but not included in this package because it is project specific.

Feel free to copy the example and adjust it as you need.

**Again, this is a very minimal example, just enough to make things work!**

#### Example Use Case

Let's say we are adding and removing form fields dynamically when someone clicks a button. For each fictional "client" that we add, we require the included form fields to be filled in.

We need data binding to keep the form fields in sync when we add and remove them. When we get validation errors upon submission, we also need to restore any previously dynamically added fields with their filled in "old input".

#### Extra Setup

To get this to work, we need some sort of JS "form manager" that indexes the added clients and keeps a record of all of their input data. Let's create a Vue component that will do this:

```js
/* components/FormManager.vue */
<script>
export default {
    props: {
        values: { required: true },
        errors: { required: true },
    },

    data() {
        return {
            formValues: [],
            formErrors: [],
        }
    },

    mounted() {
        this.formValues = this.values
        this.formErrors = this.errors
    },

    methods: {
        add() {
            this.formValues.push({})
        },

        remove(index) {
            this.formValues.splice(index, 1)
            this.formErrors = []
        },

        getError(key) {
            return (this.formErrors[key] || [])[0]
        }
    },

    render() {
        return this.$scopedSlots.default({
            formValues: this.formValues,
            addFormEntry: this.add,
            removeFormEntry: this.remove,
            getError: this.getError,
        })
    },
}
</script>
```

Don't forget to register the component in your `app.js` file:

```js
/* app.js */
window.Vue = require('vue');

Vue.component('form-manager', require('./components/FormManager').default);

const app = new Vue({
    el: '#app',
});
```

#### Using the Form Manager

Let's wrap our dynamic form parts with this new component. We also need to feed it any "old input" and validation errors, in case we get redirected back after the form submission fails.

```blade
<form-manager
    :values="{{ json_encode(old('clients', [])) }}"
    :errors="{{ json_encode($errors->getMessages()) }}"
    v-slot:default="{ formValues: clients, addFormEntry, removeFormEntry, getError }"
>
	<div v-for="(client, index) in clients" :key="index">
	    <h2>Client #@{{ index + 1 }}</h2>
	    
	    @include('forms.address', ['prefixer' => FormFieldPrefixer::make('clients')->asMultiDimensionalArray('${ index || 0 }')])
	    
	    <button type="button" @click="removeFormEntry(index)">
	        Remove Client
	    </button>
	</div>
	
	<button type="button" @click="addFormEntry()">
	    Add Client
	</button>
</form-manager>
```

**What we are doing here is:**

- we are feeding the `form-manager` any old `clients` input values in JSON format
- we are also feeding the `form-manager` the `errors` array in JSON format
- we use a scoped slot to access and bind the feeded `formValues` using the `clients` alias
- we use a scoped slot to access the  `addFormEntry`, `removeFormEntry` and `getError` methods
- we are using a prefix of `clients` with the `FormFieldPrefixer` 
- we are using a JS string as multi dimensional array key which will eventually print the array index
- `clients` will be the basename of the multi dimensional array
- this package will use the `clients` prefix as variable name to generate input bindings
- **the `formValues` alias should be the same as the `FormFieldPrefixer` prefix**
- we add a button to add and remove a set of client form fields
- the `client` variable from the for loop is not used in this example

#### Template Enhancements

To use the form field templates with both PHP and JS, you need to add one thing to your select fields:

```blade
<select
    {{ $prefixer->name('title') }}
    {{ $prefixer->id('title') }}
    {{ $prefixer->select('title') /* Add this line for JS compatibility */ }}
>
    @foreach(['Mr.', 'Mrs.'] as $option)
        <option value="{{ $option }}" {{ $prefixer->selected('title', $option, 'Mr.') }}>
            {{ $option }}
        </option>
    @endforeach
</select>
```

This will compile to:

```blade
<select
    :name="`clients[${ index || 0 }][title]`"
    :id="`clients_${ index || 0 }_title`"
    v-model="clients[index || 0]['title']"
>
    <option value="mr">Mr.</option>
    <option value="mrs">Mrs.</option>
</select>
```

Notice that instead of a `selected` attribute on an option, there is now a `v-model` attribute on the select.

When you use the same template in a partial that does not have any JS key passed to it, the `v-model` will not be rendered and the `selected` attribute will. So you can use this "enhanced" template for all use cases.

**The other form field templates do not need to be changed.**

Text inputs will automatically get a `v-model` attribute instead of a `value` attribute.

#### Default Values are Ignored

If you want to load default values in your JS-driven form fields when the page loads, you will need to feed those values to the `form-manager`, in the same way you feed it the "old input".

Any default values passed to the `FormFieldPrefixer` methods are ignored when using JS keys.

#### Validation Errors

I ended up extracting a form error partial:

```blade
@php $prefixer = $prefixer ?? FormFieldPrefixer::make(); @endphp

{!!-- For JS enabled fields that render client side... --!!}
@if ($prefixer->isJavaScript())
    <div v-show="!!getError({{ $prefixer->validationKey($field) }})">
        <div v-text="getError({{ $prefixer->validationKey($field) }})"></div>
    </div>
@endif

{!!-- For normal fields that render server side... --!!}
@error($prefixer->validationKey($field))
    <div>{{ $message }}</div>
@enderror
```

Then just include it...

```blade
@include('partials.form-error', ['field' => 'field_name'])
```

#### Browser Compatibility (JS)

Works with all modern browsers, except Internet Explorer, because it uses template strings:

https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals

### Modify or Remove the Attribute Name

If you only want the attribute's value, without its name and the quotes, you can pass `null` as a second parameter to the `name`, `id` and `for` method, or as a third parameter to the `value` method.

```blade
<input name="{{ $prefixer->name('some_field', null) }}"> // will echo: some_field
```

You can also specify a different attribute name:

```blade
<label {{ $prefixer->id('some_field', 'for') }}> // will echo: for="some_field"
```

### Provide a Fallback FormFieldPrefixer

If you want to be able to use your partials even without passing it a `FormFieldPrefixer` instance, you could add the following snippet to the top of you partials:

```blade
@php $prefixer = $prefixer ?? FormFieldPrefixer::make(); @endphp
```

This will make sure there is always a `$prefixer` variable available.

Of course, you are free to choose another variable name.

## 🚧 Testing

```bash
composer test
```

## ☕️ Credits

- [Ivan Vermeyen](https://byterider.io)
- [All contributors](../../contributors)

## 🔓 Security

If you discover any security related issues, please [e-mail me](mailto:ivan@codezero.be) instead of using the issue tracker.

## 📑 Changelog

See a list of important changes in the [changelog](CHANGELOG.md).

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
