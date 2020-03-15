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

        $this->assertEquals('title="test value"', $prefixer->value('abc', 'title'));

    }

    /** @test */
    public function it_gets_the_value_without_the_attribute_name()
    {
        Session::flashInput([
            'abc' => 'test value'
        ]);

        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('test value', $prefixer->value('abc', null));
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
