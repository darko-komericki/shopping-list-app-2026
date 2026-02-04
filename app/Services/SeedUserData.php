<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use App\Models\Template;
use App\Models\User;

class SeedUserData
{
    private const DEFAULT_ITEMS = [
        // Mliječni proizvodi
        ['name' => 'Mlijeko', 'category' => 'mliječno', 'default_unit' => 'L'],
        ['name' => 'Maslac', 'category' => 'mliječno', 'default_unit' => 'kom'],
        ['name' => 'Sir', 'category' => 'mliječno', 'default_unit' => 'g'],
        ['name' => 'Jogurt', 'category' => 'mliječno', 'default_unit' => 'kom'],
        ['name' => 'Jaja', 'category' => 'mliječno', 'default_unit' => 'kom'],
        ['name' => 'Vrhnje', 'category' => 'mliječno', 'default_unit' => 'ml'],
        ['name' => 'Kiselo vrhnje', 'category' => 'mliječno', 'default_unit' => 'kom'],
        ['name' => 'Svježi sir', 'category' => 'mliječno', 'default_unit' => 'g'],
        ['name' => 'Parmezan', 'category' => 'mliječno', 'default_unit' => 'g'],

        // Meso
        ['name' => 'Pileća prsa', 'category' => 'meso', 'default_unit' => 'kg'],
        ['name' => 'Mljeveno meso', 'category' => 'meso', 'default_unit' => 'kg'],
        ['name' => 'Svinjski kotlet', 'category' => 'meso', 'default_unit' => 'kg'],
        ['name' => 'Slanina', 'category' => 'meso', 'default_unit' => 'g'],
        ['name' => 'Kobasice', 'category' => 'meso', 'default_unit' => 'kom'],
        ['name' => 'Šunka', 'category' => 'meso', 'default_unit' => 'g'],
        ['name' => 'Kulen', 'category' => 'meso', 'default_unit' => 'g'],
        ['name' => 'Ćevapi', 'category' => 'meso', 'default_unit' => 'kg'],
        ['name' => 'Pileći bataci', 'category' => 'meso', 'default_unit' => 'kg'],

        // Voće i povrće
        ['name' => 'Jabuke', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Banane', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Rajčice', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Krumpir', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Luk', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Mrkva', 'category' => 'voće-povrće', 'default_unit' => 'kg'],
        ['name' => 'Salata', 'category' => 'voće-povrće', 'default_unit' => 'kom'],
        ['name' => 'Krastavci', 'category' => 'voće-povrće', 'default_unit' => 'kom'],
        ['name' => 'Paprika', 'category' => 'voće-povrće', 'default_unit' => 'kom'],
        ['name' => 'Češnjak', 'category' => 'voće-povrće', 'default_unit' => 'kom'],
        ['name' => 'Limun', 'category' => 'voće-povrće', 'default_unit' => 'kom'],
        ['name' => 'Naranče', 'category' => 'voće-povrće', 'default_unit' => 'kg'],

        // Pekarski proizvodi
        ['name' => 'Kruh', 'category' => 'pekara', 'default_unit' => 'kom'],
        ['name' => 'Pecivo', 'category' => 'pekara', 'default_unit' => 'kom'],
        ['name' => 'Burek', 'category' => 'pekara', 'default_unit' => 'kom'],
        ['name' => 'Kifle', 'category' => 'pekara', 'default_unit' => 'kom'],
        ['name' => 'Tost kruh', 'category' => 'pekara', 'default_unit' => 'kom'],

        // Smočnica
        ['name' => 'Riža', 'category' => 'smočnica', 'default_unit' => 'kg'],
        ['name' => 'Tjestenina', 'category' => 'smočnica', 'default_unit' => 'pak'],
        ['name' => 'Brašno', 'category' => 'smočnica', 'default_unit' => 'kg'],
        ['name' => 'Šećer', 'category' => 'smočnica', 'default_unit' => 'kg'],
        ['name' => 'Sol', 'category' => 'smočnica', 'default_unit' => 'kom'],
        ['name' => 'Maslinovo ulje', 'category' => 'smočnica', 'default_unit' => 'L'],
        ['name' => 'Ulje', 'category' => 'smočnica', 'default_unit' => 'L'],
        ['name' => 'Konzervirane rajčice', 'category' => 'smočnica', 'default_unit' => 'kom'],
        ['name' => 'Pahuljice', 'category' => 'smočnica', 'default_unit' => 'kom'],
        ['name' => 'Med', 'category' => 'smočnica', 'default_unit' => 'kom'],
        ['name' => 'Ocat', 'category' => 'smočnica', 'default_unit' => 'kom'],

        // Pića
        ['name' => 'Sok od naranče', 'category' => 'pića', 'default_unit' => 'L'],
        ['name' => 'Kava', 'category' => 'pića', 'default_unit' => 'pak'],
        ['name' => 'Čaj', 'category' => 'pića', 'default_unit' => 'pak'],
        ['name' => 'Voda', 'category' => 'pića', 'default_unit' => 'L'],
        ['name' => 'Gazirana voda', 'category' => 'pića', 'default_unit' => 'L'],
        ['name' => 'Pivo', 'category' => 'pića', 'default_unit' => 'kom'],
        ['name' => 'Vino', 'category' => 'pića', 'default_unit' => 'kom'],

        // Smrznuto
        ['name' => 'Sladoled', 'category' => 'smrznuto', 'default_unit' => 'kom'],
        ['name' => 'Smrznuta pizza', 'category' => 'smrznuto', 'default_unit' => 'kom'],
        ['name' => 'Smrznuto povrće', 'category' => 'smrznuto', 'default_unit' => 'pak'],
        ['name' => 'Smrznuta riba', 'category' => 'smrznuto', 'default_unit' => 'pak'],

        // Čišćenje
        ['name' => 'Deterdžent za suđe', 'category' => 'čišćenje', 'default_unit' => 'kom'],
        ['name' => 'Deterdžent za rublje', 'category' => 'čišćenje', 'default_unit' => 'kom'],
        ['name' => 'Papirnati ručnici', 'category' => 'čišćenje', 'default_unit' => 'pak'],
        ['name' => 'Toaletni papir', 'category' => 'čišćenje', 'default_unit' => 'pak'],
        ['name' => 'Sredstvo za čišćenje', 'category' => 'čišćenje', 'default_unit' => 'kom'],
        ['name' => 'Spužve', 'category' => 'čišćenje', 'default_unit' => 'pak'],
        ['name' => 'Vreće za smeće', 'category' => 'čišćenje', 'default_unit' => 'pak'],

        // Ostalo
        ['name' => 'Pasta za zube', 'category' => 'ostalo', 'default_unit' => 'kom'],
        ['name' => 'Šampon', 'category' => 'ostalo', 'default_unit' => 'kom'],
        ['name' => 'Sapun', 'category' => 'ostalo', 'default_unit' => 'kom'],
        ['name' => 'Baterije', 'category' => 'ostalo', 'default_unit' => 'pak'],
        ['name' => 'Pelene', 'category' => 'ostalo', 'default_unit' => 'pak'],
    ];

    // Item indices: mliječno 0-8, meso 9-17, voće-povrće 18-29, pekara 30-34,
    // smočnica 35-45, pića 46-52, smrznuto 53-56, čišćenje 57-63, ostalo 64-68
    private const DEFAULT_TEMPLATES = [
        [
            'name' => 'Tjedno mliječno',
            'description' => 'Osnovni mliječni proizvodi za tjedan',
            'color' => 'blue',
            'item_indices' => [0, 1, 2, 3, 4],
        ],
        [
            'name' => 'Mesnica',
            'description' => 'Meso za pripremu obroka',
            'color' => 'red',
            'item_indices' => [9, 10, 11, 12, 13],
        ],
        [
            'name' => 'Dan čišćenja',
            'description' => 'Sredstva za čišćenje kućanstva',
            'color' => 'purple',
            'item_indices' => [57, 58, 59, 60, 61, 62],
        ],
        [
            'name' => 'Osnovne namirnice',
            'description' => 'Svakodnevne potrepštine',
            'color' => 'emerald',
            'item_indices' => [0, 30, 4, 1, 19],
        ],
    ];

    private const DEFAULT_CATEGORIES = [
        ['name' => 'mliječno', 'color' => 'blue', 'sort_order' => 0],
        ['name' => 'meso', 'color' => 'red', 'sort_order' => 1],
        ['name' => 'voće-povrće', 'color' => 'green', 'sort_order' => 2],
        ['name' => 'pekara', 'color' => 'amber', 'sort_order' => 3],
        ['name' => 'smočnica', 'color' => 'orange', 'sort_order' => 4],
        ['name' => 'pića', 'color' => 'cyan', 'sort_order' => 5],
        ['name' => 'smrznuto', 'color' => 'indigo', 'sort_order' => 6],
        ['name' => 'čišćenje', 'color' => 'purple', 'sort_order' => 7],
        ['name' => 'ostalo', 'color' => 'gray', 'sort_order' => 8],
    ];

    public function __invoke(User $user): void
    {
        // Categories
        foreach (self::DEFAULT_CATEGORIES as $category) {
            $user->categories()->create($category);
        }

        // Items
        $itemIds = [];
        foreach (self::DEFAULT_ITEMS as $item) {
            $itemIds[] = $user->items()->create($item)->id;
        }

        // Templates with items
        foreach (self::DEFAULT_TEMPLATES as $template) {
            $indices = $template['item_indices'];
            unset($template['item_indices']);

            $created = $user->templates()->create([
                ...$template,
                'is_default' => true,
            ]);

            $created->items()->attach(
                array_map(fn ($idx) => $itemIds[$idx], $indices)
            );
        }
    }
}
