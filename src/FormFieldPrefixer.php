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
    protected $arrayKey;

    /**
     * FormPrefixer constructor.
     *
     * @param null $prefix
     */
    public function __construct($prefix = null)
    {
        $this->prefix = $prefix;
        $this->arrayKey = null;
    }

    /**
     * Set the array key to use for the form field.
     *
     * @param string $arrayKey
     *
     * @return $this
     */
    public function asArray($arrayKey)
    {
        $this->arrayKey = $arrayKey;

        return $this;
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
        return $this->buildFormFieldIdentifier($name, true);
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
        $useArraySyntax = $useArraySyntax && $this->isArray();
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
        return $this->hasPrefix() ? $this->prefix : $name;
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
        return $this->isArray() ? $this->buildArrayIdentifier($this->arrayKey, $useArraySyntax, $separator) : '';
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
        return $this->hasPrefix() ? $this->buildArrayIdentifier($name, $useArraySyntax, $separator) : '';
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
