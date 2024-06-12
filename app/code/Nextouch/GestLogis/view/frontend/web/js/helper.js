define(["jquery", "Magento_Ui/js/modal/modal", "mage/translate"], function (
  $,
  modal,
  $t
) {
  var PostcodeModal = {
    initGestLogicHelper: function (config) {
      var $target = $(config.target);
      $target.modal({
        autoOpen: false,
        responsive: true,
        innerScroll: true,
        modalClass: "tooltip-modal-class",
        title: $t("Standard Delivery"),
        buttons: [
          {
            text: $t("Select This Delivery"),
            class: "",
            click: function () {
              this.closeModal();
            },
          },
        ],
      });

      $(".delivery-tooltip-container").on("click", function () {
        $target.modal("openModal");
      });

      // Services Tooltip Modal
      var $serviceTarget = $(config.serviceTarget);
      $serviceTarget.modal({
        autoOpen: false,
        responsive: true,
        innerScroll: true,
        modalClass: "service-tooltip-modal-class",
        title: $t("Services"),
        buttons: [],
      });

      // Handle click on each service item
      $(".service-tooltip-container").on("click", function () {
        // Find the corresponding service-tooltip-content
        var serviceIndex = $(".service-tooltip-container").index(this);
        var $serviceContent = $serviceTarget.eq(serviceIndex);

        // Get the title dynamically (change 'data-title' to the actual attribute you use)
        var dynamicTitle = $(this).attr("data-title");

        // Set the title dynamically
        $serviceContent
          .parents(".service-tooltip-modal-class")
          .find(".modal-title")
          .text(dynamicTitle);

        // Open the modal for the clicked service item
        $serviceContent.modal("openModal");
      });
    },
  };

  // Toggle options
  if($(".shipping-dynamic-price").hasClass('price-available')) {
    $(document).on("click", ".shipping-dynamic-price", function () {
      var options = $(this).parents(".shipping-container").next();
      if ($(options).hasClass("active")) {
          $(options).hide();
          $(options).removeClass("active");
          $(this).parents(".shipping-container").removeClass("active");
      } else {
          $(options).show();
          $(options).addClass("active");
          $(this).parents(".shipping-container").addClass("active");
      }
    });
  }

  return PostcodeModal;
});
