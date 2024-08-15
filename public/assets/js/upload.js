
// =======Photo upload=========
const photoUploads = document.querySelectorAll('.photo-upload');
if (photoUploads) {
    photoUploads.forEach(photoUpload => {
        const idTarget = photoUpload.getAttribute('photo-input-target');
        if (idTarget) {
            const overlayDiv = document.createElement('div');
            overlayDiv.className = 'overlay';

            const icon = document.createElement('i');
            icon.className = 'bi bi-camera';

            const input = document.createElement('input');
            input.type = 'file';
            input.id = idTarget;
            input.className = 'photoInput';

            overlayDiv.appendChild(icon);
            overlayDiv.appendChild(input);

            photoUpload.insertBefore(overlayDiv, photoUpload.firstChild);
        }

        const overlay = photoUpload.querySelector('.overlay');
        const fileInput = photoUpload.querySelector('input[type="file"]');
        const profileImage = photoUpload.querySelector('img');

        overlay.onclick = () => {
            fileInput.click();
        }

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

    });
}

//File upload
const uploadSections = document.querySelectorAll('.upload-section');
uploadSections.forEach(section => {
    const fileInput = section.querySelector('input[type="file"]');
    const fileSelector = section.querySelector('.file-selector');
    const fileSelectorTarget = fileSelector.getAttribute('file-input-target')
    const docxFile = '<img src="../../../../assets/img/docx.png"  alt="">';
    const pdfFile = '<img src="../../../../assets/img/pdf.png" alt="">';
    const blankFile = '<img src="../../../../assets/img/paper.png" alt="">';
    if(fileSelectorTarget && fileSelectorTarget === fileInput.id){
        fileSelector.onclick = function (){
            fileInput.click()
        }
    }

    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        if (files.length > 0) {
            for (let file of files) {
                let extension = file.name.split('.');
                extension = extension[extension.length -1];
                while (fileSelector.firstChild){
                    fileSelector.removeChild(fileSelector.firstChild)
                }
                if(extension === 'docx' || extension === 'doc'){
                    fileSelector.innerHTML = docxFile
                }else if(extension === 'pdf'){
                    fileSelector.innerHTML = pdfFile
                }else{
                    fileSelector.innerHTML = blankFile
                }
            }
        }
    });
});



