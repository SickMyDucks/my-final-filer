window.onload = () => {
    var validateButton = document.querySelectorAll('.validate-move');
    var renameButtons = document.querySelectorAll('.edit')
    moveFolder(validateButton);
    rename(renameButtons);
    moveButtons();
    confirmEdit();
}

function moveFolder(validateButton) {
    for (var i = 0; i < validateButton.length; i++) {
        validateButton[i].onclick = (event) => {
            var url = window.location.href;
            url = new URL(url);
            if (typeof event.currentTarget.closest('td').parentElement.querySelector('.name').children[0] != 'undefined') {
                var file = "&file=" + event.currentTarget.closest('td').parentElement.querySelector('.name').children[0].innerHTML;
                var from = "&from=" + url.searchParams.get("dir") + "/" + event.currentTarget.closest('td').parentElement.querySelector('.name').children[0].innerHTML;
            } else {
                var file = "&file=" + event.currentTarget.closest('td').parentElement.querySelector('.name').innerHTML;
                var from = "&from=" + url.searchParams.get("dir") + "/" + event.currentTarget.closest('td').parentElement.querySelector('.name').innerHTML;
            }
            var to = "&to=" + event.currentTarget.closest('div').children[0].value;
            var target = "?action=moveItem" + from + to + file;
            event.currentTarget.href = target;
        }
    }
}

function rename(renameButtons) {
    for (var i = 0; i < renameButtons.length; i++) {
        renameButtons[i].onclick = (e) => {
            oldInput = e.currentTarget.closest('tr').querySelector('.name').children[0].innerHTML;
            e.currentTarget.closest('tr').querySelector('.name').children[0].setAttribute('contenteditable', 'true');
            e.currentTarget.closest('tr').querySelector('.name').children[0].focus();
            var actions = document.createElement('div');
            validate = document.createElement('div');
            validate.className = 'validate';
            validate.innerHTML = '<i class="fas fa-check"></i>'
            cancel = document.createElement('div');
            cancel.innerHTML = '<i class="fas fa-times"></i>'
            actions.append(validate, cancel);
            actions.className = 'actions';
            e.currentTarget.closest('tr').querySelector('.name').append(actions);

            document.querySelector('[contenteditable=true]').onblur = function (e) {
                if (e.explicitOriginalTarget.classList.contains('fa-check') || e.explicitOriginalTarget.classList.contains('validate')) {
                    var url = window.location.href;
                    url = new URL(url);
                    dir = url.searchParams.get("dir");
                    target = '?action=renameItem' + '&dir=' + dir + "&from=" + oldInput + "&to=" + this.innerHTML;
                    window.location.href = target;
                }
                if (e.currentTarget != document.querySelector('.actions div:first-child')) {
                    this.setAttribute('contenteditable', 'false');
                    element = document.querySelector('.actions');
                    element.parentElement.removeChild(element);
                    this.innerHTML = oldInput;
                }
            }
        }
    }
}

function moveButtons() {
    var moveButtons = document.querySelectorAll('.move');
    for (var i in moveButtons) {
        moveButtons[i].onclick = function() {
            this.style.display = 'none';
            this.parentElement.children[1].style.display = 'flex';
            this.parentElement.querySelector('.cancel-move').onclick = function() {
                this.closest('td').children[0].style.display = 'block';
                this.closest('td').children[1].style.display = 'none';
            }
        }
    }
}

function confirmEdit() {
    editForm = document.querySelector('form[name="editingFile"]');
    if (editForm != null) {
        editForm.onsubmit = function() {
        var url = window.location.href;
        url = new URL(url);
        file = url.searchParams.get("file");
        target = '?action=editFile&file=' + file + '&file-content=' + encodeURIComponent(this[0].value) + "&type=" + url.searchParams.get("type");
        window.location.href = target;
        return false;
        }
    }
}