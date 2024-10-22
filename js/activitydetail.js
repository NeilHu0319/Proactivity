$(document).ready(function () {
    $('.btn_edit').on("click", function (e) {
        e.preventDefault();
        var taskid = this.id;
        var liElement = $('#li_' + taskid);
        var innerHtml = '<input type="text" value="' + '" id="edit_' + taskid + '" class="form-control" placeholder="' + liElement.text() + '"  aria-describedby="basic-addon1" />';
        $(liElement.after().html(innerHtml));


        $('form').on('submit', function (e) { e.preventDefault(); });
        $('#edit_' + taskid).on('blur keypress', function (e) {
            if (e.type === 'keypress' && e.which === 13) {
                e.preventDefault();
                var enteredText = $(this).val();
                $.ajax({
                    url: 'activitydetail_name_change.php',
                    type: 'POST',
                    data: { action: 'changename', taskid: taskid, enteredText: enteredText }, // Data to send to PHP
                    success: function (result) {
                        if (result != "ok") {
                            alert(result);
                        }
                        else {
                            //$(liElement).after().html(enteredText);
                            location.reload();
                        }
                    },
                    // error: function (jqXHR, textStatus, errorThrown) {
                    //     alert("something error, please try again");
                    // }
                });
            }

        });

    });

});
