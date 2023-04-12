let options = {
    message          : "Déposez vos photos ici",
    script           : "/admin/trees/uploadPicture",
    collectionHolder : "treePictures",
    clone            : true,
    complete         : function (json) {
        return false;
    }
};

$.fn.dropfile = function (o)
{
    let replace = false;
    if (o) $.extend(options, o);

    this.each(function () {
        $("<span>").addClass('instructions').append(options.message).appendTo(this);
        $("<span>").addClass('progress').appendTo(this);

        $(this).bind({
            dragenter: function (e)
            {
                e.preventDefault();

            },
            dragover: function (e)
            {
                e.preventDefault();
                $(this).addClass('hover');
            },
            dragleave: function (e)
            {
                e.preventDefault();
                $(this).removeClass('hover');
            }
        });

        this.addEventListener('drop', function (e) {
            e.preventDefault();

            let files = e.dataTransfer.files;

            if ($(this).data('value')) {
                replace = true;
            }

            upload(files, $(this), 0);
        }, false);
    });

    function upload( files, area, index ) {
        let file = files[index];

        if (index > 0 && options.clone)
        {
            area = area.clone().html('').insertAfter(area).dropfile(options);
            area.data('value', null);
            area.data('folder', area.data('folder'));
        }

        let xhr = new XMLHttpRequest();
        let progress = area.find('.progress');

        //Evenements
        xhr.addEventListener('load', function (e) {
            let json = $.parseJSON(e.target.responseText);
            area.removeClass('hover');
            progress.css({height: 0});

            if (index < files.length - 1)
            {
                upload(files, area, index + 1);
            }

            if (json.error)
            {
                alert(json.error);
                return false;
            }

            let collectionHolder = document.getElementById(options.collectionHolder);
            let prototype = $(collectionHolder).data('prototype');
            let indexCollection = $(collectionHolder).data('index');
            let newForm = prototype.replace(/__name__/g, indexCollection);

            $(collectionHolder).data('index', indexCollection + 1);

            if (!area.find('input').length)
            {
                $(area).prepend(newForm);
            }

            area.find('input').val(json.name);

            if (options.complete(json))
            {
                return true;
            }

            if (options.clone && !replace && index === files.length - 1)
            {
                area.clone().html('').data('folder', area.data('folder')).insertAfter(area).dropfile(options);
            }

            area.data('value', json.name);
            area.append(json.content);
        }, false);

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable)
            {
                let perc = (Math.round(e.loaded / e.total) * 100) + '%';
                progress.css({ height: perc }).html(perc);
            }
        }, false);

        xhr.open('post', options.script, true);
        xhr.setRequestHeader("Content-Type", "multipart/form-data");
        xhr.setRequestHeader("x-file-type", file.type);
        xhr.setRequestHeader("x-file-size", file.size);
        xhr.setRequestHeader("x-file-name", file.name);

        for (let i in area.data())
        {
            if (typeof area.data(i) !== 'object')
            {
                xhr.setRequestHeader('x-param-' + i, area.data(i));
            }
        }
        xhr.send(file);

        // xhr.onload = function () {
        //     console.log(xhr.responseText);
        //
        // };
    }

    return this;
};


