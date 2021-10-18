function rsTuningDdInit() {
    var elements = document.querySelectorAll('.js-rstuning-sortable');

    if (elements.length > 0) {
        for (var i = 0; i < elements.length; i++) {
            Sortable.create(elements[i], {
                handle: '.js-rstuning-sortable-handle',
                ghostClass: "rstuning__option__dd__ghost",
                forceFallback: true,
                fallbackClass: 'rstuning__option__dd__fallback',
                onEnd: function (event) {
                    var arSortedResult = [],
                        list = event.to,
                        rsTuningComponent = new RS.TuningComponent();
                        tuningObj = rsTuningComponent.getTuningComponent(),
                        el = list.parentElement

                    while ((el = el.parentElement) && !el.classList.contains('js-rs_option_info'))

                    if (el && el.getAttribute('data-reload') == 'Y') {
                        tuningObj.setAttribute('data-reload', 'Y')
                    }

                    var name = el.getAttribute('data-control-name')
                        elements = el.querySelectorAll('input[name="' + name + '[]"]')

                    if (elements && elements.length > 0) {
                        for (i = 0; i < elements.length; i++) {
                            arSortedResult.push(elements[i].value)
                        }
                    }

                    var event = new CustomEvent('rs.tuning.option.dd.onEnd', {'detail' : {'element': el, 'value': arSortedResult}});
                    document.dispatchEvent(event);
                    
                    rsTuningComponent.formSubmit();
                },
            })
        }
    }
}

$(document).ready(function(){
    rsTuningDdInit();
});
