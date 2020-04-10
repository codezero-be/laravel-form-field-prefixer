<?php

namespace CodeZero\FormFieldPrefixer\Tests\Feature;

use CodeZero\FormFieldPrefixer\FormFieldPrefixer;
use CodeZero\FormFieldPrefixer\Tests\TestCase;
use Illuminate\Support\Facades\Session;

class OldInputValueTest extends TestCase
{
    /** @test */
    public function it_builds_the_value_attribute()
    {
        Session::flashInput([
            'abc' => 'test value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_accepts_a_default_value()
    {
        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('value="default value"', $prefixer->value('abc', 'default value'));
    }

    /** @test */
    public function it_prefers_the_old_value_over_the_default_value()
    {
        Session::flashInput([
            'abc' => 'old value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('value="old value"', $prefixer->value('abc', 'default value'));
    }

    /** @test */
    public function it_prefers_old_values_even_when_empty()
    {
        Session::flashInput([
            'abc' => null
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('value=""', $prefixer->value('abc', 'default value'));
    }

    /** @test */
    public function it_builds_the_value_attribute_with_prefix()
    {
        Session::flashInput([
            'prefix_abc' => 'test value'
        ]);

        $prefixer = new FormFieldPrefixer('prefix');

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_the_value_attribute_with_arrays()
    {
        Session::flashInput([
            'abc' => [
                'arrayKey' => 'test value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer())->asArray('arrayKey');

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_the_value_attribute_with_prefixed_arrays()
    {
        Session::flashInput([
            'prefix_abc' => [
                'arrayKey' => 'test value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('arrayKey');

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_the_value_attribute_with_prefixed_arrays_using_the_field_name_as_array_key()
    {
        Session::flashInput([
            'prefix' => [
                'abc' => 'test value'
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asArray();

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_the_value_attribute_with_multi_dimensional_arrays()
    {
        Session::flashInput([
            'prefix' => [
                'arrayKey' => [
                    'abc' => 'test value'
                ]
            ]
        ]);

        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('arrayKey');

        $this->assertEquals('value="test value"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_lets_you_change_the_attribute_name()
    {
        Session::flashInput([
            'abc' => 'test value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('title="test value"', $prefixer->value('abc', null, 'title'));

    }

    /** @test */
    public function it_gets_the_value_without_the_attribute_name()
    {
        Session::flashInput([
            'abc' => 'test value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('test value', $prefixer->value('abc', null, null));
    }

    /** @test */
    public function it_escapes_input_values()
    {
        Session::flashInput([
            'abc' => 'test "value"'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('value="test &quot;value&quot;"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_prefix_is_provided()
    {
        $prefixer = new FormFieldPrefixer('${ prefix }');

        $this->assertEquals('v-model="prefix_abc"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('${ arrayKey }');

        $this->assertEquals('v-model="prefix_abc[arrayKey]"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_javascript_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asArray('${ arrayKey }');

        $this->assertEquals('v-model="abc[arrayKey]"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_multi_dimensional_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('v-model="prefix[arrayKey][\'abc\']"', $prefixer->value('abc'));
    }

    /** @test */
    public function it_builds_a_v_model_attribute_if_a_multi_dimensional_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('v-model="abc[arrayKey]"', $prefixer->value('abc'));
    }
}
