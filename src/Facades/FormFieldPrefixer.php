<?php

namespace CodeZero\FormFieldPrefixer\Facades;

use Illuminate\Support\Facades\Facade;

class FormFieldPrefixer extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \CodeZero\FormFieldPrefixer\FormFieldPrefixer::class;
    }
}
