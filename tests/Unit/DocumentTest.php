<?php

namespace Tests\Unit;

use App\Document;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{
    /** @var Document */
    private $document;

    // TODO: It can convert to human file size (maybe make separate class and test for that)

    public function setUp()
    {
        parent::setUp();

        $this->document = factory(Document::class)->make([
            'name' => 'Test.docx',
            'sha256' => 'aFlP1k1CpAQZUKjsTI0qEHTDsPc8wHlbNkuwr4YdoPImuyFy9tXwWVgkPUPF5igN',
            'size' => 3004
        ]);
    }


    /** @test */
    public function it_is_a_document()
    {
        $this->assertInstanceOf(Document::class, $this->document);
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->assertEquals('Test.docx', $this->document->name);
    }

    /** @test */
    public function a_name_is_optional()
    {
        $doc = factory(Document::class)->make(['name' => null]);

        $this->assertNull($doc->name);
    }

    /** @test */
    public function it_has_a_sha256()
    {
        $this->assertEquals('aFlP1k1CpAQZUKjsTI0qEHTDsPc8wHlbNkuwr4YdoPImuyFy9tXwWVgkPUPF5igN', $this->document->sha256);
    }

    /** @test */
    public function it_has_a_size()
    {
        $this->assertEquals(3004, $this->document->size);
    }

    /** @test */
    public function it_knows_its_path()
    {
        $this->assertStringEndsWith('/doc/aFlP1k1CpAQZUKjsTI0qEHTDsPc8wHlbNkuwr4YdoPImuyFy9tXwWVgkPUPF5igN', $this->document->path());
    }
}