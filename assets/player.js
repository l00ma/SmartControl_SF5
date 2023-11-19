function sendVideo(videoIds, action) {
    fetch(action, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(videoIds),
    })
        .then(response => response.json())
        .then(data =>  {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(error => console.error("Erreur :", error))
}

function getSelection() {
    const videoIds = [];
    const elements = document.querySelectorAll(".videoId");
    elements.forEach((element) => {
        let checkbox = element.querySelector('.form-check-input');
        if (checkbox.checked) {
            videoIds.push(element.getAttribute('value'))
        }
    });
    return videoIds;
}

function validateSelection(selection, message) {
    let valid = false;
    if (selection.length) {
        if (selection.length === 1) {
            valid = confirm('Confirmer ' + message + ' de cette video ?');
        } else {
            valid = confirm('Confirmer ' + message + ' de ' + selection.length + ' videos ?');
        }
    } else {
        alert('Veuillez sélectionner au moins une video.');
    }
    return valid;
}

document.addEventListener("DOMContentLoaded", function () {

    //action de selection des vidéos à télécharger
    document.getElementById("but_download").addEventListener("click", function () {
        const videoIds = getSelection();
        if (validateSelection(videoIds, 'le téléchargement')) {
            sendVideo(videoIds, 'download');
        }
    });

    //action de selection des vidéos à supprimer
    document.getElementById("but_delete").addEventListener("click", function () {
        const videoIds = getSelection();
        if (validateSelection(videoIds, 'la suppression')) {
            sendVideo(videoIds, 'delete');
        }
    });
});