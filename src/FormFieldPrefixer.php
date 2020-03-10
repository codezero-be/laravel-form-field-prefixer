<?php

namespace CodeZero\FormFieldPrefixer;

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
        $this->multiDimensional = $multiDimensional;

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
     * Get the form field name.
     *
     * @param string $name
     *
     * @return string
     */
    public function name($name)
    {
        return $this->buildFormFieldIdentifier($name, $this->isArray());
    }

    /**
     * Get the form field ID.
     *
     * @param string $id
     *
     * @return string
     */
    public function id($id)
    {
        return $this->buildFormFieldIdentifier($id, false);
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
        $separator = $this->isArray() ? $this->getArrayValidationSeparator() : $this->getDefaultSeparator();

        return $this->buildFormFieldIdentifier($key, false, $separator);
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
     * Build the form field identifier.
     *
     * @param string $name
     * @param bool $useArraySyntax
     * @param string|null $separator
     *
     * @return string
     */
    protected function buildFormFieldIdentifier($name, $useArraySyntax, $separator = null)
    {
        $separator = $separator ?: $this->getDefaultSeparator();

        $prefix = $this->buildName($name);
        $arrayKey = $this->buildArrayKey($useArraySyntax, $separator);
        $arrayName = $this->buildArrayName($name, $useArraySyntax, $separator);

        return $prefix . $arrayKey . $arrayName;
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
