jQuery(document).ready(function($) {
    // Toggle active status via AJAX
    $('.cm-toggle').on('change', function() {
        var checkbox = $(this);
        var id = checkbox.data('id');
        var status = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
            url: cm.ajax_url,
            type: 'POST',
            data: {
                action: 'cm_toggle',
                id: id,
                status: status,
                nonce: cm.nonce
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error updating status.');
                    checkbox.prop('checked', !status);
                }
            },
            error: function() {
                alert('Request failed.');
                checkbox.prop('checked', !status);
            }
        });
    });

    // Initialize CodeMirror if element exists
    if ($('#snippet_code').length && typeof wp !== 'undefined' && wp.codeEditor) {
        var editorSettings = typeof cm_editor_settings !== 'undefined' ? cm_editor_settings : wp.codeEditor.defaultSettings;
        editorSettings = editorSettings ? _.clone(editorSettings) : {};
        
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 4,
                tabSize: 4,
                lineNumbers: true,
                mode: 'application/x-httpd-php'
            }
        );

        var editor = wp.codeEditor.initialize($('#snippet_code'), editorSettings);

        // Store contents for each type
        var typeContents = {
            'php': '',
            'css': '',
            'javascript': '',
            'html': ''
        };
        var currentType = $('#snippet_type').val();
        typeContents[currentType] = editor.codemirror.getValue();

        // Update stored content on change
        editor.codemirror.on('change', function(cm) {
            var type = $('#snippet_type').val();
            typeContents[type] = cm.getValue();
            $('#snippet_code').val(cm.getValue());
        });

        // Change mode based on type selection
        $('#snippet_type').on('change', function() {
            var type = $(this).val();
            var mode = 'application/x-httpd-php';
            var title = 'PHP Snippet';
            
            if (type === 'css') {
                mode = 'text/css';
                title = 'CSS Snippet';
            } else if (type === 'javascript') {
                mode = 'text/javascript';
                title = 'JavaScript Snippet';
            } else if (type === 'html') {
                mode = 'text/html';
                title = 'HTML Snippet';
            }

            if (editor) {
                editor.codemirror.setOption('mode', mode);
                // Switch to the content stored for this type
                if (editor.codemirror.getValue() !== typeContents[type]) {
                    editor.codemirror.setValue(typeContents[type]);
                }
            }
            $('.editor-title').text(title);

            if (type === 'php') {
                $('#php_syntax_hint').show();
            } else {
                $('#php_syntax_hint').hide();
            }
        });

        // Initialize UI state without resetting content
        $('#snippet_type').trigger('change');
    }
});
