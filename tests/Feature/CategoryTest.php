<?php

use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test("category index page is displayed", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    Category::factory(5)->create();

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('categories.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertSuccessful();
    $response->assertSee('categories');
});

test("category create page is displayed", function () {
    // AAA - Arrange, Act, Assert

    // Arrange

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('categories.create'));

    // Assert
    $response->assertStatus(200);
    $response->assertSuccessful();
    $response->assertSee('Create Category');
    $response->assertSee('Cancel');
});

test("store a new category", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $categoryData = [
        'name' => 'New Category',
    ];

    // Act
    $response = $this->actingAs($this->user)
        ->post(route('categories.store'), $categoryData);

    // Assert
    $response->assertStatus(302);
    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', $categoryData);
    $this->assertDatabaseCount('categories', 1);
});

test("validate category name is required", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $categoryData = [
        'name' => '',
    ];

    // Act
    $response = $this->actingAs($this->user)
        ->post(route('categories.store'), $categoryData);

    // Assert
    $response->assertStatus(302);
    $response->assertSessionHasErrors('name');
    $response->assertInvalid(['name']);

    $this->assertDatabaseCount('categories', 0);
});

test("edit category form is displaying proper name", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $category = Category::factory()->create([
        'name' => 'Television',
    ]);

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('categories.edit', $category));

    // Assert
    $response->assertStatus(200);
    $response->assertSuccessful();
    $response->assertSee('Television');
});

test("update category name", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $category = Category::factory()->create([
        'name' => 'Television',
    ]);

    $updatedData = [
        'name' => 'TV',
    ];

    // Act
    $response = $this->actingAs($this->user)
        ->put(route('categories.update', $category), $updatedData);

    // Assert
    $response->assertStatus(302);
    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', $updatedData);
});

test("validate category name is required on update", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $category = Category::factory()->create([
        'name' => 'Television',
    ]);

    $updatedData = [
        'name' => '',
    ];

    // Act
    $response = $this->actingAs($this->user)
        ->put(route('categories.update', $category), $updatedData);

    // Assert
    $response->assertStatus(302);
    $response->assertSessionHasErrors('name');
    $response->assertInvalid(['name']);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Television',
    ]);
});

test("delete a category", function () {
    // AAA - Arrange, Act, Assert

    // Arrange
    $category = Category::factory()->create();

    // Act
    $response = $this->actingAs($this->user)
        ->delete(route('categories.destroy', $category));

    // Assert
    $response->assertStatus(302);
    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

test("requires authentication to see category index page", function () {
    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));
});
