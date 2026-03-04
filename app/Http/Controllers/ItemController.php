<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Items API',
    description: 'API for managing items inventory',
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Local development server')]
#[OA\Schema(
    schema: 'Item',
    required: ['name', 'price'],
    properties: [
        new OA\Property(property: 'uuid', type: 'string', format: 'uuid', readOnly: true, example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', type: 'string', example: 'Laptop'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'A high-end laptop'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 999.99),
        new OA\Property(property: 'is_available', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', readOnly: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', readOnly: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ItemRequest',
    required: ['name', 'price'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Laptop'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'A high-end laptop'),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 999.99),
        new OA\Property(property: 'is_available', type: 'boolean', example: true),
    ],
    type: 'object'
)]
class ItemController extends Controller
{
    #[OA\Get(
        path: '/api/items',
        summary: 'Get all items',
        description: 'Returns a list of all items',
        tags: ['Items'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Item')
                )
            ),
        ]
    )]
    public function index()
    {
        return response()->json(Item::all()->makeHidden('id'), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    #[OA\Post(
        path: '/api/items',
        summary: 'Create a new item',
        description: 'Stores a new item and returns it',
        tags: ['Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ItemRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Item created',
                content: new OA\JsonContent(ref: '#/components/schemas/Item')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0'
        ]);

        $item = Item::create($validated);

        return response()->json($item->makeHidden('id'), 201);
    }

    #[OA\Get(
        path: '/api/items/{uuid}',
        summary: 'Get a single item',
        description: 'Returns a single item by UUID',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                description: 'UUID of the item',
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(ref: '#/components/schemas/Item')
            ),
            new OA\Response(response: 404, description: 'Item not found'),
        ]
    )]
    public function show(Item $item)
    {
        return response()->json($item->makeHidden('id'), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    #[OA\Put(
        path: '/api/items/{uuid}',
        summary: 'Update an item',
        description: 'Updates an existing item and returns it',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                description: 'UUID of the item',
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ItemRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Item updated',
                content: new OA\JsonContent(ref: '#/components/schemas/Item')
            ),
            new OA\Response(response: 404, description: 'Item not found'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0'
        ]);

        $item->update($validated);

        return response()->json($item->makeHidden('id'), 200);
    }

    #[OA\Delete(
        path: '/api/items/{uuid}',
        summary: 'Delete an item',
        description: 'Deletes an item by UUID',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                description: 'UUID of the item',
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Item deleted'),
            new OA\Response(response: 404, description: 'Item not found'),
        ]
    )]
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
