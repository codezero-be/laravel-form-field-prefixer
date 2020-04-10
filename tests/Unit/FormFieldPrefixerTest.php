<?php

namespace CodeZero\FormFieldPrefixer\Tests\Unit;

use CodeZero\FormFieldPrefixer\FormFieldPrefixer;
use PHPUnit\Framework\TestCase;

class FormFieldPrefixerTest extends TestCase
{
    /** @test */
    public function it_builds_simple_input_identifiers()
    {
        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('name="abc"', $prefixer->name('abc'));
        $this->assertEquals('id="abc"', $prefixer->id('abc'));
        $this->assertEquals('for="abc"', $prefixer->for('abc'));
        $this->assertEquals('abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_with_a_prefix()
    {
        $prefixer = new FormFieldPrefixer('prefix');

        $this->assertEquals('name="prefix_abc"', $prefixer->name('abc'));
        $this->assertEquals('id="prefix_abc"', $prefixer->id('abc'));
        $this->assertEquals('for="prefix_abc"', $prefixer->for('abc'));
        $this->assertEquals('prefix_abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_with_a_prefix_via_a_method_call()
    {
        $prefixer = (new FormFieldPrefixer())->withPrefix('prefix');

        $this->assertEquals('name="prefix_abc"', $prefixer->name('abc'));
        $this->assertEquals('id="prefix_abc"', $prefixer->id('abc'));
        $this->assertEquals('for="prefix_abc"', $prefixer->for('abc'));
        $this->assertEquals('prefix_abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_as_an_array_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asArray('arrayKey');

        $this->assertEquals('name="abc[arrayKey]"', $prefixer->name('abc'));
        $this->assertEquals('id="abc_arrayKey"', $prefixer->id('abc'));
        $this->assertEquals('for="abc_arrayKey"', $prefixer->for('abc'));
        $this->assertEquals('abc.arrayKey', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_as_an_array_with_prefix()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('arrayKey');

        $this->assertEquals('name="prefix_abc[arrayKey]"', $prefixer->name('abc'));
        $this->assertEquals('id="prefix_abc_arrayKey"', $prefixer->id('abc'));
        $this->assertEquals('for="prefix_abc_arrayKey"', $prefixer->for('abc'));
        $this->assertEquals('prefix_abc.arrayKey', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_using_the_field_name_as_array_key()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray();

        $this->assertEquals('name="prefix[abc]"', $prefixer->name('abc'));
        $this->assertEquals('id="prefix_abc"', $prefixer->id('abc'));
        $this->assertEquals('for="prefix_abc"', $prefixer->for('abc'));
        $this->assertEquals('prefix.abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_as_a_multi_dimensional_array_with_prefix()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('arrayKey');

        $this->assertEquals('name="prefix[arrayKey][abc]"', $prefixer->name('abc'));
        $this->assertEquals('id="prefix_arrayKey_abc"', $prefixer->id('abc'));
        $this->assertEquals('for="prefix_arrayKey_abc"', $prefixer->for('abc'));
        $this->assertEquals('prefix.arrayKey.abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function using_multi_dimensional_arrays_without_a_prefix_results_in_a_flat_array()
    {
        $prefixer = (new FormFieldPrefixer())->asMultiDimensionalArray('arrayKey');

        $this->assertEquals('name="abc[arrayKey]"', $prefixer->name('abc'));
        $this->assertEquals('id="abc_arrayKey"', $prefixer->id('abc'));
        $this->assertEquals('for="abc_arrayKey"', $prefixer->for('abc'));
        $this->assertEquals('abc.arrayKey', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_lets_you_change_the_attribute_name()
    {
        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('title="abc"', $prefixer->name('abc', 'title'));
        $this->assertEquals('title="abc"', $prefixer->id('abc', 'title'));
        $this->assertEquals('title="abc"', $prefixer->for('abc', 'title'));

    }

    /** @test */
    public function it_returns_the_identifier_without_the_attribute_name()
    {
        $prefixer = new FormFieldPrefixer();

        $this->assertEquals('abc', $prefixer->name('abc', null));
        $this->assertEquals('abc', $prefixer->id('abc', null));
        $this->assertEquals('abc', $prefixer->for('abc', null));
    }

    /** @test */
    public function it_detects_a_javascript_key()
    {
        $this->assertFalse((new FormFieldPrefixer('arrayKey'))->isJavaScript());
        $this->assertFalse((new FormFieldPrefixer())->asArray('arrayKey')->isJavaScript());
        $this->assertFalse((new FormFieldPrefixer())->asMultiDimensionalArray('arrayKey')->isJavaScript());

        $this->assertTrue((new FormFieldPrefixer('${ arrayKey }'))->isJavaScript());
        $this->assertTrue((new FormFieldPrefixer())->asArray('${ arrayKey }')->isJavaScript());
        $this->assertTrue((new FormFieldPrefixer())->asMultiDimensionalArray('${ arrayKey }')->isJavaScript());
    }

    /** @test */
    public function it_builds_a_template_string_if_a_javascript_prefix_is_provided()
    {
        $prefixer = new FormFieldPrefixer('${ prefix }');

        $this->assertEquals(':name="`${ prefix }_abc`"', $prefixer->name('abc'));
        $this->assertEquals(':id="`${ prefix }_abc`"', $prefixer->id('abc'));
        $this->assertEquals(':for="`${ prefix }_abc`"', $prefixer->for('abc'));
        $this->assertEquals('`${ prefix }_abc`', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_a_template_string_if_a_javascript_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('${ arrayKey }');

        $this->assertEquals(':name="`prefix_abc[${ arrayKey }]`"', $prefixer->name('abc'));
        $this->assertEquals(':id="`prefix_abc_${ arrayKey }`"', $prefixer->id('abc'));
        $this->assertEquals(':for="`prefix_abc_${ arrayKey }`"', $prefixer->for('abc'));
        $this->assertEquals('`prefix_abc.${ arrayKey }`', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_a_template_string_if_a_javascript_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asArray('${ arrayKey }');

        $this->assertEquals(':name="`abc[${ arrayKey }]`"', $prefixer->name('abc'));
        $this->assertEquals(':id="`abc_${ arrayKey }`"', $prefixer->id('abc'));
        $this->assertEquals(':for="`abc_${ arrayKey }`"', $prefixer->for('abc'));
        $this->assertEquals('`abc.${ arrayKey }`', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_a_template_string_if_a_multi_dimensional_array_key_is_provided()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals(':name="`prefix[${ arrayKey }][abc]`"', $prefixer->name('abc'));
        $this->assertEquals(':id="`prefix_${ arrayKey }_abc`"', $prefixer->id('abc'));
        $this->assertEquals(':for="`prefix_${ arrayKey }_abc`"', $prefixer->for('abc'));
        $this->assertEquals('`prefix.${ arrayKey }.abc`', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_a_template_string_if_a_multi_dimensional_array_key_is_provided_without_prefix()
    {
        $prefixer = (new FormFieldPrefixer())->asMultiDimensionalArray('${ arrayKey }');

        $this->assertEquals(':name="`abc[${ arrayKey }]`"', $prefixer->name('abc'));
        $this->assertEquals(':id="`abc_${ arrayKey }`"', $prefixer->id('abc'));
        $this->assertEquals(':for="`abc_${ arrayKey }`"', $prefixer->for('abc'));
        $this->assertEquals('`abc.${ arrayKey }`', $prefixer->validationKey('abc'));
    }
}
