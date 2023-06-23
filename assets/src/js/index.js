document.addEventListener('DOMContentLoaded', () => {
  executeCodeIfAssetsPresent();
});

/**
 *
 */
function executeCodeIfAssetsPresent() {
  // jQuery
  if (window.jQuery) {
    maybeInitJQueryPlugins();
  }
}

/**
 *
 */
function maybeInitJQueryPlugins() {
  // select2
  if (jQuery.fn.select2) {
    maybeInitSelect2();
  }
}

/**
 *
 */
function maybeInitSelect2() {
  let fields = document.querySelectorAll('.saveyour--select2');

  if (fields) {
    initSelect2(fields);
  }
}

/**
 *
 */
function initSelect2(fields) {
  Array.from(fields).forEach(select => {
    let config = JSON.parse(select.dataset.saveyour__select2);

    jQuery(select).select2(config);
  });
}
