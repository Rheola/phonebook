'use strict';
$(function () {
    $('#create').click(function () {

        const form = $('#create-form')[0];

        // Create an FormData object
        const formData = new FormData(form);

        // If you want to add an extra field for the FormData
        formData.append("CustomField", "This is some extra data, testing");
        $.ajax({
            type: "POST",
            url: "/phone/create",
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: formData,
        })
            .done(function (rawResponse) {
                const response = JSON.parse(rawResponse);
                if (response.success) {
                    const phone = response.data;

                    let tdFile = `<td></td>`;
                    console.log(phone.file)
                    if (phone.file != '') {
                        tdFile = `<td>
                                <img src="/upload/min/${phone.file}" alt="" class="img-thumbnail">
                            </td>`;
                    }
                    console.log(phone.tdFile);

                    $('tbody').prepend(`
                    <tr data-id="${phone.id}">
                             <td>${phone.first_name}</td>
                            <td>${phone.last_name}</td>
                            <td>${phone.phone}</td>
                            <td>${phone.email}</td>
                            ${tdFile}
                            <td>
                                         <div class="btn-group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary btn-info edit" data-id="${phone.id}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary  btn-danger delete" data-id="${phone.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                            </td>
                    </tr>
                    `
                    );
                    $('#phone').val('');
                    $('#first_name').val('');
                    $('#last_name').val('');
                    $(`.invalid-feedback`).hide();
                    $('input').removeClass('is-invalid');
                    return false;
                } else {
                    const errors = response.errors;
                    for (const [key, value] of Object.entries(errors)) {
                        $(`.invalid-feedback.${key}`).text(value);
                        $(`.invalid-feedback.${key}`).show();
                        $(`#${key}`).addClass('is-invalid');
                    }
                }
            })
            .fail(function (data) {
                console.error(data);

                alert("error");
            });

        return false;
    });


    $(document).on('click', '.delete', function () {
            const id = this.getAttribute('data-id');

            $.ajax({
                type: "POST",
                url: "/phone/delete/" + id,
            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);
                    if (response.success) {
                        $(`tr[data-id=${id}]`).remove();
                    } else {
                        alert('Что-то пошло не так');
                    }
                })
                .fail(function (data) {
                    console.log('error');
                    console.log(data);

                    alert("error");
                });

            return false;
            // what you want to happen when mouseover and mouseout
            // occurs on elements that match '.dosomething'
        }
    );

    $(document).on('click', '.edit', function () {
            const id = this.getAttribute('data-id');
            console.log(id);
            $.ajax({
                type: "GET",
                url: "/phone/view/" + id,
            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);

                    if (response.success) {
                        // $('#editModal').show();
                        const contact = response.data;

                        for (const [key, value] of Object.entries(contact)) {
                            if (key == 'file') {
                                continue;
                            }
                            $(`#${key}-edit`).val(value);
                        }
                        if (contact.file != '') {
                            console.log(contact.id);
                            $('#file-prev').attr('src', '/upload/min/' + contact.file);
                            $('#delete-file').attr('data-id', contact.id);
                            $('#delete-file').show();
                        } else {
                            $('#delete-file').hide();
                        }
                        $('#save').attr('data-id', contact.id);
                        $('#editModal').modal('show');

                    } else {
                        alert('Что-то пошло не так');
                    }
                })
                .fail(function (data) {
                    console.log('error');
                    console.log(data);

                    alert("error");
                });

            return false;
            // what you want to happen when mouseover and mouseout
            // occurs on elements that match '.dosomething'
        }
    );

    $(document).on('click', '#save', function () {
            const id = this.getAttribute('data-id');

            const form = $('#edit-form')[0];
            // Create an FormData object
            const formData = new FormData(form);

            // If you want to add an extra field for the FormData
            formData.append("CustomField", "This is some extra data, testing");
            $.ajax({
                type: "POST",
                url: "/phone/update/" + id,
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                data: formData,
            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);
                    if (response.success) {
                        const phone = response.data;

                        const innerHtml = `<td>${phone.first_name}</td>
                            <td>${phone.last_name}</td>
                            <td>${phone.phone}</td>
                            <td>${phone.email}</td>
                            <td>
                                         <div class="btn-group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary btn-info edit" data-id="${phone.id}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary  btn-danger delete" data-id="${phone.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                            </td>`;
                        $('input').removeClass('is-invalid');
                        $('#editModal').modal('hide');
                        $(`tr[data-id='${phone.id}']`).html(innerHtml);
                        return false;
                    } else {
                        const errors = response.errors;
                        for (const [key, value] of Object.entries(errors)) {
                            $(`.invalid-feedback.${key}`).text(value);
                            $(`.invalid-feedback.${key}`).show();
                            $(`#${key}`).addClass('is-invalid');
                        }
                    }
                })
                .fail(function (data) {
                    console.error(data);

                    alert("error");
                });

            return false;
        }
    );

    $(document).on('click', '#delete-file', function () {
            const id = this.getAttribute('data-id');


            $.ajax({
                type: "POST",
                url: "/phone/deletefile/" + id,
            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);
                    if (response.success) {
                        $('#file-prev').hide();
                        $('#delete-file').hide();


                        return false;
                    } else {
                        const errors = response.errors;
                        console.error(errors);

                        alert("error");
                    }
                })
                .fail(function (data) {
                    console.error(data);

                    alert("error");
                });

            return false;
        }
    );

});