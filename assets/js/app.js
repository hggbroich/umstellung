require('../css/app.scss');

import Stepper from 'bs-stepper'
let bsn = require('bootstrap.native');
require('../../vendor/schulit/common-bundle/Resources/assets/js/polyfill');
require('../../vendor/schulit/common-bundle/Resources/assets/js/menu');

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[title]').forEach(function(el) {
        new bsn.Tooltip(el, {
            placement: 'bottom'
        });
    });

    new Stepper(document.querySelector('.bs-stepper'), {
        linear: false,
        animation: true,
        selectors: {
            steps: '.step',
            trigger: '.step-trigger',
            stepper: '.bs-stepper'
        }
    });
});