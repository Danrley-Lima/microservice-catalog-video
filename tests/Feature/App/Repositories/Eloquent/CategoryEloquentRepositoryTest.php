<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Core\Domain\Exception\NotFoundException;
use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase {
  protected $repository;

  protected function setUp(): void {
    parent::setUp();
    $this->repository = new CategoryEloquentRepository(new Model());
  }

  public function testInsert() {
    $entity = new EntityCategory(
      name: 'Teste',
    );

    $response = $this->repository->insert($entity);

    $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
    $this->assertInstanceOf(EntityCategory::class, $response);
    $this->assertDatabaseHas('categories', [
      'name' => 'Teste',
    ]);
  }

  public function testFindById() {
    $category = Model::factory()->create();

    $response = $this->repository->findById($category->id);

    $this->assertInstanceOf(EntityCategory::class, $response);
    $this->assertEquals($category->id, $response->id());
  }

  public function testFindByIdNotFound() {
    try {
      $this->repository->findById("fakeValue");
      $this->assertTrue(false);
    } catch (\Throwable $th) {
      $this->assertInstanceOf(NotFoundException::class, $th);
    }
  }

  public function testFindAll() {
    Model::factory()->count(15)->create();
    $response = $this->repository->findAll();

    $this->assertCount(15, $response);
  }
}
