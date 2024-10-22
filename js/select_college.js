$(document).ready(function () {
    $(document).on('click', '.select-btn', function () {
        var item = $(this).closest('li');
        item.find('.select-btn').remove();
        item.html("<a href='#' class='university-link'>" + item.text().trim() + "</a> <button class='btn btn-sm btn-danger float-right remove-btn'>Remove</button>");
        $('#selectedList').append(item);
        saveSelection();
    });

    $(document).on('click', '.remove-btn', function () {
        var item = $(this).closest('li');
        item.find('.remove-btn').remove();
        item.html(item.find('.university-link').text().trim());
        $('#availableList').append(item);
        saveSelection();
    });

    //$('#moveToSelected').click(function () {
    //    $('#availableList .list-group-item.active').each(function () {
    //        $(this).find('.btn').remove();
    //        $(this).html("<a href='essay_brainstorming.php' class='university-link'>" + $(this).text().trim() + "</a> <button class='btn btn-sm btn-primary remove-btn ms-auto'>Remove</button>");
    //        $('#selectedList').append($(this).removeClass('active'));
    //    });
    //    saveSelection();
    //});

    $('#moveToSelected').click(function () {
        $('#availableList .list-group-item.active').each(function () {
            var universityName = $(this).text().trim();
            var listItemHtml = "<li class='list-group-item d-flex justify-content-between align-items-center'>" +
                "<a href='essay_brainstorming.php' class='university-link'>" + universityName + "</a>" +
                "<button class='btn btn-sm btn-primary remove-btn ms-auto'>Remove</button>" +
                "</li>";
            // Append the new list item to the selected list
            $('#selectedList').append(listItemHtml);
            // Remove the selected item from the available list
            $(this).remove();
        });
        saveSelection(); // Save the updated selections
    });

    $('#moveToAvailable').click(function () {
        $('#selectedList .list-group-item.active').each(function () {
            $(this).find('.btn').remove();
            var item = $(this).find('.university-link').text().trim();
            $(this).html(item);
            $(this).removeClass('active');
            $('#availableList').append($(this));
        });
        saveSelection();
    });

    $(document).on('click', '.list-group-item', function () {
        $(this).toggleClass('active');
    });

    function saveSelection() {
        var selected = [];
        $('#selectedList .list-group-item').each(function () {
            var universityName = $(this).find('.university-link').text().trim();
            selected.push(universityName);
        });

        $.ajax({
            url: 'save_college.php',
            method: 'POST',
            data: { selected: JSON.stringify(selected) },
            success: function (response) {
                console.log('Saved:', response);
            }
        });
    }
});




