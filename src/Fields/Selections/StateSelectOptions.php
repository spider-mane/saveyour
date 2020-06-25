<?php

namespace WebTheory\Saveyour\Fields\Selections;

use InvalidArgumentException;
use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

class StateSelectOptions implements SelectionProviderInterface
{
    /**
     *
     */
    protected $groups = [];

    /**
     *
     */
    protected $reSort;

    /**
     *
     */
    protected const ALLOWED_GROUPS = ['States', 'Territories', 'ArmedForces'];

    /**
     *
     */
    public const STATES = [
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
    ];

    /**
     *
     */
    public const TERRITORIES = [
        "AS" => "American Samoa",
        "GU" => "Guam",
        "MP" => "Northern Mariana Islands",
        "PR" => "Puerto Rico",
        "UM" => "United States Minor Outlying Islands",
        "VI" => "Virgin Islands",
    ];

    /**
     *
     */
    public const ARMED_FORCES = [
        "AA" => "Armed Forces Americas",
        "AP" => "Armed Forces Pacific",
        "AE" => "Armed Forces Other",
    ];

    /**
     *
     */
    protected const MAP = [
        'States' => self::STATES,
        'Territories' => self::TERRITORIES,
        'ArmedForces' => self::ARMED_FORCES
    ];

    /**
     *
     */
    public function __construct(array $groups = ['States'], $reSort = false)
    {
        $this->setGroups($groups);
        $this->reSort = $reSort;
    }

    /**
     *
     */
    protected function setGroups(array $groups)
    {
        foreach ($groups as $group) {
            if (!in_array($group, static::ALLOWED_GROUPS)) {
                $valid = implode(', ', static::ALLOWED_GROUPS);
                $alert = "{$group} is not an allowed group. Allowed groups include {$valid}.";

                throw new InvalidArgumentException($alert);
            } elseif (!in_array($group, $this->groups)) {
                $this->groups[] = $group;
            }
        }
    }

    /**
     *
     */
    public function getSelection(): array
    {
        $groups = array_merge(...$this->getOptions());

        if ($this->reSort) {
            sort($groups);
        }

        return $groups;
    }

    /**
     *
     */
    protected function getOptions()
    {
        return array_map(function ($group) {
            return static::MAP[$group];
        }, $this->groups);
    }
}
