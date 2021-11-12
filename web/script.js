function createTd(innerTextOrHtml, ishtml = false) {
    let td = document.createElement('td')
    if (ishtml)
        td.innerHTML = innerTextOrHtml;
    else
        td.innerText = innerTextOrHtml;
    return td;
}

function genAlert(message, level) {
    let alertdiv = document.createElement('div');
    alertdiv.classList.add('alert');
    alertdiv.classList.add('alert-' + level);
    alertdiv.setAttribute('role', 'alert');
    alertdiv.innerText = message;
    let cbtn = document.createElement('button');
    cbtn.type = 'button';
    cbtn.className = 'btn-close';
    cbtn.setAttribute('data-bs-dismiss', 'alert');
    cbtn.setAttribute('aria-label', 'close');
    alertdiv.appendChild(cbtn);
    let body = document.getElementsByTagName('body')[0];
    body.insertBefore(alertdiv, body.firstElementChild);
}