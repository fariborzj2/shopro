<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Prevent multiple initializations if included multiple times
        if (window.tinymce_config_initialized) return;
        window.tinymce_config_initialized = true;

        const isDark = document.documentElement.classList.contains('dark');
        const skin = isDark ? 'oxide-dark' : 'oxide';
        const contentCss = isDark ? 'dark' : 'default';

        // Get context from PHP, default to 'general'
        const uploadContext = '<?php echo $tinyMceContext ?? "general"; ?>';

        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'directionality link image lists table media code searchreplace autolink autosave save visualblocks visualchars fullscreen template codesample charmap pagebreak nonbreaking anchor insertdatetime advlist wordcount help quickbars emoticons accordion',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | forecolor backcolor | code ltr rtl | fullscreen preview',
            language: 'fa',
            height: 500,
            relative_urls: false,
            remove_script_host: false,
            directionality: 'rtl',
            skin: skin,
            content_css: [
                contentCss,
                '/css/tiny-custom.css'
            ],

            // Image Upload Configuration
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '<?php echo url('api/upload-image'); ?>');

                // Get the current CSRF token from the meta tag
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                xhr.setRequestHeader('X-CSRF-TOKEN', token);

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        return reject('HTTP Error: ' + xhr.status);
                    }

                    let json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        return reject('Invalid JSON response: ' + xhr.responseText);
                    }

                    if (!json || typeof json.location != 'string') {
                        return reject('Invalid JSON: ' + xhr.responseText);
                    }

                    // Update CSRF token if rotated
                    if (json.csrf_token) {
                        if (tokenMeta) {
                            tokenMeta.setAttribute('content', json.csrf_token);
                        }
                        // Also update any hidden inputs
                        const inputTokens = document.querySelectorAll('input[name="csrf_token"]');
                        inputTokens.forEach(input => input.value = json.csrf_token);
                    }

                    resolve(json.location);
                };

                xhr.onerror = () => reject('Image upload failed due to a network error.');

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('context', uploadContext);

                xhr.send(formData);
            })
        });
    });
</script>
