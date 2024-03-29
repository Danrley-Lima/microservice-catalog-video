<?php

namespace Tests\Unit\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category as EntityCategory;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\UpdateCategory\UpdateCategoryInputDto;
use Core\UseCase\DTO\Category\UpdateCategory\UpdateCategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateCategoryUseCaseUnitTest extends TestCase {
  public function testRenameCategory() {
    $uuid = (string) Uuid::uuid4()->toString();
    $name = "Name";
    $description = "Description";

    $this->mockEntity = Mockery::mock(EntityCategory::class, [
      $uuid,
      $name,
      $description,
    ]);
    $this->mockEntity->shouldReceive('update');
    $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));


    $this->mockRepository = Mockery::mock(CategoryRepositoryInterface::class);
    $this->mockRepository->shouldReceive('findById')->andReturn($this->mockEntity);
    $this->mockRepository->shouldReceive('update')->andReturn($this->mockEntity);

    $this->mockInputDto = Mockery::mock(UpdateCategoryInputDto::class, [
      $uuid,
      "new name",
    ]);

    $useCase = new UpdateCategoryUseCase($this->mockRepository);
    $responseUseCase = $useCase->execute($this->mockInputDto);

    $this->assertInstanceOf(UpdateCategoryOutputDto::class, $responseUseCase);

    /**
     * Spies
     */
    $this->spy = Mockery::spy(CategoryRepositoryInterface::class);
    $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
    $this->spy->shouldReceive('update')->andReturn($this->mockEntity);
    $useCase = new UpdateCategoryUseCase($this->spy);
    $responseUseCase = $useCase->execute($this->mockInputDto);
    $this->spy->shouldHaveReceived('findById');
    $this->spy->shouldHaveReceived('update');
  }

  protected function tearDown(): void {
    Mockery::close();
    parent::tearDown();
  }
}
