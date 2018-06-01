<?php

/**
 * File with all crafting recipes
 * More recipes on https://www.albiononline2d.com/en/item
 * Crafting recipe for Food craft 10 items, for potions craft 5 items
 * Crafting recipe with component can't have resources
 */

// Ressources : ['PLANKS', 'METALBAR', 'LEATHER', 'CLOTH']
$recipes = [
	'Accessories' => [
		'Bag' => [
			'BAG' => [
				'name' => 'Bag',
				'resources' => [0,0,8,8]
			]
		],
		'Cape' => [
			'CAPE' => [
				'name' => 'Cape',
				'resources' => [0,0,4,4]
			]
		]
	],
	'Ranged' => [
		'Bow' => [
			'2H_BOW' => [
				'name' => 'Bow',
				'resources' => [32,0,0,0],
			],
			'2H_LONGBOW' => [
				'name' => 'Longbow',
				'resources' => [32,0,0,0]
			],
			'2H_WARBOW' => [
				'name' => 'Warbow',
				'resources' => [32,0,0,0]
			],
			// '2H_BOW_HELL' => [
			// 	'name' => 'Wailing Bow',
			// 	'resources' => [32,0,0,0],
			// 	'artifacts' => 'ARTEFACT_2H_BOW_HELL',
			// ],
			// '2H_BOW_KEEPER' => [
			// 	'name' => 'Bow of Badon',
			// 	'resources' => [32,0,0,0],
			// 	'artifacts' => 'ARTEFACT_2H_BOW_KEEPER',
			// ],
			// '2H_LONGBOW_UNDEAD' => [
			// 	'name' => 'Whispering Bow',
			// 	'resources' => [32,0,0,0],
			// 	'artifacts' => 'ARTEFACT_2H_LONGBOW_UNDEAD',
			// ]
		]
	],
	'Consumable' => [
		'Cooked' => [
			'T3_MEAL_PIE' => [
				'name' => 'Chicken Pie',
				'item_value' => 34.67,
				'component' => [
					'T3_MEAT' => 0.8,
					'T3_FLOUR' => 0.4,
					'T3_WHEAT' => 0.2
				]
			],
			'T5_MEAL_PIE' => [
				'name' => 'Goose Pie',
				'item_value' => 112.02,
				'component' => [
					'T5_MEAT' => 2.4,
					'T3_FLOUR' => 1.2,
					'T4_MILK' => 0.6,
					'T6_CABBAGE' => 0.6
				]
			],
			'T7_MEAL_PIE' => [
				'name' => 'Pork Pie',
				'item_value' => 336.06,
				'component' => [
					'T7_MEAT' => 7.2,
					'T3_FLOUR' => 3.6,
					'T6_MILK' => 1.8,
					'T7_CORN' => 1.8,
				]
			]
		],
		'Potion' => [
			'T4_POTION_COOLDOWN' => [
				'name' => 'Minor Poison Potion',
				'item_value' => 96,
				'component' => [
					'T4_BURDOCK' => 1.6,
					'T3_COMFREY' => 0.8
				]
			],
			'T6_POTION_COOLDOWN' => [
				'name' => 'Poison Potion',
				'item_value' => 400.01,
				'component' => [
					'T6_FOXGLOVE' => 4.8,
					'T5_TEASEL' => 2.4,
					'T3_COMFREY' => 2.4,
					'T6_MILK' => 1.2
				]
			],
			'T8_POTION_COOLDOWN' => [
				'name' => 'Major Poison Potion',
				'item_value' => 1152.02,
				'component' => [
					'T8_YARROW' => 14.4,
					'T7_MULLEIN' => 4.8,
					'T5_TEASEL' => 4.8,
					'T8_MILK' => 3.6,
					'T8_ALCOHOL' => 3.6,
				]
			]
		]
	]
];