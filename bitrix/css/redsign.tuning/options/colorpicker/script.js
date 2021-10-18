function rsTuningSpectrumReflow(tab) {
    var $spectrumInputs = $(tab).find('.colorpickerHolder');

    $spectrumInputs.each(function(){
        $(this).spectrum('reflow');
    });

    BX.removeCustomEvent('rs.tuning.tabs.after.change', rsTuningSpectrumReflow);
}

function rsTuningSpectrumSetInputValues(name) {
  var $spectrum = $('#' + name),
      $spectrumOption = $spectrum.closest('.js-rs_option_info'),
      hexColor = $spectrumOption.find('.active').find('input').val() + '',
      rsColor = new RS.Color(hexColor);

  $spectrum.spectrum('set', rsColor._hex);

  $spectrumOption.find('.field.r').find('input').val(rsColor.getRgb().R).change();
  $spectrumOption.find('.field.g').find('input').val(rsColor.getRgb().G).change();
  $spectrumOption.find('.field.b').find('input').val(rsColor.getRgb().B).change();
  $spectrumOption.find('.field.hex').find('input').val(rsColor.getHex()).change();

  $spectrumOption.find('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').val(hexColor);
  document.querySelector('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').value = hexColor;
  if (typeof(Event) === 'function')
  {
    var event = new Event('change');
  } else {
    var event = document.createEvent('Event'); 
    event.initEvent('change', true, true);
  }
  document.querySelector('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').dispatchEvent(event);
  $spectrumOption.find('.js-rstuning__option__colorpicker__alone-color.active .js-colorpicker-paint').css('backgroundColor', '#' + hexColor);
}

function rsTuningSpectrumInit(name) {
    var $spectrum = $('#' + name),
        $spectrumOption = $spectrum.closest('.js-rs_option_info'),
        hexColor = $spectrum.data('dcolor') + '',
        rsColor = new RS.Color(hexColor);

    // init colorpicker
    $spectrum.spectrum({
      containerClassName: 'rstuning__spectrum',
      flat: true,
      color: rsColor._hex,
      showButtons: false,
      move: function (color) {
        $spectrumOption.find('.field.r').find('input').val(color.toRgb().r).change();
        $spectrumOption.find('.field.g').find('input').val(color.toRgb().g).change();
        $spectrumOption.find('.field.b').find('input').val(color.toRgb().b).change();
        $spectrumOption.find('.field.hex').find('input').val(color.toHex()).change()
        $spectrumOption.find('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').val(color.toHex());
        document.querySelector('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').value = color.toHex();
        if (typeof(Event) === 'function')
        {
          var event = new Event('change');
        } else {
          var event = document.createEvent('Event'); 
          event.initEvent('change', true, true);
        }
        document.querySelector('.js-rstuning__option__colorpicker__alone-color.active .js-rstuning__option__colorpicker__input').dispatchEvent(event);
        $spectrumOption.find('.js-rstuning__option__colorpicker__alone-color.active .js-colorpicker-paint').css('backgroundColor', color.toHexString());
      }
    });

    if (!$spectrumOption.is(':visible')) {
        BX.addCustomEvent('rs.tuning.tabs.after.change', rsTuningSpectrumReflow);
    }
  
    rsTuningSpectrumSetInputValues(name);
  }

$(document).ready(function(){

  $(document).on('click', '.js-colorpicker-set', function(e) {
    var $this = $(this),
        value = $this.data('value'),
        $option = $this.closest('.js-rs_option_info');

    for (valkey in value) {
      if ($('[data-valkey="' + valkey + '"]').length > 0) {
        document.querySelector('[data-valkey="' + valkey + '"]').value = value[valkey];
        if (typeof(Event) === 'function')
        {
          var event = new Event('change');
        } else {
          var event = document.createEvent('Event'); 
          event.initEvent('change', true, true);
        }
        document.querySelector('[data-valkey="' + valkey + '"]').dispatchEvent(event);
        $('[data-valkey="' + valkey + '"]').parent().find('.js-colorpicker-paint').css('backgroundColor', '#' + value[valkey]);
      }
    }

    $option.find('.active').removeClass('active');
    $this.parent().addClass('active');
    $option.find('.js-colorpicker-val:first').parent().addClass('active');

    rsTuningSpectrumSetInputValues($option.find('.active').data('colorpicker-id'));

    return false;
  });

  $(document).on('click', '.js-colorpicker-val', function(e) {
    var $this = $(this),
        value = $this.parent().children('input').val(),
        hexColor = value + '',
        $option = $this.closest('.js-rs_option_info');

    if (hexColor.length == 6) {
      $option.find('.active').removeClass('active');
      $this.parent().addClass('active');
      rsTuningSpectrumSetInputValues($option.find('.active').data('colorpicker-id'));
      $('.js-colorpicker-set').removeClass('active');
    }

    return false;
  });

  $(document).on('blur keyup', '.js-colorpicker-hex', function() {
    var $this = $(this),
        $option = $this.closest('.js-rs_option_info');

    if ($this.val().length == 6) {
      $option.find('.active').find('input').val($this.val());
      rsTuningSpectrumSetInputValues($option.find('.active').data('colorpicker-id'));
    }

    return false;
  });

});
