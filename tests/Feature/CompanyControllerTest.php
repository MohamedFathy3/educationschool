<?php

use App\Models\Company;
use App\Models\Department;

beforeEach(function () {
    // Ensure migrations are run before each test
    $this->artisan('migrate');

    // Seed the database
    $this->seed();  // This will run the seeder you have for companies and departments
});

// Test index method for fetching all companies
it('can fetch all companies', function () {
    $company = Company::create([
        'name' => 'Test Company',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'TC',
        'type' => 'hq',
    ]);

    $response = $this->getJson(route('company.index'));

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Company']);
});

// Test store method for creating a company
it('can store a new company', function () {
    $data = [
        'name' => 'New Company',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'NC',
        'type' => 'hq',
    ];

    $response = $this->postJson(route('company.store'), $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('companies', ['name' => 'New Company']);
});

// Test show method for fetching a specific company
it('can fetch a company by ID', function () {
    $company = Company::create([
        'name' => 'Test Company',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'TC',
        'type' => 'hq',
    ]);

    $response = $this->getJson(route('company.show', $company));

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Test Company']);
});

// Test update method for updating a company
it('can update an existing company', function () {
    $company = Company::create([
        'name' => 'Old Company',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'OC',
        'type' => 'hq',
    ]);

    $updatedData = [
        'name' => 'Updated Company',
        'country' => 'Canada',
        'city' => 'Toronto',
        'code' => 'UC',
        'type' => 'branch',
    ];

    $response = $this->putJson(route('company.update', $company), $updatedData);

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Updated Company']);
    $this->assertDatabaseHas('companies', ['name' => 'Updated Company']);
});

// Test destroy method for deleting a company
it('can delete a company', function () {
    $company = Company::create([
        'name' => 'Company to delete',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'CD',
        'type' => 'hq',
    ]);

    $response = $this->deleteJson(route('company.destroy', $company));

    $response->assertStatus(200);
    $this->assertDatabaseMissing('companies', ['id' => $company->id]);
});

// Test search functionality in the index method
it('can search companies by name, country, city, and code', function () {
    $company = Company::create([
        'name' => 'Search Company',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'SC',
        'type' => 'hq',
    ]);

    $searchTerm = 'Search';
    $response = $this->getJson(route('company.index', ['search' => $searchTerm]));

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Search Company']);
});

// Test relationship between companies and departments (load departments)
it('can load departments for a company', function () {
    $company = Company::create([
        'name' => 'Company with Departments',
        'country' => 'USA',
        'city' => 'New York',
        'code' => 'CWD',
        'type' => 'hq',
    ]);

    $department = Department::create(['name' => 'Operations']);
    $company->departments()->attach($department);

    $response = $this->getJson(route('company.show', $company));

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Operations']);
});
