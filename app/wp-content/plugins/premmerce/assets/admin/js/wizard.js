jQuery(function ($) {

  /**
   * Change container state handler
   */
  $(document).on('change', '[data-set-container-state]', function () {
    var $this = $(this);
    var container = $('[data-container]');

    // Show preloader
    container.addClass('task-container--ajax-block');

    updateWizard({'update': 'container-state', 'state': $(this).val()}, function (responce) {
      $('[data-container]').replaceWith($('[data-container]', responce));
      $('[data-container-state]').attr('data-container-state', $this.val());
      initSortTable();
    });
  });

  /**
   * Show all task click handler
   */
  $(document).on('click', '[data-show-all-task]', function () {
    $('[data-set-container-state][value="all"]').trigger('click');
  });

  /**
   * Set task state handler
   * Runnig on click on skip/un skip and ckeck/ un check button
   */
  $(document).on('change', '[data-set-state]', function () {
    var $this = $(this);

    var task = $this.closest('[data-task]');
    var state = $this.is(':checked') ? $this.val() : 'todo';

    if (state === "skipped") {
      task.find('[data-check-task-checkbox]').attr('disabled', 'disabled');
    } else {
      task.find('[data-check-task-checkbox]').removeAttr('disabled');
    }

    setState(state, task);
  });

  /**
   * Install and activation handler
   */
  $(document).on('click', '[data-action]', function (e) {
    e.preventDefault();

    var $this = $(this);
    var data = $this.data('action');
    var task = $this.closest('[data-task]');
    var waitAjaxClass = 'updating-message';
    var spinner = task.find(".premmerce-wp-spinner");

    $this.addClass(waitAjaxClass);

    premmerce.actions.run(data, {
      success: function () {
        window.location.reload();
      },
      error: function (response) {
        $('[data-error-container]').append('<div class="notice notice-error notice-alt is-dismissible"><p>' + response.errorMessage + '</p></div>');
        spinner.hide();

      }
    });
  });

  /**
   * Set task state
   * @param state
   * @param task
   */
  function setState(state, task) {
    var container = $('[data-container]');
    var change = state === 'todo' ? +1 : -1;

    changeCount(change);

    task.removeClass('task-container__task--state-' + task.attr('data-task-state')).addClass('task-container__task--state-' + state);

    var containerState = container.attr('data-container-state');

    if (containerState !== 'all' && containerState !== state) {
      task.fadeOut(500, function () {
        task.attr('data-task-state', state);

        if (state === 'todo') {
          handleLast();
        }

      });
    } else {
      task.attr('data-task-state', state);
    }

    var key = task.data('task');

    updateWizard({task: key, state: state, update: 'state'}, function () {

    });
  }

  /**
   * Show "Show all block" when user unskip or uncheck last task
   */
  function handleLast() {
    var listState = $('[data-set-container-state]').filter(':checked').val();
    var totalTask = $('[data-container]').find('[data-task-state="' + listState + '"]').filter(':visible');

    if (totalTask.length === 0) {
      $('[data-show-all-task--container]').removeClass('hidden');
    }

  }

  /**
   *
   * @param data
   * @param callback
   */
  function updateWizard(data, callback) {
    data.action = 'premmerce_wizard_actions';

    $.post(ajaxurl, data, function (responce) {
      if (callback instanceof Function)
        callback(responce);
    }, 'json')
  }

  /**
   *
   * @param val
   */
  function changeCount(val) {

    $('[data-premmerce-count]').each(function () {

      var counter = $(this);
      var count = parseInt(counter.text());

      var newCount = count + val;

      if (newCount < 0) {
        newCount = 0;
      }

      $('.premmerce-wizard-counter.count-' + count).each(function () {
        $(this).removeClass('count-' + count);
        $(this).addClass('count-' + newCount);
      });

      counter.text(newCount)

    });
  }

  /**
   * Init sorting
   */
  function initSortTable() {
    var container = $('[data-container]');

    container.sortable();

    container.on('sortupdate', function () {
      var data = {positions: [], update: 'position'};

      $('[data-task]').each(function () {
        data.positions.push($(this).attr('data-task'));
      });
      updateWizard(data);
    });
  }

  initSortTable();
});