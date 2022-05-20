import Dropzone from 'dropzone/src/dropzone';
import defaultOptions from "dropzone/src/options";

// Make sure Dropzone doesn't try to attach itself to the
// element automatically.
// This behaviour will change in future versions.
Dropzone.autoDiscover = false;

window.Dropzone = Dropzone;

const loadDropzoneInitialFile = (dropzone, fileUrl) => {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", fileUrl, false);
  xhr.onreadystatechange = () =>
  {
    if(xhr.readyState === 4)
    {
      // Get file data from URL
      let size = 0;
      let type = undefined;
      if(xhr.status === 200)
      {
        size = xhr.response.length;
        type = xhr.getResponseHeader('Content-Type') || undefined;
      }

      const mockFile = {
        name: fileUrl.replace(/^.*[\\\/]/, ''),
        size,
        type,
        accepted: true,
      }
      // Ask to skip thumbnail generation if not an image
      if (!type || !type.startsWith("image/")) {
        mockFile.noPreview = true;
      }
      // Display file
      dropzone.files.push(mockFile);
      dropzone.displayExistingFile(mockFile, fileUrl);
    }
  }
  xhr.send();
}

const loadDropzoneInitialFiles = (dropzone, element) => {
  const values = element.find("input").data("value");
  if (!values || values.length === 0) {
    return
  }

  const maxFiles = dropzone.options.maxFiles;
  for (const i in values) {
    if (maxFiles && maxFiles > 1 && i >= maxFiles) {
      break;
    }
    loadDropzoneInitialFile(dropzone, values[i]);
  }
}

window.bpFieldInitDropzoneElement = (element) => {
  const hasMultiple = !!element.data("allow-multiple");
  const config = {
    maxFiles: hasMultiple ? null : 1,
    ...element.data('config'),
    init: function () {
      // Add setTimeout to trigger events
      setTimeout(() => {
        loadDropzoneInitialFiles(this, element)
      }, 0);
    },
    // Skip files without preview on initialization
    thumbnail(file, dataUrl) {
      if (file.noPreview) {
        return;
      }
      return defaultOptions.thumbnail(file, dataUrl);
    },
  };

  const myDropzone = new Dropzone(`#${element.attr('id')}`, config);
  const token = window.$('meta[name="csrf-token"]').attr('content');
  const input = element.find('input').get(0);


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
        file.dataURL = uploadResponse.path + uploadResponse.name;
      }
    };
  });

  // Remove first file if a new one is uploaded
  myDropzone.on('addedfile', function () {
    const maxFiles = this.options.maxFiles;
    if (!maxFiles || maxFiles < 1) {
      return;
    }

    while (this.files.length > maxFiles) {
      this.removeFile(this.files[0]);
    }
  });

  const updateFields = () => {
    const wrapper = element.find("[data-input-list]");
    const fieldName = input.getAttribute("data-name") + (hasMultiple ? '[]' : '');

    wrapper.empty();
    for (const file of myDropzone.getAcceptedFiles()) {
      const field = `<input type="hidden" name="${fieldName}" value="${file.dataURL}">`;
      wrapper.append(field);
    }
  };
  myDropzone.on('removedfile', updateFields);
  myDropzone.on('complete', () => {
    setTimeout(updateFields, 0);
  });
};
