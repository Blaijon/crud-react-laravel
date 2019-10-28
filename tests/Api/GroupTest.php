<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Group;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GroupControllerTest extends TestCase
{
    use WithFaker;

    public function testGroupCreated()
    {
      $expected = [
        'group_name' => 'Test Group'
    ];
    $response = $this->postJson('/api/groups', [
        'file' => new UploadedFile(resource_path('../tests/testGroup.csv'), 'testGroup.csv', null, null, null, true),
    ]);
    $response
        ->assertStatus(201)
        ->assertJsonFragment($expected);
    }

    // public function testGroupRetrieved()
    // {

    // }

    // public function testAllGroupsRetrieved()
    // {

    // }

    // public function testNoGroupRetrieved()
    // {

    // }

    // public function testGroupUpdated()
    // {

    // }

    // public function testGroupDeleted()
    // {


    // }
}
