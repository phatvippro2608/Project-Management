//Image Upload
const btnPhoto = document.querySelector('.btn_photo')
const overlays = document.querySelectorAll('.overlay-upload');
const fileInput = document.getElementById('fileInput');
const profileImage = document.getElementById('profileImage');

overlays.forEach(overlay => {
    overlay.addEventListener('click', function() {
        fileInput.click();
    });
});

fileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            profileImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    btnPhoto.classList.remove('d-none')
});

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
