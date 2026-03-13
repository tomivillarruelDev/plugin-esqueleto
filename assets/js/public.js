/**
 * JavaScript del frontend (sitio público).
 *
 * Se carga en todas las páginas. Si sólo lo necesitás en ciertas páginas,
 * condicioná el wp_enqueue_script() con is_page(), is_singular(), etc.
 */

(function ($) {
  "use strict";

  $(document).ready(function () {
    console.log("Mi Plugin Public listo.");
  });
})(jQuery);
