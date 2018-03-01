window.onload = () => {
    var validateButton = document.querySelectorAll('.validate-move');
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