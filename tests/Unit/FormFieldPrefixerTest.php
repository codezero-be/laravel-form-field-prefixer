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

        $this->assertEquals('abc', $prefixer->name('abc'));
        $this->assertEquals('abc', $prefixer->id('abc'));
        $this->assertEquals('abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_with_a_prefix()
    {
        $prefixer = new FormFieldPrefixer('prefix');

        $this->assertEquals('prefix_abc', $prefixer->name('abc'));
        $this->assertEquals('prefix_abc', $prefixer->id('abc'));
        $this->assertEquals('prefix_abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_with_an_array_prefix()
    {
        $prefixer = (new FormFieldPrefixer('prefix'))->asArray('arrayKey');

        $this->assertEquals('prefix[arrayKey][abc]', $prefixer->name('abc'));
        $this->assertEquals('prefix_arrayKey_abc', $prefixer->id('abc'));
        $this->assertEquals('prefix.arrayKey.abc', $prefixer->validationKey('abc'));
    }

    /** @test */
    public function it_builds_input_identifiers_as_an_array()
    {
        $prefixer = (new FormFieldPrefixer())->asArray('arrayKey');

        $this->assertEquals('abc[arrayKey]', $prefixer->name('abc'));
        $this->assertEquals('abc_arrayKey', $prefixer->id('abc'));
        $this->assertEquals('abc.arrayKey', $prefixer->validationKey('abc'));
    }
}
