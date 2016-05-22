import 'foundation-sites/js/foundation.core';
import 'foundation-sites/js/foundation.util.mediaQuery';
import 'foundation-sites/js/foundation.responsiveToggle.js';

!function ($) {
    "use strict";

    // Initialize
    var responsive_toggle = new Foundation.ResponsiveToggle($('[data-responsive-toggle]'));

    // Register this module
    AppRobertVrabel.register('responsive_toggle', responsive_toggle);

}(jQuery);