import Dropzone from 'dropzone';

// Make sure Dropzone doesn't try to attach itself to the
// element automatically.
// This behaviour will change in future versions.
Dropzone.autoDiscover = false;

window.Dropzone = Dropzone;

window.bpFieldInitDropzoneElement = (element) => {
  const myDropzone = new Dropzone(
    `#${element.attr('id')}`,
    element.data('config')
  );
  const token = window.$('meta[name="csrf-token"]').attr('content');
  const input = element.find('input').get(0);
  const button = document.getElementById(`${element.attr('id')}-button`);

  // Append token to the request - required for web routes
  myDropzone.on('sending', (file, xhr, formData) => {
    formData.append('_token', token);

    // This will track all request so we can get the correct request that returns final response:
    // We will change the load callback but we need to ensure that we will call original
    // load callback from dropzone
    const dropzoneOnLoad = xhr.onload;
    xhr.onload = function (e) {
      dropzoneOnLoad(e);

      // Check for final chunk and get the response
      const uploadResponse = JSON.parse(xhr.responseText);
      if (typeof uploadResponse.name === 'string') {
        input.name = input.dataset.name;
        input.value = uploadResponse.path + uploadResponse.name;
      }
    };
  });

  myDropzone.on('maxfilesexceeded', function (file) {
    this.removeAllFiles();
    this.addFile(file);
  });

  myDropzone.on('removedfile', function (file) {
    if (button && !button.parentNode.classList.contains('d-none')) {
      input.removeAttribute('name');
    }

    input.value = '';
  });

  if (button) {
    button.addEventListener('click', () => {
      if (!input.value) {
        input.name = input.dataset.name;
        input.value = '';
      }
      button.parentNode.classList.add('d-none');
    });
  }
};
