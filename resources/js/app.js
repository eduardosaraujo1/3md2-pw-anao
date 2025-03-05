import htmx from 'htmx.org';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

htmx.config.responseHandling = [
    {
        code: '...',
        swap: true,
    },
];
