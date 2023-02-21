import './bootstrap'

$(document).ready(function() {
    if (window.FileReader) {
        $('#images').change(function() {
            const imagesWrapper = '.images-wrapper';
            let counter = 0, file;
            let template = `<div class="col-12 d-flex justify-content-center align-items-center">
                                <img src="__url__" class="card-img-top" style="max-width: 200px; margin: 0 auto; display: block">
                            </div>`;

            $(imagesWrapper).html('');

            while(file = this.files[counter++]) {
                let reader = new FileReader();
                reader.onloadend = (() => function() {
                    let img = template.replace('__url__', this.result);
                    $(imagesWrapper).append(img);
                })(file);
                reader.readAsDataURL(file);
            }
        })

        $('#thumbnail').change(function() {
            let reader = new FileReader();
            reader.onloadend = (e) => {
                $('#thumbnail-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        })
    }

    $(document).on('click', '.remove-product-image', function (e) {
        e.preventDefault();

        const $btn = $(this);
        $.ajax({
            url: $btn.data('route'),
            type: 'DELETE',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(data) {
                console.log('data', data);
                $btn.parent().remove();
            },
            error: function(data) {
                console.log('Error: ', data)
            }
        });
    });
});
