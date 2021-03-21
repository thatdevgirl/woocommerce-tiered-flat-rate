( function( $ ) {

  const tieredShipping = {

    /**
     * Initialize JS for shipping method admin form functionality.
     */
    init: function() {
      this.enabledCheck = $('#woocommerce_tiered_shipping_enabled');

      if (this.enabledCheck.length) {
        this.getFields();
        this.toggleForm();
        this.enabledCheck.click( () => { this.toggleForm(); } );
      }
    },

    /**
     * Get form fields and save them to this object.
     */
    getFields: function() {
      // Get availability field
      this.availabilityField = $('#woocommerce_tiered_shipping_availability').closest('tr');

      // Get country select field.
      this.countriesField = $('#woocommerce_tiered_shipping_countries').closest('tr');
      this.markCountriesFields();

      // Get all form rows but the first one!
      this.tableRows = $('.form-table tr:not(.countries)').slice(1);
    },

    /**
     * Show/hide the form based on whether the shipping method has been enabled.
     */
    toggleForm: function() {
      if (this.enabledCheck.is(':checked')) {
        this.tableRows.show();
        this.showCountryField();
      } else {
        this.tableRows.hide();
        this.hideCountryField();
      }
    },

    /**
     * HELPER FUNCTION: Add classes to the availability (by countries) fields.
     */
    markCountriesFields: function() {
      this.availabilityField.addClass('availability');
      this.countriesField.addClass('availability countries');
    },

    /**
     * HELPER FUNCTIONS: Show/hide the country field based on whether the shipping
     * method has been enabled and whether the field should be visible anyway.
     */
    showCountryField: function() {
      if (this.countriesField.attr('data-visible') == 'visible') {
        this.countriesField.show();
      }
    },

    hideCountryField: function() {
      this.countriesField.removeAttr('data-visible');

      // If the country field was visible, mark that in a data attribute.
      if (this.countriesField.is(':visible')) {
        this.countriesField.attr('data-visible', 'visible');
      }

      this.countriesField.hide();
    }
  }

  $(window).on('load', function() {
    tieredShipping.init();
  });

})( jQuery );
