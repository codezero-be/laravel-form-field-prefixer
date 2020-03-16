<?php

namespace CodeZero\FormFieldPrefixer;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class FormFieldPrefixer
{
    /**
     * The form field prefix.
     *
     * @var string|null
     */
    protected $prefix;

    /**
     * The array key for the form field.
     *
     * @var string|null
     */
    protected $arrayKey = null;

    /**
     * Indicator if we are generating a form field name
     * with a multi dimensional array.
     *
     * @var bool
     */
    protected $multiDimensional = false;

    /**
     * Get a FormFieldPrefixer instance.
     *
     * @param string|null $prefix
     *
     * @return static
     */
    public static function make($prefix = null)
    {
        return new static($prefix);
    }

    /**
     * FormPrefixer constructor.
     *
     * @param string|null $prefix
     */
    public function __construct($prefix = null)
    {
        $this->withPrefix($prefix);
    }

    /**
     * Set the prefix to be used for the form fields.
     *
     * @param string|null $prefix
     *
     * @return $this
     */
    public function withPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the array key to use for the form field
     * and generate a name with a flat array.
     *
     * @param string $arrayKey
     * @param bool $multiDimensional
     *
     * @return $this
     */
    public function asArray($arrayKey, $multiDimensional = false)
    {
        $this->arrayKey = $arrayKey;
        $this->multiDimensional = $multiDimensional && $this->prefix !== null;

        return $this;
    }

    /**
     * Set the array key to use for the form field and
     * generate a name with a multi dimensional array.
     *
     * @param string $arrayKey
     *
     * @return $this
     */
    public function asMultiDimensionalArray($arrayKey)
    {
        return $this->asArray($arrayKey, true);
    }

    /**
     * Determine if any key uses javascript.
     *
     * @return bool
     */
    public function isJavaScript()
    {
        return Str::startsWith($this->prefix, '${') || Str::startsWith($this->arrayKey, '${');
    }

    /**
     * Get the form field's "name" attribute.
     *
     * @param string $name
     * @param string|null $attribute
     *
     * @return string
     */
    public function name($name, $attribute = 'name')
    {
        return $this->buildAttribute(
            $this->buildAttributeValue($name, $this->isArray()),
            $this->buildAttributeName($attribute)
        );
    }

    /**
     * Get the form field's "id" attribute.
     *
     * @param string $id
     * @param string|null $attribute
     *
     * @return string
     */
    public function id($id, $attribute = 'id')
    {
        return $this->buildAttribute(
            $this->buildAttributeValue($id, false),
            $this->buildAttributeName($attribute)
        );
    }

    /**
     * Get the label's "for" attribute.
     *
     * @param string $id
     * @param string|null $attribute
     *
     * @return string
     */
    public function for($id, $attribute = 'for')
    {
        return $this->id($id, $attribute);
    }

    /**
     * Get the input's "value" attribute.
     *
     * @param string $name
     * @param string|null $default
     * @param string|null $attribute
     *
     * @return string
     */
    public function value($name, $default = null, $attribute = 'value')
    {
        $value = $this->isJavaScript()
            ? $this->buildJavaScriptValueKey($name)
            : e($this->getCurrentValue($name, $default));

        return $this->buildAttribute($value, $this->buildAttributeName($attribute));
    }

    /**
     * Get the "selected" attribute for the given option value.
     *
     * @param string $name
     * @param string $value
     * @param string|null $default
     *
     * @return string
     */
    public function selected($name, $value, $default = null)
    {
        if ($value != $this->getCurrentValue($name, $default)) {
            return '';
        }

        return $this->buildAttribute('selected', $this->buildAttributeName('selected'));
    }

    /**
     * Get the select's "v-model" binding when using javascript.
     *
     * @param string $name
     *
     * @return string
     */
    public function select($name)
    {
        if ( ! $this->isJavaScript()) {
            return '';
        }

        return $this->buildAttribute(
            $this->buildJavaScriptValueKey($name),
            $this->buildAttributeName('v-model')
        );
    }

    /**
     * Get the validation key for the form field.
     *
     * @param string $key
     *
     * @return string
     */
    public function validationKey($key)
    {
        $separator = $this->isArray() || $this->isJavaScript()
            ? $this->getArrayValidationSeparator()
            : $this->getDefaultSeparator();

        return $this->buildAttributeValue($key,false, $separator);
    }

