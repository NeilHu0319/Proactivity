$(document).ready(function () {
    $('.btn_edit').on("click", function (e) {
        e.preventDefault();
        var s = this.id;
        var activityid = s.substring(s.indexOf('_') + 1);
        var tdElement = $('#td_' + activityid);
        var innerHtml = '<input type="text" value="' + '" id="edit_' + activityid + '" class="form-control" placeholder="' + tdElement.text().trim() + '"  aria-describedby="basic-addon1" />';
        $(tdElement).after().html(innerHtml);

        $('form').on('submit', function (e) { e.preventDefault(); });
        $('#edit_' + activityid).on('blur keypress', function (e) {
            if (e.type === 'keypress' && e.which === 13) {
                e.preventDefault();
                var enteredText = $(this).val();
                // alert(enteredText);
                $.ajax({
                    url: 'activity_name_change.php',
                    type: 'POST',
                    data: { action: 'changename', activityid: activityid, enteredText: enteredText }, // Data to send to PHP
                    success: function (result) {
                        if (result != "ok") {
                            alert(result);
                        }
                        else {
                            //$(liElement).after().html(enteredText);
                            location.reload();
                        }
                    },
                    error: function () {
                        alert('Error occurred, please try again');
                    }
                });
            }

        });

    });

});
