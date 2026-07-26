import '@aejkatappaja/phantom-ui';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './search-float';
import './feed-sentinel';

// Register Alpine.js Intersect plugin on Livewire's Alpine instance
import intersect from '@alpinejs/intersect';
if (window.Alpine) {
    window.Alpine.plugin(intersect);
}

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (pusherKey) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
    });
}
