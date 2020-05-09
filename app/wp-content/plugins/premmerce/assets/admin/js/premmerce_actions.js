jQuery(function ($) {
    premmerce = {};

    premmerce.actions = {
      run : function (data, callbacks) {

        if (!callbacks instanceof Object) {
          callbacks = {};
        }
        if (!callbacks.hasOwnProperty('success')) {
          callbacks.success = this.successCallback;
        }
        if (!callbacks.hasOwnProperty('error')) {
          callbacks.success = this.errorCallback;
        }
        if (data.handler === 'wp.updates') {
          this.runWpAction(data, callbacks);
        } else {
          this.runAction(data, callbacks);
        }
      },
      runAction : function (data, callbacks) {
        $.post(ajaxurl, data, function (response) {
          if (response.success) {
            callbacks.success(response)
          } else {
            callbacks.error(response)
          }
        }, 'json')
      },
      runWpAction : function (data, callbacks) {
        wp.updates.ajax(data['action'], {
          slug: data['slug'],
          success: callbacks.success,
          error: callbacks.error
        });
      },
      successCallback : function (response) {
        window.location.reload();
      },
      errorCallback : function (response) {
        $('[data-error-container]').append('<div class="notice notice-error notice-alt is-dismissible"><p>' + response.errorMessage + '</p></div>');
        spinner.hide();

      }
    };
});

