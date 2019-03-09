<?php

namespace Tests\Unit;

use App\Document;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{
    /** @var Document */
    private $document;

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

    /**
     * @test
     * @dataProvider fileSizeProvider
     */
    public function it_can_convert_to_human_readable_file_size($size, $humanSize)
    {
        $doc = factory(Document::class)->make([ 'size' => $size ]);

        $this->assertEquals($humanSize, $doc->humanFileSize());
    }

    public function fileSizeProvider()
    {
        return [
            [100, '100 B'],
            [300, '300 B'],
            [1000, '1.00 kB'],
            [1000000, '1.00 MB'],
            [1000000000, '1.00 GB'],
            [1000000000000, '1.00 TB'],
            [1000000000000000, '1.00 PB'],
            [1000000000000000000, '1.00 EB'],
            [1000000000000000000000, '1.00 ZB'],
            [1000000000000000000000000, '1.00 YB'],
        ];
    }
}