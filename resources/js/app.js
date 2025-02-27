import 'htmx.org';
import Alpine from 'alpinejs';
import htmx from 'htmx.org';

window.Alpine = Alpine;

Alpine.start();

htmx.config.responseHandling = [
    {
        code: "...",
        swap: true,
    }
]