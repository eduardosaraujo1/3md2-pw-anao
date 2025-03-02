import 'htmx.org';
import Alpine from 'alpinejs';
import htmx from 'htmx.org';

window.Alpine = Alpine;

Alpine.start();

htmx.config.responseHandling = [
    {
        code: '...',
        swap: true,
    },
];

document.body.addEventListener('soft-refresh', () => {
    htmx.ajax('GET', window.location.href, {
        target: 'body',
        swap: 'outerHTML',
    });
});

document.querySelector('body').style.removeProperty('visibility');
