<?php

namespace CodeZero\FormFieldPrefixer\Tests\Feature;

use CodeZero\FormFieldPrefixer\Facades\FormFieldPrefixer as FormFieldPrefixerFacade;
use CodeZero\FormFieldPrefixer\FormFieldPrefixer;
use CodeZero\FormFieldPrefixer\Tests\TestCase;

class FormFieldPrefixerTest extends TestCase
{
    /** @test */
    public function it_makes_a_new_form_field_prefixer_instance()
    {
        $prefixer = FormFieldPrefixerFacade::make();

        $this->assertInstanceOf(FormFieldPrefixer::class, $prefixer);
        $this->assertEquals('abc', $prefixer->name('abc', null));
    }

    /** @test */
    public function it_makes_a_new_form_field_prefixer_instance_with_prefix()
    {
        $prefixer = FormFieldPrefixerFacade::make('prefix');

        $this->assertInstanceOf(FormFieldPrefixer::class, $prefixer);
        $this->assertEquals('prefix_abc', $prefixer->name('abc', null));
    }
}
