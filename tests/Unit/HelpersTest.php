<?php

namespace CodeZero\FormFieldPrefixer\Tests\Unit;

use CodeZero\FormFieldPrefixer\FormFieldPrefixer;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_returns_a_form_field_prefixer_instance()
    {
        $prefixer = formFieldPrefixer();

        $this->assertInstanceOf(FormFieldPrefixer::class, $prefixer);
        $this->assertEquals('field', $prefixer->name('field', null));
    }

    /** @test */
    public function it_accepts_a_prefix()
    {
        $prefixer = formFieldPrefixer('prefix');

        $this->assertInstanceOf(FormFieldPrefixer::class, $prefixer);
        $this->assertEquals('prefix_field', $prefixer->name('field', null));
    }
}
