<?php

namespace WebTheory\Saveyour\Selections;

use InvalidArgumentException;
use WebTheory\Saveyour\Contracts\OptionsProviderInterface;

class StateSelectOptions implements OptionsProviderInterface
{
    /**
     *
     */
    protected $groups = [];

    /**
     *
     */
    protected $text = 'name';

    /**
     * @var bool
     */
    protected $reSort = false;

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
    protected const ALLOWED_GROUPS = ['States', 'Territories', 'ArmedForces'];

    /**
     *
     */
    protected const ALLOWED_TEXT = ['abbr', 'name'];

    /**
     *
     */
    public function __construct(array $groups = ['States'], string $text = 'name', $reSort = false)
    {
        $this->setGroups($groups);
        $this->setText($text);

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

        return $this;
    }

    /**
     *
     */
    protected function setText(string $text)
    {
        if (!in_array($text, static::ALLOWED_TEXT)) {
            $valid = implode(', ', static::ALLOWED_TEXT);
            $alert = "valid text includes {$valid} only";

            throw new InvalidArgumentException($alert);
        }

        $this->text = $text;

        return $this;
    }

    /**
     *
     */
    protected function getOptions()
    {
        $options = [];

        foreach ($this->groups as $group) {
            foreach (static::MAP[$group] as $abbr => $name) {
                $options[] = [
                    'name' => $name,
                    'abbr' => $abbr
                ];
            }
        }

        if ($this->reSort) {
            sort($options);
        }

        return $options;
    }

    /**
     *
     */
    public function provideSelectionsData(): array
    {
        return $this->getOptions();
    }

    /**
     *
     */
    public function defineSelectionText($item): string
    {
        return $item[$this->text];
    }

    /**
     *
     */
    public function defineSelectionValue($item): string
    {
        return $item['abbr'];
    }
}
