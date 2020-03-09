<?php

if (! function_exists('formFieldPrefixer')) {
    /**
     * Create an instance of FormFieldPrefixer.
     *
     * @param string $prefix
     *
     * @return \CodeZero\FormFieldPrefixer\FormFieldPrefixer
     */
    function formFieldPrefixer($prefix = '')
    {
        return new \CodeZero\FormFieldPrefixer\FormFieldPrefixer($prefix);
    }
}
