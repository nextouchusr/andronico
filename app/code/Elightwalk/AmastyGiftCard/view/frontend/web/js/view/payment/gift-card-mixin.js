define(["jquery", "underscore"], function($, _) {
  "use strict";

  return function(target) {
    return target.extend({
      applyDone: function(response) {
        this._super(response);

        if ($(".opc-block-summary .heading.opc-summary-heading").length) {
            $(".opc-block-summary .heading.opc-summary-heading").trigger("click");
        }
      },
    });
  };
});
