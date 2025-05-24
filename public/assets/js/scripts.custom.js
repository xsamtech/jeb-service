/**
 * Custom script
 * 
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
// Common variables
const navigator = window.navigator;
const currentLanguage = $('html').attr('lang');
const currentUser = $('[name="jeb-visitor"]').attr('content');
const currentHost = $('[name="jeb-url"]').attr('content');
const apiHost = $('[name="jeb-api-url"]').attr('content');
const headers = { 'Authorization': 'Bearer ' + $('[name="jeb-ref"]').attr('content'), 'Accept': $('.mime-type').val(), 'X-localization': navigator.language };
// Modals
const modalUser = $('#cropModalUser');
// Preview images
const retrievedAvatar = document.getElementById('retrieved_image');
const retrievedMediaCover = document.getElementById('retrieved_media_cover');
const currentMediaCover = document.querySelector('#mediaCoverWrapper img');
const retrievedImageProfile = document.getElementById('retrieved_image_profile');
const currentImageProfile = document.querySelector('#profileImageWrapper img');
const retrievedImageRecto = document.getElementById('retrieved_image_recto');
const currentImageRecto = document.querySelector('#rectoImageWrapper img');
const retrievedImageVerso = document.getElementById('retrieved_image_verso');
const currentImageVerso = document.querySelector('#versoImageWrapper img');
let cropper;

/**
 * Check string is numeric
 * 
 * @param string str
 */
function isNumeric(str) {
    if (typeof str != 'string') {
        return false
    } // we only process strings!

    return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

/**
 * Get cookie by name
 * 
 * @param string cname
 */
function getCookie(cname) {
    let name = cname + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');

    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];

        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }

    return '';
}

/**
 * Toggle Password Visibility
 * 
 * @param string current
 * @param string element
 */
function passwordVisible(current, element) {
    var el = document.getElementById(element);

    if (el.type === 'password') {
        el.type = 'text';
        current.innerHTML = '<i class="bi bi-eye-slash-fill"></i>'

    } else {
        el.type = 'password';
        current.innerHTML = '<i class="bi bi-eye-fill"></i>'
    }
}

/**
 * Switch between two elements visibility
 * 
 * @param string current
 * @param string element1
 * @param string element2
 * @param string message1
 * @param string message2
 */
function switchDisplay(current, form_id, element1, element2, message1, message2) {
    var _form = document.getElementById(form_id);
    var el1 = document.getElementById(element1);
    var el2 = document.getElementById(element2);

    _form.reset();
    el1.classList.toggle('d-none');
    el2.classList.toggle('d-none');

    if (el1.classList.contains('d-none')) {
        current.innerHTML = message1;
    }

    if (el2.classList.contains('d-none')) {
        current.innerHTML = message2;
    }
}

/**
 * Token writter
 * 
 * @param string id
 */
function tokenWritter(id) {
    var _val = document.getElementById(id).value;
    var _splitId = id.split('_');
    var key = event.keyCode || event.charCode;

    if (key === 8 || key === 46 || key === 37) {
        if (_splitId[2] !== '1') {
            var previousElement = document.getElementById('check_digit_' + (parseInt(_splitId[2]) - 1));

            previousElement.focus();
        }

    } else {
        var nextElement = document.getElementById('check_digit_' + (parseInt(_splitId[2]) + 1));

        if (key === 39) {
            nextElement.focus();
        }

        if (_splitId[2] !== '7') {
            if (_val !== undefined && Number.isInteger(parseInt(_val))) {
                nextElement.focus();
            }
        }
    }
}

$(function () {
    $('.navbar, .card, .btn').addClass('shadow-0');
    $('.btn').css({ textTransform: 'inherit', paddingBottom: '0.5rem' });
    $('.back-to-top').click(function (e) {
        $("html, body").animate({ scrollTop: "0" });
    });

    /* Auto-resize textarea */
    autosize($('textarea'));

    /* jQuery Date picker */
    $('#birthdate, #register_birthdate, #update_birthdate').datepicker({
        dateFormat: currentLanguage.startsWith('fr') || currentLanguage.startsWith('ln') ? 'dd/mm/yy' : 'mm/dd/yy',
        onSelect: function () {
            $(this).focus();
        }
    });

    // AVATAR with ajax
    $('#avatar').on('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            retrievedAvatar.src = url;
            var modal = new bootstrap.Modal(document.getElementById('cropModalUser'), { keyboard: false });

            modal.show();
        };

        if (files && files.length > 0) {
            var reader = new FileReader();

            reader.onload = function () {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $(modalUser).on('shown.bs.modal', function () {
        cropper = new Cropper(retrievedAvatar, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '#cropModalUser .preview',
            done: function (data) { console.log(data); },
            error: function (data) { console.log(data); }
        });

    }).on('hidden.bs.modal', function () {
        cropper.destroy();

        cropper = null;
    });

    $('#cropModalUser #crop_avatar').click(function () {
        // Ajax loading image to tell user to wait
        $('.user-image').attr('src', currentHost + '/assets/img/ajax-loading.gif');

        console.log(headers);

        var canvas = cropper.getCroppedCanvas({
            width: 700,
            height: 700
        });

        canvas.toBlob(function (blob) {
            URL.createObjectURL(blob);

            var reader = new FileReader();

            reader.readAsDataURL(blob);
            reader.onloadend = function () {
                var base64_data = reader.result;
                var mUrl = apiHost + '/user/update_avatar_picture/' + parseInt(currentUser);
                var datas = JSON.stringify({ '_token': $('[name="csrf-token"]').attr('content'), 'id': parseInt(currentUser), 'user_id': currentUser, 'image_64': base64_data });

                $.ajax({
                    headers: headers,
                    type: 'PUT',
                    contentType: 'application/json',
                    url: mUrl,
                    dataType: 'json',
                    data: datas,
                    success: function (res) {
                        $('.user-image').attr('src', res);
                        location.reload(true);
                    },
                    error: function (xhr, error, status_description) {
                        console.log(xhr.responseJSON);
                        console.log(xhr.status);
                        console.log(error);
                        console.log(status_description);
                    }
                });
            };
        });
    });

    // AVATAR without ajax
    $('#image_profile').on('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            retrievedImageProfile.src = url;
            var modal = new bootstrap.Modal(document.getElementById('cropModal_profile'), { keyboard: false });

            modal.show();
        };

        if (files && files.length > 0) {
            var reader = new FileReader();

            reader.onload = function () {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#cropModal_profile').on('shown.bs.modal', function () {
        cropper = new Cropper(retrievedImageProfile, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '#cropModal_profile .preview'
        });

    }).on('hidden.bs.modal', function () {
        cropper.destroy();

        cropper = null;
    });

    $('#cropModal_profile #crop_profile').on('click', function () {
        var canvas = cropper.getCroppedCanvas({
            width: 700,
            height: 700
        });

        canvas.toBlob(function (blob) {
            URL.createObjectURL(blob);
            var reader = new FileReader();

            reader.readAsDataURL(blob);
            reader.onloadend = function () {
                var base64_data = reader.result;

                $(currentImageProfile).attr('src', base64_data);
                $('#image_64').attr('value', base64_data);
            };
        });
    });
});
