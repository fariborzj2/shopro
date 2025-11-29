var id = () => Math.random().toString(36).substr(2, 9);

var cuteAlert = ({
                       type,
                       title,
                       message,
                       img,
                       buttonText = 'OK',
                       confirmText = 'OK',
                       vibrate = [],
                       playSound = null,
                       cancelText = 'Cancel',
                       closeStyle,
                   }) => {
    return new Promise((resolve) => {
        var existingAlert = document.querySelector('.alert-wrapper');

        if (existingAlert) {
            existingAlert.remove();
        }

        var body = document.querySelector('.main');

        var scripts = document.getElementsByTagName('script');

        let src = '';

        for (let script of scripts) {
            if (script.src.includes('cute-alert.js')) {
                src = script.src.substring(0, script.src.lastIndexOf('/'));
            }
        }

        let btnTemplate = `
      <button class="alert-button ">${buttonText}</button>
    `;

        if (type === 'question') {
            btnTemplate = `
        <div class="question-buttons">
          <button class="confirm-button">${confirmText}</button>
          <button class="cancel-button">${cancelText}</button>
        </div>
      `;
        }

        if (vibrate.length > 0) {
            navigator.vibrate(vibrate);
        }

        if (playSound !== null) {
            let sound = new Audio(playSound);
            sound.play();
        }

        var template = `
      <div class="alert-wrapper ${type}-alert">
        <div class="alert-frame">
          ${
            img === undefined
                ? '<div class="alert-header">'
                : '<div class="alert-header-base">'
        }
            <span class="alert-close ${
            closeStyle === 'circle'
                ? 'alert-close-circle'
                : 'alert-close-default'
        }"><i class="icon-add"></i></span>
            ${
            img === undefined
                ? '<img class="alert-img" src="' +
                src +
                '../../images/alert/' +
                type +
                '.svg' +
                '" />'
                : '<div class="custom-img-wrapper">' + img + '</div>'
        }
          </div>
          <div class="alert-body">
            <span class="alert-title">${title}</span>
            <span class="alert-message">${message}</span>
            ${btnTemplate}
          </div>
        </div>
      </div>
    `;

        body.insertAdjacentHTML('afterend', template);

        var alertWrapper = document.querySelector('.alert-wrapper');
        var alertFrame = document.querySelector('.alert-frame');
        var alertClose = document.querySelector('.alert-close');

        if (type === 'question') {
            var confirmButton = document.querySelector('.confirm-button');
            var cancelButton = document.querySelector('.cancel-button');

            confirmButton.addEventListener('click', () => {
                alertWrapper.remove();
                resolve('confirm');
            });

            cancelButton.addEventListener('click', () => {
                alertWrapper.remove();
                resolve();
            });
        } else {
            var alertButton = document.querySelector('.alert-button');
            alertButton.addEventListener('click', () => {
                alertWrapper.remove();
                resolve('ok');
            });
        }

        alertClose.addEventListener('click', () => {
            alertWrapper.remove();
            resolve('close');
        });

        alertFrame.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
};

var cuteToast = ({type, title, message, timer = 5000, vibrate = [], playSound = null}) => {
    return new Promise(resolve => {
        var body = document.querySelector('.main');

        var scripts = document.getElementsByTagName('script');

        let src = '';

        for (let script of scripts) {
            if (script.src.includes('cute-alert.js')) {
                src = script.src.substring(0, script.src.lastIndexOf('/'));
            }
        }

        let templateContainer = document.querySelector('.toast-container');

        if (!templateContainer) {
            body.insertAdjacentHTML(
                'afterend',
                '<div class="toast-container"></div>',
            );
            templateContainer = document.querySelector('.toast-container');
        }

        var toastId = id();

        var templateContent = `
        <div class="toast-content ${type}-bg" id="${toastId}-toast-content">
        <div>
        <div class="toast-frame">
        <div class="toast-body">
        <img class="toast-body-img" src="../../images/alert/${type}.svg" />
        <div class="toast-body-content">
        <span class="toast-title">${title}</span>
        <span class="toast-message">${message}</span>
        </div>
        <div class="toast-close" id="${toastId}-toast-close"><i class="icon-add"></i></div>
        </div>
        </div>
        <div class="toast-timer ${type}-timer"  style="animation: timer ${timer}ms linear;">
        </div>
        </div>
        `;

        var toasts = document.querySelectorAll('.toast-content');

        if (toasts.length) {
            toasts[0].insertAdjacentHTML('beforebegin', templateContent);
        } else {
            templateContainer.innerHTML = templateContent;
        }

        var toastContent = document.getElementById(`${toastId}-toast-content`);

        if (vibrate.length > 0) {
            navigator.vibrate(vibrate);
        }

        if (playSound !== null) {
            let sound = new Audio(playSound);
            sound.play();
        }

        setTimeout(() => {
            toastContent.remove();
            resolve();
        }, timer);

        var toastClose = document.getElementById(`${toastId}-toast-close`);

        toastClose.addEventListener('click', () => {
            toastContent.remove();
            resolve();
        });
    });
};
