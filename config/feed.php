<?php

return [
    'per_page'        => (int) env('FEED_PER_PAGE', 10),
    'friends_per_page'=> (int) env('FRIENDS_PER_PAGE', 20),
    'groups_per_page' => (int) env('GROUPS_PER_PAGE', 15),
    'search_per_page' => (int) env('SEARCH_PER_PAGE', 20),
    'max_media_mb'    => (int) env('MAX_MEDIA_MB', 100),
    'max_media_files' => (int) env('MAX_MEDIA_FILES', 10),
    'allowed_image_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'allowed_video_mimes' => ['mp4', 'mov', 'avi', 'webm', 'mkv', '3gp'],
    'rate_post_per_minute'    => (int) env('RATE_POST_PER_MINUTE', 10),
    'rate_comment_per_minute' => (int) env('RATE_COMMENT_PER_MINUTE', 30),
    'rate_like_per_minute'    => (int) env('RATE_LIKE_PER_MINUTE', 60),
    'rate_friend_per_minute'  => (int) env('RATE_FRIEND_PER_MINUTE', 5),
    'rate_upload_per_minute'  => (int) env('RATE_UPLOAD_PER_MINUTE', 5),
];
