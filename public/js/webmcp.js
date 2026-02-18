if (typeof navigator !== 'undefined' && navigator.modelContext) {
    const mc = navigator.modelContext;

    async function api(method, path, body) {
        const opts = {
            method,
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' },
        };
        if (body) {
            opts.headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(body);
        }
        const res = await fetch(`/mcp${path}`, opts);
        if (res.status === 204) return null;
        return res.json();
    }

    function result(data) {
        return { content: [{ type: 'text', text: JSON.stringify(data) }] };
    }

    mc.registerTool({
        name: 'getShoppingLists',
        description: 'Get all shopping lists for the current user',
        inputSchema: { type: 'object', properties: {} },
        execute: async () => result(await api('GET', '/lists')),
    });

    mc.registerTool({
        name: 'getShoppingList',
        description: 'Get a shopping list with all its items',
        inputSchema: {
            type: 'object',
            properties: { listId: { type: 'integer', description: 'Shopping list ID' } },
            required: ['listId'],
        },
        execute: async ({ listId }) => result(await api('GET', `/lists/${listId}`)),
    });

    mc.registerTool({
        name: 'getItems',
        description: 'Get all items in the user\'s catalog',
        inputSchema: { type: 'object', properties: {} },
        execute: async () => result(await api('GET', '/items')),
    });

    mc.registerTool({
        name: 'getTemplates',
        description: 'Get all shopping list templates with their items',
        inputSchema: { type: 'object', properties: {} },
        execute: async () => result(await api('GET', '/templates')),
    });

    mc.registerTool({
        name: 'createShoppingList',
        description: 'Create a new shopping list, optionally from a template',
        inputSchema: {
            type: 'object',
            properties: {
                name: { type: 'string', description: 'List name (default: "Nova lista")' },
                template_id: { type: 'integer', description: 'Template ID to create from' },
            },
        },
        execute: async (params) => result(await api('POST', '/lists', params)),
    });

    mc.registerTool({
        name: 'addItemToList',
        description: 'Add an existing item from the catalog to a shopping list',
        inputSchema: {
            type: 'object',
            properties: {
                listId: { type: 'integer', description: 'Shopping list ID' },
                item_id: { type: 'integer', description: 'Item ID from catalog' },
            },
            required: ['listId', 'item_id'],
        },
        execute: async ({ listId, item_id }) => result(await api('POST', `/lists/${listId}/items`, { item_id })),
    });

    mc.registerTool({
        name: 'createAndAddItem',
        description: 'Create a new item and add it to a shopping list. Categories: mliječno, meso, voće-povrće, pekara, smočnica, pića, smrznuto, čišćenje, ostalo. Units: kom, kg, g, l, ml, pak',
        inputSchema: {
            type: 'object',
            properties: {
                listId: { type: 'integer', description: 'Shopping list ID' },
                name: { type: 'string', description: 'Item name' },
                category: { type: 'string', description: 'Item category' },
                unit: { type: 'string', description: 'Unit of measurement' },
            },
            required: ['listId', 'name', 'category', 'unit'],
        },
        execute: async ({ listId, name, category, unit }) =>
            result(await api('POST', `/lists/${listId}/items/create`, { name, category, unit })),
    });

    mc.registerTool({
        name: 'toggleListItem',
        description: 'Toggle the checked state of an item on a shopping list',
        inputSchema: {
            type: 'object',
            properties: { listItemId: { type: 'integer', description: 'List item ID' } },
            required: ['listItemId'],
        },
        execute: async ({ listItemId }) => result(await api('PATCH', `/list-items/${listItemId}/toggle`)),
    });

    mc.registerTool({
        name: 'removeItemFromList',
        description: 'Remove an item from a shopping list',
        inputSchema: {
            type: 'object',
            properties: { listItemId: { type: 'integer', description: 'List item ID' } },
            required: ['listItemId'],
        },
        execute: async ({ listItemId }) => result(await api('DELETE', `/list-items/${listItemId}`)),
    });

    mc.registerTool({
        name: 'clearCheckedItems',
        description: 'Remove all checked items from a shopping list',
        inputSchema: {
            type: 'object',
            properties: { listId: { type: 'integer', description: 'Shopping list ID' } },
            required: ['listId'],
        },
        execute: async ({ listId }) => result(await api('DELETE', `/lists/${listId}/checked`)),
    });

    mc.registerTool({
        name: 'deleteShoppingList',
        description: 'Delete a shopping list',
        inputSchema: {
            type: 'object',
            properties: { listId: { type: 'integer', description: 'Shopping list ID' } },
            required: ['listId'],
        },
        execute: async ({ listId }) => result(await api('DELETE', `/lists/${listId}`)),
    });
}
