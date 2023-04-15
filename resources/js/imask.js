import IMask from "imask";

document.addEventListener('DOMContentLoaded', function () {
    let phoneElement = document.querySelector('input[name=phone]');

    if (phoneElement) {
        let maskOptions = {
            mask: '+{375} 00 0000000',
        };
        IMask(phoneElement, maskOptions);
    }

    let expiresAtElement = document.querySelector('input[name=details\\[expires_at\\]]');

    if (expiresAtElement) {
        let maskOptions = {
            mask: '00/00',
        };
        IMask(expiresAtElement, maskOptions);
    }

    let cardNumberElement = document.querySelector('input[name=details\\[card_number\\]]');

    if (cardNumberElement) {
        let maskOptions = {
            mask: '0000 0000 0000 0000',
        };
        IMask(cardNumberElement, maskOptions);
    }
})
