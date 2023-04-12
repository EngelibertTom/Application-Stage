import './styles/app.scss';
import './scss/material-dashboard.scss';
import '/assets/js/core/popper.min'
import './bootstrap';
import 'bootstrap-material-design'
import 'moment/dist/moment'
import 'sweetalert2/dist/sweetalert2.min'
import 'jquery-validation/dist/jquery.validate.min'
import 'jquery-bootstrap-wizard/jquery.bootstrap.wizard.js'
import 'bootstrap-select/dist/js/bootstrap-select.min'
import '/assets/js/plugins/bootstrap-selectpicker'
import '/assets/js/material-dashboard.min'
// import '/assets/js/app'
import '/assets/js/collectionForm'
// import '/assets/js/selectPosition'
import 'datatables.net-bs4'
import 'datatables.net-buttons'
import 'datatables.net-responsive'
import '/assets/js/dataTableConfig'
import '/assets/js/plugins/dropfile'
import '/assets/js/chartist'
import '/assets/js/settings'
import 'fancybox/dist/js/jquery.fancybox'
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';

import Cropper from 'cropperjs';

$(document).ready(function ()
{
    let $modal = $('#modal');
    let image = document.getElementById('image');
    let cropper;
    let item = null;
    let file = null;

    $("body").on("change", ".image", function(e){
        item = $(this).parents('.item-collection');
        let files = e.target.files;
        let done = function (url) {
            image.src = url;
            $modal.modal('show');
        };
        let reader;
        let url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $(".pictureTree").find('img').on("click", function(e){
        $(e.target).parents('.item-collection').find('.inputFileHidden').click();
    });

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    $("#crop").click(function(){
        $('.treePictures').find('.add_link').addClass('disabled');

        let canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
        });

        canvas.toBlob(function(blob) {
            let btnCrop = $('#crop');
            let htmlBtnCrop = btnCrop.html();

            btnCrop.html('<img src="/img/spinner.gif" height="15px">');
            btnCrop.addClass('disabled');

            let url = URL.createObjectURL(blob);
            let reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                let base64data = reader.result;
                let picture = item.find('.pictureTree')[0];
                let input = item.find('input')[0];
                // let idPicture = picture.dataset.id ? '/' + picture.dataset.id : '';
                // let idTree = picture.dataset.tree;

                let url = item.data('ajax');

                cropper.clear().getCroppedCanvas({
                    width: 800,
                    height: 800,
                }).toBlob(function (blob2) {
                    let reader2 = new FileReader();
                    reader2.readAsDataURL(blob2);

                    setTimeout(function () {
                        let base64data2 = reader2.result;

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: url,
                            data: {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'picture': base64data,
                                'original': base64data2
                            },
                            success: function (data) {
                                picture.dataset.id = data.id;
                                picture.innerHTML = data.content;

                                $(".pictureTree").unbind('click');
                                $(".pictureTree").find('img').on("click", function (e) {
                                    $(e.target).parents('.item-collection').find('.inputFileHidden').click();
                                });
                                $(item).find('.form-file-upload').hide();

                                input.file = file;
                                $modal.modal('hide');

                                $('.treePictures').find('.add_link').removeClass('disabled');
                                btnCrop.removeClass('disabled');
                                btnCrop.html(htmlBtnCrop);
                            }
                        });
                    }, 300);
                })
            };
        });
    });


    $('label').removeClass('bmd-label-static').addClass('bmd-label-floating');

    // $('select.select-position').selectPosition();

    //
    // // $.fn.bootstrapDP = $.fn.datepicker.noConflict();
    //

    if ($('div.managementNurseries').length) {
        $('div.managementNurseries').collectionForm({
            textAddBtn: 'Ajouter une pépinière',
            idModal:    '#confirmDeleteManagementNursery',
            classDeleteBtn: 'col-md-2 pt-md-2'
        });
    }

    if ($('div.works').length) {
        $('div.works').collectionForm({
            textAddBtn: 'Ajouter un travaux',
            idModal:    '#confirmDeleteWork',
            classDeleteBtn: 'col-md-1 pt-md-2'
        });
    }

    if ($('div.observations').length) {
        $('div.observations').collectionForm({
            textAddBtn: 'Ajouter une observation',
            idModal:    '#confirmDeleteObservation',
            classDeleteBtn: 'col-md-1 pt-md-2'
        });
    }

    if ($('div.locations').length) {
        $('div.locations').collectionForm({
            textAddBtn: 'Ajouter une aire',
            idModal:    '#confirmDeleteLocation',
            classDeleteBtn: 'col-md-2'
        });
    }

    if ($('div.treePictures').length) {
        $('div.treePictures').collectionForm({
            textAddBtn: 'Ajouter une photo',
            idModal:    '#confirmDeletePicture',
            classDeleteBtn: 'col-md-5 d-inline-block',
            sizeBtnDelete: 'btn-sm',
            callbackDelete: function (elem) {
                let id = $(elem).parents('.item-collection ').find('.pictureTree').data('id');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/admin/trees/removePicture/" + id
                });
            }
        });
    }

    if ($('div.lotPictures').length) {
        $('div.lotPictures').collectionForm({
            textAddBtn: 'Ajouter une photo',
            idModal:    '#confirmDeletePicture',
            classDeleteBtn: 'col-md-8',
            sizeBtnDelete: 'btn-sm',
            callbackDelete: function (elem) {
                let id = $(elem).parents('.item-collection ').find('.pictureTree').data('id');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/admin/lots/removePicture/" + id
                });
            }
        });
    }

    $('.dropfile').dropfile();

    if( /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $('body').addClass('sidebar-mini');
    }

    // Carousel sur la vue détail des arbres
    $("a.fancybox").fancybox({
        'transitionIn'	:	'elastic',
        'transitionOut'	:	'elastic',
        'speedIn'		:	600,
        'speedOut'		:	200,
        'overlayShow'	:	false
    });

    $('.owl-carousel').owlCarousel({
        // items: 4,
        autoWidth: true,
        margin: 15
    });

    $('#selectNursery').on('change', function (e) {
        let idNursery = e.target.value;

        $.ajax({
            method: "POST",
            url: "/admin/users/change-main-nursery/" + idNursery,
            data: {
                'idNursery': idNursery
            }
        }).done(function() {
            location.reload();
        });
    })
});
