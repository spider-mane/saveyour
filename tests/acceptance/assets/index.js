$(document).ready(function () {
  let select2 = $(".saveyour--select2");

  Array.from(select2).forEach((select) => {
    let config = JSON.parse(select.dataset.saveyour__select2);

    console.log(config);

    $(select).select2({
      dir: config.dir,
      disabled: config.disabled,
      multiple: config.multiple,
      placeholder: config.placeholder,
      width: config.width ? config.width : "style",
    });
  });
});
