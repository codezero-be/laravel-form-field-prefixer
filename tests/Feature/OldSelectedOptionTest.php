<?php

namespace CodeZero\FormFieldPrefixer\Tests\Feature;

use CodeZero\FormFieldPrefixer\FormFieldPrefixer;
use CodeZero\FormFieldPrefixer\Tests\TestCase;
use Illuminate\Support\Facades\Session;

class OldSelectedOptionTest extends TestCase
{
    /** @test */
    public function it_builds_the_selected_attribute()
    {
        Session::flashInput([
            'abc' => 'selected option value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_selects_a_default_option()
    {
        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('', $prefixer->selected('abc', 'other value', 'default value'));
        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'default value', 'default value'));
    }

    /** @test */
    public function it_prefers_the_old_value_over_the_default_value()
    {
        Session::flashInput([
            'abc' => 'old value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'old value', 'default value'));
        $this->assertEquals('', $prefixer->selected('abc', 'default value', 'default value'));
    }

    /** @test */
    public function it_prefers_old_values_even_when_empty()
    {
        Session::flashInput([
            'abc' => null
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('', $prefixer->selected('abc', 'old value', 'default value'));
        $this->assertEquals('', $prefixer->selected('abc', 'default value', 'default value'));
    }

    /** @test */
    public function it_builds_the_selected_attribute_with_prefix()
    {
        Session::flashInput([
            'prefix_abc' => 'selected option value'
        ]);

        $prefixer = new FormFieldPrefixer('prefix');

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_the_selected_attribute_with_arrays()
    {
        Session::flashInput([
            'abc' => [
                'arrayKey' => 'selected option value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer())->asArray('arrayKey');

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_the_selected_attribute_with_prefixed_arrays()
    {
        Session::flashInput([
            'prefix_abc' => [
                'arrayKey' => 'selected option value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('arrayKey');

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_the_selected_attribute_with_prefixed_arrays_using_the_field_name_as_array_key()
    {
        Session::flashInput([
            'prefix' => [
                'abc' => 'selected option value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asArray();

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_the_selected_attribute_with_multi_dimensional_arrays()
    {
        Session::flashInput([
            'prefix' => [
                'arrayKey' => [
                    'abc' => 'selected option value'
                ]
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('arrayKey');

        $this->assertEquals('selected="selected"', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_prefix_is_provided()
    {
        $prefixer = new FormFieldPrefixer('${ prefix }');

        $this->assertEquals('v-model="prefix_abc"', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('${ arrayKey }');

        $this->assertEquals('v-model="prefix_abc[arrayKey]"', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asArray('${ arrayKey }');

        $this->assertEquals('v-model="abc[arrayKey]"', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_multi_dimensional_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('v-model="prefix[arrayKey][\'abc\']"', $prefixer->select('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_multi_dimensional_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('v-model="abc[arrayKey]"', $prefixer->select('abc'));
    }

    /** @test */
    public function it_does_not_render_a_selected_attribute_if_a_javascript_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('', $prefixer->selected('abc', 'old value', 'default value'));
        $this->assertEquals('', $prefixer->selected('abc', 'default value', 'default value'));
    }
}
