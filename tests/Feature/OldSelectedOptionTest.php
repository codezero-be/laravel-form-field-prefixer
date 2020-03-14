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
    public function it_builds_a_v_model_attribute_if_a_javascript_key_is_detected()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals('', $prefixer->selected('abc', 'selected option value'));
        $this->assertEquals('', $prefixer->selected('abc', 'other option value'));
        $this->assertEquals('v-model="prefix[arrayKey][\'abc\']"', $prefixer->select('abc'));
    }
}
