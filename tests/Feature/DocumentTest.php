<?php

namespace Tests\Feature;

use App\Document;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_an_index_of_documents()
    {
        $documents = factory(Document::class, 3)->create();

        $response = $this->get('/doc');

        $response->assertDontSee($documents[0]->sha256);
        $response->assertSee(str_limit($documents[0]->sha256, 48, ''));
    }

    /** @test */
    public function it_shows_the_document()
    {
        $document = factory(Document::class)->create();

        $response = $this->get($document->path());

        $response->assertOk();
        $response->assertSee($document->sha256);
        $response->assertSee($document->name);
    }

    /** @test */
    public function it_creates_a_document_with_valid_data()
    {
        $response = $this->post('/doc', [
            'name' => 'Test Doc.xml',
            'sha256' => 'AgSFVxSoXkuVppwDTf5YQr0cid84IEvWxC762cGeKsIOlppCuCOjle8dugpLNARw',
            'size' => '1000'
        ]);

        $response->assertLocation('/doc/AgSFVxSoXkuVppwDTf5YQr0cid84IEvWxC762cGeKsIOlppCuCOjle8dugpLNARw');

        $this->assertDatabaseHas('documents', [
            'name' => 'Test Doc.xml',
            'sha256' => 'AgSFVxSoXkuVppwDTf5YQr0cid84IEvWxC762cGeKsIOlppCuCOjle8dugpLNARw',
            'size' => '1000'
        ]);
    }

    /** @test */
    public function it_doesnt_create_a_test_with_invalid_data()
    {
        $response = $this->post('/doc', [
            'sha256' => 'This is not a valid sha',
            'size' => '-1',
        ]);

        $response->assertSessionHasErrors([
            'sha256', 'size'
        ]);

        $this->assertDatabaseMissing('documents', [
            'name' => 'This is not a valid sha',
            'size' => '-1'
        ]);
    }

    /** @test */
    public function it_doesnt_create_duplicate_documents()
    {
        // Note: documents are considered duplicate when they have
        // the same sha256 value.
        
        $doc1 = factory(Document::class)->create();

        $response = $this->post('/doc', [
            'name' => 'Second Document',
            'sha256' => $doc1->sha256
        ]);

        $response->assertRedirect();

        $this->assertDatabaseMissing('documents', [
            'name' => 'Second Document'
        ]);
    }
    
    /** @test */
    public function it_doesnt_save_the_document_when_comparing()
    {
        $original_document = factory(Document::class)->create();

        $compared_document = factory(Document::class)->make();

        $response = $this->post('/doc', $compared_document->toArray() + ['compare_to' => $original_document->sha256]);
        $response->assertRedirect($original_document->path());

        $response->assertSessionHas(['confirmation']);

        $this->assertDatabaseMissing('documents', ['sha256' => $compared_document->sha256]);
    }

    /** @test */
    public function it_shows_the_comparison_when_comparing()
    {
        $original_document = factory(Document::class)->create();

        $matching_document = factory(Document::class)->make(['sha256' => $original_document->sha256]);
        $mismatched_document = factory(Document::class)->make();

        $matching_response = $this->post('/doc', $matching_document->toArray() + ['compare_to' => $original_document->sha256]);
        $matching_response->assertSessionHas('confirmation', true);

        $mismatched_response = $this->post('/doc', $mismatched_document->toArray() + ['compare_to' => $original_document->sha256]);
        $mismatched_response->assertSessionHas('confirmation', false);
    }
}
