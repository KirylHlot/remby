jQuery(function ($) {

    $(document).on('click', '[data-action]', function (e) {

        var $this = $(this);
        var data = $this.data('action');

        var successState = $this.attr('data-success-state');
        var row = $this.closest('[data-plugin-row]');
        var spinner = $this.find(".premmerce-wp-spinner");

        spinner.show();

        premmerce.actions.run(data, {
            success: function () {
                spinner.hide();
                row.attr('data-plugin-row', successState);
                window.location.reload();
            },
            error: function (response) {
                spinner.hide();
                $('[data-error-container]').append('<div class="notice notice-error notice-alt is-dismissible"><p>' + response.errorMessage + '</p></div>');
            }
        });
    });
});
