<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feed Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Controls number of items per page for feed, friends, and group listings.
    |
    */
    'per_page' => env('FEED_PER_PAGE', 10),
    'per_page_options' => [10, 20, 50],
    'friends_per_page' => env('FRIENDS_PER_PAGE', 20),
    'groups_per_page' => env('GROUPS_PER_PAGE', 12),
    'discover_groups_per_page' => env('DISCOVER_GROUPS_PER_PAGE', 12),
];
