


function deleteCategory(catId) {
    if (confirm("Möchten Sie die Leistung wirklich löschen?")) {
        var action = "delete";
        $.ajax({
            url: BloombaseUrl + '/bannerPriceNewAdmin/category',
            method: "POST",
            data: { action: action, 'cat_id': catId },
            success: function (data) {
                fetch_new_price_data(JSON.parse(data));
            },
            error: function (jqxhr, textStatus, error) {
                // add whatever debug you want here.
                var err = textStatus + ', ' + error;
                console.log('Failed: ' + err);
            }
        });
    }
}
function deleteItem(itemId, catId) {
    if (confirm("Möchten Sie die Leistung wirklich löschen?")) {
        var action = "delete";
        $.ajax({
            url: BloombaseUrl + '/bannerPriceNewAdmin/item',
            method: "POST",
            data: { action: action, 'item_id': itemId, 'cat_id': catId },
            success: function (data) {
                fetch_new_price_data(JSON.parse(data));
            },
            error: function (jqxhr, textStatus, error) {
                // add whatever debug you want here.
                var err = textStatus + ', ' + error;
                console.log('Failed: ' + err);
            }
        });
    }
}

function addExtraInfo() {

    let currentLength = $("#extra_info_container").children().length;
    let html = `
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Info - ${currentLength + 1}</span>
            </div>
            <input type="text" class="form-control" name="extraInfo[]" aria-label="Extra Info"
                aria-describedby="basic-addon1">
        </div>`;



    $("#extra_info_container").append(html);


}


function fetch_new_price_data() {
    var action = "fetch";
    $.ajax({
        url: BloombaseUrl + '/bannerPriceNewAdmin/category',
        method: "POST",
        data: { action: action },
        success: function (data) {
            $('#banner_price_new_table > tbody').html(data);
        },
        error: function (jqxhr, textStatus, error) {
            // add whatever debug you want here.
            var err = textStatus + ', ' + error;
            console.log('Failed: ' + err);
        }
    });

    // saveOrderbanner_field()
}

function fetch_category_data() {
    var action = "fetch_array";
    $.ajax({
        url: BloombaseUrl + '/bannerPriceNewAdmin/category',
        method: "POST",
        data: { action: action },
        success: function (data) {
            populateCategorySelect(JSON.parse(data));
        },
        error: function (jqxhr, textStatus, error) {
            // add whatever debug you want here.
            var err = textStatus + ', ' + error;
            console.log('Failed: ' + err);
        }
    });
    // saveOrderbanner_field()
}


function populateCategorySelect(categories) {

    $("#add_item_form  select").html('');

    if (typeof categories == 'object') {
        categories = Object.values(categories);
    }


    categories.forEach(cat => {
        $("#add_item_form  select").append("<option value='" + cat._id + "' >" + cat.name + "</option>");
    });

}


(function ($) {

    $('#addItemModal').on('hidden.bs.modal', function () {
        $("#extra_info_container").html('');
    })

    $(document).ready(function () {

        //  url: BloombaseUrl + '/termine/terminFinden',
        fetch_new_price_data();

        // Insert Images

        $('#add_banner').click(function () {
            $('#imageModalbanner').modal('show');
            $('#image_form')[0].reset();
            $('.modal-title').text("Banner Bild einfügen");
            $('#image_id').val('');
            $('#action').val("insert");
            $('#insert').val("Einfügen");
        });


        $("#add_item").click(function () {
            $("#addItemModal").modal('show');
            $("#add_item_form")[0].reset();
            fetch_category_data();
        });


        $('#add_category_form').on('submit', function (event) {
            event.preventDefault();
            var category_name = $('#new_category_name').val().trim();
            console.log('category_name', category_name);
            if (category_name == '') {
                alert("Bitte wählen Sie Bild");
                return false;
            } else {
                $.ajax({
                    url: BloombaseUrl + '/bannerPriceNewAdmin/category',
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        let _data = JSON.parse(data);
                        console.log(_data);


                        let boxType = _data.success ? 'success' : 'error';
                        boxElement = $('#add-category-' + boxType + '-box');

                        let boxMessage = _data.success ? _data.success_msg : _data.error_msg;

                        boxElement.removeClass('hide');
                        boxElement.addClass('show');

                        boxElement.html(boxMessage);

                        if (_data.success == true) {


                            setTimeout(function () {
                                $('#addCategoryModal').modal('hide');
                                fetch_new_price_data();
                            }, 2000);
                        }

                        setTimeout(function () {
                            boxElement.removeClass('show');
                            boxElement.addClass('hide');
                        }, 2000);


                        $('#add_category_form')[0].reset();
                    }
                });
            }
        });


        $('#add_item_form').on('submit', function (event) {
            event.preventDefault();

            var categoryId = $('#category_id').val();
            var itemTitle = $('#item_title').val().trim();
            var itemPrice = $('#item_price').val().trim();


            if (categoryId == '' || itemTitle == '' || itemPrice == '') {
                alert("Bitte füllen Sie das Formular korrekt aus");
                return false;
            } else {
                $.ajax({
                    url: BloombaseUrl + '/bannerPriceNewAdmin/item',
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        let _data = JSON.parse(data);
                        console.log(_data);

                        let boxType = _data.success ? 'success' : 'error';
                        boxElement = $('#add-item-' + boxType + '-box');

                        let boxMessage = _data.success ? _data.success_msg : _data.error_msg;

                        boxElement.removeClass('hide');
                        boxElement.addClass('show');

                        boxElement.html(boxMessage);

                        if (_data.success == true) {

                            setTimeout(function () {
                                $('#addItemModal').modal('hide');
                                fetch_new_price_data();
                            }, 2000);
                        }

                        setTimeout(function () {
                            boxElement.removeClass('show');
                            boxElement.addClass('hide');
                        }, 2000);


                        $('#add_item_form')[0].reset();
                    }
                });
            }
        });


        // $(document).on('click', '.update', function() {
        //     $('#image_id').val($(this).attr("id"));
        //     $('#action').val("update");
        //     $('.modal-title').text("Bild aktualisieren");
        //     $('#insert').val("Aktualisieren");
        //     $('#imageModalbanner').modal('show');
        // });

        // DELETE
        $(document).on('click', '.delete', function () {
            var image_id = $(this).attr("id");
            var action = 'delete';
            if (confirm("Möchten Sie dieses Bild wirklich entfernen?")) {
                $.ajax({
                    url: BloombaseUrl + '/bannerPriceAdmin/banner',
                    method: "POST",
                    data: { image_id: image_id, action: action },
                    success: function (data) {
                        alert(data);
                        fetch_new_price_data();
                    }
                });
            } else {
                return false;
            }
        });

    });

})(jQuery);