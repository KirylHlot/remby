;
(function ($) {

    /**
     * Add to compare button and change total items in compare
     * scope - "add_to"
     */
    $(document).on('click', '[data-pc-add-to-compare-btn="add"]', function (e) {

        e.preventDefault();
        var button = $(this);
        var scope = button.closest('[data-pc-add-to-compare]');
        var params = JSON.parse(scope.find('[data-pc-add-to-compare-data]').attr('data-pc-add-to-compare-data'));

        $.ajax({
            url: params.url,
            type: 'post',
            data: params,
            beforeSend: function () {
                button.html(button.data('loader'));
            },
            success: function () {
                scope.find('[data-pc-add-to-compare-btn]').toggleClass('hidden');
                $(document.body).trigger('wc_fragment_refresh');
            }
        });

    });

})(jQuery);