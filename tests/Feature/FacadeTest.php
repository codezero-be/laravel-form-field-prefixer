<?php

namespace CodeZero\FormFieldPrefixer\Tests\Feature;

use CodeZero\FormFieldPrefixer\Facades\FormFieldPrefixer;
use CodeZero\FormFieldPrefixer\FormFieldPrefixer as FormFieldPrefixerInstance;
use CodeZero\FormFieldPrefixer\Tests\TestCase;

class FacadeTest extends TestCase
{
    /** @test */
    public function it_makes_a_new_form_field_prefixer_instance()
    {
        $prefixer = FormFieldPrefixer::make();

        $this->assertInstanceOf(FormFieldPrefixerInstance::class, $prefixer);
        $this->assertEquals('abc', $prefixer->name('abc', null));
    }

    /** @test */
    public function it_makes_a_new_form_field_prefixer_instance_with_prefix()
    {
        $prefixer = FormFieldPrefixer::make('prefix');

        $this->assertInstanceOf(FormFieldPrefixerInstance::class, $prefixer);
        $this->assertEquals('prefix_abc', $prefixer->name('abc', null));
    }
}
