$(document).ready(function () {

    const dropArea = $(".drop-area");
    loadPositions();

    $(".draggable-block").draggable({
        revert: "invalid",
        stack: ".draggable-block",
        //drag: function (event, ui) {
        //    updatePosition(ui.helper);
        //},
        stop: function (event, ui) {
            updatePosition(ui.helper);
        }
    });

    dropArea.droppable({
        accept: ".draggable-block",
        drop: function (event, ui) {
            const $droppedBlock = ui.helper;
            $(this).append($droppedBlock.css({ position: 'relative', top: 0, left: 0 }));
            alignBlocksHorizontally($(this));
            updatePosition(ui.helper);
        }
    });

    function alignBlocksHorizontally($dropArea) {
        $dropArea.children('.draggable-block').each(function () {
            $(this).css({ display: 'inline-block', margin: '10px 5px' });
        });
    }

    function updatePosition($block) {
        const positions = JSON.parse(localStorage.getItem('UC1_blockPositions')) || [];
        const blockId = $block.text();
        const position = $block.position();
        const divId = $block.parent().attr('id');

        const existingBlock = positions.find(item => item.id === blockId && item.divId === divId);
        if (existingBlock) {
            existingBlock.top = position.top;
            existingBlock.left = position.left;
        } else {
            positions.push({ id: blockId, divId: divId, top: position.top, left: position.left });
        }

        localStorage.setItem('UC1_blockPositions', JSON.stringify(positions));
    }

    function savePositions() {
        const positions = [];
        dropArea.children('.draggable-block').each(function () {
            const position = $(this).position();
            positions.push({ id: $(this).text(), top: position.top, left: position.left });
        });
        localStorage.setItem('UC1_blockPositions', JSON.stringify(positions));
    }

    function loadPositions() {
        const savedPositions = JSON.parse(localStorage.getItem('UC1_blockPositions')) || [];
        savedPositions.forEach(function (block) {
            const parentDiv = block.divId;
            const $block = $(`.draggable-block:contains(${block.id})`);
            if ($block.length) {
                //dropArea.append($block.css({ position: 'absolute', top: block.top, left: block.left }));
                $('#' + parentDiv).append($block.css({ position: 'relative', top: 0, left: 0 }));
            }
        });
        alignBlocksHorizontally(dropArea);
    }

    $('#Btn_Reset').on('click', function () {
        localStorage.removeItem("UC1_blockPositions");
        location.reload();
    });

});