    /**
     * Check if the form field is an array.
     *
     * @return bool
     */
    public function isArray()
    {
        return ! is_null($this->arrayKey);
    }

    /**
     * Check if the form field is an array.
     *
     * @return bool
     */
    public function isMultiDimensionalArray()
    {
        return $this->multiDimensional;
    }

    /**
     * Check if the form field has a prefix.
     *
     * @return bool
     */
    public function hasPrefix()
    {
        return !! $this->prefix;
    }

    /**
     * Build the attribute.
     *
     * @param string $value
     * @param string|null $attribute
     *
     * @return string
     */
    protected function buildAttribute($value, $attribute)
    {
        if ($attribute) {
            return new HtmlString($attribute . '="' . $value . '"');
        }

        return $value;
    }

    /**
     * Build the attribute name if needed.
     *
     * @param string|null $attribute
     *
     * @return string
     */
    protected function buildAttributeName($attribute)
    {
        if ( ! $attribute) {
            return '';
        }

        if ($attribute === 'value' && $this->isJavaScript()) {
            return 'v-model';
        }

        if ($attribute !== 'v-model' && $this->isJavaScript()) {
            return ":{$attribute}";
        }

        return $attribute;
    }

    /**
     * Build the attribute value.
     *
     * @param string $name
     * @param bool $useArraySyntax
     * @param string|null $separator
     *
     * @return string
     */
    protected function buildAttributeValue($name, $useArraySyntax, $separator = null)
    {
        $separator = $separator ?: $this->getDefaultSeparator();

        $prefix = $this->buildName($name);
        $arrayKey = $this->buildArrayKey($useArraySyntax, $separator);
        $arrayName = $this->buildArrayName($name, $useArraySyntax, $separator);

        $identifier = $prefix . $arrayKey . $arrayName;

        if ($this->isJavaScript()) {
            $identifier = "`{$identifier}`";
        }

        return $identifier;
    }

    /**
     * Build the (base)name of the form field.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildName($name)
    {
        if ( ! $this->hasPrefix()) {
            return $name;
        }

        if ($this->isMultiDimensionalArray()) {
            return $this->prefix;
        }

        return $this->prefix . $this->getDefaultSeparator() . $name;
    }

    /**
     * Build the array key part of the form field identifier if needed.
     *
     * @param bool $useArraySyntax
     * @param string $separator
     *
     * @return string
     */
    protected function buildArrayKey($useArraySyntax, $separator)
    {
        if ( ! $this->isArray()) {
            return '';
        }

        return $this->buildArrayIdentifier($this->arrayKey, $useArraySyntax, $separator);
    }

    /**
     * Build the array part of the form field identifier
     * that has the form field name if needed.
     *
     * @param string $name
     * @param bool $useArraySyntax
     * @param string $separator
     *
     * @return string
     */
    protected function buildArrayName($name, $useArraySyntax, $separator)
    {
        if ( ! $this->hasPrefix() || ! $this->isMultiDimensionalArray()) {
            return '';
        }

        return $this->buildArrayIdentifier($name, $useArraySyntax, $separator);
    }

    /**
     * Build the array part of a form field identifier.
     *
     * @param string $value
     * @param bool $useArraySyntax
     * @param string $separator
     *
     * @return string
     */
    protected function buildArrayIdentifier($value, $useArraySyntax, $separator)
    {
        return $useArraySyntax ? "[{$value}]" : $separator . $value;
    }

    /**
     * Build the javascript key that holds the inputs value.
     *
     * @param string $key
     *
     * @return string
     */
    protected function buildJavaScriptValueKey($key)
    {
        if ($this->isMultiDimensionalArray()) {
            $key = "'{$key}'";
        }

        $key = $this->name($key, null);
        $key = preg_replace('/`|\${\s*|\s*}/', '', $key);

        return $key;
    }

    /**
     * Get the old input value or any default value.
     *
     * @param string $name
     * @param string|null $default
     *
     * @return mixed
     */
    protected function getCurrentValue($name, $default)
    {
        return Session::hasOldInput() ? Session::getOldInput($this->validationKey($name)) : $default;
    }

    /**
     * Get the default separator for form fields.
     *
     * @return string
     */
    protected function getDefaultSeparator()
    {
        return '_';
    }

    /**
     * Get the separator to use for validation keys.
     *
     * @return string
     */
    protected function getArrayValidationSeparator()
    {
        return '.';
    }
}
