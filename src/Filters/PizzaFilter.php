<?php

namespace WebId\Flan\Filters;

use Illuminate\Support\Facades\DB;
use WebId\Flan\Filters\Base\Filter;
use WebId\Flan\Models\Pizza;

class PizzaFilter extends Filter
{
    /**
     * @param Pizza $model
     */
    public function __construct(Pizza $model)
    {
        parent::__construct($model);

        $this->setDefinition('ingredients', [
            'mutation' => [
                'type' => 'concat',
            ],
            'table' => 'ingredients',
            'column_name' => 'name',
            'join' => 'joinIngredients',
        ]);

        $this->setDefinition('active', [
            'mutation' => [
                'type' => 'array',
            ],
            'data' => Pizza::ACTIVE_LIST,
        ]);

        $this->setDefinition('created_at_with_time', [
            'column_name' => 'created_at',
            'table' => 'pizzas',
        ]);

        $this->setDefinition('count_ingredients', [
            'sub_select' => function () {
                return DB::table('ingredient_pizza')
                    ->selectRaw('COUNT(*)')
                    ->where('pizza_id', 'pizzas.id');
            },
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array
    {
        return [
            'name' => 'pizzas',
            'filters' => [
                [
                    'text' => 'ID',
                    'name' => 'id',
                    'active' => true,
                    'field' => [
                        'type' => 'number',
                    ],
                ],
                [
                    'text' => 'Name',
                    'name' => 'name',
                    'active' => true,
                    'field' => [
                        'type' => 'text',
                    ],
                ],
                [
                    'text' => 'Price',
                    'name' => 'price',
                    'active' => true,
                    'field' => [
                        'type' => 'number',
                    ],
                ],
                [
                    'text' => 'Ingredients',
                    'name' => 'ingredients',
                    'active' => true,
                    'field' => [
                        'type' => 'text',
                    ],
                ],
                [
                    'text' => 'Active',
                    'name' => 'active',
                    'active' => true,
                    'field' => [
                        'type' => 'select',
                        'options' => [
                            [
                                'value' => '1',
                                'text' => 'Activate',
                            ],
                            [
                                'value' => '0',
                                'text' => 'Unactivate',
                            ],
                        ]
                    ],
                ],
                [
                    'text' => 'Created At',
                    'name' => 'created_at',
                    'active' => true,
                    'field' => [
                        'type' => 'date',
                    ],
                ],
                [
                    'text' => 'Created At (with time)',
                    'name' => 'created_at_with_time',
                    'active' => true,
                    'field' => [
                        'type' => 'datetime',
                    ],
                ],
                [
                    'text' => 'number of ingredients',
                    'name' => 'count_ingredients',
                    'active' => true,
                    'field' => [
                        'type' => 'number',
                    ],
                ],
                [
                    'text' => 'Indice GSS',
                    'name' => 'gss',
                    'active' => true,
                    'field' => [
                        'type' => 'checkbox'
                    ]
                ]
            ],
        ];
    }

    protected function joinIngredients(): void
    {
        $this->query->leftJoin(
            'ingredient_pizza',
            'pizzas.id',
            '=',
            'ingredient_pizza.pizza_id'
        );

        $this->query->leftJoin(
            'ingredients',
            'ingredient_pizza.ingredient_id',
            '=',
            'ingredients.id'
        );
    }
}
