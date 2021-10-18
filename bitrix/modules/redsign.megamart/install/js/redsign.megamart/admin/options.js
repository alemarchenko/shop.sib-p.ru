function RSMegaMartOptionsReady() 
{
    
    if (window.dragula) {        
        var dragContainers = document.querySelectorAll('.rs-adm-options-drag-container');
        
        for (var i = 0; i < dragContainers.length; i++) {
            dragula([dragContainers[i]]);
        }
    }
    
    var addInputButtons = document.querySelectorAll('[data-rs-options-add-input]');
    for (var i = 0; i < addInputButtons.length; i++) {
            
        addInputButtons[i].addEventListener("click", function () {
            var inputName = this.getAttribute('data-rs-options-add-input');
            var container = document.querySelector('[data-rs-options-container="' + inputName + '"]');
            
            container.insertAdjacentHTML(
                'beforeend',
                '<div class="rs-adm-options-input-container">' + 
                    '<input type="text" size="40" value="" name="' + inputName + '[]" id="' + inputName + '">' + 
                '</div>'
            );
        });
    }
}

document.addEventListener("DOMContentLoaded", RSMegaMartOptionsReady);