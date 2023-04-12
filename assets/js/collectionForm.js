class CollectionForm {

    constructor(conf) {
        this.$collectionHolder = conf.collectionHolder;
        this.conf = conf;

        if (typeof conf.collectionHolder === "undefined") {
            console.error('Collection non trouvé');
        } else {
            this.init();
        }
    }

    init() {
        const CollectionForm = this;
        const $addSpeakerLink = $('<button class="add_link btn btn-secondary mt-3" type="button"> ' + this.conf.textAddBtn + ' </button>');
        const $newLinkDiv = $('<div class="col-md-12"></div>').append($addSpeakerLink);

        this.$collectionHolder.append($newLinkDiv);

        this.$collectionHolder.find('.item-collection').each(function() {
            CollectionForm.addFormDeleteLink($(this));
        });

        this.$collectionHolder.data('index', this.$collectionHolder.find(':input').length);

        $addSpeakerLink.on('click', function(e) {
            e.preventDefault();
            CollectionForm.addForm($newLinkDiv);
        });
    }

    addFormDeleteLink($elem) {
        let CollectionForm = this;
        let $removeForm = $('<div class="' + this.conf.classDeleteBtn + '">' +
            '<button class="btn btn-danger btn-block ' + this.conf.sizeBtnDelete + '" type="button">' + this.conf.textDeleteBtn + '</button>' +
            '</div>');

        if ($elem.find('.form-row').length) {
           $elem = $($elem.find('.form-row')[$elem.find('.form-row').length - 1]);
        }

        $elem.append($removeForm);

        $removeForm.on('click', function(e) {
            e.preventDefault();

            if (CollectionForm.conf.idModal) {

                $(CollectionForm.conf.idModal).modal('toggle');

                $(CollectionForm.conf.idModal).find('.btn-confirm').unbind('click');

                $(CollectionForm.conf.idModal).find('.btn-confirm').on('click', function() {
                    CollectionForm.delete(e.target);
                    $(CollectionForm.conf.idModal).modal('hide');
                });
            } else {
                CollectionForm.delete(e.target);
            }
        });
    }

    delete(elem) {
        $(elem).parents('.item-collection').remove();

        if (this.conf.callbackDelete)
        {
            this.conf.callbackDelete(elem);
        }

        if (!this.$collectionHolder.find('.item-collection').length) {
            this.$collectionHolder.find('.hideBlock').show();
        }
    }

    addForm($newLinkDiv) {
        let prototype = this.$collectionHolder.data('prototype');
        let index = this.$collectionHolder.data('index');
        let newForm = prototype.replace(/__name__/g, index);

        this.$collectionHolder.data('index', index + 1);

        let $newFormDiv = $(newForm);
        this.addFormDeleteLink($newFormDiv);
        $newLinkDiv.before($newFormDiv);

        this.$collectionHolder.find('.hideBlock').hide();

        this.addPlugins();

        let readURL = function(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $(input).parents('.picture-wrapper').find('.profile-pic').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        };

        $(".upload-button").on('click', function(e) {
            let $fileUpload = $(e.target).parents('.picture-wrapper').find('.file-upload');
            $fileUpload.click();

            $fileUpload.unbind('change');
            $fileUpload.on('change', function(){
                readURL(this);
            });
        });
    }

    addPlugins() {
        this.conf.collectionHolder.find('.form-row').bootstrapMaterialDesign();
        // this.conf.collectionHolder.find('input[data-toggle="datepicker"]').datepicker();

        // this.conf.collectionHolder.find(".multiselect-dropdown").selectpicker({
        //     theme: "bootstrap4",
        //     placeholder: "Select an option",
        // });
    }
}

let confDefault = {
    textAddBtn: 'Ajouter',
    textDeleteBtn: '<i class="fa fa-trash"></i>',
    classDeleteBtn : 'col-md-12',
    classDiv: 'col-xl-4 col-md-6 col-xs-12',
    idModal: null,
    callbackDelete: null,
    sizeBtnDelete: '',
};

$.fn.extend({
    collectionForm: function (conf = confDefault) {
        conf.collectionHolder = this;

        conf.textAddBtn = conf.textAddBtn !== undefined ? conf.textAddBtn : confDefault.textAddBtn;
        conf.textDeleteBtn = conf.textDeleteBtn !== undefined ? conf.textDeleteBtn : confDefault.textDeleteBtn;
        conf.classDeleteBtn = conf.classDeleteBtn !== undefined ? conf.classDeleteBtn : confDefault.classDeleteBtn;
        conf.classDiv = conf.classDiv !== undefined ? conf.classDiv : confDefault.classDiv;
        conf.idModal = conf.idModal !== undefined ? conf.idModal : confDefault.idModal;
        conf.callbackDelete = conf.callbackDelete !== undefined ? conf.callbackDelete : confDefault.callbackDelete;
        conf.sizeBtnDelete = conf.sizeBtnDelete !== undefined ? conf.sizeBtnDelete : confDefault.sizeBtnDelete;

        new CollectionForm(conf);
    }}
);
