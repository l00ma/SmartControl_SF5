function sendVideo(videoIds, action) {
    fetch( action, {
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
            if (data.zip_file) {
                window.location.href = window.location.origin + '/' + data.zip_file;
                deleteZipFile();
            }
        })
        .catch(error => console.error("Erreur :", error))
}

function deleteZipFile() {
    fetch( '/gallery/clean')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la requête: HTTP code ' + response.status);
            }
            return response.json();
        })
        .then(data => {})
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression du contenu du répértoire temporaire');
        });
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
            sendVideo(videoIds, '/gallery/download');
        }
    });

    //action de selection des vidéos à supprimer
    document.getElementById("but_delete").addEventListener("click", function () {
        const videoIds = getSelection();
        if (validateSelection(videoIds, 'la suppression')) {
            sendVideo(videoIds, '/gallery/delete');
        }
    });
});