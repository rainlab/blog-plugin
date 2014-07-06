+function ($) { "use strict";
    var PostForm = function () {
        this.$preview = $('#blog-post-preview')
        this.$form = this.$preview.closest('form')
        this.formAction = this.$form.attr('action')
        this.sessionKey = $('input[name=_session_key]', this.$form).val()
        this.$textarea = $('[name="Post[content]"]', this.$form)
        this.$previewContent = $('.preview-content', this.$preview)
        this.codeEditor = $('textarea[name="Post[content]"]', this.$form).closest('.field-codeeditor').data('oc.codeEditor')
        this.createIndicator()

        this.$textarea.on('oc.codeEditorChange', $.proxy(this.handleChange, this))

        this.loading = false
        this.updatesPaused = false
        this.initPreview()
        this.initDropzones()
        this.initFormEvents()
        this.initLayout()
    }

    PostForm.prototype.handleChange = function() {
        if (this.updatesPaused)
            return

        var self = this

        if (this.loading) {
            if (this.dataTrackInputTimer === undefined) {
                this.dataTrackInputTimer = window.setInterval(function(){
                    self.handleChange()
                }, 100)
            }

            return
        }

        window.clearTimeout(this.dataTrackInputTimer)
        this.dataTrackInputTimer = undefined

        var self = this;
        self.update();
    }

    PostForm.prototype.createIndicator = function() {
        var $previewContainer = $('#blog-post-preview').closest('.loading-indicator-container')
        this.$indicator = $('<div class="loading-indicator transparent"><div></div><span></span></div>')
        $previewContainer.prepend(this.$indicator)
    }

    PostForm.prototype.update = function() {
        var self = this

        this.loading = true
        this.showIndicator()

        this.$form.request('onRefreshPreview', {
            success: function(data) {
                self.$previewContent.html(data.preview)
                self.initPreview()
                self.updateScroll()
            }
        }).done(function(){
            self.hideIndicator()
            self.loading = false
        })
    }

    PostForm.prototype.showIndicator = function() {
        this.$indicator.css('display', 'block')
    }

    PostForm.prototype.hideIndicator = function() {
        this.$indicator.css('display', 'none')
    }

    PostForm.prototype.initPreview = function() {
        prettyPrint()
        this.initImageUploaders()
    }

    PostForm.prototype.updateScroll = function() {
        this.$preview.data('oc.scrollbar').update()
    }

    PostForm.prototype.initImageUploaders = function() {
        var self = this
        $('span.image-placeholder .dropzone', this.$preview).each(function(){
            var 
                $placeholder = $(this).parent(),
                $input = $('input[type=file].file', $placeholder),
                placeholderIndex = $placeholder.data('index')

            $input.fileupload({
                'dropZone': $(this),
                'url': self.formAction,
                'paramName': 'file',
                'formData': {
                    'X_BLOG_IMAGE_UPLOAD': 1,
                    '_session_key': self.sessionKey
                },
                done: function(e, data) {
                    if (data.result.error)
                        alert(data.result.error)
                    else {
                        self.pauseUpdates()
                        var $img = $('<img src="'+data.result.path+'">')
                        $img.load(function(){
                            self.updateScroll()
                        })

                        $placeholder.replaceWith($img)

                        self.codeEditor.editor.replace('!['+data.result.file+']('+data.result.path+')', {
                            needle: '!['+placeholderIndex+'](image)'
                        })
                        self.resumeUpdates()
                    }
                },
                fail: function(e, data) {
                    alert('Error uploading file.')
                },
                start: function(e, data) {
                    $placeholder.addClass('loading')
                },
                stop: function(e, data) {
                    $placeholder.removeClass('loading')
                }
            })
        })
    }

    PostForm.prototype.pauseUpdates = function() {
        this.updatesPaused = true
    }

    PostForm.prototype.resumeUpdates = function() {
        this.updatesPaused = false
    }

    PostForm.prototype.initDropzones = function() {
        $(document).bind('dragover', function (e) {
            var dropZone = $('span.image-placeholder .dropzone'),
                foundDropzone,
                timeout = window.dropZoneTimeout

            if (!timeout)
                dropZone.addClass('in');
            else
                clearTimeout(timeout);

            var found = false,
                node = e.target

            do{
                if ($(node).hasClass('dropzone')) {
                    found = true;
                    foundDropzone = $(node);
                    break;
                }

                node = node.parentNode;

            } while (node != null);

            dropZone.removeClass('in hover');

            if (found)
                foundDropzone.addClass('hover');

            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100)
        });

        $(document).on('click', '#blog-post-preview span.dropzone', function() {
            $('input[type=file].trigger', $(this).parent()).click()
        })

        $(document).on('change', '#blog-post-preview input[type=file].trigger', function(e) {
            $('input[type=file].file', $(this).closest('.image-placeholder')).fileupload('add', {
                files: e.target.files || [{name: this.value}],
                fileInput: $(this)
            })

            $(this).val('')
        })
    }

    PostForm.prototype.initFormEvents = function() {
        $(document).on('ajaxSuccess', '#post-form', function(event, context, data){
            if (context.handler == 'onSave' && !data.X_OCTOBER_ERROR_FIELDS) {
                $(this).trigger('unchange.oc.changeMonitor')
            }
        })

        $('#Datepicker-formPublishedAt-input-published_at').triggerOn({
            triggerCondition: 'checked',
            trigger: '#Form-field-Post-published',
            triggerType: 'enable'
        })
    }

    PostForm.prototype.initLayout = function() {
        $('#Form-secondaryTabs .tab-pane.layout-cell:not(:first-child)').addClass('padded-pane')
    }

    PostForm.prototype.replacePlaceholder = function(placeholder, placeholderHtmlReplacement, mdCodePlaceholder, mdCodeReplacement) {
        this.pauseUpdates()
        placeholder.replaceWith(placeholderHtmlReplacement)

        this.codeEditor.editor.replace(mdCodeReplacement, {
            needle: mdCodePlaceholder
        })
        this.updateScroll()
        this.resumeUpdates()
    }
    
    $(document).ready(function(){
        var form = new PostForm()

        if ($.oc === undefined)
            $.oc = {}

        $.oc.blogPostForm = form
    })

}(window.jQuery);